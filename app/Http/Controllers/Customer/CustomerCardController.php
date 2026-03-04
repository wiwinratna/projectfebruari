<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AccommodationCode;
use App\Models\Application;
use App\Models\Card;
use App\Models\TransportationCode;
use App\Models\VenueAccess;
use App\Models\ZoneAccessCode;
use App\Services\Card\CardAccessResolver;
use Illuminate\Http\Request;

class CustomerCardController extends Controller
{
    public function show(Application $application, CardAccessResolver $resolver)
    {
        // ✅ pastikan customer login (sesuaikan dengan sistem session kamu)
        $customerId = session('customer_id'); // atau session('customer_user_id') sesuai punyamu
        abort_unless($customerId, 403);

        // ✅ pastikan application ini milik customer
        abort_unless((int)$application->user_id === (int)$customerId, 403);

        // cari card dari application ini
        $card = Card::where('application_id', $application->id)->firstOrFail();

        // hanya boleh lihat kalau sudah issued
        abort_unless($card->status === 'issued', 403);

        // eventId ambil dari card (karena customer ga punya session event)
        $eventId = (int) $card->event_id;

        $card->load('application.user.profile');

        $final = $resolver->getFinalAccess($card);

        $qrText = $card->qr_payload ?: ($card->qr_token ? url("/cards/verify/{$card->qr_token}") : null);

        $qrByCardId = [
            $card->id => $this->qrBase64($qrText),
        ];

        $photoByCardId = [
            $card->id => $this->photoBase64FromProfile($card->application?->user?->profile?->profile_photo),
        ];

        $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
        $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

        [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

        // ✅ mode preview (bukan auto print)
        return view('menu.admin.card.print.sheet-a5', [
            'cards' => collect([$card]),
            'finalAccessByCardId' => [$card->id => $final],
            'qrByCardId' => $qrByCardId,
            'photoByCardId' => $photoByCardId,
            'transportById' => $transportById,
            'accomById' => $accomById,
            'venueMap' => $venueMap,
            'zoneMap'  => $zoneMap,
            'mode' => 'preview',
            'autoPrint' => false,
        ]);
    }

    private function buildAccessMaps(int $eventId): array
    {
        $venueMap = VenueAccess::where('event_id', $eventId)
            ->get()
            ->keyBy('id')
            ->map(fn($v) => [
                'code' => $v->nama_vanue,
                'name' => $v->nama_vanue,
                'desc' => $v->keterangan,
            ])->toArray();

        $zoneMap = ZoneAccessCode::where('event_id', $eventId)
            ->get()
            ->keyBy('id')
            ->map(fn($z) => [
                'code' => $z->kode_zona,
                'name' => $z->kode_zona,
                'desc' => $z->keterangan,
            ])->toArray();

        return [$venueMap, $zoneMap];
    }

    private function qrBase64(?string $text): ?string
    {
        if (!$text) return null;

        $writer = new \Endroid\QrCode\Writer\PngWriter();

        $qrCode = \Endroid\QrCode\QrCode::create($text)
            ->setSize(220)
            ->setMargin(2);

        $result = $writer->write($qrCode);

        return base64_encode($result->getString());
    }

    private function photoBase64FromProfile(?string $profilePhoto): ?string
    {
        if (!$profilePhoto) return null;

        $full = storage_path('app/public/' . ltrim($profilePhoto, '/'));
        if (!file_exists($full)) return null;

        return base64_encode(file_get_contents($full));
    }
}