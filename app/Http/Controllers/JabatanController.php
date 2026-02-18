<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Jabatan;
use Illuminate\Http\Request;

/**
 * CRUD Controller for Jabatan, scoped to admin's event via session.
 */
class JabatanController extends Controller
{
    private function getEvent(): Event
    {
        $eventId = session('admin_event_id');
        abort_unless($eventId, 403, 'Admin belum ditugaskan ke event.');
        return Event::findOrFail($eventId);
    }

    public function index()
    {
        $event = $this->getEvent();
        $jabatanList = $event->jabatan()
            ->withCount('accreditations')
            ->orderBy('nama_jabatan')
            ->get();

        return view('menu.events.jabatan.index', compact('event', 'jabatanList'));
    }

    public function create()
    {
        $event = $this->getEvent();
        return view('menu.events.jabatan.create', compact('event'));
    }

    public function store(Request $request)
    {
        $event = $this->getEvent();

        $validated = $request->validate([
            'nama_jabatan' => 'required|string|max:255',
        ]);

        $event->jabatan()->create($validated);

        return redirect()
            ->route('admin.master-data.jabatan.index')
            ->with('status', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        $event = $this->getEvent();
        abort_unless($jabatan->event_id === $event->id, 403);

        return view('menu.events.jabatan.edit', compact('event', 'jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $event = $this->getEvent();
        abort_unless($jabatan->event_id === $event->id, 403);

        $validated = $request->validate([
            'nama_jabatan' => 'required|string|max:255',
        ]);

        $jabatan->update($validated);

        return redirect()
            ->route('admin.master-data.jabatan.index')
            ->with('status', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $event = $this->getEvent();
        abort_unless($jabatan->event_id === $event->id, 403);

        $accreditationsCount = $jabatan->accreditations()->count();
        if ($accreditationsCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus jabatan yang digunakan oleh ' . $accreditationsCount . ' akreditasi',
            ], 422);
        }

        $jabatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jabatan berhasil dihapus.',
        ]);
    }
}
