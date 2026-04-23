<?php

namespace App\Http\Controllers\Admin\Certificate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Certificate;
use App\Models\CertificateLayout;
use App\Models\Event;
use App\Models\JobCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    // ──────────────────────────────────────────────────
    // Admin: List certificates for the event
    // ──────────────────────────────────────────────────

    public function index(Request $request)
    {
        $eventId = session('admin_event_id');
        if (!$eventId) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin event session tidak ditemukan');
        }

        $event = Event::findOrFail($eventId);
        $activeLayout = $event->activeCertificateLayout;

        $q = trim((string) $request->get('q', ''));

        $query = Certificate::with(['application.user'])
            ->where('event_id', $eventId)
            ->orderByDesc('id');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->whereJsonContains('payload->volunteer_name', $q)
                    ->orWhere('cert_code', 'like', "%{$q}%")
                    ->orWhere('payload', 'like', "%{$q}%");
            });
        }

        $statusFilter = $request->get('status');
        if ($statusFilter) {
            $dbStatus = ($statusFilter === 'cancelled') ? 'revoked' : $statusFilter;
            $query->where('status', $dbStatus);
        }

        $certificates = $query->get();

        // New counters for simplified UI
        $publishedCount = Certificate::where('event_id', $eventId)->where('status', 'published')->count();
        $cancelledCount = Certificate::where('event_id', $eventId)->where('status', 'revoked')->count();

        // Count eligible applications
        $eligibleCount = $this->countEligible($eventId);
        $canGenerate = $this->canGenerate($event, $activeLayout);

        return view('admin.certificates.index', compact(
            'event',
            'certificates',
            'activeLayout',
            'eligibleCount',
            'publishedCount',
            'cancelledCount',
            'canGenerate',
            'q',
            'statusFilter'
        ));
    }

    // ──────────────────────────────────────────────────
    // Batch generate certificates for all eligible volunteers
    // ──────────────────────────────────────────────────

    public function generate(Request $request)
    {
        $eventId = session('admin_event_id');
        if (!$eventId) {
            return response()->json(['error' => 'Event session tidak ditemukan'], 403);
        }

        $event = Event::findOrFail($eventId);
        $activeLayout = $event->activeCertificateLayout;

        if (!$this->canGenerate($event, $activeLayout)) {
            return response()->json([
                'error' => 'Sertifikat hanya dapat digenerate setelah event selesai dan layout sudah dipublikasikan.',
            ], 422);
        }

        // Freeze the full layout JSON at generation time
        $layoutSnapshot = $activeLayout->layout_json;

        // Fetch all approved applications for this event
        $eligibleApps = $this->getEligibleApplications($eventId);

        if ($eligibleApps->isEmpty()) {
            return response()->json(['error' => 'Tidak ada relawan yang memenuhi syarat.'], 422);
        }

        // Load job categories
        $jobCategoryIds = $eligibleApps->pluck('job_category_id')->unique()->filter();
        $jobCategories = JobCategory::whereIn('id', $jobCategoryIds)->get()->keyBy('id');

        // Existing certs by application_id
        $existingCertAppIds = Certificate::where('event_id', $eventId)
            ->pluck('application_id')
            ->toArray();

        $generated = 0;
        $skipped = 0;

        // Sequential counter for cert_code
        $baseCount = Certificate::where('event_id', $eventId)->lockForUpdate()->count();
        $seq = $baseCount;

        DB::transaction(function () use ($eligibleApps, $existingCertAppIds, $jobCategories, $eventId, $event, $activeLayout, $layoutSnapshot, &$generated, &$skipped, &$seq) {
            foreach ($eligibleApps as $app) {
                if (in_array($app->application_id, $existingCertAppIds, false)) {
                    $skipped++;
                    continue;
                }

                $seq++;
                $certCode = sprintf('CERT-%d-%05d', $eventId, $seq);

                $qrToken = bin2hex(random_bytes(32)); // 64 hex chars
                $verifyUrl = url("/sertifikat/verify/{$qrToken}");
                $signature = hash_hmac('sha256', $qrToken . '|' . $certCode, config('app.key'));

                // Role from job_category_name — NOT worker_opening title (per rule #8)
                $jobCatId = (int) $app->job_category_id;
                $roleLabel = $jobCategories->get($jobCatId)?->name ?? '—';

                $payload = [
                    'volunteer_name' => $app->applicant_name,
                    'role_label' => $roleLabel,
                    'event_title' => $event->title,
                    'event_start_at' => $event->start_at?->format('d F Y'),
                    'event_end_at' => $event->end_at?->format('d F Y'),
                    'issue_date' => now()->format('d F Y'),
                    'background_path' => $activeLayout->background_path,
                    'event_logo_path' => $activeLayout->event_logo_path ?? $event->logo_path,
                    'org_logo_path' => $activeLayout->org_logo_path,
                    'qr_url' => $verifyUrl,
                ];

                $snapshot = [
                    'volunteer_name' => $app->applicant_name,
                    'volunteer_email' => $app->applicant_email,
                    'job_category_id' => $jobCatId,
                    'role_label' => $roleLabel,
                    'opening_title' => $app->opening_title,
                    'event_title' => $event->title,
                ];

                Certificate::create([
                    'event_id' => $eventId,
                    'application_id' => $app->application_id,
                    'layout_id' => $activeLayout->id,
                    'layout_snapshot' => $layoutSnapshot,
                    'status' => 'published',
                    'cert_code' => $certCode,
                    'qr_token' => $qrToken,
                    'verify_url' => $verifyUrl,
                    'signature' => $signature,
                    'payload' => $payload,
                    'snapshot' => $snapshot,
                    'published_at' => now(),
                    'issued_at' => now(),
                    'created_by' => session('admin_id'),
                    'updated_by' => session('admin_id'),
                ]);

                $generated++;
            }
        });

        return response()->json([
            'success' => true,
            'message' => "Successfully published {$generated} certificates.",
        ]);
    }

    // ──────────────────────────────────────────────────
    // Cancel a certificate
    // ──────────────────────────────────────────────────

    public function cancel(Certificate $certificate)
    {
        $eventId = session('admin_event_id');
        abort_unless($certificate->event_id == $eventId, 403);

        $certificate->update(['status' => 'revoked']);

        return back()->with('success', "Certificate for {$certificate->volunteer_name} has been cancelled.");
    }

    // ──────────────────────────────────────────────────
    // Restore/Re-issue a certificate
    // ──────────────────────────────────────────────────

    public function restore(Certificate $certificate)
    {
        $eventId = session('admin_event_id');
        abort_unless($certificate->event_id == $eventId, 403);

        $certificate->update(['status' => 'published']);

        return back()->with('success', "Certificate for {$certificate->volunteer_name} has been re-issued.");
    }

    // ──────────────────────────────────────────────────
    // Preview a single certificate (HTML)
    // ──────────────────────────────────────────────────

    public function preview(Certificate $certificate)
    {
        $eventId = session('admin_event_id');
        abort_unless($certificate->event_id == $eventId, 403);

        $event = Event::findOrFail($eventId);

        $layout = $certificate->getEffectiveLayout();
        $payload = $certificate->payload ?? [];
        $qrBase64 = $this->qrBase64($payload['qr_url'] ?? url('/sertifikat/verify/' . $certificate->qr_token));

        return view('admin.certificates.preview-sample', [
            'layout' => $layout,
            'event' => $event,
            'layoutModel' => $certificate->layout ?? CertificateLayout::find($certificate->layout_id),
            'samplePayload' => array_merge($payload, ['qr_base64' => $qrBase64]),
            'certificate' => $certificate,
            'isReal' => true,
        ]);
    }

    // ──────────────────────────────────────────────────
    // Download certificate as PDF
    // ──────────────────────────────────────────────────

    public function downloadPdf(Certificate $certificate)
    {
        $eventId = session('admin_event_id');
        abort_unless($certificate->event_id == $eventId, 403);

        $event = Event::findOrFail($eventId);
        $layout = $certificate->getEffectiveLayout();
        $payload = $certificate->payload ?? [];
        $qrBase64 = $this->qrBase64($payload['qr_url'] ?? url('/sertifikat/verify/' . $certificate->qr_token));

        $pdf = Pdf::loadView('admin.certificates.preview-content', [
            'layout' => $layout,
            'layoutModel' => $certificate->layout ?? CertificateLayout::find($certificate->layout_id),
            'event' => $event,
            'payload' => array_merge($payload, ['qr_base64' => $qrBase64]),
            'isPdf' => true,
        ])->setPaper('a4', 'landscape');

        // Mark as downloaded
        $certificate->update(['downloaded_at' => now()]);

        $filename = 'sertifikat-' . \Illuminate\Support\Str::slug($payload['volunteer_name'] ?? $certificate->cert_code) . '.pdf';

        return $pdf->download($filename);
    }

    // ──────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────

    /**
     * Determine if generation is allowed for this event.
     * Rule: event.status == 'completed' OR end_at has passed, AND a published layout exists.
     */
    private function canGenerate(Event $event, ?CertificateLayout $activeLayout): bool
    {
        if (!$activeLayout || !$activeLayout->isLocked()) {
            return false;
        }

        return $event->status === 'completed' || (
            $event->end_at !== null && $event->end_at->isPast()
        );
    }

    /**
     * Get all eligible applications (approved) for this event.
     * Mirrors the exact query from CardController — approved applications only.
     */
    private function getEligibleApplications(int $eventId)
    {
        return Application::query()
            ->select([
                'applications.id as application_id',
                'applications.user_id',
                'applications.worker_opening_id',
                'applications.status as application_status',
                'worker_openings.title as opening_title',
                'worker_openings.job_category_id',
                'users.name as applicant_name',
                'users.email as applicant_email',
            ])
            ->join('worker_openings', 'worker_openings.id', '=', 'applications.worker_opening_id')
            ->join('users', 'users.id', '=', 'applications.user_id')
            ->where('worker_openings.event_id', $eventId)
            ->where('applications.status', 'approved')   // ← same eligibility as card system
            ->orderByDesc('applications.id')
            ->get();
    }

    /**
     * Count eligible applications that don't yet have a certificate.
     */
    private function countEligible(int $eventId): int
    {
        $total = Application::query()
            ->join('worker_openings', 'worker_openings.id', '=', 'applications.worker_opening_id')
            ->where('worker_openings.event_id', $eventId)
            ->where('applications.status', 'approved')
            ->count();

        $generated = Certificate::where('event_id', $eventId)->count();

        return max(0, $total - $generated);
    }

    /**
     * Generate a QR code as a base64 PNG data URI.
     */
    private function qrBase64(?string $text): ?string
    {
        if (!$text)
            return null;

        $writer = new \Endroid\QrCode\Writer\PngWriter();
        $qrCode = \Endroid\QrCode\QrCode::create($text)
            ->setSize(220)
            ->setMargin(2);

        $result = $writer->write($qrCode);

        return 'data:image/png;base64,' . base64_encode($result->getString());
    }
}
