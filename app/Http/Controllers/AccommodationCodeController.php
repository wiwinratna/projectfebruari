<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\AccommodationCode;
use Illuminate\Http\Request;

class AccommodationCodeController extends Controller
{
    public function index(Event $event)
    {
        $accommodationCodes = $event->accommodationCodes()
            ->orderBy('kode')
            ->get();

        return view('menu.events.accommodation-codes.index', compact('event', 'accommodationCodes'));
    }

    public function create(Event $event)
    {
        return view('menu.events.accommodation-codes.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'kode'       => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        // Unique check scoped to event
        $exists = $event->accommodationCodes()->where('kode', $validated['kode'])->exists();
        if ($exists) {
            return back()->withErrors(['kode' => 'Kode sudah digunakan untuk event ini.'])->withInput();
        }

        $event->accommodationCodes()->create($validated);

        return redirect()
            ->route('admin.events.accommodation-codes.index', $event)
            ->with('status', 'Kode akomodasi berhasil ditambahkan.');
    }

    public function edit(Event $event, AccommodationCode $accommodationCode)
    {
        abort_unless($accommodationCode->event_id === $event->id, 403);

        return view('menu.events.accommodation-codes.edit', compact('event', 'accommodationCode'));
    }

    public function update(Request $request, Event $event, AccommodationCode $accommodationCode)
    {
        abort_unless($accommodationCode->event_id === $event->id, 403);

        $validated = $request->validate([
            'kode'       => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        // Unique check scoped to event, excluding current record
        $exists = $event->accommodationCodes()
            ->where('kode', $validated['kode'])
            ->where('id', '!=', $accommodationCode->id)
            ->exists();
        if ($exists) {
            return back()->withErrors(['kode' => 'Kode sudah digunakan untuk event ini.'])->withInput();
        }

        $accommodationCode->update($validated);

        return redirect()
            ->route('admin.events.accommodation-codes.index', $event)
            ->with('status', 'Kode akomodasi berhasil diperbarui.');
    }

    public function destroy(Event $event, AccommodationCode $accommodationCode)
    {
        abort_unless($accommodationCode->event_id === $event->id, 403);

        $accommodationCode->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kode akomodasi berhasil dihapus.',
        ]);
    }
}
