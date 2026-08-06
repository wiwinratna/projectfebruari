@php
  use App\Support\CardLayoutRenderStyle;
  $snap  = is_array($card->snapshot) ? $card->snapshot : json_decode($card->snapshot, true);

  $mapping = $card->mapping ?? null;
  $acc     = $mapping?->nama_akreditasi ?? $snap['mapping_name'] ?? ('M'.$card->accreditation_mapping_id);
  $color   = $mapping?->warna ?? $snap['mapping_color'] ?? '#16a34a';

  $final  = $finalAccessByCardId[$card->id] ?? [];
  $venues = $final['venues'] ?? [];
  $zones  = $final['zones'] ?? [];

  $venueChips = array_slice($venues, 0, 4);
  $zoneChips  = array_slice($zones, 0, 4);

  $qr    = $qr;
  $photo = $photo;

  $tId = $final['transportation_id'] ?? null;
  $aIds = collect($final['accommodation_ids'] ?? [])
    ->map(fn($v) => (int)$v)
    ->filter()
    ->unique()
    ->values();
  if ($aIds->isEmpty() && !empty($final['accommodation_id'])) {
    $aIds = collect([(int)$final['accommodation_id']]);
  }

  $t = $tId ? ($transportById[$tId] ?? null) : null;
  $a = (!$aIds->isEmpty()) ? ($accomById[$aIds->first()] ?? null) : null;

  $tBadge = $t ? transportBadge($t) : ['type'=>'none','icon'=>null,'code'=>null,'show_code'=>true];
  $aBadge = $a ? accommodationBadge($a) : ['type'=>'none','icon'=>null,'code'=>null,'show_code'=>true];

  $tHasIcon = filled($tBadge['icon'] ?? null);
  $aHasIcon = filled($aBadge['icon'] ?? null);

  $tShowCode = (bool)($tBadge['show_code'] ?? true);
  $aShowCode = (bool)($aBadge['show_code'] ?? true);

  $tShouldShowCode = filled($tBadge['code'] ?? null) && $tShowCode;
  $aShouldShowCode = filled($aBadge['code'] ?? null) && $aShowCode;

  $snapshotTransports = collect($snap['transports'] ?? [])->filter(fn($item) => is_array($item))->values();
  $snapshotAccommodations = collect($aIds)->map(function ($aid) use ($accomById) {
    $code = $accomById[$aid] ?? null;
    if (!$code) {
      return null;
    }
    $ab = accommodationBadge($code);
    return [
      'code' => $ab['code'] ?? $code->kode,
      'icon_key' => $ab['icon'] ?? null,
      'show_icon' => (bool)($code->show_icon ?? false),
      'show_code' => (bool)($ab['show_code'] ?? true),
      'kind' => 'hotel',
    ];
  })->filter()->values();
  $snapshotVenueChips = collect($snap['venue_chips'] ?? [])->filter(fn($item) => is_array($item))->values();
  $snapshotZoneChips = collect($snap['zone_chips'] ?? [])->filter(fn($item) => is_array($item))->values();

  // Handle raw text for imported cards
  $rawTransport = $final['raw_transport'] ?? null;
  $rawSeating = $final['raw_seating_access'] ?? null;
  $rawVenue = $final['raw_venue_access'] ?? null;
  $rawZone = $final['raw_zone_access'] ?? null;

  if ($snapshotTransports->isEmpty() && $t) {
    $snapshotTransports = collect([[
      'code' => $tBadge['code'] ?? $t->kode,
      'icon_key' => $tBadge['icon'] ?? null,
      'show_icon' => (bool)($t->show_icon ?? false),
      'show_code' => (bool)($tBadge['show_code'] ?? true),
    ]]);
  }

  if ($snapshotAccommodations->isEmpty()) {
    $snapshotAccommodations = collect($snap['accommodations'] ?? [])->filter(fn($item) => is_array($item))->values();
  }

  if ($snapshotAccommodations->isEmpty() && $a) {
    $snapshotAccommodations = collect([[
      'code' => $aBadge['code'] ?? $a->kode,
      'icon_key' => $aBadge['icon'] ?? null,
      'show_icon' => (bool)($a->show_icon ?? false),
      'show_code' => (bool)($aBadge['show_code'] ?? true),
    ]]);
  }

  if ($snapshotVenueChips->isEmpty()) {
    $snapshotVenueChips = collect($venueChips)->map(function ($vid) use ($venueMap) {
      $v = ($venueMap ?? [])[$vid] ?? null;
      return ['code' => $v['code'] ?? ($v['name'] ?? ('V'.$vid))];
    })->values();
  }

  if ($snapshotZoneChips->isEmpty()) {
    $snapshotZoneChips = collect($zoneChips)->map(function ($zid) use ($zoneMap) {
      $z = ($zoneMap ?? [])[$zid] ?? null;
      return ['code' => $z['code'] ?? ($z['name'] ?? ('Z'.$zid))];
    })->values();
  }

  if ($rawTransport) {
      $rawTransportsCol = collect(array_filter(array_map('trim', explode(',', $rawTransport))))
          ->map(function($code) use ($transportById) {
              $match = collect($transportById)->first(fn($t) => strtoupper(trim($t->kode ?? '')) === strtoupper($code));
              if ($match) {
                  $badge = transportBadge($match);
                  return ['code' => $badge['code'] ?? $match->kode, 'icon_key' => $badge['icon'] ?? null, 'show_icon' => (bool)($match->show_icon ?? false), 'show_code' => (bool)($badge['show_code'] ?? true)];
              }
              return ['code' => $code, 'icon_key' => null, 'show_icon' => false, 'show_code' => true];
          });
      $existingCodes = $snapshotTransports->pluck('code')->map(fn($c) => strtoupper(trim($c)))->toArray();
      $rawTransportsCol = $rawTransportsCol->reject(fn($t) => in_array(strtoupper(trim($t['code'])), $existingCodes));
      $snapshotTransports = $snapshotTransports->concat($rawTransportsCol);
  }
  if ($rawSeating) {
      $rawSeatingCol = collect(array_filter(array_map('trim', explode(',', $rawSeating))))
          ->map(function($code) use ($accomById) {
              $match = collect($accomById)->first(fn($a) => strtoupper(trim($a->kode ?? '')) === strtoupper($code));
              if ($match) {
                  $ab = accommodationBadge($match);
                  return ['code' => $ab['code'] ?? $match->kode, 'icon_key' => $ab['icon'] ?? null, 'show_icon' => (bool)($match->show_icon ?? false), 'show_code' => (bool)($ab['show_code'] ?? true), 'kind' => 'hotel'];
              }
              return ['code' => $code, 'icon_key' => null, 'show_icon' => false, 'show_code' => true, 'kind' => 'hotel'];
          });
      $existingCodes = $snapshotAccommodations->pluck('code')->map(fn($c) => strtoupper(trim($c)))->toArray();
      $rawSeatingCol = $rawSeatingCol->reject(fn($a) => in_array(strtoupper(trim($a['code'])), $existingCodes));
      $snapshotAccommodations = $snapshotAccommodations->concat($rawSeatingCol);
  }
  if ($rawVenue) {
      $rawVenueCol = collect(array_filter(array_map('trim', explode(',', $rawVenue))))->map(fn($v) => ['code' => $v]);
      $existingCodes = $snapshotVenueChips->pluck('code')->map(fn($c) => strtoupper(trim($c)))->toArray();
      $rawVenueCol = $rawVenueCol->reject(fn($v) => in_array(strtoupper(trim($v['code'])), $existingCodes));
      $snapshotVenueChips = $snapshotVenueChips->concat($rawVenueCol);
  }
  if ($rawZone) {
      $rawZoneCol = collect(array_filter(array_map('trim', explode(',', $rawZone))))->map(fn($z) => ['code' => $z]);
      $existingCodes = $snapshotZoneChips->pluck('code')->map(fn($c) => strtoupper(trim($c)))->toArray();
      $rawZoneCol = $rawZoneCol->reject(fn($z) => in_array(strtoupper(trim($z['code'])), $existingCodes));
      $snapshotZoneChips = $snapshotZoneChips->concat($rawZoneCol);
  }
