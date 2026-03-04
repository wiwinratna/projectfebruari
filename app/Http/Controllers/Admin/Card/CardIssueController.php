<?php

namespace App\Http\Controllers\Admin\Card;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CardIssueController extends Controller
{
    public function issue(Card $card)
    {
        $eventId = session('admin_event_id');
        abort_unless($card->event_id == $eventId, 403);

        if ($card->status === 'issued') {
            return back()->with('error', 'Card sudah issued.');
        }

        DB::transaction(function () use ($card, $eventId) {
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

            $card->update([
                'status' => 'issued',
                'card_number' => $cardNumber,
                'qr_token' => $qrToken,
                'qr_payload' => $payload,
                'signature' => $signature,
                'issued_at' => now(),
                'issued_by' => session('admin_id'),
            ]);
        });

        return back()->with('success', 'Card berhasil di-ISSUE.');
    }

    public function issueBatch(Request $request)
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

        DB::transaction(function () use ($draftCards, $eventId) {
            $ym = now()->format('Ym');

            // ambil counter issued bulan ini + lock
            $baseCount = \App\Models\Card::where('event_id', $eventId)
                ->where('status', 'issued')
                ->whereNotNull('issued_at')
                ->whereRaw("DATE_FORMAT(issued_at, '%Y%m') = ?", [$ym])
                ->lockForUpdate()
                ->count();

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
                ]);
            }
        });

        return back()->with('success', 'Berhasil issue card terpilih.');
    }
}