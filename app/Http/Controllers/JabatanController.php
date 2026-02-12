<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Jabatan;
use Illuminate\Http\Request;

/**
 * CRUD Controller for Jabatan, scoped to an Event.
 */
class JabatanController extends Controller
{
    public function index(Event $event)
    {
        $jabatanList = $event->jabatan()
            ->withCount('accreditations')
            ->orderBy('nama_jabatan')
            ->get();

        return view('menu.events.jabatan.index', compact('event', 'jabatanList'));
    }

    public function create(Event $event)
    {
        return view('menu.events.jabatan.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'nama_jabatan' => 'required|string|max:255',
        ]);

        $event->jabatan()->create($validated);

        return redirect()
            ->route('admin.events.jabatan.index', $event)
            ->with('status', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Event $event, Jabatan $jabatan)
    {
        abort_unless($jabatan->event_id === $event->id, 403);

        return view('menu.events.jabatan.edit', compact('event', 'jabatan'));
    }

    public function update(Request $request, Event $event, Jabatan $jabatan)
    {
        abort_unless($jabatan->event_id === $event->id, 403);

        $validated = $request->validate([
            'nama_jabatan' => 'required|string|max:255',
        ]);

        $jabatan->update($validated);

        return redirect()
            ->route('admin.events.jabatan.index', $event)
            ->with('status', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Event $event, Jabatan $jabatan)
    {
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
