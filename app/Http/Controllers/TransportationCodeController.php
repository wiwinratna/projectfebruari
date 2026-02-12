<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TransportationCode;
use Illuminate\Http\Request;

class TransportationCodeController extends Controller
{
    public function index(Event $event)
    {
        $transportationCodes = $event->transportationCodes()
            ->orderBy('kode')
            ->get();

        return view('menu.events.transportation-codes.index', compact('event', 'transportationCodes'));
    }

    public function create(Event $event)
    {
        return view('menu.events.transportation-codes.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
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
            ->route('admin.events.transportation-codes.index', $event)
            ->with('status', 'Kode transportasi berhasil ditambahkan.');
    }

    public function edit(Event $event, TransportationCode $transportationCode)
    {
        abort_unless($transportationCode->event_id === $event->id, 403);

        return view('menu.events.transportation-codes.edit', compact('event', 'transportationCode'));
    }

    public function update(Request $request, Event $event, TransportationCode $transportationCode)
    {
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
            ->route('admin.events.transportation-codes.index', $event)
            ->with('status', 'Kode transportasi berhasil diperbarui.');
    }

    public function destroy(Event $event, TransportationCode $transportationCode)
    {
        abort_unless($transportationCode->event_id === $event->id, 403);

        $transportationCode->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kode transportasi berhasil dihapus.',
        ]);
    }
}
