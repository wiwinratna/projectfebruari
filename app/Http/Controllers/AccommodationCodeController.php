<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\AccommodationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Mark each code as in_use if referenced in access_card_configs (JSON column)
        $usedIds = DB::table('access_card_configs')
            ->where('event_id', $event->id)
            ->whereNotNull('accommodation_code_id')
            ->pluck('accommodation_code_id')
            ->flatMap(fn($v) => (array) json_decode($v, true))
            ->unique()
            ->values()
            ->all();

        $accommodationCodes->each(function ($code) use ($usedIds) {
            $code->access_card_configs_count = in_array($code->id, $usedIds) ? 1 : 0;
        });

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
            'kode'       => ['required', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'icon_key'   => ['nullable', 'string', 'max:50'],
            'show_icon'  => ['nullable'],
            'show_code'  => ['nullable'],
        ]);

        $exists = $event->accommodationCodes()->where('kode', $validated['kode'])->exists();
        if ($exists) {
            return back()->withErrors(['kode' => 'Kode sudah digunakan untuk event ini.'])->withInput();
        }

        $data = [
            'kode'       => $validated['kode'],
            'keterangan' => $validated['keterangan'] ?? null,
            'icon_key'   => $validated['icon_key'] ?? null,
            'show_icon'  => $request->boolean('show_icon'),
            'show_code'  => $request->boolean('show_code'),
        ];

        $event->accommodationCodes()->create($data);

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
            'kode'       => ['required', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'icon_key'   => ['nullable', 'string', 'max:50'],
            'show_icon'  => ['nullable'],
            'show_code'  => ['nullable'],
        ]);

        $exists = $event->accommodationCodes()
            ->where('kode', $validated['kode'])
            ->where('id', '!=', $accommodationCode->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['kode' => 'Kode sudah digunakan untuk event ini.'])->withInput();
        }

        $data = [
            'kode'       => $validated['kode'],
            'keterangan' => $validated['keterangan'] ?? null,
            'icon_key'   => $validated['icon_key'] ?? null,
            'show_icon'  => $request->boolean('show_icon'),
            'show_code'  => $request->boolean('show_code'),
        ];

        $accommodationCode->update($data);

        return redirect()
            ->route('admin.master-data.accommodation-codes.index')
            ->with('status', 'Kode akomodasi berhasil diperbarui.');
    }

    public function destroy(AccommodationCode $accommodationCode)
    {
        $event = $this->getEvent();
        abort_unless($accommodationCode->event_id === $event->id, 403);

        try {
            $accommodationCode->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kode akomodasi berhasil dihapus.',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode akomodasi ini tidak bisa dihapus karena sedang digunakan (in use).',
                ]);
            }
            throw $e;
        }
    }
}
