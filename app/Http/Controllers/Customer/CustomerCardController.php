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
use Illuminate\Support\Facades\Storage;

class CustomerCardController extends Controller
{
    public function show(Application $application, CardAccessResolver $resolver)
    {
        $customerId = session('customer_id');
        abort_unless($customerId, 403);

        abort_unless((int) $application->user_id === (int) $customerId, 403);

        $card = Card::where('application_id', $application->id)->first();

        if (!$card || $card->status !== 'issued') {
            return response()->view('menu.customer.card-pending', [
                'application' => $application,
            ], 200);
        }

        $eventId = (int) $card->event_id;

        $card->load('application.user.profile', 'event.activeCardLayout');

        $final = $resolver->getFinalAccess($card);

        $qrText = $card->qr_payload ?: ($card->qr_token ? url("/cards/verify/{$card->qr_token}") : "ARISE-CARD-{$card->id}");

        $qrByCardId = [
            $card->id => $this->qrBase64($qrText),
        ];

        $photoByCardId = [
            $card->id => $this->photoBase64FromProfile(
                $card->application?->user?->profile?->profile_photo
                    ?? (is_array($card->snapshot) ? ($card->snapshot['applicant_photo'] ?? null) : (json_decode((string) $card->snapshot, true)['applicant_photo'] ?? null))
            ),
        ];

        $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
        $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

        [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

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
            'layout' => $card->event->activeCardLayout,
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

        if (str_starts_with($profilePhoto, 'data:image/')) {
            return $profilePhoto;
        }

        $normalized = ltrim($profilePhoto, '/');
        $normalized = str_starts_with($normalized, 'storage/')
            ? substr($normalized, strlen('storage/'))
            : $normalized;
        $normalized = str_starts_with($normalized, 'public/')
            ? substr($normalized, strlen('public/'))
            : $normalized;

        if (!Storage::disk('public')->exists($normalized)) {
            return null;
        }

        $bytes = Storage::disk('public')->get($normalized);
        $mime = Storage::disk('public')->mimeType($normalized) ?: 'image/jpeg';

        return 'data:' . $mime . ';base64,' . base64_encode($bytes);
    }
}
