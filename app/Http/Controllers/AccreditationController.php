<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Accreditation;
use Illuminate\Http\Request;

/**
 * CRUD Controller for Accreditation, scoped to an Event.
 * Eager loads jabatan to prevent N+1.
 */
class AccreditationController extends Controller
{
    public function index(Event $event)
    {
        $accreditations = $event->accreditations()
            ->with('jabatan:id,nama_jabatan')
            ->orderBy('nama_akreditasi')
            ->get();

        return view('menu.events.accreditations.index', compact('event', 'accreditations'));
    }

    public function create(Event $event)
    {
        // Only load jabatan for this event (memory efficient)
        $jabatanList = $event->jabatan()
            ->select('id', 'nama_jabatan')
            ->orderBy('nama_jabatan')
            ->get();

        return view('menu.events.accreditations.create', compact('event', 'jabatanList'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'jabatan_id'       => 'required|integer|exists:jabatan,id',
            'nama_akreditasi'  => 'required|string|max:255',
            'warna'            => 'nullable|string|max:50',
            'keterangan'       => 'nullable|string|max:1000',
        ]);

        // Access control: verify jabatan belongs to this event
        $jabatan = $event->jabatan()->find($validated['jabatan_id']);
        abort_unless($jabatan !== null, 422, 'Jabatan tidak valid untuk event ini.');

        $event->accreditations()->create($validated);

        return redirect()
            ->route('admin.events.accreditations.index', $event)
            ->with('status', 'Akreditasi berhasil ditambahkan.');
    }

    public function edit(Event $event, Accreditation $accreditation)
    {
        abort_unless($accreditation->event_id === $event->id, 403);

        $jabatanList = $event->jabatan()
            ->select('id', 'nama_jabatan')
            ->orderBy('nama_jabatan')
            ->get();

        return view('menu.events.accreditations.edit', compact('event', 'accreditation', 'jabatanList'));
    }

    public function update(Request $request, Event $event, Accreditation $accreditation)
    {
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
            ->route('admin.events.accreditations.index', $event)
            ->with('status', 'Akreditasi berhasil diperbarui.');
    }

    public function destroy(Event $event, Accreditation $accreditation)
    {
        abort_unless($accreditation->event_id === $event->id, 403);

        $accreditation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Akreditasi berhasil dihapus.',
        ]);
    }
}
