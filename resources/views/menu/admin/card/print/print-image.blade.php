<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <style>
    @page { margin:0; size:A5 portrait; }
    html,body{margin:0;padding:0;background:#fff;font-family:DejaVu Sans,sans-serif;}
    .page{width:148mm;height:210mm;position:relative;overflow:hidden;background:#fff;}

    :root{
      --left: 10mm;
      --top: 35mm;
      --width: 128mm;
      --height: 140mm;
    }
    .content{position:absolute;left:var(--left);top:var(--top);width:var(--width);height:var(--height);}

    .card{position:relative;width:100%;height:100%;}

    /* Photo */
    .photo{position:absolute;left:0;top:0;width:54mm;height:62mm;border-radius:4px;overflow:hidden;background:#fff;}
    .photo img{width:100%;height:100%;object-fit:cover;display:block;}

    /* Accreditation block (samain tinggi foto) */
    .accWrap{position:absolute;right:0;top:0;width:64mm;height:62mm;border-radius:4px;background:#fff;overflow:hidden;}
    .accLogoSpace{height:38mm;background:#fff;}
    .accBar{height:24mm;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:22pt;color:#fff;}

    /* Name + role */
    .name{position:absolute;left:0;top:66mm;width:70mm;font-size:16pt;font-weight:900;color:#111827;line-height:1.1;word-break:break-word;}
    .meta{position:absolute;left:0;top:79mm;width:70mm;font-size:11pt;color:#6b7280;font-weight:700;line-height:1.2;}

    /* Privileges */
    .privTitle{position:absolute;left:0;top:92mm;font-size:10pt;color:#6b7280;font-weight:800;}
    .chipRow{position:absolute;left:0;top:98mm;display:flex;gap:2mm;flex-wrap:wrap;max-width:70mm;}

    /* Access */
    .accessTitle{position:absolute;left:0;top:114mm;font-size:10pt;color:#6b7280;font-weight:800;}
    .accessRow{position:absolute;left:0;top:120mm;display:flex;gap:2mm;flex-wrap:wrap;max-width:86mm;}

    /* Chip */
    .chip{
      padding:2mm 3mm;
      border:1px solid #d1d5db;
      border-radius:1.5mm;
      font-size:12pt;
      font-weight:900;
      color:#111827;
      background:#fff;
      display:inline-flex;
      align-items:center;
      gap:2mm;
      white-space:nowrap;
    }

    /* QR */
    .qrBox{position:absolute;right:0;top:66mm;width:54mm;height:54mm;overflow:hidden;background:transparent;border:none;}
    .qrBox img{width:100%;height:100%;display:block;}

    /* Inline SVG size */
    .ico{width:16pt;height:16pt;display:inline-block;}
  </style>
</head>
<body>
@foreach($cards as $card)
  @php
    $snap  = is_array($card->snapshot) ? $card->snapshot : json_decode($card->snapshot, true);

    $acc   = $snap['mapping_name'] ?? ('M'.$card->accreditation_mapping_id);
    $color = $snap['mapping_color'] ?? '#16a34a';

    $final  = $finalAccessByCardId[$card->id] ?? [];
    $venues = $final['venues'] ?? [];
    $zones  = $final['zones'] ?? [];

    // tampilkan 4-4 biar rapi
    $venueChips = array_slice($venues, 0, 4);
    $zoneChips  = array_slice($zones, 0, 4);

    $qr    = $qrByCardId[$card->id] ?? null;
    $photo = $photoByCardId[$card->id] ?? null;

    $tId = $final['transportation_id'] ?? null;
    $aId = $final['accommodation_id'] ?? null;

    $t = $tId ? ($transportById[$tId] ?? null) : null;
    $a = $aId ? ($accomById[$aId] ?? null) : null;

    // helper kamu: icon/code + show_code
    $tBadge = $t ? transportBadge($t) : ['type'=>'none','icon'=>null,'code'=>null];
    $aBadge = $a ? accommodationBadge($a) : ['type'=>'none','icon'=>null,'code'=>null];

    // ✅ SVG mapping (tambah kalau perlu)
    $svg = function($key){
      return match($key){
        // Transport
        'bus' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 16V6a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v10"/><path d="M4 16h16"/><path d="M7 16v3"/><path d="M17 16v3"/><circle cx="7" cy="21" r="1"/><circle cx="17" cy="21" r="1"/></svg>',
        'car' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l2-6h14l2 6"/><path d="M5 12h14"/><path d="M6 18h.01"/><path d="M18 18h.01"/><path d="M5 12v6"/><path d="M19 12v6"/></svg>',
        'taxi' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l2-6h14l2 6"/><path d="M7 6l1-3h8l1 3"/><path d="M5 12v6"/><path d="M19 12v6"/><path d="M6 18h.01"/><path d="M18 18h.01"/></svg>',
        'train' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 3h12v11a4 4 0 0 1-4 4H10a4 4 0 0 1-4-4V3z"/><path d="M6 14h12"/><path d="M8 21l2-3"/><path d="M16 21l-2-3"/><circle cx="9" cy="16" r="1"/><circle cx="15" cy="16" r="1"/></svg>',
        'plane' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 21l2-8 8-2-8-2-2-8-2 8-8 2 8 2 2 8z"/></svg>',
        'ship' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 18l9 3 9-3"/><path d="M5 16V8l7-3 7 3v8"/><path d="M5 12h14"/></svg>',
        'motorcycle' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="6" cy="17" r="3"/><circle cx="18" cy="17" r="3"/><path d="M9 17h6l-2-6h-3"/><path d="M13 11l2-2h3"/></svg>',
        'bicycle' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="5" cy="17" r="3"/><circle cx="19" cy="17" r="3"/><path d="M5 17h5l2-7h4"/><path d="M10 10l3 7"/><path d="M14 10h2"/></svg>',
        'person-walking' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="2"/><path d="M10 22l2-7 2 2 1 5"/><path d="M8 11l4-2 3 2"/></svg>',
        'van-shuttle' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12V7a3 3 0 0 1 3-3h10a3 3 0 0 1 3 3v5"/><path d="M3 12h18"/><path d="M7 12v6"/><path d="M17 12v6"/><circle cx="7" cy="20" r="1"/><circle cx="17" cy="20" r="1"/></svg>',
        'truck' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h13v10H3z"/><path d="M16 10h4l1 2v5h-5z"/><circle cx="7" cy="19" r="1"/><circle cx="18" cy="19" r="1"/></svg>',

        // Dining / Accommodation (contoh)
        'utensils' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 3v7a2 2 0 0 0 2 2v9"/><path d="M8 3v7"/><path d="M12 3v7a2 2 0 0 1-2 2"/><path d="M16 3v18"/><path d="M16 7h4"/></svg>',
        'mug-hot' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8h10v8a4 4 0 0 1-4 4H6z"/><path d="M16 10h2a2 2 0 0 1 0 4h-2"/><path d="M8 2v3"/><path d="M12 2v3"/></svg>',
        'bed' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h18v10H3z"/><path d="M3 12h18"/><path d="M7 7v5"/><path d="M17 7v5"/></svg>',
        'hotel' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 22V2h18v20"/><path d="M7 6h2"/><path d="M7 10h2"/><path d="M7 14h2"/><path d="M13 6h2"/><path d="M13 10h2"/><path d="M13 14h2"/></svg>',

        default => null
      };
    };
  @endphp

  <div class="page">
    <div class="content">
      <div class="card">

        {{-- PHOTO --}}
        <div class="photo">
          @if($photo)
            <img src="data:image/jpeg;base64,{{ $photo }}" alt="Photo">
          @endif
        </div>

        {{-- ACC --}}
        <div class="accWrap">
          <div class="accLogoSpace"></div>
          <div class="accBar" style="background: {{ $color }};">
            {{ $acc }}
          </div>
        </div>

        {{-- NAME + ROLE --}}
        <div class="name">{{ $snap['name'] ?? '—' }}</div>
        <div class="meta">{{ $snap['job_category_name'] ?? '' }}</div>

        {{-- PRIVILEGES --}}
        <div class="privTitle">Additional Privileges</div>
        <div class="chipRow">
          @if($t)
            @php
              $iconSvg = filled($tBadge['icon'] ?? null) ? $svg($tBadge['icon']) : null;
              $codeTxt = $tBadge['code'] ?? null;
              $showAny = $iconSvg || filled($codeTxt);
            @endphp
            @if($showAny)
              <span class="chip">
                @if($iconSvg){!! $iconSvg !!}@endif
                @if(filled($codeTxt)) <span>{{ $codeTxt }}</span> @endif
              </span>
            @endif
          @endif

          @if($a)
            @php
              $iconSvg = filled($aBadge['icon'] ?? null) ? $svg($aBadge['icon']) : null;
              $codeTxt = $aBadge['code'] ?? null;
              $showAny = $iconSvg || filled($codeTxt);
            @endphp
            @if($showAny)
              <span class="chip">
                @if($iconSvg){!! $iconSvg !!}@endif
                @if(filled($codeTxt)) <span>{{ $codeTxt }}</span> @endif
              </span>
            @endif
          @endif
        </div>

        {{-- ACCESS --}}
        <div class="accessTitle">Venue and Sport Access</div>
        <div class="accessRow">
          @foreach($venueChips as $vid)
            @php
              $v = $venueMap[$vid] ?? null;
              $label = $v['code'] ?? ($v['name'] ?? ('V'.$vid));
            @endphp
            <span class="chip">{{ $label }}</span>
          @endforeach

          @foreach($zoneChips as $zid)
            @php
              $z = $zoneMap[$zid] ?? null;
              $label = $z['code'] ?? ($z['name'] ?? ('Z'.$zid));
            @endphp
            <span class="chip">{{ $label }}</span>
          @endforeach
        </div>

        {{-- QR --}}
        <div class="qrBox">
          @if($qr)
            <img src="data:image/png;base64,{{ $qr }}" alt="QR">
          @endif
        </div>

      </div>
    </div>
  </div>
@endforeach
</body>
</html>