<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\VenueLocation;
use Illuminate\Http\Request;

class VenueLocationController extends Controller
{
    public function index(Event $event)
    {
        $venueLocations = $event->venueLocations()
            ->withCount('disciplins')
            ->orderBy('nama')
            ->get();

        return view('menu.events.venue-locations.index', compact('event', 'venueLocations'));
    }

    public function create(Event $event)
    {
        return view('menu.events.venue-locations.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'gugus'     => 'required|string|max:255',
            'nama'      => 'required|string|max:255',
            'alamat'    => 'nullable|string|max:500',
        ]);

        $event->venueLocations()->create($validated);

        return redirect()
            ->route('admin.events.venue-locations.index', $event)
            ->with('status', 'Venue location berhasil ditambahkan.');
    }

    public function edit(Event $event, VenueLocation $venueLocation)
    {
        abort_unless($venueLocation->event_id === $event->id, 403);

        return view('menu.events.venue-locations.edit', compact('event', 'venueLocation'));
    }

    public function update(Request $request, Event $event, VenueLocation $venueLocation)
    {
        abort_unless($venueLocation->event_id === $event->id, 403);

        $validated = $request->validate([
            'gugus'     => 'required|string|max:255',
            'nama'      => 'required|string|max:255',
            'alamat'    => 'nullable|string|max:500',
        ]);

        $venueLocation->update($validated);

        return redirect()
            ->route('admin.events.venue-locations.index', $event)
            ->with('status', 'Venue location berhasil diperbarui.');
    }

    public function destroy(Event $event, VenueLocation $venueLocation)
    {
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
