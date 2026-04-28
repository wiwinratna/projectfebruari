<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font (Inter) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    /* ===== PRINT LOCK ===== */
    @page { size: A5 portrait; margin: 0; }

    *{
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      box-sizing: border-box;
    }

    html, body { margin:0; padding:0; }

    /* ===== FONT BASE (SAFE) ===== */
    html, body{
      font-family: "Inter", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-rendering: geometricPrecision;
    }

    /* preview mode background */
    body.preview-bg{ background:#f3f4f6; }

    /* ===== WRAP =====
       - preview: center + bisa banyak page (batch)
       - print: rapat tanpa gap
    */
    .wrap{
      min-height:100vh;
      display:flex;
      flex-direction:column;    /* ✅ penting: batch tetap kebawah */
      align-items:center;       /* ✅ center horizontal */
      gap:16px;                 /* ✅ jarak antar kartu saat preview */
      padding:24px 16px;
    }

    @media print {
      html, body { background:#fff !important; }
      body.preview-bg{ background:#fff !important; }
      .wrap{
        padding:0 !important;
        gap:0 !important;       /* ✅ jangan ada gap pas print */
      }
      .no-print{ display:none !important; }
    }

    /* ===== A5 SHEET ===== */
    .page{
      width:148mm;
      height:210mm;
      background:#fff;
      position:relative;
      overflow:hidden;
    }

    /* ✅ 1 CARD = 1 SHEET (BATCH PRINT FIX) */
    @media print {
      .page{
        page-break-after: always;
        break-after: page;
      }
      .page:last-child{
        page-break-after: auto;
        break-after: auto;
      }
    }

    /* ===== DEFAULT BLANK TEMPLATE: ONLY MIDDLE AREA PRINTED ===== */
    :root{
      --left: 10mm;
      --top: 35mm;
      --width: 128mm;
      --height: 140mm;

      /* typographic colors */
      --ink-strong:#111827;
      --ink:#374151;
      --ink-soft:#6b7280;

      --chip-bg: rgba(17,24,39,.06);
      --chip-border: rgba(17,24,39,.12);
    }

    .content{
      position:absolute;
      left:var(--left);
      top:var(--top);
      width:var(--width);
      height:var(--height);
    }

    /* ===== CARD CANVAS ===== */
    .card{ position:relative; width:100%; height:100%; }

    /* photo */
    .photo{
      position:absolute;
      left:0;
      top:0;
      width:55mm;
      height:66mm;
      border-radius:4px;
      overflow:hidden;
      background:#e5e7eb;
    }
    .photo img{ width:100%; height:100%; object-fit:cover; display:block; }

    /* acc block (D) */
    .accWrap{
      position:absolute;
      right:0;
      top:0;               /* ✅ tetap di atas */
      width:64mm;
      height:62mm;
      border-radius: 0 10px 0 10px;
      background:#fff;
      overflow:hidden;
      border:0;
    }

    .accBar{
      height:24mm;
      line-height:24mm;
      text-align:center;
      font-weight:900;
      font-size:55pt;
      color:#fff;
      letter-spacing:0.3px;

      /* ungu ikut rounded */
      border-radius: 0 0 0 20px;
    }

    /* ===== LEFT AREA (FLOW) =====
       Start-nya tetap ngikut posisi kamu (70mm).
       Elemen bawah ikut turun otomatis.
    */
    .leftCol{
      position:absolute;
      left:0;
      top:70mm;            /* ✅ ini sesuai posisi name kamu */
      width:70mm;
    }

    .name{
      font-size:19pt;
      font-weight:800;
      color:var(--ink-strong);
      line-height:1.12;
      word-break:break-word;
      letter-spacing:0.1px;

      display:-webkit-box;
      -webkit-line-clamp:2;
      -webkit-box-orient:vertical;
      overflow:hidden;

      max-height:2.25em;
      padding-bottom:0.6mm;
    }

    .meta{
      margin-top:2mm;
      font-size:12pt;
      color:var(--ink-soft);
      font-weight:500;
      line-height:1.25;
    }

    .privTitle, .accessTitle{
      margin-top:5mm;
      font-size:9pt;
      color:var(--ink);
      font-weight:600;
      letter-spacing:0.2px;
    }

    .chipRow, .accessRow{
      margin-top:2mm;
      display:flex;
      gap:2mm;
      flex-wrap:wrap;
      max-width:70mm;
    }

    .chip{
      padding:1.6mm 2.4mm;
      border:1px solid var(--chip-border);
      border-radius:1mm;
      font-size:12pt;
      font-weight:700;
      color:var(--ink-strong);
      background:var(--chip-bg);
      display:inline-flex;
      align-items:center;
      gap:1.0mm;
      white-space:nowrap;
      line-height:1;
      letter-spacing:0.1px;
    }

    .mono{
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      font-weight:600;
    }

    /* icon sizing (SVG/IMG) */
    .chip svg{
      width:12pt; height:12pt;
      display:inline-block;
      vertical-align:middle;
      opacity:.92;
    }
    .chip img.icon{
      width:12pt; height:12pt;
      display:inline-block;
      vertical-align:middle;
      opacity:.92;
    }

    /* QR */
    .qrBox{
      position:absolute;
      right:-5mm;
      top:72mm;            /* ✅ tetap */
      width:56mm;
      height:56mm;
      overflow:hidden;
      background:transparent;
      border:none;
      border-radius:0;
    }
    .qrBox img{ width:100%; height:100%; display:block; }

  </style>
</head>

@php
  $modeVal   = ($mode ?? 'preview');
  $isPreview = ($modeVal === 'preview');
  
  // Mode 2 rule: layout is global by event (never fallback to per-card snapshot layout_id)
  $eventLayout = null;
  if (isset($layout)) {
    // Layout passed directly
    $eventLayout = $layout;
  } elseif (isset($cards) && count($cards) > 0) {
    // Get layout only from first card's event active layout
    $firstCard = $cards[0];
    if (isset($firstCard->event) && isset($firstCard->event->activeCardLayout)) {
      $eventLayout = $firstCard->event->activeCardLayout;
    }
  }
  
  // Use layout if available, otherwise use default
  $useLayout = $eventLayout && isset($eventLayout->layout_json);
  $layoutJson = $useLayout ? $eventLayout->layout_json : \App\Models\CardLayout::getDefaultLayout();
@endphp

<body class="{{ $isPreview ? 'preview-bg' : '' }}">
<div class="wrap">

@foreach($cards as $card)
  @php
    $snap  = is_array($card->snapshot) ? $card->snapshot : json_decode($card->snapshot, true);

    $acc   = $snap['mapping_name'] ?? ('M'.$card->accreditation_mapping_id);
    $color = $snap['mapping_color'] ?? '#16a34a';

    $final  = $finalAccessByCardId[$card->id] ?? [];
    $venues = $final['venues'] ?? [];
    $zones  = $final['zones'] ?? [];

    $venueChips = array_slice($venues, 0, 4);
    $zoneChips  = array_slice($zones, 0, 4);

    $qr    = $qrByCardId[$card->id] ?? null;
    $photo = $photoByCardId[$card->id] ?? null;

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
    $a = !$aIds->isEmpty() ? ($accomById[$aIds->first()] ?? null) : null;

    $tBadge = $t ? transportBadge($t) : ['type'=>'none','icon'=>null,'code'=>null,'show_code'=>true];
    $aBadge = $a ? accommodationBadge($a) : ['type'=>'none','icon'=>null,'code'=>null,'show_code'=>true];

    $tHasIcon = filled($tBadge['icon'] ?? null);
    $aHasIcon = filled($aBadge['icon'] ?? null);

    $tShowCode = (bool)($tBadge['show_code'] ?? true);
    $aShowCode = (bool)($aBadge['show_code'] ?? true);

    $tShouldShowCode = filled($tBadge['code'] ?? null) && $tShowCode;
    $aShouldShowCode = filled($aBadge['code'] ?? null) && $aShowCode;
    $accommodationBadges = $aIds->map(function ($aid) use ($accomById) {
      $code = $accomById[$aid] ?? null;
      if (!$code) {
        return null;
      }
      $ab = accommodationBadge($code);
      return [
        'hasIcon' => filled($ab['icon'] ?? null),
        'icon' => $ab['icon'] ?? null,
        'showCode' => (bool)($ab['show_code'] ?? true),
        'code' => $ab['code'] ?? $code->kode,
      ];
    })->filter()->values();
  @endphp

  <div class="page">
    @php
      // Get event for template background
      $event = $card->event;
      $templatePath = $event?->card_template_path;
      $templateExists = $templatePath ? Storage::disk('public')->exists($templatePath) : false;
      $templateUrl = $templatePath ? url('/media/' . ltrim($templatePath, '/')) : null;
    @endphp
    
    @if($templateExists && $templateUrl)
      {{-- Template Background --}}
      <img
        src="{{ $templateUrl }}"
        alt="Template"
        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;"
      />
    @endif
    
    @if($useLayout)
      {{-- RENDER WITH CUSTOM LAYOUT --}}
      @include('menu.admin.card.print.sheet-a5-layout', [
        'card' => $card,
        'layout' => $layoutJson,
        'qr' => $qrByCardId[$card->id] ?? null,
        'photo' => $photoByCardId[$card->id] ?? null,
        'finalAccessByCardId' => $finalAccessByCardId,
        'venueMap' => $venueMap ?? [],
        'zoneMap' => $zoneMap ?? [],
        'transportById' => $transportById ?? [],
        'accomById' => $accomById ?? [],
      ])
    @else
      {{-- FALLBACK TO MODE 1 DEFAULT LAYOUT --}}
      <div class="content">
        <div class="card">

          {{-- Photo --}}
          <div class="photo">
            @if($photo)
              <img src="{{ $photo }}" alt="Photo">
            @endif
          </div>

          {{-- Accreditation (D) --}}
          <div class="accWrap">
            <div class="accLogoSpace"></div>
            <div class="accBar" style="background: {{ $color }};">
              {{ $acc }}
            </div>
          </div>

          {{-- LEFT COLUMN (flow) --}}
          <div class="leftCol">
            <div class="name">{{ $snap['name'] ?? '—' }}</div>
            <div class="meta">{{ $snap['job_category_name'] ?? '' }}</div>

            <div class="privTitle">Transportation &amp; Accommodation</div>
            <div class="chipRow">
              @if($t && ($tHasIcon || $tShouldShowCode))
                <span class="chip">
                  @if($tHasIcon && filled($tBadge['icon'] ?? null))
                    <x-card.icon-svg :icon-key="$tBadge['icon']" type="transport" size="12pt" />
                  @endif
                  @if($tShouldShowCode)
                    <span class="mono">{{ $tBadge['code'] }}</span>
                  @endif
                </span>
              @endif

              @foreach($accommodationBadges as $ab)
                @if(($ab['hasIcon'] ?? false) || (($ab['showCode'] ?? true) && filled($ab['code'] ?? null)))
                  <span class="chip">
                    @if(($ab['hasIcon'] ?? false) && filled($ab['icon'] ?? null))
                      <x-card.icon-svg :icon-key="$ab['icon']" type="accommodation" size="12pt" />
                    @endif
                    @if(($ab['showCode'] ?? true) && filled($ab['code'] ?? null))
                      <span class="mono">{{ $ab['code'] }}</span>
                    @endif
                  </span>
                @endif
              @endforeach
            </div>

            <div class="accessTitle">Venue &amp; Sport Access</div>
            <div class="accessRow">
              @foreach($venueChips as $vid)
                @php
                  $v = ($venueMap ?? [])[$vid] ?? null;
                  $label = $v['code'] ?? ($v['name'] ?? ('V'.$vid));
                @endphp
                <span class="chip">{{ $label }}</span>
              @endforeach

              @foreach($zoneChips as $zid)
                @php
                  $z = ($zoneMap ?? [])[$zid] ?? null;
                  $label = $z['code'] ?? ($z['name'] ?? ('Z'.$zid));
                @endphp
                <span class="chip">{{ $label }}</span>
              @endforeach
            </div>
          </div>

          {{-- QR --}}
          <div class="qrBox">
            @if($qr)
              <img src="data:image/png;base64,{{ $qr }}" alt="QR">
            @endif
          </div>

        </div>
      </div>
    @endif
  </div>
@endforeach

</div>

<!-- Auto shrink name (max 2 lines) -->
<script>
(function(){
  function shrinkName(el, minPt=12){
    if(!el) return;
    const maxH = parseFloat(getComputedStyle(el).maxHeight);
    if(!maxH || isNaN(maxH)) return;

    let sizePx = parseFloat(getComputedStyle(el).fontSize);
    const minPx = minPt * 1.333;

    while(el.scrollHeight > maxH + 0.5 && sizePx > minPx){
      sizePx -= 1;
      el.style.fontSize = sizePx + "px";
    }
  }

  window.addEventListener('load', () => {
    document.querySelectorAll('.name').forEach(el => shrinkName(el, 12));
  });
})();
</script>

{{-- Auto Print mode --}}
@if(($modeVal ?? '') === 'print' && !empty($autoPrint))
<script>
  window.addEventListener('load', () => {
    setTimeout(() => window.print(), 400);
    window.addEventListener('afterprint', () => setTimeout(() => window.close(), 300));
  });
</script>
@endif

</body>
</html>
