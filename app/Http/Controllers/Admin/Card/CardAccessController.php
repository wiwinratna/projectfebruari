<?php

namespace App\Http\Controllers\Admin\Card;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\VenueAccess;
use App\Models\ZoneAccessCode;
use App\Models\TransportationCode;
use App\Models\AccommodationCode;
use App\Services\Card\CardAccessResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CardAccessOverride;

class CardAccessController extends Controller
{
    public function edit(Card $card, CardAccessResolver $resolver)
    {
        $eventId = session('admin_event_id');
        abort_unless($card->event_id == $eventId, 403);

        $venues = VenueAccess::where('event_id', $eventId)->orderBy('nama_vanue')->get();
        $zones = ZoneAccessCode::where('event_id', $eventId)->orderBy('kode_zona')->get();
        $transportations = TransportationCode::where('event_id', $eventId)->orderBy('kode')->get();
        $accommodations  = AccommodationCode::where('event_id', $eventId)->orderBy('kode')->get();

        $final   = $resolver->getFinalAccess($card);
        $history = $resolver->buildHistoryIndex($card);

        return view('menu.admin.card.access-edit', compact(
            'card', 'venues', 'zones', 'transportations', 'accommodations', 'final', 'history'
        ));
    }

    public function update(Request $request, Card $card, CardAccessResolver $resolver)
    {
        $eventId = session('admin_event_id');
        abort_unless($card->event_id == $eventId, 403);

        if ($card->status === 'issued') {
            return back()->with('error', 'Card sudah issued dan tidak bisa diubah.');
        }

        $data = $request->validate([
            'venues' => ['array'],
            'venues.*' => ['integer'],
            'zones' => ['array'],
            'zones.*' => ['integer'],
            'transportation_id' => ['nullable', 'integer'],
            'accommodation_ids' => ['array'],
            'accommodation_ids.*' => ['integer'],
        ]);

        $desiredVenues = collect($data['venues'] ?? [])->map(fn($v)=>(int)$v)->unique()->values();
        $desiredZones  = collect($data['zones'] ?? [])->map(fn($v)=>(int)$v)->unique()->values();
        $desiredTransportation = isset($data['transportation_id']) && $data['transportation_id'] !== '' ? (int)$data['transportation_id'] : null;
        $desiredAccommodationIds = collect($data['accommodation_ids'] ?? [])->map(fn($v)=>(int)$v)->unique()->values();

        $default = $resolver->getDefaultFromConfig($card);

        $this->syncSetOverrides($card, 'venue', $default['venues'], $desiredVenues->all());
        $this->syncSetOverrides($card, 'zone',  $default['zones'],  $desiredZones->all());
        $this->syncSetOverrides($card, 'accommodation', $default['accommodation_ids'] ?? ($default['accommodation_id'] ? [(int)$default['accommodation_id']] : []), $desiredAccommodationIds->all());

        $this->syncSingleOverride($card, 'transportation', $default['transportation_id'], $desiredTransportation);

        return back()->with('success', 'Akses kartu berhasil disimpan.');
    }

    private function syncSetOverrides(Card $card, string $type, array $defaultIds, array $desiredIds): void
    {
        $default = collect($defaultIds)->map(fn($v)=>(int)$v)->unique();
        $desired = collect($desiredIds)->map(fn($v)=>(int)$v)->unique();

        $needAdds = $desired->diff($default)->values();
        $needRemoves = $default->diff($desired)->values();

        CardAccessOverride::where('card_id', $card->id)
            ->where('type', $type)
            ->where('source', 'custom')
            ->delete();

        $rows = [];
        $now = now();
        $uid = Auth::id();

        foreach ($needAdds as $id) {
            $rows[] = [
                'card_id' => $card->id,
                'type' => $type,
                'ref_id' => (int)$id,
                'action' => 'add',
                'source' => 'custom',
                'changed_by' => $uid,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach ($needRemoves as $id) {
            $rows[] = [
                'card_id' => $card->id,
                'type' => $type,
                'ref_id' => (int)$id,
                'action' => 'remove',
                'source' => 'custom',
                'changed_by' => $uid,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($rows) {
            CardAccessOverride::upsert(
                $rows,
                ['card_id', 'type', 'ref_id', 'action'],
                ['source', 'changed_by', 'updated_at']
            );
        }
    }

    private function syncSingleOverride(Card $card, string $type, $defaultId, $desiredId): void
    {
        $defaultId = $defaultId ? (int)$defaultId : null;
        $desiredId = $desiredId ? (int)$desiredId : null;

        CardAccessOverride::where('card_id', $card->id)
            ->where('type', $type)
            ->where('source', 'custom')
            ->delete();

        $rows = [];
        $now = now();
        $uid = Auth::id();

        if ($desiredId && $desiredId !== $defaultId) {
            $rows[] = [
                'card_id' => $card->id,
                'type' => $type,
                'ref_id' => $desiredId,
                'action' => 'add',
                'source' => 'custom',
                'changed_by' => $uid,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($defaultId) {
                $rows[] = [
                    'card_id' => $card->id,
                    'type' => $type,
                    'ref_id' => $defaultId,
                    'action' => 'remove',
                    'source' => 'custom',
                    'changed_by' => $uid,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (!$desiredId && $defaultId) {
            $rows[] = [
                'card_id' => $card->id,
                'type' => $type,
                'ref_id' => $defaultId,
                'action' => 'remove',
                'source' => 'custom',
                'changed_by' => $uid,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($rows) {
            CardAccessOverride::upsert(
                $rows,
                ['card_id', 'type', 'ref_id', 'action'],
                ['source', 'changed_by', 'updated_at']
            );
        }
    }
}
