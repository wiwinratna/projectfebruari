<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\VenueLocation;
use Illuminate\Http\Request;

class VenueLocationController extends Controller
{
    /**
     * Ambil event dari session admin yang sedang login.
     */
    private function getEvent(): Event
    {
        $eventId = session('admin_event_id');
        abort_unless($eventId, 403, 'Admin belum ditugaskan ke event.');
        return Event::findOrFail($eventId);
    }

    public function index()
    {
        $event = $this->getEvent();
        $venueLocations = $event->venueLocations()
            ->withCount('disciplins')
            ->orderBy('nama')
            ->get();

        return view('menu.events.venue-locations.index', compact('event', 'venueLocations'));
    }

    public function create()
    {
        $event = $this->getEvent();
        return view('menu.events.venue-locations.create', compact('event'));
    }

    public function store(Request $request)
    {
        $event = $this->getEvent();

        $validated = $request->validate([
            'gugus'     => 'required|string|max:255',
            'nama'      => 'required|string|max:255',
            'alamat'    => 'nullable|string|max:500',
        ]);

        $event->venueLocations()->create($validated);

        return redirect()
            ->route('admin.master-data.venue-locations.index')
            ->with('status', 'Venue location berhasil ditambahkan.');
    }

    public function edit(VenueLocation $venueLocation)
    {
        $event = $this->getEvent();
        abort_unless($venueLocation->event_id === $event->id, 403);

        return view('menu.events.venue-locations.edit', compact('event', 'venueLocation'));
    }

    public function update(Request $request, VenueLocation $venueLocation)
    {
        $event = $this->getEvent();
        abort_unless($venueLocation->event_id === $event->id, 403);

        $validated = $request->validate([
            'gugus'     => 'required|string|max:255',
            'nama'      => 'required|string|max:255',
            'alamat'    => 'nullable|string|max:500',
        ]);

        $venueLocation->update($validated);

        return redirect()
            ->route('admin.master-data.venue-locations.index')
            ->with('status', 'Venue location berhasil diperbarui.');
    }

    public function destroy(VenueLocation $venueLocation)
    {
        $event = $this->getEvent();
        abort_unless($venueLocation->event_id === $event->id, 403);

        if ($venueLocation->disciplins()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus, venue masih digunakan oleh disiplin.',
            ], 422);
        }

        $venueLocation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Venue location berhasil dihapus.',
        ]);
    }
}
