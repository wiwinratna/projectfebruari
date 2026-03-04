<?php

namespace App\Http\Controllers\Admin\Card;

use App\Http\Controllers\Controller;
use App\Models\AccommodationCode;
use App\Models\Card;
use App\Models\TransportationCode;
use App\Models\VenueAccess;
use App\Models\ZoneAccessCode;
use App\Services\Card\CardAccessResolver;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CardPrintController extends Controller
{
    public function preview(Card $card, CardAccessResolver $resolver)
    {
        $eventId = session('admin_event_id');
        abort_unless($card->event_id == $eventId, 403);

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
        ]);
    }

    public function printSingle(Card $card, CardAccessResolver $resolver)
    {
        $eventId = session('admin_event_id');
        abort_unless($card->event_id == $eventId, 403);

        if ($card->status !== 'issued') {
            return back()->with('error', 'Card harus ISSUED dulu sebelum print.');
        }

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

        $pdf = Pdf::loadView('menu.admin.card.print.sheet-a5', [
            'cards' => collect([$card]),
            'finalAccessByCardId' => [$card->id => $final],
            'qrByCardId' => $qrByCardId,
            'photoByCardId' => $photoByCardId,
            'transportById' => $transportById,
            'accomById' => $accomById,
            'venueMap' => $venueMap,
            'zoneMap'  => $zoneMap,
            'mode' => 'pdf',
        ])->setPaper('a5', 'portrait');

        return $pdf->download("card-{$card->card_number}.pdf");
    }

    public function printBatch(Request $request, CardAccessResolver $resolver)
    {
        $eventId = session('admin_event_id');

        $data = $request->validate([
            'card_ids' => ['required', 'array'],
            'card_ids.*' => ['integer'],
        ]);

        $cards = Card::where('event_id', $eventId)
            ->whereIn('id', $data['card_ids'])
            ->where('status', 'issued')
            ->orderBy('id')
            ->get();

        if ($cards->isEmpty()) {
            return back()->with('error', 'Tidak ada card ISSUED yang dipilih.');
        }

        $cards->load('application.user.profile');

        $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
        $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

        [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

        $finalAccessByCardId = [];
        $qrByCardId = [];
        $photoByCardId = [];

        foreach ($cards as $c) {
            $finalAccessByCardId[$c->id] = $resolver->getFinalAccess($c);

            $qrText = $c->qr_payload ?: ($c->qr_token ? url("/cards/verify/{$c->qr_token}") : null);
            $qrByCardId[$c->id] = $this->qrBase64($qrText);

            $photoByCardId[$c->id] = $this->photoBase64FromProfile($c->application?->user?->profile?->profile_photo);
        }

        $pdf = Pdf::loadView('menu.admin.card.print.sheet-a5', [
            'cards' => $cards,
            'finalAccessByCardId' => $finalAccessByCardId,
            'qrByCardId' => $qrByCardId,
            'photoByCardId' => $photoByCardId,
            'transportById' => $transportById,
            'accomById' => $accomById,
            'venueMap' => $venueMap,
            'zoneMap'  => $zoneMap,
            'mode' => 'pdf',
        ])->setPaper('a5', 'portrait');

        return $pdf->download("cards-issued-event{$eventId}.pdf");
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

 public function printHtmlSingle(Card $card, CardAccessResolver $resolver)
{
    $eventId = session('admin_event_id');
    abort_unless($card->event_id == $eventId, 403);

    if ($card->status !== 'issued') {
        return back()->with('error', 'Card harus ISSUED dulu sebelum print.');
    }

    $card->load('application.user.profile');
    $final = $resolver->getFinalAccess($card);

    $qrText = $card->qr_payload ?: ($card->qr_token ? url("/cards/verify/{$card->qr_token}") : null);

    $qrByCardId = [$card->id => $this->qrBase64($qrText)];
    $photoByCardId = [$card->id => $this->photoBase64FromProfile($card->application?->user?->profile?->profile_photo)];

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
        'mode' => 'print',
        'autoPrint' => true,
    ]);
}

public function printHtmlBatch(Request $request, CardAccessResolver $resolver)
{
    $eventId = session('admin_event_id');

    $data = $request->validate([
        'card_ids' => ['required', 'array'],
        'card_ids.*' => ['integer'],
    ]);

    $cards = Card::where('event_id', $eventId)
        ->whereIn('id', $data['card_ids'])
        ->where('status', 'issued')
        ->orderBy('id')
        ->get();

    if ($cards->isEmpty()) {
        return back()->with('error', 'Tidak ada card ISSUED yang dipilih.');
    }

    $cards->load('application.user.profile');

    $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
    $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

    [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

    $finalAccessByCardId = [];
    $qrByCardId = [];
    $photoByCardId = [];

    foreach ($cards as $c) {
        $finalAccessByCardId[$c->id] = $resolver->getFinalAccess($c);

        $qrText = $c->qr_payload ?: ($c->qr_token ? url("/cards/verify/{$c->qr_token}") : null);
        $qrByCardId[$c->id] = $this->qrBase64($qrText);

        $photoByCardId[$c->id] = $this->photoBase64FromProfile($c->application?->user?->profile?->profile_photo);
    }

    return view('menu.admin.card.print.sheet-a5', [
        'cards' => $cards,
        'finalAccessByCardId' => $finalAccessByCardId,
        'qrByCardId' => $qrByCardId,
        'photoByCardId' => $photoByCardId,
        'transportById' => $transportById,
        'accomById' => $accomById,
        'venueMap' => $venueMap,
        'zoneMap'  => $zoneMap,
        'mode' => 'print',
        'autoPrint' => true,
    ]);
}


public function previewAll(Request $request, CardAccessResolver $resolver)
{
    $eventId = session('admin_event_id');

    $q = trim((string) $request->get('q', ''));
    $statusCard = $request->get('card_status');

    $cardsQuery = Card::query()
        ->where('event_id', $eventId)
        ->orderByDesc('id');

    if ($statusCard) $cardsQuery->where('status', $statusCard);

    if ($q !== '') {
        $cardsQuery->where(function ($w) use ($q) {
            $w->where('snapshot', 'like', "%{$q}%")
              ->orWhere('card_number', 'like', "%{$q}%");
        });
    }

    $cards = $cardsQuery->limit(50)->get();
    $cards->load('application.user.profile');

    $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
    $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

    [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

    $finalAccessByCardId = [];
    $qrByCardId = [];
    $photoByCardId = [];

    foreach ($cards as $c) {
        $finalAccessByCardId[$c->id] = $resolver->getFinalAccess($c);

        $qrText = $c->qr_payload ?: ($c->qr_token ? url("/cards/verify/{$c->qr_token}") : null);
        $qrByCardId[$c->id] = $this->qrBase64($qrText);

        $photoByCardId[$c->id] = $this->photoBase64FromProfile(
            $c->application?->user?->profile?->profile_photo
        );
    }

    return view('menu.admin.card.print.sheet-a5', [
        'cards' => $cards,
        'finalAccessByCardId' => $finalAccessByCardId,
        'qrByCardId' => $qrByCardId,
        'photoByCardId' => $photoByCardId,
        'transportById' => $transportById,
        'accomById' => $accomById,
        'venueMap' => $venueMap,
        'zoneMap'  => $zoneMap,
        'mode' => 'preview',     // ✅ cuma preview
        'autoPrint' => false,    // ✅ jangan auto print
    ]);
}
}