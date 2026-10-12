<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventTrainingMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Event;

class EventMaterialController extends Controller
{
    /**
     * View list of training materials for the admin's event.
     */
    public function index()
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin event session tidak ditemukan');
        }

        $event = Event::findOrFail($eventId);
        $materials = EventTrainingMaterial::where('event_id', $eventId)
            ->latest()
            ->paginate(15);

        return view('admin.events.materials.index', compact('materials', 'event'));
    }

    /**
     * Store a new training material.
     */
    public function store(Request $request)
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId) {
            return back()->with('error', 'Admin event session tidak ditemukan');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:20480', // max 20MB
            'link_url' => 'nullable|url|max:1000',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after_or_equal:available_from',
        ]);

        if (!$request->hasFile('file') && empty($validated['link_url'])) {
            return back()->with('error', 'Harap unggah file dokumen atau masukkan tautan link/video materi.')->withInput();
        }

        $filePath = null;
        $originalFilename = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('event-materials/' . $eventId, 'local');
            
            if (!$filePath) {
                return back()->with('error', 'Gagal mengunggah file.')->withInput();
            }
            $originalFilename = $file->getClientOriginalName();
        }

        EventTrainingMaterial::create([
            'event_id' => $eventId,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'original_filename' => $originalFilename,
            'link_url' => $validated['link_url'] ?? null,
            'available_from' => $validated['available_from'] ? Carbon::parse($validated['available_from']) : null,
            'available_until' => $validated['available_until'] ? Carbon::parse($validated['available_until']) : null,
        ]);

        return back()->with('success', 'Materi pelatihan berhasil ditambahkan.');
    }

    /**
     * Delete a training material.
     */
    public function destroy(EventTrainingMaterial $material)
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId || $material->event_id !== $eventId) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus materi ini.');
        }

        if (Storage::disk('local')->exists($material->file_path)) {
            Storage::disk('local')->delete($material->file_path);
        }

        $material->delete();

        return back()->with('success', 'Materi pelatihan berhasil dihapus.');
    }
}
