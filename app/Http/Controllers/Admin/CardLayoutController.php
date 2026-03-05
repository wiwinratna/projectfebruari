<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CardLayout;
use App\Models\Event;
use App\Models\Card;
use Illuminate\Http\Request;

class CardLayoutController extends Controller
{
    private const PX_PER_MM = 3.77953;

    /**
     * Show card builder view
     */
    public function builder()
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin event session tidak ditemukan');
        }

        $event = Event::findOrFail($eventId);
        $activeLayout = $event->activeCardLayout;

        return view('admin.card_layouts.builder', compact('event', 'activeLayout'));
    }

    /**
     * Get active layout for event (JSON API)
     */
    public function getActive()
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId) {
            return response()->json(['error' => 'Event session tidak ditemukan'], 403);
        }

        $layout = CardLayout::where('event_id', $eventId)
            ->where('is_active', true)
            ->orderByDesc('id')
            ->first();

        if (!$layout) {
            return response()->json([
                'layout' => null,
                'exists' => false,
            ]);
        }

        [$normalizedLayout, $convertedLegacy] = $this->normalizeLayoutToMm($layout->layout_json ?? []);

        if ($convertedLegacy) {
            $layout->update([
                'layout_json' => $normalizedLayout,
                'updated_by' => auth()->id(),
            ]);
        }

        return response()->json([
            'layout' => $normalizedLayout,
            'exists' => true,
            'id' => $layout->id,
            'name' => $layout->name,
            'version' => $layout->version,
            'converted_legacy' => $convertedLegacy,
        ]);
    }

    /**
     * Save or update layout
     */
    public function save(Request $request)
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId) {
            return response()->json(['error' => 'Event session tidak ditemukan'], 403);
        }

        $validated = $request->validate([
            'layout_json' => 'required|json',
            'name' => 'nullable|string|max:255',
        ]);

        $layoutJson = json_decode($validated['layout_json'], true);
        
        if (!$layoutJson) {
            return response()->json(['error' => 'Layout JSON tidak valid'], 422);
        }

        if (!isset($layoutJson['schemaVersion']) || !isset($layoutJson['elements'])) {
            return response()->json(['error' => 'Layout structure tidak sesuai'], 422);
        }

        [$normalizedLayout, $convertedLegacy] = $this->normalizeLayoutToMm($layoutJson);

        CardLayout::where('event_id', $eventId)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $layout = CardLayout::create([
            'event_id' => $eventId,
            'name' => $validated['name'] ?? 'Layout ' . now()->format('d/m/Y H:i'),
            'is_active' => true,
            'version' => 1,
            'layout_json' => $normalizedLayout,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Layout berhasil disimpan',
            'layout' => $layout,
            'id' => $layout->id,
            'converted_legacy' => $convertedLegacy,
        ], 201);
    }

    /**
     * Reset to default layout
     */
    public function resetDefault()
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId) {
            return response()->json(['error' => 'Event session tidak ditemukan'], 403);
        }

        $defaultLayout = CardLayout::getDefaultLayout();

        // Disable semua layout aktif lainnya
        CardLayout::where('event_id', $eventId)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        // Buat layout default
        $layout = CardLayout::create([
            'event_id' => $eventId,
            'name' => 'Default Layout (Reset)',
            'is_active' => true,
            'version' => 1,
            'layout_json' => $defaultLayout,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Layout berhasil di-reset ke default',
            'layout' => $layout,
        ], 201);
    }

    /**
     * Preview sample card dengan layout aktif
     */
    public function previewSample()
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin event session tidak ditemukan');
        }

        $event = Event::findOrFail($eventId);
        $layoutModel = CardLayout::where('event_id', $eventId)
            ->where('is_active', true)
            ->orderByDesc('id')
            ->first();

        $layout = null;
        if ($layoutModel) {
            [$layout, $convertedLegacy] = $this->normalizeLayoutToMm($layoutModel->layout_json ?? []);
            if ($convertedLegacy) {
                $layoutModel->update([
                    'layout_json' => $layout,
                    'updated_by' => auth()->id(),
                ]);
            }
        }

        // Buat sample data untuk preview
        $sampleCard = (object) [
            'id' => 0,
            'event_id' => $event->id,
            'card_number' => 'SAMPLE-001',
            'application' => (object) [
                'user_id' => 0,
                'user' => (object) [
                    'name' => 'Nama Peserta Contoh',
                    'profile' => (object) [
                        'profile_photo' => null,
                    ],
                ],
            ],
            'mapping' => (object) [
                'name' => 'VIP',
                'color_hex' => '#DC2626',
            ],
            'snapshot' => [
                'applicant_name' => 'Sample Participant Name',
                'applicant_photo' => null,
                'mapping_name' => 'VIP',
                'mapping_color' => '#DC2626',
                'job_category_name' => 'ROLE / POSITION',
                'transports' => [],
                'accommodations' => [],
                'venue_chips' => [],
                'zone_chips' => [],
            ],
            'status' => 'draft',
            'issued_at' => null,
        ];

        return view('admin.card_layouts.preview-sample', [
            'card' => $sampleCard,
            'layout' => $layout,
            'event' => $event,
        ]);
    }

    private function normalizeLayoutToMm(array $layout): array
    {
        $convertedLegacy = false;
        if (!$layout) {
            return [CardLayout::getDefaultLayout(), false];
        }

        $normalized = $layout;
        $normalized['schemaVersion'] = (string)($layout['schemaVersion'] ?? '1.0.0');

        // Canonical origin: top-left of full card (no extra offset)
        $normalized['contentArea'] = [
            'xMm' => 0.0,
            'yMm' => 0.0,
            'wMm' => 148.0,
            'hMm' => 210.0,
        ];

        $normalized['elements'] = collect($layout['elements'] ?? [])->map(function ($element) use (&$convertedLegacy) {
            $element = is_array($element) ? $element : [];
            $rect = is_array($element['rect'] ?? null) ? $element['rect'] : [];
            [$normalizedRect, $rectLegacy] = $this->normalizeRectToMm($rect, $element);
            $element['rect'] = $normalizedRect;
            $convertedLegacy = $convertedLegacy || $rectLegacy;
            return $element;
        })->values()->all();

        return [$normalized, $convertedLegacy];
    }

    private function normalizeRectToMm(array $rect, array $fallback): array
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

        $xPx = $rect['x'] ?? $fallback['x'] ?? 0;
        $yPx = $rect['y'] ?? $fallback['y'] ?? 0;
        $wPx = $rect['w'] ?? $fallback['w'] ?? 0;
        $hPx = $rect['h'] ?? $fallback['h'] ?? 0;

        return [[
            'xMm' => round(((float)$xPx) / self::PX_PER_MM, 3),
            'yMm' => round(((float)$yPx) / self::PX_PER_MM, 3),
            'wMm' => round(((float)$wPx) / self::PX_PER_MM, 3),
            'hMm' => round(((float)$hPx) / self::PX_PER_MM, 3),
        ], true];
    }
}
