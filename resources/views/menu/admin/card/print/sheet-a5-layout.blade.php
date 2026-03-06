@php
  use App\Support\CardLayoutRenderStyle;
  $snap  = is_array($card->snapshot) ? $card->snapshot : json_decode($card->snapshot, true);

  $acc   = $snap['mapping_name'] ?? ('M'.$card->accreditation_mapping_id);
  $color = $snap['mapping_color'] ?? '#16a34a';

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
@endphp

<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2;">
  @foreach ($layout['elements'] ?? [] as $element)
    @if ($element['visible'])
      @php
        $style = is_array($element['style'] ?? null) ? $element['style'] : [];
        $radiusCss = CardLayoutRenderStyle::borderRadiusCss($style, 4);
      @endphp
      <div style="position: absolute; left: {{ $element['rect']['xMm'] }}mm; top: {{ $element['rect']['yMm'] }}mm; width: {{ $element['rect']['wMm'] }}mm; height: {{ $element['rect']['hMm'] }}mm; overflow: hidden;">
        @if ($element['type'] === 'photo')
          {{-- Photo Element --}}
          @if ($photo)
            <img
              src="{{ $photo }}"
              alt="Photo"
              style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;"
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
          <div style="width: 100%; height: 100%; padding: 2mm; font-weight: {{ ($element['style']['fontWeight'] ?? 'bold') }}; font-size: {{ ($element['style']['fontSizePt'] ?? 14) }}pt; line-height: 1.2; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: {{ ($element['style']['lineClamp'] ?? 2) }}; -webkit-box-orient: vertical; color: var(--ink-strong); text-align: {{ ($element['style']['align'] ?? 'left') }};">
            {{ $snap['applicant_name'] ?? $snap['name'] ?? 'Nama Peserta' }}
          </div>

        @elseif ($element['type'] === 'text-job')
          {{-- Job Category Text --}}
          <div style="width: 100%; height: 100%; padding: 2mm; font-size: {{ ($element['style']['fontSizePt'] ?? 12) }}pt; font-weight: {{ ($element['style']['fontWeight'] ?? 'normal') }}; color: var(--ink-soft); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align: {{ ($element['style']['align'] ?? 'left') }};">
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