@endphp

<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2;">
  @foreach ($layout['elements'] ?? [] as $element)
    @if ($element['visible'])
      @php
        $style = is_array($element['style'] ?? null) ? $element['style'] : [];
        $radiusCss = CardLayoutRenderStyle::borderRadiusCss($style, 4);
      @endphp
      <div style="position: absolute; left: {{ $element['rect']['xMm'] * 3.779527 }}px; top: {{ $element['rect']['yMm'] * 3.779527 }}px; width: {{ $element['rect']['wMm'] * 3.779527 }}px; height: {{ $element['rect']['hMm'] * 3.779527 }}px; overflow: hidden;">
        @if ($element['type'] === 'photo')
          {{-- Photo Element --}}
          @if ($photo)
            @php $isFb = $photoIsFallbackByCardId[$card->id] ?? false; @endphp
            <img
              src="{{ $photo }}"
              alt="Photo"
              style="width: 100%; height: 100%; object-fit: {{ $isFb ? 'contain' : 'cover' }}; {{ $isFb ? 'background: #ffffff; padding: 4px;' : '' }} border-radius: 4px;"
            />
          @else
            <div style="width: 100%; height: 100%; background: #e5e7eb;"></div>
          @endif

        @elseif ($element['type'] === 'qr')
          {{-- QR Code Element --}}
          @if ($qr)
            <img
              src="data:image/png;base64,{{ $qr }}"
              alt="QR Code"
              style="width: 100%; height: 100%; object-fit: contain;"
            />
          @else
            <div style="width: 100%; height: 100%; background: #e5e7eb;"></div>
          @endif

        @elseif ($element['type'] === 'text-name')
          {{-- Name Text --}}
          <div style="width: 100%; height: 100%; padding: 7.56px; font-weight: {{ ($element['style']['fontWeight'] ?? 'bold') }}; font-size: {{ ($element['style']['fontSizePt'] ?? 14) * 1.333333 }}px; line-height: 1.2; overflow: hidden; text-overflow: ellipsis; color: #111827; text-align: {{ ($element['style']['align'] ?? 'left') }};">
            {{ $snap['applicant_name'] ?? $snap['name'] ?? 'Nama Peserta' }}
          </div>

        @elseif ($element['type'] === 'text-job')
          {{-- Job Category Text --}}
          <div style="width: 100%; height: 100%; padding: 7.56px; font-size: {{ ($element['style']['fontSizePt'] ?? 12) * 1.333333 }}px; font-weight: {{ ($element['style']['fontWeight'] ?? 'normal') }}; color: #6b7280; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align: {{ ($element['style']['align'] ?? 'left') }};">
            {{ $snap['job_category_name'] ?? 'Posisi' }}
          </div>

        @elseif ($element['type'] === 'text-accreditation')
          {{-- Accreditation Badge --}}
          <x-card.accreditation-label :text="$acc" :color="$color" :style="$style" />

        @elseif ($element['type'] === 'group-badges')
          {{-- Transport & Accommodation Group --}}
          @php
            $badgeItems = $snapshotTransports
              ->map(fn($it) => array_merge($it, ['kind' => 'transport']))
              ->concat($snapshotAccommodations->map(fn($it) => array_merge($it, ['kind' => 'hotel'])))
              ->values();
          @endphp
          <x-card.chips-badges :items="$badgeItems" :style="$style" />

        @elseif ($element['type'] === 'group-chips')
          {{-- Venue & Zone Chips --}}
          @php
            $maxVenue = $element['style']['maxVenueChips'] ?? 4;
            $maxZone = $element['style']['maxZoneChips'] ?? 4;
            $zoneItems = $snapshotVenueChips->take($maxVenue)->concat($snapshotZoneChips->take($maxZone))->values();
          @endphp
          <x-card.chips-zones :items="$zoneItems" :style="$style" />

        @endif
      </div>
    @endif
  @endforeach
</div>



