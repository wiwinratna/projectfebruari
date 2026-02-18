<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Accreditation;
use Illuminate\Http\Request;

/**
 * CRUD Controller for Accreditation, scoped to admin's event via session.
 */
class AccreditationController extends Controller
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
        $accreditations = $event->accreditations()
            ->with('jabatan:id,nama_jabatan')
            ->orderBy('nama_akreditasi')
            ->get();

        return view('menu.events.accreditations.index', compact('event', 'accreditations'));
    }

    public function create()
    {
        $event = $this->getEvent();
        $jabatanList = $event->jabatan()
            ->select('id', 'nama_jabatan')
            ->orderBy('nama_jabatan')
            ->get();

        return view('menu.events.accreditations.create', compact('event', 'jabatanList'));
    }

    public function store(Request $request)
    {
        $event = $this->getEvent();

        $validated = $request->validate([
            'jabatan_id'       => 'required|integer|exists:jabatan,id',
            'nama_akreditasi'  => 'required|string|max:255',
            'warna'            => 'nullable|string|max:50',
            'keterangan'       => 'nullable|string|max:1000',
        ]);

        $jabatan = $event->jabatan()->find($validated['jabatan_id']);
        abort_unless($jabatan !== null, 422, 'Jabatan tidak valid untuk event ini.');

        $event->accreditations()->create($validated);

        return redirect()
            ->route('admin.master-data.accreditations.index')
            ->with('status', 'Akreditasi berhasil ditambahkan.');
    }

    public function edit(Accreditation $accreditation)
    {
        $event = $this->getEvent();
        abort_unless($accreditation->event_id === $event->id, 403);

        $jabatanList = $event->jabatan()
            ->select('id', 'nama_jabatan')
            ->orderBy('nama_jabatan')
            ->get();

        return view('menu.events.accreditations.edit', compact('event', 'accreditation', 'jabatanList'));
    }

    public function update(Request $request, Accreditation $accreditation)
    {
        $event = $this->getEvent();
        abort_unless($accreditation->event_id === $event->id, 403);

        $validated = $request->validate([
            'jabatan_id'       => 'required|integer|exists:jabatan,id',
            'nama_akreditasi'  => 'required|string|max:255',
            'warna'            => 'nullable|string|max:50',
            'keterangan'       => 'nullable|string|max:1000',
        ]);

        $jabatan = $event->jabatan()->find($validated['jabatan_id']);
        abort_unless($jabatan !== null, 422, 'Jabatan tidak valid untuk event ini.');

        $accreditation->update($validated);

        return redirect()
            ->route('admin.master-data.accreditations.index')
            ->with('status', 'Akreditasi berhasil diperbarui.');
    }

    public function destroy(Accreditation $accreditation)
    {
        $event = $this->getEvent();
        abort_unless($accreditation->event_id === $event->id, 403);

        $accreditation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Akreditasi berhasil dihapus.',
        ]);
    }
}
