<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\AccommodationCode;
use Illuminate\Http\Request;

class AccommodationCodeController extends Controller
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
        $accommodationCodes = $event->accommodationCodes()
            ->orderBy('kode')
            ->get();

        return view('menu.events.accommodation-codes.index', compact('event', 'accommodationCodes'));
    }

    public function create()
    {
        $event = $this->getEvent();
        return view('menu.events.accommodation-codes.create', compact('event'));
    }

    public function store(Request $request)
    {
        $event = $this->getEvent();

        $validated = $request->validate([
            'kode'       => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $exists = $event->accommodationCodes()->where('kode', $validated['kode'])->exists();
        if ($exists) {
            return back()->withErrors(['kode' => 'Kode sudah digunakan untuk event ini.'])->withInput();
        }

        $event->accommodationCodes()->create($validated);

        return redirect()
            ->route('admin.master-data.accommodation-codes.index')
            ->with('status', 'Kode akomodasi berhasil ditambahkan.');
    }

    public function edit(AccommodationCode $accommodationCode)
    {
        $event = $this->getEvent();
        abort_unless($accommodationCode->event_id === $event->id, 403);

        return view('menu.events.accommodation-codes.edit', compact('event', 'accommodationCode'));
    }

    public function update(Request $request, AccommodationCode $accommodationCode)
    {
        $event = $this->getEvent();
        abort_unless($accommodationCode->event_id === $event->id, 403);

        $validated = $request->validate([
            'kode'       => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $exists = $event->accommodationCodes()
            ->where('kode', $validated['kode'])
            ->where('id', '!=', $accommodationCode->id)
            ->exists();
        if ($exists) {
            return back()->withErrors(['kode' => 'Kode sudah digunakan untuk event ini.'])->withInput();
        }

        $accommodationCode->update($validated);

        return redirect()
            ->route('admin.master-data.accommodation-codes.index')
            ->with('status', 'Kode akomodasi berhasil diperbarui.');
    }

    public function destroy(AccommodationCode $accommodationCode)
    {
        $event = $this->getEvent();
        abort_unless($accommodationCode->event_id === $event->id, 403);

        $accommodationCode->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kode akomodasi berhasil dihapus.',
        ]);
    }
}
