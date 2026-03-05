<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CardLayout;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventSettingsController extends Controller
{
    /**
     * Show event settings form with card layout builder
     */
    public function edit()
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin event session tidak ditemukan');
        }

        $event = Event::findOrFail($eventId);
        \Log::info('DEBUG card_template_path', ['card_template_path' => $event->card_template_path]);
        // Uncomment for direct browser debug:
        // dd($event->card_template_path);
        return view('admin.events.settings-with-builder', compact('event'));
    }

    /**
     * Update event settings (title, logo, template)
     */
    public function update(Request $request)
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId) {
            return back()->with('error', 'Admin event session tidak ditemukan');
        }

        $event = Event::findOrFail($eventId);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
            'card_template' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:10240',
        ], [
            'logo.image' => 'Logo harus berupa gambar',
            'logo.mimes' => 'Logo hanya boleh PNG, JPG, JPEG, atau WebP',
            'logo.max' => 'Logo maksimal 5MB',
            'card_template.image' => 'Template kartu harus berupa gambar',
            'card_template.mimes' => 'Template kartu hanya boleh PNG, JPG, JPEG, atau WebP',
            'card_template.max' => 'Template kartu maksimal 10MB',
        ]);

        $updated = false;

        // Update title only when explicitly provided from "Save Event Info"
        if ($request->filled('title')) {
            $event->update(['title' => $validated['title']]);
            $updated = true;
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Remove old logo jika ada
            if ($event->logo_path && Storage::disk('public')->exists($event->logo_path)) {
                Storage::disk('public')->delete($event->logo_path);
            }

            $logoFile = $request->file('logo');
            $logoPath = $logoFile->store(
                'event-logos/' . $event->id,
                'public'
            );
            $event->update(['logo_path' => $logoPath]);
            $updated = true;
        }

        // Handle card template upload
        if ($request->hasFile('card_template')) {
            // Remove old template jika ada
            if ($event->card_template_path && Storage::disk('public')->exists($event->card_template_path)) {
                Storage::disk('public')->delete($event->card_template_path);
            }

            $templateFile = $request->file('card_template');
            $templatePath = $templateFile->store(
                'card-templates/' . $event->id,
                'public'
            );
            
            $event->update([
                'card_template_path' => $templatePath,
                'card_template_updated_at' => now(),
            ]);

            return redirect()->route('admin.event.settings.edit')
                ->with('success', 'Template kartu berhasil di-upload. Card Layout Editor siap digunakan.');
        }

        if (!$updated) {
            return back()->with('error', 'Tidak ada perubahan yang dikirim');
        }

        return back()->with('success', 'Pengaturan event berhasil diperbarui');
    }

    /**
     * Remove event logo
     */
    public function removeLogo(Request $request)
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId) {
            return response()->json(['error' => 'Event session tidak ditemukan'], 403);
        }

        $event = Event::findOrFail($eventId);

        if ($event->logo_path && Storage::disk('public')->exists($event->logo_path)) {
            Storage::disk('public')->delete($event->logo_path);
        }

        $event->update(['logo_path' => null]);

        return response()->json(['success' => true, 'message' => 'Logo berhasil dihapus']);
    }

    /**
     * Remove event card template
     */
    public function removeTemplate(Request $request)
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId) {
            return response()->json(['error' => 'Event session tidak ditemukan'], 403);
        }

        $event = Event::findOrFail($eventId);

        if ($event->card_template_path && Storage::disk('public')->exists($event->card_template_path)) {
            Storage::disk('public')->delete($event->card_template_path);
        }

        $event->update([
            'card_template_path' => null,
            'card_template_updated_at' => null,
        ]);

        // Automatic fallback to Mode 1 default layout
        CardLayout::where('event_id', $event->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        CardLayout::create([
            'event_id' => $event->id,
            'name' => 'Default Layout (Auto Fallback)',
            'is_active' => true,
            'version' => 1,
            'layout_json' => CardLayout::getDefaultLayout(),
            'created_by' => session('admin_id'),
            'updated_by' => session('admin_id'),
        ]);

        return response()->json(['success' => true, 'message' => 'Template kartu berhasil dihapus']);
    }
}
