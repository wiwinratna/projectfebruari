@php
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
  $aId = $final['accommodation_id'] ?? null;

  $t = $tId ? ($transportById[$tId] ?? null) : null;
  $a = $aId ? ($accomById[$aId] ?? null) : null;

  $tBadge = $t ? transportBadge($t) : ['type'=>'none','icon'=>null,'code'=>null,'show_code'=>true];
  $aBadge = $a ? accommodationBadge($a) : ['type'=>'none','icon'=>null,'code'=>null,'show_code'=>true];

  $tHasIcon = filled($tBadge['icon'] ?? null);
  $aHasIcon = filled($aBadge['icon'] ?? null);

  $tShowCode = (bool)($tBadge['show_code'] ?? true);
  $aShowCode = (bool)($aBadge['show_code'] ?? true);

  $tShouldShowCode = filled($tBadge['code'] ?? null) && $tShowCode;
  $aShouldShowCode = filled($aBadge['code'] ?? null) && $aShowCode;
@endphp

<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2;">
  @foreach ($layout['elements'] ?? [] as $element)
    @if ($element['visible'])
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
          <div style="width: 100%; height: 100%; padding: 2mm 4mm; font-size: {{ ($element['style']['fontSizePt'] ?? 10) }}pt; font-weight: {{ ($element['style']['fontWeight'] ?? 'bold') }}; color: white; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; border-radius: 4px; display: flex; align-items: center; justify-content: {{ ($element['style']['align'] === 'right' ? 'flex-end' : ($element['style']['align'] === 'center' ? 'center' : 'flex-start')) }}; background-color: {{ $color }};">
            {{ $acc }}
          </div>

        @elseif ($element['type'] === 'group-badges')
          {{-- Transport & Accommodation Group --}}
          <div style="width: 100%; height: 100%; padding: 2mm; display: flex; flex-wrap: wrap; gap: 3mm; align-content: flex-start; align-items: flex-start; font-size: 8pt; overflow: hidden;">
            @if($t && ($tHasIcon || $tShouldShowCode))
              <span style="background: var(--chip-bg); border: 1px solid var(--chip-border); border-radius: 3px; padding: 2mm 4mm; display: inline-flex; align-items: center; gap: 2mm; white-space: nowrap; flex-shrink: 1; min-width: 0; max-width: 100%; overflow: hidden; text-overflow: ellipsis;">
                @if($tHasIcon)
                  <x-catalog-icon :key="$tBadge['icon']" size="12pt" />
                @endif
                @if($tShouldShowCode)
                  <span style="font-family: monospace; font-weight: 600;">{{ $tBadge['code'] }}</span>
                @endif
              </span>
            @endif

            @if($a && ($aHasIcon || $aShouldShowCode))
              <span style="background: var(--chip-bg); border: 1px solid var(--chip-border); border-radius: 3px; padding: 2mm 4mm; display: inline-flex; align-items: center; gap: 2mm; white-space: nowrap; flex-shrink: 1; min-width: 0; max-width: 100%; overflow: hidden; text-overflow: ellipsis;">
                @if($aHasIcon)
                  <x-catalog-icon :key="$aBadge['icon']" size="12pt" />
                @endif
                @if($aShouldShowCode)
                  <span style="font-family: monospace; font-weight: 600;">{{ $aBadge['code'] }}</span>
                @endif
              </span>
            @endif
          </div>

        @elseif ($element['type'] === 'group-chips')
          {{-- Venue & Zone Chips --}}
          <div style="width: 100%; height: 100%; padding: 2mm; display: flex; flex-wrap: wrap; gap: 3mm; align-content: flex-start; align-items: flex-start; font-size: 8pt; overflow: hidden;">
            @php
              $maxVenue = $element['style']['maxVenueChips'] ?? 4;
              $maxZone = $element['style']['maxZoneChips'] ?? 4;
            @endphp
            @foreach (array_slice($venueChips, 0, $maxVenue) as $vid)
              @php
                $v = ($venueMap ?? [])[$vid] ?? null;
                $label = $v['code'] ?? ($v['name'] ?? ('V'.$vid));
              @endphp
              <span style="background: var(--chip-bg); border: 1px solid var(--chip-border); border-radius: 3px; padding: 2mm 4mm; display: inline-block; white-space: nowrap; flex-shrink: 1; min-width: 0; max-width: 100%; overflow: hidden; text-overflow: ellipsis;">
                {{ Str::limit($label, 12) }}
              </span>
            @endforeach
            @foreach (array_slice($zoneChips, 0, $maxZone) as $zid)
              @php
                $z = ($zoneMap ?? [])[$zid] ?? null;
                $label = $z['code'] ?? ($z['name'] ?? ('Z'.$zid));
              @endphp
              <span style="background: var(--chip-bg); border: 1px solid var(--chip-border); border-radius: 3px; padding: 2mm 4mm; display: inline-block; white-space: nowrap; flex-shrink: 1; min-width: 0; max-width: 100%; overflow: hidden; text-overflow: ellipsis;">
                {{ Str::limit($label, 12) }}
              </span>
            @endforeach
          </div>

        @endif
      </div>
    @endif
  @endforeach
</div>

