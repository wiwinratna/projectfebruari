<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\ZoneAccessCode;
use Illuminate\Http\Request;

class ZoneAccessCodeController extends Controller
{
    public function index(Event $event)
    {
        $zoneAccessCodes = $event->zoneAccessCodes()
            ->orderBy('kode_zona')
            ->get();

        return view('menu.events.zone-access-codes.index', compact('event', 'zoneAccessCodes'));
    }

    public function create(Event $event)
    {
        return view('menu.events.zone-access-codes.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'kode_zona'  => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $exists = $event->zoneAccessCodes()->where('kode_zona', $validated['kode_zona'])->exists();
        if ($exists) {
            return back()->withErrors(['kode_zona' => 'Kode zona sudah digunakan untuk event ini.'])->withInput();
        }

        $event->zoneAccessCodes()->create($validated);

        return redirect()
            ->route('admin.events.zone-access-codes.index', $event)
            ->with('status', 'Kode zona akses berhasil ditambahkan.');
    }

    public function edit(Event $event, ZoneAccessCode $zoneAccessCode)
    {
        abort_unless($zoneAccessCode->event_id === $event->id, 403);

        return view('menu.events.zone-access-codes.edit', compact('event', 'zoneAccessCode'));
    }

    public function update(Request $request, Event $event, ZoneAccessCode $zoneAccessCode)
    {
        abort_unless($zoneAccessCode->event_id === $event->id, 403);

        $validated = $request->validate([
            'kode_zona'  => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $exists = $event->zoneAccessCodes()
            ->where('kode_zona', $validated['kode_zona'])
            ->where('id', '!=', $zoneAccessCode->id)
            ->exists();
        if ($exists) {
            return back()->withErrors(['kode_zona' => 'Kode zona sudah digunakan untuk event ini.'])->withInput();
        }

        $zoneAccessCode->update($validated);

        return redirect()
            ->route('admin.events.zone-access-codes.index', $event)
            ->with('status', 'Kode zona akses berhasil diperbarui.');
    }

    public function destroy(Event $event, ZoneAccessCode $zoneAccessCode)
    {
        abort_unless($zoneAccessCode->event_id === $event->id, 403);

        $zoneAccessCode->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kode zona akses berhasil dihapus.',
        ]);
    }
}
