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
use Spatie\Browsershot\Browsershot;

class CardPrintController extends Controller
{
    public function preview(Card $card, CardAccessResolver $resolver)
    {
        $eventId = session('admin_event_id');
        abort_unless($card->event_id == $eventId, 403);

        $card->load('application.user.profile', 'event.activeCardLayout');

        $final = $resolver->getFinalAccess($card);

        $qrText = $card->qr_payload ?: ($card->qr_token ? url("/cards/verify/{$card->qr_token}") : "ARISE-CARD-{$card->id}");

        $qrByCardId = [
            $card->id => $this->qrBase64($qrText),
        ];

        $resolved = $this->resolvePhotoPathInfo($card);
        $photoByCardId = [
            $card->id => $this->photoBase64FromProfile($resolved['path']),
        ];
        $photoIsFallbackByCardId = [
            $card->id => $resolved['is_fallback']
        ];

        $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
        $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

        [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

        // Mode 2 rule: always use latest active event layout (data lock is separate)
        $layout = $card->event->activeCardLayout;

        return view('menu.admin.card.print.sheet-a5', [
            'cards' => collect([$card]),
            'finalAccessByCardId' => [$card->id => $final],
            'qrByCardId' => $qrByCardId,
            'photoByCardId' => $photoByCardId,
            'photoIsFallbackByCardId' => $photoIsFallbackByCardId,
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

        $card->load('application.user.profile', 'event.activeCardLayout');

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

        // Mode 2 rule: always use latest active event layout (data lock is separate)
        $layout = $card->event->activeCardLayout;

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

        $cards->load('application.user.profile', 'event.activeCardLayout');

        $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
        $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

        [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

        $finalAccessByCardId = [];
        $qrByCardId = [];
        $photoByCardId = [];
        $photoIsFallbackByCardId = [];

        foreach ($cards as $c) {
            $finalAccessByCardId[$c->id] = $resolver->getFinalAccess($c);

            $qrText = $c->qr_payload ?: ($c->qr_token ? url("/cards/verify/{$c->qr_token}") : "ARISE-CARD-{$c->id}");
            $qrByCardId[$c->id] = $this->qrBase64($qrText);

            $resolvedPhoto = $this->resolvePhotoPathInfo($c);
        $photoByCardId[$c->id] = $this->photoBase64FromProfile($resolvedPhoto['path']);
        $photoIsFallbackByCardId[$c->id] = $resolvedPhoto['is_fallback'];
        }

        // Mode 2 rule: always use latest active event layout
        $layout = $cards[0]->event->activeCardLayout;

        $pdf = Pdf::loadView('menu.admin.card.print.sheet-a5', [
            'cards' => $cards,
            'finalAccessByCardId' => $finalAccessByCardId,
            'qrByCardId' => $qrByCardId,
            'photoByCardId' => $photoByCardId,
            'photoIsFallbackByCardId' => $photoIsFallbackByCardId,
            'transportById' => $transportById,
            'accomById' => $accomById,
            'venueMap' => $venueMap,
            'zoneMap'  => $zoneMap,
            'mode' => 'pdf',
            'layout' => $layout,
        ])->setPaper('a5', 'portrait');

        if ($request->input('structure') === 'zip') {
            $zip = new \ZipArchive();
            $zipFileName = 'Arise_Cards_Batch_' . time() . '.zip';
            $zipPath = storage_path('app/public/' . $zipFileName);
            
            if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                foreach ($cards as $c) {
                    $snap = is_array($c->snapshot) ? $c->snapshot : json_decode($c->snapshot, true);
                    $name = \Illuminate\Support\Str::slug(($snap['name'] ?? $snap['applicant_name'] ?? 'Card ' . $c->id) . '-' . ($snap['job_category_name'] ?? ''));
                    
                    $singlePdf = Pdf::loadView('menu.admin.card.print.sheet-a5', [
                        'cards' => collect([$c]),
                        'finalAccessByCardId' => [$c->id => $finalAccessByCardId[$c->id]],
                        'qrByCardId' => [$c->id => $qrByCardId[$c->id]],
                        'photoByCardId' => [$c->id => $photoByCardId[$c->id]],
                        'photoIsFallbackByCardId' => [$c->id => $photoIsFallbackByCardId[$c->id]],
                        'transportById' => $transportById,
                        'accomById' => $accomById,
                        'venueMap' => $venueMap,
                        'zoneMap'  => $zoneMap,
                        'mode' => 'pdf',
                        'layout' => $layout,
                    ])->setPaper('a5', 'portrait');
                    
                    $zip->addFromString("{$name}.pdf", $singlePdf->output());
                }
                $zip->close();
                
                return response()->download($zipPath)->deleteFileAfterSend(true);
            }
        }

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

    $card->load('application.user.profile', 'event.activeCardLayout');
    $final = $resolver->getFinalAccess($card);

    $qrText = $card->qr_payload ?: ($card->qr_token ? url("/cards/verify/{$card->qr_token}") : "ARISE-CARD-{$card->id}");

    $qrByCardId = [$card->id => $this->qrBase64($qrText)];
    $resolved = $this->resolvePhotoPathInfo($card);
    $photoByCardId = [$card->id => $this->photoBase64FromProfile($resolved['path'])];
    $photoIsFallbackByCardId = [$card->id => $resolved['is_fallback']];

    $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
    $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

    [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

    // Mode 2 rule: always use latest active event layout (data lock is separate)
    $layout = $card->event->activeCardLayout;

    return view('menu.admin.card.print.sheet-a5', [
        'cards' => collect([$card]),
        'finalAccessByCardId' => [$card->id => $final],
        'qrByCardId' => $qrByCardId,
        'photoByCardId' => $photoByCardId,
        'photoIsFallbackByCardId' => $photoIsFallbackByCardId,
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

    $cards->load('application.user.profile', 'event.activeCardLayout');

    $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
    $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

    [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

    $finalAccessByCardId = [];
    $qrByCardId = [];
    $photoByCardId = [];
    $photoIsFallbackByCardId = [];

    foreach ($cards as $c) {
        $finalAccessByCardId[$c->id] = $resolver->getFinalAccess($c);

        $qrText = $c->qr_payload ?: ($c->qr_token ? url("/cards/verify/{$c->qr_token}") : "ARISE-CARD-{$c->id}");
        $qrByCardId[$c->id] = $this->qrBase64($qrText);

        $resolvedPhoto = $this->resolvePhotoPathInfo($c);
        $photoByCardId[$c->id] = $this->photoBase64FromProfile($resolvedPhoto['path']);
        $photoIsFallbackByCardId[$c->id] = $resolvedPhoto['is_fallback'];
    }

    // Mode 2 rule: always use latest active event layout
    $layout = $cards[0]->event->activeCardLayout;

    return view('menu.admin.card.print.sheet-a5', [
        'cards' => $cards,
        'finalAccessByCardId' => $finalAccessByCardId,
        'qrByCardId' => $qrByCardId,
        'photoByCardId' => $photoByCardId,
        'photoIsFallbackByCardId' => $photoIsFallbackByCardId,
        'transportById' => $transportById,
        'accomById' => $accomById,
        'venueMap' => $venueMap,
        'zoneMap'  => $zoneMap,
        'mode' => 'print',
        'autoPrint' => !$request->has('no_print'),
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
    $cards->load('application.user.profile', 'event.activeCardLayout');

    $transportById = TransportationCode::where('event_id', $eventId)->get()->keyBy('id');
    $accomById     = AccommodationCode::where('event_id', $eventId)->get()->keyBy('id');

    [$venueMap, $zoneMap] = $this->buildAccessMaps($eventId);

    $finalAccessByCardId = [];
    $qrByCardId = [];
    $photoByCardId = [];
    $photoIsFallbackByCardId = [];

    foreach ($cards as $c) {
        $finalAccessByCardId[$c->id] = $resolver->getFinalAccess($c);

        $qrText = $c->qr_payload ?: ($c->qr_token ? url("/cards/verify/{$c->qr_token}") : "ARISE-CARD-{$c->id}");
        $qrByCardId[$c->id] = $this->qrBase64($qrText);

        $resolvedPhoto = $this->resolvePhotoPathInfo($c);
        $photoByCardId[$c->id] = $this->photoBase64FromProfile($resolvedPhoto['path']);
        $photoIsFallbackByCardId[$c->id] = $resolvedPhoto['is_fallback'];
    }

    // Mode 2 rule: always use latest active event layout
    $layout = $cards->isNotEmpty() ? $cards[0]->event->activeCardLayout : null;

    return view('menu.admin.card.print.sheet-a5', [
        'cards' => $cards,
        'finalAccessByCardId' => $finalAccessByCardId,
        'qrByCardId' => $qrByCardId,
        'photoByCardId' => $photoByCardId,
        'photoIsFallbackByCardId' => $photoIsFallbackByCardId,
        'transportById' => $transportById,
        'accomById' => $accomById,
        'venueMap' => $venueMap,
        'zoneMap'  => $zoneMap,
        'mode' => 'preview',     // ✅ cuma preview
        'autoPrint' => false,    // ✅ jangan auto print
        'layout' => $layout,
    ]);
}
    public function printPdfSingle(Card $card, CardAccessResolver $resolver)
    {
        abort_if($card->status !== 'issued', 403, 'Card must be issued first.');
        $layout = $card->event->activeCardLayout;
        
        $final = $resolver->getFinalAccess($card);

        $qrText = $card->qr_payload ?: ($card->qr_token ? url("/cards/verify/{$card->qr_token}") : "ARISE-CARD-{$card->id}");
        $qr = $this->qrBase64($qrText);

        $resolvedPhoto = $this->resolvePhotoPathInfo($card);
        $photo = $this->photoBase64FromProfile($resolvedPhoto['path']);
        $isFallback = $resolvedPhoto['is_fallback'];

        $tIds = explode(',', $final['raw_transport_access'] ?? '');
        $transportById = \App\Models\TransportationCode::whereIn('id', $tIds)->get()->keyBy('id');

        $aIds = collect(explode(',', $final['raw_accom_access'] ?? ''))
            ->map(fn($v) => (int)$v)
            ->filter()
            ->unique()
            ->values();
        $accomById = \App\Models\AccommodationCode::whereIn('id', $aIds)->get()->keyBy('id');

        $vIds = explode(',', $final['raw_venue_access'] ?? '');
        $venueMap = \App\Models\VenueAccess::whereIn('id', $vIds)->get()->keyBy('id');

        $zIds = explode(',', $final['raw_zone_access'] ?? '');
        $zoneMap = \App\Models\ZoneAccessCode::whereIn('id', $zIds)->get()->keyBy('id');

        // Render HTML
        $html = view('menu.admin.card.print.sheet-a5', [
            'cards' => collect([$card]),
            'finalAccessByCardId' => [$card->id => $final],
            'qrByCardId' => [$card->id => $qr],
            'photoByCardId' => [$card->id => $photo],
            'photoIsFallbackByCardId' => [$card->id => $isFallback],
            'transportById' => $transportById,
            'accomById' => $accomById,
            'venueMap' => $venueMap,
            'zoneMap'  => $zoneMap,
            'mode' => 'print',
            'autoPrint' => false,
            'layout' => $layout,
        ])->render();

        try {
            $pdf = Browsershot::html($html)
                ->setNodeBinary('C:\Program Files\nodejs\node.exe')
                ->setNpmBinary('C:\Program Files\nodejs\npm.cmd')
                ->format('A5')
                ->showBackground()
                ->margins(0, 0, 0, 0)
                ->waitUntilNetworkIdle()
                ->pdf();

            return response($pdf)
                ->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            return response('Failed to generate PDF: ' . $e->getMessage(), 500);
        }
    }

    private function resolvePhotoPathInfo(Card $card): array
    {
        if ($card->card_recipient_id && $card->cardRecipient) {
            // Sumber data: import recipient
            if ($card->cardRecipient->photo_path) {
                return ['path' => $card->cardRecipient->photo_path, 'is_fallback' => false];
            }

            // Poin 6: Fallback ke logo event jika tidak ada foto
            $event = $card->event;
            if ($event && $event->logo_path) {
                return ['path' => $event->logo_path, 'is_fallback' => true];
            }

            // Tidak ada foto sama sekali → biarkan view tampilkan placeholder
            return ['path' => null, 'is_fallback' => false];
        }

        // Sumber data: regular applicant
        $profilePhoto = $card->application?->user?->profile?->profile_photo;
        if ($profilePhoto) {
            return ['path' => $profilePhoto, 'is_fallback' => false];
        }

        $snapshot = is_array($card->snapshot) ? $card->snapshot : json_decode((string) $card->snapshot, true);
        if (is_array($snapshot) && !empty($snapshot['applicant_photo'])) {
            return ['path' => (string) $snapshot['applicant_photo'], 'is_fallback' => false];
        }

        return ['path' => null, 'is_fallback' => false];
    }
}

