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
use Illuminate\Support\Facades\Storage;

class CardPrintController extends Controller
{
    public function preview(Card $card, CardAccessResolver $resolver)
    {
        $eventId = session('admin_event_id');
        abort_unless($card->event_id == $eventId, 403);

        $card->load('application.user.profile', 'event', 'cardLayout');

        $final = $resolver->getFinalAccess($card);

        $qrText = $card->qr_payload ?: ($card->qr_token ? url("/cards/verify/{$card->qr_token}") : "ARISE-CARD-{$card->id}");

        $qrByCardId = [
            $card->id => $this->qrBase64($qrText),
        ];

        $photoByCardId = [
            $card->id => $this->photoBase64FromProfile($this->resolvePhotoPath($card)),
        ];

        $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
        $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

        [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

        // Get layout from card or event
        $layout = $card->cardLayout ?? $card->event->activeCardLayout;

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
            'layout' => $layout,
        ]);
    }

    public function printSingle(Card $card, CardAccessResolver $resolver)
    {
        $eventId = session('admin_event_id');
        abort_unless($card->event_id == $eventId, 403);

        if ($card->status !== 'issued') {
            return back()->with('error', 'Card harus ISSUED dulu sebelum print.');
        }

        $card->load('application.user.profile', 'event', 'cardLayout');

        $final = $resolver->getFinalAccess($card);

        $qrText = $card->qr_payload ?: ($card->qr_token ? url("/cards/verify/{$card->qr_token}") : "ARISE-CARD-{$card->id}");

        $qrByCardId = [
            $card->id => $this->qrBase64($qrText),
        ];

        $photoByCardId = [
            $card->id => $this->photoBase64FromProfile($this->resolvePhotoPath($card)),
        ];

        $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
        $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

        [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

        // Get layout from card or event
        $layout = $card->cardLayout ?? $card->event->activeCardLayout;

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
            'layout' => $layout,
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

        $cards->load('application.user.profile', 'event', 'cardLayout');

        $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
        $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

        [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

        $finalAccessByCardId = [];
        $qrByCardId = [];
        $photoByCardId = [];

        foreach ($cards as $c) {
            $finalAccessByCardId[$c->id] = $resolver->getFinalAccess($c);

            $qrText = $c->qr_payload ?: ($c->qr_token ? url("/cards/verify/{$c->qr_token}") : "ARISE-CARD-{$c->id}");
            $qrByCardId[$c->id] = $this->qrBase64($qrText);

            $photoByCardId[$c->id] = $this->photoBase64FromProfile($this->resolvePhotoPath($c));
        }

        // Get layout from first card or event
        $layout = $cards[0]->cardLayout ?? $cards[0]->event->activeCardLayout;

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
            'layout' => $layout,
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

        // Already a data URI (base64 stored directly)
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

 public function printHtmlSingle(Card $card, CardAccessResolver $resolver)
{
    $eventId = session('admin_event_id');
    abort_unless($card->event_id == $eventId, 403);

    if ($card->status !== 'issued') {
        return back()->with('error', 'Card harus ISSUED dulu sebelum print.');
    }

    $card->load('application.user.profile', 'event', 'cardLayout');
    $final = $resolver->getFinalAccess($card);

    $qrText = $card->qr_payload ?: ($card->qr_token ? url("/cards/verify/{$card->qr_token}") : "ARISE-CARD-{$card->id}");

    $qrByCardId = [$card->id => $this->qrBase64($qrText)];
    $photoByCardId = [$card->id => $this->photoBase64FromProfile($this->resolvePhotoPath($card))];

    $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
    $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

    [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

    // Get layout from card or event
    $layout = $card->cardLayout ?? $card->event->activeCardLayout;

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
        'layout' => $layout,
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

    $cards->load('application.user.profile', 'event', 'cardLayout');

    $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
    $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

    [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

    $finalAccessByCardId = [];
    $qrByCardId = [];
    $photoByCardId = [];

    foreach ($cards as $c) {
        $finalAccessByCardId[$c->id] = $resolver->getFinalAccess($c);

        $qrText = $c->qr_payload ?: ($c->qr_token ? url("/cards/verify/{$c->qr_token}") : "ARISE-CARD-{$c->id}");
        $qrByCardId[$c->id] = $this->qrBase64($qrText);

        $photoByCardId[$c->id] = $this->photoBase64FromProfile($this->resolvePhotoPath($c));
    }

    // Get layout from first card or event
    $layout = $cards[0]->cardLayout ?? $cards[0]->event->activeCardLayout;

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
        'layout' => $layout,
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
    $cards->load('application.user.profile', 'event', 'cardLayout');

    $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
    $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

    [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

    $finalAccessByCardId = [];
    $qrByCardId = [];
    $photoByCardId = [];

    foreach ($cards as $c) {
        $finalAccessByCardId[$c->id] = $resolver->getFinalAccess($c);

        $qrText = $c->qr_payload ?: ($c->qr_token ? url("/cards/verify/{$c->qr_token}") : "ARISE-CARD-{$c->id}");
        $qrByCardId[$c->id] = $this->qrBase64($qrText);

        $photoByCardId[$c->id] = $this->photoBase64FromProfile($this->resolvePhotoPath($c));
    }

    // Get layout from first card or event
    $layout = $cards->isNotEmpty() ? ($cards[0]->cardLayout ?? $cards[0]->event->activeCardLayout) : null;

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
        'layout' => $layout,
    ]);
}
    private function resolvePhotoPath(Card $card): ?string
    {
        $profilePhoto = $card->application?->user?->profile?->profile_photo;
        if ($profilePhoto) {
            return $profilePhoto;
        }

        $snapshot = is_array($card->snapshot) ? $card->snapshot : json_decode((string) $card->snapshot, true);
        if (is_array($snapshot) && !empty($snapshot['applicant_photo'])) {
            return (string) $snapshot['applicant_photo'];
        }

        return null;
    }
}

