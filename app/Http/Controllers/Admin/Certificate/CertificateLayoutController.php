<?php

namespace App\Http\Controllers\Admin\Certificate;

use App\Http\Controllers\Controller;
use App\Models\CertificateLayout;
use App\Models\Certificate;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateLayoutController extends Controller
{
    private const PX_PER_MM = 3.77953;

    // ──────────────────────────────────────────────────
    // Builder View
    // ──────────────────────────────────────────────────

    public function builder()
    {
        $eventId = session('admin_event_id');
        if (!$eventId) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin event session tidak ditemukan');
        }

        $event = Event::findOrFail($eventId);
        // Load the latest layout (could be draft or published)
        $activeLayout = $event->certificateLayouts()->orderByDesc('id')->first();

        return response()
            ->view('admin.certificates.builder', compact('event', 'activeLayout'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    // ──────────────────────────────────────────────────
    // List all layouts for this event
    // ──────────────────────────────────────────────────

    public function index()
    {
        $eventId = session('admin_event_id');
        if (!$eventId) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin event session tidak ditemukan');
        }

        $event = Event::findOrFail($eventId);
        $layouts = CertificateLayout::where('event_id', $eventId)
            ->orderByDesc('id')
            ->get();

        return view('admin.certificates.layouts.index', compact('event', 'layouts'));
    }

    // ──────────────────────────────────────────────────
    // Get latest layout JSON (API)
    // ──────────────────────────────────────────────────

    public function getActive()
    {
        $eventId = session('admin_event_id');
        if (!$eventId) {
            return response()->json(['error' => 'Event session tidak ditemukan'], 403);
        }

        // Fetch the most recent layout for the builder, regardless of active status
        $layout = CertificateLayout::where('event_id', $eventId)
            ->orderByDesc('id')
            ->first();

        if (!$layout) {
            return response()->json([
                'layout' => null,
                'exists' => false,
            ]);
        }

        [$normalizedLayout, $convertedLegacy] = $this->normalizeLayout($layout->layout_json ?? []);

        return response()->json([
            'layout'           => $normalizedLayout,
            'exists'           => true,
            'id'               => $layout->id,
            'name'             => $layout->name,
            'status'           => $layout->status,
            'is_locked'        => $layout->isLocked(),
            'version'          => $layout->version,
            'converted_legacy' => $convertedLegacy,
            'background_path'  => $layout->background_path,
            'event_logo_path'  => $layout->event_logo_path,
            'org_logo_path'    => $layout->org_logo_path,
        ]);
    }

    // ──────────────────────────────────────────────────
    // Save / create a new DRAFT layout
    // ──────────────────────────────────────────────────

    public function save(Request $request)
    {
        $eventId = session('admin_event_id');
        if (!$eventId) {
            return response()->json(['error' => 'Event session tidak ditemukan'], 403);
        }

        // Refuse to save over a published (locked) layout
        // We check the latest layout because that's what the builder is editing.
        $existing = CertificateLayout::where('event_id', $eventId)
            ->orderByDesc('id')
            ->first();

        if ($existing && $existing->isLocked()) {
            return response()->json([
                'error' => 'This layout is already published and cannot be modified. Please Revert to Draft to edit it.',
            ], 422);
        }

        $validated = $request->validate([
            'layout_json' => 'required|json',
            'name'        => 'nullable|string|max:255',
        ]);

        $layoutJson = json_decode($validated['layout_json'], true);

        if (!$layoutJson || !isset($layoutJson['elements'])) {
            return response()->json(['error' => 'Layout JSON tidak valid'], 422);
        }

        [$normalizedLayout] = $this->normalizeLayout($layoutJson);

        $existingDraft = CertificateLayout::where('event_id', $eventId)
            ->where('status', 'draft')
            ->orderByDesc('id')
            ->first();

        if ($existingDraft) {
            $existingDraft->update([
                'name'        => $validated['name'] ?? $existingDraft->name,
                'layout_json' => $normalizedLayout,
                'is_active'   => true, // Reactivate it just in case it was reverted
                'updated_by'  => session('admin_id'),
            ]);
            $layout = $existingDraft;
        } else {
            $layout = CertificateLayout::create([
                'event_id'    => $eventId,
                'name'        => $validated['name'] ?? 'Layout ' . now()->format('d/m/Y H:i'),
                'status'      => 'draft',
                'is_active'   => true,
                'version'     => 1,
                'layout_json' => $normalizedLayout,
                'created_by'  => session('admin_id'),
                'updated_by'  => session('admin_id'),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Layout sertifikat berhasil disimpan',
            'layout'  => $layout,
            'id'      => $layout->id,
        ], 201);
    }

    // ──────────────────────────────────────────────────
    // Publish a draft layout (locks it)
    // ──────────────────────────────────────────────────

    public function publish(Request $request, CertificateLayout $layout)
    {
        $eventId = session('admin_event_id');
        abort_unless($layout->event_id == $eventId, 403);

        if ($layout->isLocked()) {
            return response()->json(['error' => 'This layout is already published.'], 422);
        }

        // Deactivate previously published layouts for this event
        CertificateLayout::where('event_id', $eventId)
            ->where('is_active', true)
            ->where('id', '!=', $layout->id)
            ->update(['is_active' => false]);

        $layout->update([
            'status'     => 'published',
            'is_active'  => true,
            'updated_by' => session('admin_id'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Layout has been published and locked.'
        ]);
    }

    // ──────────────────────────────────────────────────
    // Revert a published layout to draft
    // ──────────────────────────────────────────────────

    public function unpublish(CertificateLayout $layout)
    {
        $eventId = session('admin_event_id');
        abort_unless($layout->event_id == $eventId, 403);

        // Revert layout to draft
        $layout->update([
            'status'     => 'draft',
            'is_active'  => false,
            'updated_by' => session('admin_id'),
        ]);

        // Delete all generated certificates for this event so the admin can republish cleanly
        $deletedCount = \App\Models\Certificate::where('event_id', $eventId)->delete();

        return back()->with('success', "Layout reverted to Draft. {$deletedCount} generated certificate(s) have been cleared. You can now edit and re-publish.");
    }

    // ──────────────────────────────────────────────────
    // Reset to default layout (creates new draft)
    // ──────────────────────────────────────────────────

    public function resetDefault()
    {
        $eventId = session('admin_event_id');
        if (!$eventId) {
            return response()->json(['error' => 'Event session tidak ditemukan'], 403);
        }

        $defaultLayout = CertificateLayout::getDefaultLayout();

        CertificateLayout::where('event_id', $eventId)
            ->where('is_active', true)
            ->where('status', 'draft')
            ->update(['is_active' => false]);

        $layout = CertificateLayout::create([
            'event_id'    => $eventId,
            'name'        => 'Default Layout (Reset)',
            'status'      => 'draft',
            'is_active'   => true,
            'version'     => 1,
            'layout_json' => $defaultLayout,
            'created_by'  => session('admin_id'),
            'updated_by'  => session('admin_id'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Layout berhasil di-reset ke default',
            'layout'  => $layout,
        ], 201);
    }

    // ──────────────────────────────────────────────────
    // Duplicate a layout (creates a new draft copy)
    // ──────────────────────────────────────────────────

    public function duplicate(CertificateLayout $layout)
    {
        $eventId = session('admin_event_id');
        abort_unless($layout->event_id == $eventId, 403);

        // Deactivate existing active drafts
        CertificateLayout::where('event_id', $eventId)
            ->where('is_active', true)
            ->where('status', 'draft')
            ->update(['is_active' => false]);

        $copy = CertificateLayout::create([
            'event_id'         => $eventId,
            'name'             => $layout->name . ' (Salinan)',
            'status'           => 'draft',
            'is_active'        => true,
            'version'          => $layout->version + 1,
            'layout_json'      => $layout->layout_json,
            'background_path'  => $layout->background_path,
            'event_logo_path'  => $layout->event_logo_path,
            'org_logo_path'    => $layout->org_logo_path,
            'created_by'       => session('admin_id'),
            'updated_by'       => session('admin_id'),
            'duplicated_from'  => $layout->id,
        ]);

        return redirect()->route('admin.certificate-layouts.builder')
            ->with('success', 'Layout berhasil diduplikat sebagai draft baru. Sekarang Anda dapat mengeditnya.');
    }

    // ──────────────────────────────────────────────────
    // Preview sample with placeholder data
    // ──────────────────────────────────────────────────

    public function previewSample()
    {
        $eventId = session('admin_event_id');
        if (!$eventId) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin event session tidak ditemukan');
        }

        $event = Event::findOrFail($eventId);

        $layoutModel = CertificateLayout::where('event_id', $eventId)
            ->where('is_active', true)
            ->orderByDesc('id')
            ->first();

        $layout = null;
        if ($layoutModel) {
            [$layout] = $this->normalizeLayout($layoutModel->layout_json ?? []);
        } else {
            $layout = CertificateLayout::getDefaultLayout();
        }

        // Sample payload data for preview
        $samplePayload = [
            'volunteer_name'  => 'Nama Relawan Contoh',
            'role_label'      => 'Fotografer / Media',
            'event_title'     => $event->title,
            'event_start_at'  => $event->start_at?->format('d F Y') ?? '1 Januari 2026',
            'event_end_at'    => $event->end_at?->format('d F Y') ?? '5 Januari 2026',
            'issue_date'      => now()->format('d F Y'),
            'event_logo_path' => $event->logo_path,
            'org_logo_path'   => $layoutModel?->org_logo_path,
            'qr_url'          => url('/sertifikat/verify/SAMPLE_TOKEN'),
        ];

        return view('admin.certificates.preview-sample', [
            'layout'        => $layout,
            'event'         => $event,
            'layoutModel'   => $layoutModel,
            'samplePayload' => $samplePayload,
        ]);
    }

    // ──────────────────────────────────────────────────
    // Asset upload (background, event logo, org logo)
    // ──────────────────────────────────────────────────

    public function uploadAsset(Request $request, CertificateLayout $layout)
    {
        $eventId = session('admin_event_id');
        abort_unless($layout->event_id == $eventId, 403);

        if ($layout->isLocked()) {
            return response()->json(['error' => 'Layout sudah dipublikasikan dan tidak dapat diubah.'], 422);
        }

        $request->validate([
            'type'  => 'required|in:background,event_logo,org_logo',
            'file'  => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $type = $request->input('type');
        $column = match($type) {
            'background' => 'background_path',
            'event_logo' => 'event_logo_path',
            'org_logo'   => 'org_logo_path',
        };

        if ($layout->$column) {
            Storage::disk('public')->delete($layout->$column);
        }

        $path = $request->file('file')->store("certificate-assets/{$eventId}", 'public');

        $layout->update([
            $column      => $path,
            'updated_by' => session('admin_id'),
        ]);

        return response()->json([
            'success' => true,
            'path'    => $path,
            'url'     => Storage::disk('public')->url($path),
        ]);
    }

    // ──────────────────────────────────────────────────
    // Upload signature image for a specific element
    // ──────────────────────────────────────────────────

    public function uploadSignature(Request $request, CertificateLayout $layout)
    {
        $eventId = session('admin_event_id');
        abort_unless($layout->event_id == $eventId, 403);

        if ($layout->isLocked()) {
            return response()->json(['error' => 'Layout sudah dipublikasikan dan tidak dapat diubah.'], 422);
        }

        $request->validate([
            'element_id' => 'required|string|max:100',
            'file'       => 'required|file|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $elementId = $request->input('element_id');
        $path      = $request->file('file')->store("certificate-assets/{$eventId}/signatures", 'public');

        // Patch the layout_json to update signatureImagePath for the matching element
        $layoutJson = is_array($layout->layout_json) ? $layout->layout_json : [];
        $elements   = $layoutJson['elements'] ?? [];

        foreach ($elements as &$el) {
            if (($el['id'] ?? null) === $elementId) {
                // Delete old signature image if exists
                if (!empty($el['signatureImagePath'])) {
                    Storage::disk('public')->delete($el['signatureImagePath']);
                }
                $el['signatureImagePath'] = $path;
                break;
            }
        }
        unset($el);

        $layoutJson['elements'] = $elements;

        $layout->update([
            'layout_json' => $layoutJson,
            'updated_by'  => session('admin_id'),
        ]);

        return response()->json([
            'success'    => true,
            'path'       => $path,
            'url'        => Storage::disk('public')->url($path),
            'element_id' => $elementId,
        ]);
    }

    // ──────────────────────────────────────────────────
    // Normalization helpers (mirrors CardLayoutController)
    // ──────────────────────────────────────────────────

    private function normalizeLayout(array $layout): array
    {
        $convertedLegacy = false;

        if (!$layout) {
            return [CertificateLayout::getDefaultLayout(), false];
        }

        $normalized = $layout;
        $normalized['schemaVersion'] = (string)($layout['schemaVersion'] ?? '1.0.0');
        $normalized['canvasType']    = 'certificate';

        $normalized['contentArea'] = [
            'xMm' => 0.0,
            'yMm' => 0.0,
            'wMm' => 297.0,
            'hMm' => 210.0,
        ];

        $normalized['elements'] = collect($layout['elements'] ?? [])->map(function ($element) use (&$convertedLegacy) {
            $element = is_array($element) ? $element : [];
            $rect = is_array($element['rect'] ?? null) ? $element['rect'] : [];
            [$normalizedRect, $rectLegacy] = $this->normalizeRect($rect);
            $element['rect'] = $normalizedRect;
            $convertedLegacy = $convertedLegacy || $rectLegacy;
            return $element;
        })->values()->all();

        return [$normalized, $convertedLegacy];
    }

    private function normalizeRect(array $rect): array
    {
        $hasMm = isset($rect['xMm'], $rect['yMm'], $rect['wMm'], $rect['hMm']);
        if ($hasMm) {
            return [[
                'xMm' => (float)$rect['xMm'],
                'yMm' => (float)$rect['yMm'],
                'wMm' => (float)$rect['wMm'],
                'hMm' => (float)$rect['hMm'],
            ], false];
        }

        $xPx = $rect['x'] ?? 0;
        $yPx = $rect['y'] ?? 0;
        $wPx = $rect['w'] ?? 0;
        $hPx = $rect['h'] ?? 0;

        return [[
            'xMm' => round(((float)$xPx) / self::PX_PER_MM, 3),
            'yMm' => round(((float)$yPx) / self::PX_PER_MM, 3),
            'wMm' => round(((float)$wPx) / self::PX_PER_MM, 3),
            'hMm' => round(((float)$hPx) / self::PX_PER_MM, 3),
        ], true];
    }
}
