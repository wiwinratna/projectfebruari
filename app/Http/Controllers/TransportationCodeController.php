<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TransportationCode;
use Illuminate\Http\Request;

class TransportationCodeController extends Controller
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
        $transportationCodes = $event->transportationCodes()
            ->orderBy('kode')
            ->get();

        return view('menu.events.transportation-codes.index', compact('event', 'transportationCodes'));
    }

    public function create()
    {
        $event = $this->getEvent();
        return view('menu.events.transportation-codes.create', compact('event'));
    }

    public function store(Request $request)
    {
        $event = $this->getEvent();

        $validated = $request->validate([
            'kode'       => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $exists = $event->transportationCodes()->where('kode', $validated['kode'])->exists();
        if ($exists) {
            return back()->withErrors(['kode' => 'Kode sudah digunakan untuk event ini.'])->withInput();
        }

        $event->transportationCodes()->create($validated);

        return redirect()
            ->route('admin.master-data.transportation-codes.index')
            ->with('status', 'Kode transportasi berhasil ditambahkan.');
    }

    public function edit(TransportationCode $transportationCode)
    {
        $event = $this->getEvent();
        abort_unless($transportationCode->event_id === $event->id, 403);

        return view('menu.events.transportation-codes.edit', compact('event', 'transportationCode'));
    }

    public function update(Request $request, TransportationCode $transportationCode)
    {
        $event = $this->getEvent();
        abort_unless($transportationCode->event_id === $event->id, 403);

        $validated = $request->validate([
            'kode'       => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $exists = $event->transportationCodes()
            ->where('kode', $validated['kode'])
            ->where('id', '!=', $transportationCode->id)
            ->exists();
        if ($exists) {
            return back()->withErrors(['kode' => 'Kode sudah digunakan untuk event ini.'])->withInput();
        }

        $transportationCode->update($validated);

        return redirect()
            ->route('admin.master-data.transportation-codes.index')
            ->with('status', 'Kode transportasi berhasil diperbarui.');
    }

    public function destroy(TransportationCode $transportationCode)
    {
        $event = $this->getEvent();
        abort_unless($transportationCode->event_id === $event->id, 403);

        $transportationCode->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kode transportasi berhasil dihapus.',
        ]);
    }
}
