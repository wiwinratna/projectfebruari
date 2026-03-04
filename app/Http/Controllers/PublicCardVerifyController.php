<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class PublicCardVerifyController extends Controller
{
    public function show(string $token)
    {
        $card = Card::where('qr_token', $token)->first();

        if (!$card) {
            return view('public.cards.verify', ['valid' => false, 'reason' => 'Card not found.', 'card' => null]);
        }

        if ($card->status !== 'issued') {
            return view('public.cards.verify', ['valid' => false, 'reason' => 'Card is not issued.', 'card' => $card]);
        }

        // ✅ validate signature (new format)
        $expectedNew = hash_hmac('sha256', $card->qr_token . '|' . $card->card_number, config('app.key'));

        // (optional) fallback old format if you already issued cards using url|card_number
        $expectedOld = hash_hmac('sha256', ($card->qr_payload ?? '') . '|' . ($card->card_number ?? ''), config('app.key'));

        $isValid = hash_equals((string)$card->signature, (string)$expectedNew) || hash_equals((string)$card->signature, (string)$expectedOld);

        if (!$isValid) {
            return view('public.cards.verify', ['valid' => false, 'reason' => 'Invalid signature.', 'card' => $card]);
        }

        return view('public.cards.verify', ['valid' => true, 'reason' => null, 'card' => $card]);
    }

    // ✅ ini contoh "halaman bisa diisi" tanpa login (check-in / catatan)
    public function store(Request $request, string $token)
    {
        $card = Card::where('qr_token', $token)->firstOrFail();

        // basic validation
        $data = $request->validate([
            'visitor_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'note' => ['nullable', 'string', 'max:300'],
        ]);

        // Simpan ke snapshot dulu (cepat tanpa tabel baru)
        // Kalau mau lebih rapi nanti kita bikin tabel logs.
        $snap = is_array($card->snapshot) ? $card->snapshot : json_decode($card->snapshot, true);
        $snap['verify_form'] = [
            'visitor_name' => $data['visitor_name'],
            'phone' => $data['phone'] ?? null,
            'note' => $data['note'] ?? null,
            'submitted_at' => now()->toDateTimeString(),
        ];

        $card->snapshot = $snap;
        $card->save();

        return back()->with('success', 'Submitted. Thank you!');
    }
}