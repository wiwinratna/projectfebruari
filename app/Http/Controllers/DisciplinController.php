<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Disciplin;
use App\Models\Sport;
use Illuminate\Http\Request;

class DisciplinController extends Controller
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
        $disciplins = $event->disciplins()
            ->with(['sport:id,name,code', 'venueLocation:id,nama'])
            ->orderBy('nama_disiplin')
            ->get();

        return view('menu.events.disciplins.index', compact('event', 'disciplins'));
    }

    public function create()
    {
        $event = $this->getEvent();

        $sports = Sport::where('is_active', true)
            ->select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        $venueLocations = $event->venueLocations()
            ->select('id', 'nama')
            ->orderBy('nama')
            ->get();

        return view('menu.events.disciplins.create', compact('event', 'sports', 'venueLocations'));
    }

    public function store(Request $request)
    {
        $event = $this->getEvent();

        $validated = $request->validate([
            'sport_id'          => 'required|integer|exists:sports,id',
            'venue_location_id' => 'required|integer|exists:venue_locations,id',
            'nama_disiplin'     => 'required|string|max:255',
            'keterangan'        => 'nullable|string|max:1000',
        ]);

        $venueLocation = $event->venueLocations()->find($validated['venue_location_id']);
        abort_unless($venueLocation !== null, 422, 'Venue location tidak valid untuk event ini.');

        $event->disciplins()->create($validated);

        return redirect()
            ->route('admin.master-data.disciplins.index')
            ->with('status', 'Disiplin berhasil ditambahkan.');
    }

    public function edit(Disciplin $disciplin)
    {
        $event = $this->getEvent();
        abort_unless($disciplin->event_id === $event->id, 403);

        $sports = Sport::where('is_active', true)
            ->select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        $venueLocations = $event->venueLocations()
            ->select('id', 'nama')
            ->orderBy('nama')
            ->get();

        return view('menu.events.disciplins.edit', compact('event', 'disciplin', 'sports', 'venueLocations'));
    }

    public function update(Request $request, Disciplin $disciplin)
    {
        $event = $this->getEvent();
        abort_unless($disciplin->event_id === $event->id, 403);

        $validated = $request->validate([
            'sport_id'          => 'required|integer|exists:sports,id',
            'venue_location_id' => 'required|integer|exists:venue_locations,id',
            'nama_disiplin'     => 'required|string|max:255',
            'keterangan'        => 'nullable|string|max:1000',
        ]);

        $venueLocation = $event->venueLocations()->find($validated['venue_location_id']);
        abort_unless($venueLocation !== null, 422, 'Venue location tidak valid untuk event ini.');

        $disciplin->update($validated);

        return redirect()
            ->route('admin.master-data.disciplins.index')
            ->with('status', 'Disiplin berhasil diperbarui.');
    }

    public function destroy(Disciplin $disciplin)
    {
        $event = $this->getEvent();
        abort_unless($disciplin->event_id === $event->id, 403);

        $disciplin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Disiplin berhasil dihapus.',
        ]);
    }
}
