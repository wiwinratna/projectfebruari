<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\VenueAccess;
use Illuminate\Http\Request;

class VenueAccessController extends Controller
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
        $venueAccesses = $event->venueAccesses()
            ->orderBy('nama_vanue')
            ->get();

        return view('menu.events.venue-accesses.index', compact('event', 'venueAccesses'));
    }

    public function create()
    {
        $event = $this->getEvent();
        return view('menu.events.venue-accesses.create', compact('event'));
    }

    public function store(Request $request)
    {
        $event = $this->getEvent();

        $validated = $request->validate([
            'nama_vanue' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $event->venueAccesses()->create($validated);

        return redirect()
            ->route('admin.master-data.venue-accesses.index')
            ->with('status', 'Venue access berhasil ditambahkan.');
    }

    public function edit(VenueAccess $venueAccess)
    {
        $event = $this->getEvent();
        abort_unless($venueAccess->event_id === $event->id, 403);

        return view('menu.events.venue-accesses.edit', compact('event', 'venueAccess'));
    }

    public function update(Request $request, VenueAccess $venueAccess)
    {
        $event = $this->getEvent();
        abort_unless($venueAccess->event_id === $event->id, 403);

        $validated = $request->validate([
            'nama_vanue' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $venueAccess->update($validated);

        return redirect()
            ->route('admin.master-data.venue-accesses.index')
            ->with('status', 'Venue access berhasil diperbarui.');
    }

    public function destroy(VenueAccess $venueAccess)
    {
        $event = $this->getEvent();
        abort_unless($venueAccess->event_id === $event->id, 403);

        $venueAccess->delete();

        return response()->json([
            'success' => true,
            'message' => 'Venue access berhasil dihapus.',
        ]);
    }
}
