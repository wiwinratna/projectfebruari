<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\WorkerOpening;
use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\AccessCardConfig;
use App\Services\Card\CardAccessResolver;
use App\Models\AccreditationMapping;
use Illuminate\Support\Facades\DB;
use App\Notifications\ApplicationStatusChangedNotification;


class ApplicationController extends Controller
{
    public function show(Application $application)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Check if application's opening belongs to admin's assigned event
        $adminEventId = session('admin_event_id');
        if ($application->opening->event_id !== $adminEventId) {
            return back()->withErrors(['message' => 'You are not authorized to view this application.']);
        }

        $application->load([
            'user.profile',
            'user.certificates' => fn($q) => $q->latest(), //paling baru cenah
            'opening.event',
            'opening.jobCategory',
        ]);

        return view('menu.admin.applications.show', compact('application'));
    }

public function update(Request $request, Application $application)
{
    if (!session('admin_authenticated')) {
        return redirect('/admin/login');
    }

    // Check if application's opening belongs to admin's assigned event
    $adminEventId = session('admin_event_id');
    if ($application->opening->event_id !== $adminEventId) {
        return back()->withErrors(['message' => 'You are not authorized to update this application.']);
    }

    $validated = $request->validate([
        'status' => 'required|in:approved,rejected,pending',
        'review_notes' => 'nullable|string|max:1000',
    ]);

    $oldStatus = $application->status;
    DB::transaction(function () use ($validated, $application, $oldStatus) {

        $application->update([
            'status' => $validated['status'],
            'review_notes' => $validated['review_notes'],
            'reviewed_by' => session('admin_id'),
            'reviewed_at' => now(),
        ]);

        // ===== slots_filled update =====
        $job = $application->opening;

        if ($oldStatus !== 'approved' && $validated['status'] === 'approved') {
            $job->increment('slots_filled');
        } elseif ($oldStatus === 'approved' && $validated['status'] !== 'approved') {
            $job->decrement('slots_filled');
        }

        // ===== auto close/open job =====
        $job->refresh();
        if ($job->slots_filled >= $job->slots_total && $job->status === 'open') {
            $job->update(['status' => 'closed']);
        } elseif ($job->slots_filled < $job->slots_total && $job->status === 'closed') {
            if ($job->application_deadline > now()) {
                $job->update(['status' => 'open']);
            }
        }

        // ===== ✅ CARD (NEW) LOGIC =====
        if ($validated['status'] === 'approved') {

            $eventId = $application->opening->event_id;        // event dari opening
            $jobCategoryId = $application->opening->job_category_id;

            // cari mapping utk job category ini (khusus event)
            $pivot = DB::table('accreditation_mapping_job_category')
                ->where('event_id', $eventId)
                ->where('job_category_id', $jobCategoryId)
                ->first();

            if (!$pivot) {
                // stop transaksi biar admin sadar mapping belum diset
                throw new \Exception("Job Category belum dimapping ke Accreditation untuk event ini. Set dulu di Accreditation Mapping.");
            }

            $mappingId = (int) $pivot->accreditation_mapping_id;

            // cari default access config utk mapping tsb
            $config = AccessCardConfig::where('event_id', $eventId)
                ->where('accreditation_mapping_id', $mappingId)
                ->first();

            // create / get draft card (idempotent)
            $card = Card::firstOrCreate(
                ['event_id' => $eventId, 'application_id' => $application->id],
                [
                    'accreditation_mapping_id' => $mappingId,
                    'access_card_config_id' => $config?->id,
                    'status' => 'draft',
                    'snapshot' => [
                        'name' => $application->user->name ?? null,
                        'email' => $application->user->email ?? null,
                        'opening_title' => $application->opening->title ?? null,
                        'job_category_id' => $jobCategoryId,
                        'job_category_name' => $application->opening->jobCategory->name ?? null,
                        'mapping_name' => optional(AccreditationMapping::find($mappingId))->nama_akreditasi,
                        'mapping_color' => optional(AccreditationMapping::find($mappingId))->warna,
                    ],
                ]
            );

            // seed default overrides (venues/zones/transport/accommodation) supaya otomatis terisi
            app(CardAccessResolver::class)->seedDefaultOverrides($card);

        } else {
            // kalau status bukan approved -> hapus card draft (opsional)
            $eventId = $application->opening->event_id;

            $card = Card::where('event_id', $eventId)
                ->where('application_id', $application->id)
                ->first();

            if ($card && $card->status !== 'issued') {
                // jika belum issued, aman dihapus. Kalau sudah issued, sebaiknya revoke (nanti).
                $card->overrides()->delete();
                $card->delete();
            }
        }
    });

    if ($oldStatus !== $validated['status'] && in_array($validated['status'], ['approved', 'rejected'], true)) {
        $application->loadMissing(['user', 'opening.event']);
        $application->user?->notify(new ApplicationStatusChangedNotification(
            $application->opening->event->title ?? 'Event',
            $application->opening->title ?? 'Opening',
            $validated['status'],
            route('customer.applications')
        ));
    }

    $job = $application->opening; // sudah ada, tapi biar aman ambil ulang
    return redirect()->route('admin.workers.show', $job->id)
        ->with('status', "Application for {$application->user->name} has been {$validated['status']}.");
}
}
