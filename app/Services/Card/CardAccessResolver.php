<?php

namespace App\Services\Card;

use App\Models\Card;
use App\Models\CardAccessOverride;
use App\Models\AccessCardConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CardAccessResolver
{
    /**
     * Ambil default access dari AccessCardConfig (berdasarkan card->access_card_config_id atau mapping).
     */
    public function getDefaultFromConfig(Card $card): array
    {
        $config = $this->resolveConfig($card);
        if (!$config) {
            return [
                'venues' => [],
                'zones' => [],
                'transportation_id' => null,
                'accommodation_id' => null,
                'accommodation_ids' => [],
                'config_id' => null,
            ];
        }

        $accommodationIds = collect($config->accommodation_code_id ?: [])
            ->map(fn($v) => (int)$v)
            ->filter()
            ->unique()
            ->values();

        return [
            'venues' => $config->venues()->pluck('venue_accesses.id')->all(),
            'zones'  => $config->zones()->pluck('zone_access_codes.id')->all(),
            'transportation_id' => $config->transportation_code_id,
            'accommodation_ids' => $accommodationIds->all(),
            'accommodation_id'  => $accommodationIds->first(),
            'config_id' => $config->id,
        ];
    }

    /**
     * Hitung FINAL access = default ∪ add - remove (unik, anti-double).
     */
    public function getFinalAccess(Card $card): array
    {
        $default = $this->getDefaultFromConfig($card);

        $overrides = $card->overrides()
            ->select(['type', 'ref_id', 'action'])
            ->get();

        $finalVenues = $this->applySetOps(
            $default['venues'],
            $overrides->where('type', 'venue')
        );

        $finalZones = $this->applySetOps(
            $default['zones'],
            $overrides->where('type', 'zone')
        );

        // transport/accommodation: anggap 1 pilihan final (bukan set)
        $finalTransportationId = $this->resolveSingleFinal(
            $default['transportation_id'],
            $overrides->where('type', 'transportation')
        );

        $finalAccommodationIds = $this->applySetOps(
            $default['accommodation_ids'] ?? [],
            $overrides->where('type', 'accommodation')
        );

        return [
            'venues' => $finalVenues,
            'zones' => $finalZones,
            'transportation_id' => $finalTransportationId,
            'accommodation_ids' => $finalAccommodationIds,
            'accommodation_id' => collect($finalAccommodationIds)->first(),
        ];
    }

    /**
     * Seed default access ke overrides (biar riwayat terlihat jelas di Customize Access).
     * Ini bisa dipanggil saat create draft card.
     */
    public function seedDefaultOverrides(Card $card): void
    {
        $default = $this->getDefaultFromConfig($card);

        // simpan config_id ke card kalau belum ada
        if (!$card->access_card_config_id && $default['config_id']) {
            $card->access_card_config_id = $default['config_id'];
            $card->save();
        }

        $rows = [];

        foreach ($default['venues'] as $id) {
            $rows[] = $this->row($card->id, 'venue', $id, 'add', 'default');
        }
        foreach ($default['zones'] as $id) {
            $rows[] = $this->row($card->id, 'zone', $id, 'add', 'default');
        }
        if (!empty($default['transportation_id'])) {
            $rows[] = $this->row($card->id, 'transportation', (int)$default['transportation_id'], 'add', 'default');
        }
        foreach (($default['accommodation_ids'] ?? []) as $id) {
            $rows[] = $this->row($card->id, 'accommodation', (int)$id, 'add', 'default');
        }

        if (!$rows) return;

        // Upsert anti-double berdasarkan unique(card_id,type,ref_id,action)
        CardAccessOverride::upsert(
            $rows,
            ['card_id', 'type', 'ref_id', 'action'],
            ['source', 'changed_by', 'updated_at']
        );
    }

    /**
     * Buat “riwayat/status” per item untuk UI Customize (default/custom/removed).
     * Return: map id -> status + source
     */
    public function buildHistoryIndex(Card $card): array
    {
        $default = $this->getDefaultFromConfig($card);

        // index awal dari default
        $history = [
            'venue' => [],
            'zone' => [],
            'transportation' => [],
            'accommodation' => [],
        ];

        foreach ($default['venues'] as $id) {
            $history['venue'][$id] = ['state' => 'owned', 'source' => 'default'];
        }
        foreach ($default['zones'] as $id) {
            $history['zone'][$id] = ['state' => 'owned', 'source' => 'default'];
        }
        if ($default['transportation_id']) {
            $id = (int)$default['transportation_id'];
            $history['transportation'][$id] = ['state' => 'owned', 'source' => 'default'];
        }
        foreach (($default['accommodation_ids'] ?? []) as $id) {
            $id = (int)$id;
            $history['accommodation'][$id] = ['state' => 'owned', 'source' => 'default'];
        }

        // terapkan overrides
        $overrides = $card->overrides()->get();
        foreach ($overrides as $ov) {
            $type = $ov->type;
            $id   = (int)$ov->ref_id;

            if ($ov->action === 'add') {
                $history[$type][$id] = ['state' => 'owned', 'source' => $ov->source];
            } else { // remove
                $history[$type][$id] = ['state' => 'removed', 'source' => $ov->source];
            }
        }

        return $history;
    }

    // =========================
    // Helpers
    // =========================

    private function resolveConfig(Card $card): ?AccessCardConfig
    {
        if ($card->access_card_config_id) {
            return AccessCardConfig::query()
                ->with(['venues', 'zones'])
                ->find($card->access_card_config_id);
        }

        // fallback: cari config berdasarkan mapping id
        return AccessCardConfig::query()
            ->with(['venues', 'zones'])
            ->where('accreditation_mapping_id', $card->accreditation_mapping_id)
            ->first();
    }

    private function applySetOps(array $defaultIds, Collection $typeOverrides): array
    {
        $defaultSet = collect($defaultIds)->map(fn($v) => (int)$v)->unique();

        $adds = $typeOverrides->where('action', 'add')->pluck('ref_id')->map(fn($v) => (int)$v)->unique();
        $removes = $typeOverrides->where('action', 'remove')->pluck('ref_id')->map(fn($v) => (int)$v)->unique();

        $final = $defaultSet
            ->merge($adds)
            ->unique()
            ->reject(fn($id) => $removes->contains($id))
            ->values()
            ->all();

        return $final;
    }

    private function resolveSingleFinal($defaultId, Collection $typeOverrides): ?int
    {
        $defaultId = $defaultId ? (int)$defaultId : null;

        // kalau ada remove default dan tidak ada add lain -> null
        $removed = $typeOverrides->where('action', 'remove')->pluck('ref_id')->map(fn($v)=>(int)$v)->unique();
        $adds = $typeOverrides->where('action', 'add')->pluck('ref_id')->map(fn($v)=>(int)$v)->unique()->values();

        if ($adds->count() > 0) {
            // ambil add terakhir sebagai final (simple rule)
            return (int)$adds->last();
        }

        if ($defaultId && $removed->contains($defaultId)) {
            return null;
        }

        return $defaultId;
    }

    private function row(int $cardId, string $type, int $refId, string $action, string $source): array
    {
        $now = now();
        return [
            'card_id' => $cardId,
            'type' => $type,
            'ref_id' => $refId,
            'action' => $action,
            'source' => $source,
            'changed_by' => Auth::id(),
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
