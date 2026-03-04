<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardVerificationLog;
use App\Services\Card\CardAccessResolver;
use Illuminate\Http\Request;
use App\Models\VenueAccess;
use App\Models\ZoneAccessCode;
use App\Models\TransportationCode;
use App\Models\AccommodationCode;

class CardVerifyController extends Controller
{
    public function show(string $token, CardAccessResolver $resolver)
    {
        $card = Card::where('qr_token', $token)->first();

        if (!$card) {
            return view('public.cards.verify', [
                'valid' => false,
                'reason' => 'Token not found',
                'card' => null,
                'final' => null,
                'logs' => collect(),
                'venueMap' => $venueMap,
                'zoneMap' => $zoneMap,
                'transportMap' => $transportMap,
                'accomMap' => $accomMap,
            ]);
        }

        if ($card->status !== 'issued') {
            return view('public.cards.verify', [
                'valid' => false,
                'reason' => 'Card not issued',
                'card' => $card,
                'final' => null,
                'logs' => collect(),
                'venueMap' => $venueMap,
                'zoneMap' => $zoneMap,
                'transportMap' => $transportMap,
                'accomMap' => $accomMap,
            ]);
        }

        $snap = is_array($card->snapshot) ? $card->snapshot : json_decode($card->snapshot, true);

        $final = $resolver->getFinalAccess($card);
        // VENUE MAP: id -> (code = nama_vanue)
        $venueMap = \App\Models\VenueAccess::where('event_id', $card->event_id)
        ->get()
        ->keyBy('id')
        ->map(fn($v) => [
            'code' => $v->nama_vanue ?? ('V'.$v->id),   // ✅ ini yang tampil di card
            'name' => $v->nama_vanue ?? ('Venue #'.$v->id),
            'desc' => $v->keterangan,
        ])
        ->toArray();

        // ZONE MAP: id -> (code = kode_zona)
        $zoneMap = \App\Models\ZoneAccessCode::where('event_id', $card->event_id)
        ->get()
        ->keyBy('id')
        ->map(fn($z) => [
            'code' => $z->kode_zona ?? ('Z'.$z->id),    // ✅ ini yang tampil di card
            'name' => $z->kode_zona ?? ('Zone #'.$z->id),
            'desc' => $z->keterangan,
        ])
        ->toArray();

            $transportMap = TransportationCode::where('event_id', $card->event_id)
            ->get()
            ->keyBy('id')
            ->map(fn($t) => [
                'code' => $t->kode,
                'desc' => $t->keterangan,
                'icon_key' => $t->icon_key,
                'show_icon' => (bool)$t->show_icon,
                'show_code' => (bool)($t->show_code ?? true),
            ])
            ->toArray();

            $accomMap = AccommodationCode::where('event_id', $card->event_id)
            ->get()
            ->keyBy('id')
            ->map(fn($a) => [
                'code' => $a->kode,
                'desc' => $a->keterangan,
                'icon_key' => $a->icon_key,
                'show_icon' => (bool)$a->show_icon,
                'show_code' => (bool)($a->show_code ?? true),
            ])
            ->toArray();
        // riwayat pengecekan
        $logs = CardVerificationLog::where('card_id', $card->id)
            ->latest()
            ->take(10)
            ->get();

        return view('public.cards.verify', [
            'valid' => true,
            'reason' => null,
            'card' => $card,
            'snap' => $snap,
            'final' => $final,
            'logs' => $logs,
            'venueMap' => $venueMap,
            'zoneMap' => $zoneMap,
            'transportMap' => $transportMap,
            'accomMap' => $accomMap,
        ]);
    }

    public function store(Request $request, string $token)
    {
        $card = Card::where('qr_token', $token)->first();

        if (!$card || $card->status !== 'issued') {
            return redirect()->route('cards.verify.public', $token)
                ->with('error', 'Invalid card/token.');
        }

        $data = $request->validate([
            'visitor_name' => ['required','string','max:120'],
            'phone' => ['nullable','string','max:50'],
            'note' => ['nullable','string','max:1000'],
        ]);

        CardVerificationLog::create([
            'card_id' => $card->id,
            'qr_token' => $token,
            'visitor_name' => $data['visitor_name'],
            'phone' => $data['phone'] ?? null,
            'note' => $data['note'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        return redirect()->route('cards.verify.public', $token)
            ->with('success', 'Submitted. Thank you!');
    }
}