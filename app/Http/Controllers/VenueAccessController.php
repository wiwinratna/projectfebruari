<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\VenueAccess;
use Illuminate\Http\Request;

class VenueAccessController extends Controller
{
    public function index(Event $event)
    {
        $venueAccesses = $event->venueAccesses()
            ->orderBy('nama_vanue')
            ->get();

        return view('menu.events.venue-accesses.index', compact('event', 'venueAccesses'));
    }

    public function create(Event $event)
    {
        return view('menu.events.venue-accesses.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'nama_vanue' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $event->venueAccesses()->create($validated);

        return redirect()
            ->route('admin.events.venue-accesses.index', $event)
            ->with('status', 'Venue access berhasil ditambahkan.');
    }

    public function edit(Event $event, VenueAccess $venueAccess)
    {
        abort_unless($venueAccess->event_id === $event->id, 403);

        return view('menu.events.venue-accesses.edit', compact('event', 'venueAccess'));
    }

    public function update(Request $request, Event $event, VenueAccess $venueAccess)
    {
        abort_unless($venueAccess->event_id === $event->id, 403);

        $validated = $request->validate([
            'nama_vanue' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $venueAccess->update($validated);

        return redirect()
            ->route('admin.events.venue-accesses.index', $event)
            ->with('status', 'Venue access berhasil diperbarui.');
    }

    public function destroy(Event $event, VenueAccess $venueAccess)
    {
        abort_unless($venueAccess->event_id === $event->id, 403);

        $venueAccess->delete();

        return response()->json([
            'success' => true,
            'message' => 'Venue access berhasil dihapus.',
        ]);
    }
}
