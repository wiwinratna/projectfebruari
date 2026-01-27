<?php

namespace App\Http\Controllers;

use App\Models\AccessCard;
use Illuminate\Http\Request;

class AccessCardVerifyController extends Controller
{
    public function show(string $token)
    {
        $card = AccessCard::query()
            ->where('qr_token', $token)
            ->with([
                'accessCodes',                 // event_access_codes
                'workerOpening.event.city',       // event
                'user.profile',                // photo
            ])
            ->first();

        // invalid token
        if (!$card) {
            return view('access-cards.verify', [
                'ok' => false,
                'reason' => 'Token tidak ditemukan / kartu tidak valid.',
            ]);
        }

        // contoh aturan status (sesuaikan kolom status kamu)
        $status = strtolower((string)($card->status ?? 'active'));
        if (!in_array($status, ['active', 'issued'])) {
            return view('access-cards.verify', [
                'ok' => false,
                'reason' => 'Kartu tidak aktif (revoked/expired).',
                'card' => $card,
            ]);
        }

        return view('access-cards.verify', [
            'ok' => true,
            'card' => $card,
        ]);
    }
}
