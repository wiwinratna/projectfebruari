<?php

namespace App\Http\Controllers\Admin\Card;

use App\Http\Controllers\Controller;
use App\Models\AccommodationCode;
use App\Models\Card;
use App\Models\TransportationCode;
use App\Models\VenueAccess;
use App\Models\ZoneAccessCode;
use App\Notifications\CardIssuedNotification;
use App\Services\Card\CardAccessResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardIssueController extends Controller
{
    public function issue(Card $card, CardAccessResolver $resolver)
    {
        $eventId = session('admin_event_id');
        abort_unless($card->event_id == $eventId, 403);

        if ($card->status === 'issued') {
            return back()->with('error', 'Card sudah issued.');
        }

        DB::transaction(function () use ($card, $eventId, $resolver) {
            // generate running number per event per month
            $ym = now()->format('Ym');

            $issuedCount = Card::where('event_id', $eventId)
                ->where('status', 'issued')
                ->whereNotNull('issued_at')
                ->whereRaw("DATE_FORMAT(issued_at, '%Y%m') = ?", [$ym])
                ->lockForUpdate()
                ->count();

            $seq = $issuedCount + 1;
            $cardNumber = sprintf("EV%d-%s-%05d", $eventId, $ym, $seq);

            $qrToken = bin2hex(random_bytes(32)); // 64 hex
            $payload = url("/cards/verify/{$qrToken}");

            $signature = hash_hmac('sha256', $payload . '|' . $cardNumber, config('app.key'));

            // Get active layout for snapshot at issue time
            $activeLayout = \App\Models\CardLayout::where('event_id', $eventId)
                ->where('is_active', true)
                ->first();

            $card->update([
                'status' => 'issued',
                'card_number' => $cardNumber,
                'qr_token' => $qrToken,
                'qr_payload' => $payload,
                'signature' => $signature,
                'issued_at' => now(),
                'issued_by' => session('admin_id'),
                'layout_id' => $activeLayout?->id,  // Snapshot layout at issue time
            ]);

            $card->refresh();
            $card->update([
                'snapshot' => $this->buildIssuedSnapshot($card, $resolver),
            ]);
        });

        $card->loadMissing(['application.user', 'application.opening.event']);
        $application = $card->application;
        if ($application && $application->user) {
            $application->user->notify(new CardIssuedNotification(
                $application->opening->event->title ?? 'Event',
                $application->opening->title ?? 'Opening',
                (string) optional($card->issued_at)->toIso8601String(),
                route('customer.applications.card', $application)
            ));
        }

        return back()->with('success', 'Card berhasil di-ISSUE.');
    }

    public function issueBatch(Request $request, CardAccessResolver $resolver)
    {
        $eventId = session('admin_event_id');

        $data = $request->validate([
            'card_ids' => ['required', 'array'],
            'card_ids.*' => ['integer'],
        ]);

        $cardIds = collect($data['card_ids'])->unique()->values();

        $cards = \App\Models\Card::where('event_id', $eventId)
            ->whereIn('id', $cardIds)
            ->get();

        if ($cards->isEmpty()) {
            return back()->with('error', 'Tidak ada card yang dipilih.');
        }

        // hanya draft yang di-issue
        $draftCards = $cards->where('status', 'draft')->values();
        if ($draftCards->isEmpty()) {
            return back()->with('error', 'Semua card yang dipilih sudah issued.');
        }

        DB::transaction(function () use ($draftCards, $eventId, $resolver) {
            $ym = now()->format('Ym');

            // ambil counter issued bulan ini + lock
            $baseCount = \App\Models\Card::where('event_id', $eventId)
                ->where('status', 'issued')
                ->whereNotNull('issued_at')
                ->whereRaw("DATE_FORMAT(issued_at, '%Y%m') = ?", [$ym])
                ->lockForUpdate()
                ->count();

            // Get active layout for snapshot at issue time
            $activeLayout = \App\Models\CardLayout::where('event_id', $eventId)
                ->where('is_active', true)
                ->first();

            $transportMap = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
            $accommodationMap = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');
            $venueMap = VenueAccess::where('event_id', $eventId)->get()->keyBy('id');
            $zoneMap = ZoneAccessCode::where('event_id', $eventId)->get()->keyBy('id');

            $seq = $baseCount;

            foreach ($draftCards as $card) {
                $seq++;
                $cardNumber = sprintf("EV%d-%s-%05d", $eventId, $ym, $seq);

                $qrToken = bin2hex(random_bytes(32));
                $payload = url("/cards/verify/{$qrToken}");
                $signature = hash_hmac('sha256', $qrToken . '|' . $cardNumber, config('app.key'));

                $card->update([
                    'status' => 'issued',
                    'card_number' => $cardNumber,
                    'qr_token' => $qrToken,
                    'qr_payload' => $payload,
                    'signature' => $signature,
                    'issued_at' => now(),
                    'issued_by' => session('admin_id'),
                    'layout_id' => $activeLayout?->id,  // Snapshot layout at issue time
                ]);

                $card->refresh();
                $card->update([
                    'snapshot' => $this->buildIssuedSnapshot(
                        $card,
                        $resolver,
                        $transportMap,
                        $accommodationMap,
                        $venueMap,
                        $zoneMap
                    ),
                ]);
            }
        });

        $draftCards->loadMissing(['application.user', 'application.opening.event']);
        foreach ($draftCards as $card) {
            $application = $card->application;
            if (!$application || !$application->user) {
                continue;
            }
            $application->user->notify(new CardIssuedNotification(
                $application->opening->event->title ?? 'Event',
                $application->opening->title ?? 'Opening',
                (string) optional($card->issued_at)->toIso8601String(),
                route('customer.applications.card', $application)
            ));
        }

        return back()->with('success', 'Berhasil issue card terpilih.');
    }

    private function buildIssuedSnapshot(
        Card $card,
        CardAccessResolver $resolver,
        $transportMap = null,
        $accommodationMap = null,
        $venueMap = null,
        $zoneMap = null
    ): array {
        $snapshot = is_array($card->snapshot) ? $card->snapshot : (json_decode((string) $card->snapshot, true) ?: []);
        $final = $resolver->getFinalAccess($card);

        $transportMap = $transportMap ?: TransportationCode::where('event_id', $card->event_id)->get()->keyBy('id');
        $accommodationMap = $accommodationMap ?: AccommodationCode::where('event_id', $card->event_id)->get()->keyBy('id');
        $venueMap = $venueMap ?: VenueAccess::where('event_id', $card->event_id)->get()->keyBy('id');
        $zoneMap = $zoneMap ?: ZoneAccessCode::where('event_id', $card->event_id)->get()->keyBy('id');

        $transports = [];
        $transportId = $final['transportation_id'] ?? null;
        if ($transportId && $transportMap->has($transportId)) {
            $t = $transportMap->get($transportId);
            $tb = transportBadge($t);
            $transports[] = [
                'id' => $t->id,
                'code' => $tb['code'] ?? $t->kode,
                'icon_key' => $tb['icon'] ?? null,
                'show_icon' => (bool)($t->show_icon ?? false),
                'show_code' => (bool)($tb['show_code'] ?? true),
            ];
        }

        $accommodations = [];
        $accommodationId = $final['accommodation_id'] ?? null;
        if ($accommodationId && $accommodationMap->has($accommodationId)) {
            $a = $accommodationMap->get($accommodationId);
            $ab = accommodationBadge($a);
            $accommodations[] = [
                'id' => $a->id,
                'code' => $ab['code'] ?? $a->kode,
                'icon_key' => $ab['icon'] ?? null,
                'show_icon' => (bool)($a->show_icon ?? false),
                'show_code' => (bool)($ab['show_code'] ?? true),
            ];
        }

        $venueChips = collect($final['venues'] ?? [])->map(function ($id) use ($venueMap) {
            $v = $venueMap->get($id);
            if (!$v) {
                return null;
            }
            return [
                'id' => $v->id,
                'code' => $v->nama_vanue,
            ];
        })->filter()->values()->all();

        $zoneChips = collect($final['zones'] ?? [])->map(function ($id) use ($zoneMap) {
            $z = $zoneMap->get($id);
            if (!$z) {
                return null;
            }
            return [
                'id' => $z->id,
                'code' => $z->kode_zona,
            ];
        })->filter()->values()->all();

        $snapshot['transports'] = $transports;
        $snapshot['accommodations'] = $accommodations;
        $snapshot['venue_chips'] = $venueChips;
        $snapshot['zone_chips'] = $zoneChips;

        return $snapshot;
    }
}
