{{-- resources/views/admin/access-cards/print.blade.php --}}
@php use SimpleSoftwareIO\QrCode\Facades\QrCode; @endphp
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Access Card Print</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --ink:#0f172a;
      --muted:#64748b;
      --line:#e2e8f0;
      --soft:#f8fafc;
      --paper:#ffffff;
      --bg:#f1f5f9;

      /* rose elegan (kalem) */
      --accent:#e11d48;
      --accent2:#fb7185;
      --accentDark:#9f1239;

      --shadow: 0 18px 46px rgba(15,23,42,.12);
      --radius: 16px;
    }

    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
      background:var(--bg);
      color:var(--ink);
    }

    /* ================= SCREEN (PREVIEW) ================= */
    .wrap{ max-width: 980px; margin: 18px auto; padding: 0 16px; }

    /* tombol nggak nanggung: sticky + floating style */
    .topbar{
      position: sticky;
      top: 12px;
      z-index: 60;
      display:flex;
      justify-content:flex-end;
      gap:10px;
      margin: 10px 0 18px;
    }
    .btn{
      border:1px solid var(--line);
      background:#fff;
      padding:10px 14px;
      border-radius:999px;
      font-weight:900;
      cursor:pointer;
      color:var(--ink);
      box-shadow: 0 10px 24px rgba(15,23,42,.08);
    }
    .btn.primary{
      border-color: rgba(225,29,72,.25);
      background: rgba(225,29,72,.12);
      color: var(--accentDark);
    }

    /* 1 kolom (front lalu back) dan kartu besar */
    .sheet{
      display:flex;
      flex-direction:column;
      gap: 26px;
      align-items:center;
      padding-bottom: 60px;
    }

    /* kartu ukuran asli mm, tapi di layar dibesarin */
    .badge{
      width: 90mm;
      height: 140mm;
      border-radius: var(--radius);
      border:1px solid var(--line);
      background: var(--paper);
      box-shadow: var(--shadow);
      overflow:hidden;
      position:relative;
      transform: scale(1.25);
      transform-origin: top center;
    }

    /* ================= PRINT ================= */
    @page { size: A4; margin: 12mm; }

    @media print{
      body{ background:#fff; }
      .topbar{ display:none !important; }
      .wrap{ max-width:none; margin:0; padding:0; }

      .sheet{ display:block; padding:0; }

      .badge{
        transform:none !important;      /* layar doang yang scale */
        box-shadow:none !important;
        margin: 0 auto;
        page-break-after: always;       /* 1 kartu = 1 halaman */
      }
      .badge:last-child{ page-break-after: auto; }
    }

    /* ================= CARD HEADER STRIP ================= */
    .strip{
      position:absolute;
      left:0; right:0; top:0;
      height: 16mm;
      background: linear-gradient(90deg, rgba(225,29,72,1), rgba(251,113,133,1));
    }
    .strip::after{
      content:"";
      position:absolute;
      left:0; right:0; bottom:0;
      height:1px;
      background: rgba(255,255,255,.35);
    }
    .badge.back .strip{
    background: #ffffff;
    border-bottom: 1px solid var(--line);
    }


    /* ================= PAD (NO FLEX) ================= */
    .pad{
      position:absolute;
      inset:0;
      padding: 12px;
    }

    /* ================= HEADER (ABSOLUTE) ================= */
    .hdr{
      position:absolute;
      left:12px;
      right:12px;
      top:10px;
      z-index:3;
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:10px;
      color:#fff;
    }
    .hdr-left{ min-width:0; }
    .event-title{
      font-size:11.2px;
      font-weight:900;
      letter-spacing:.2px;
      line-height:1.12;
      display:-webkit-box;
      -webkit-line-clamp:2;
      -webkit-box-orient:vertical;
      overflow:hidden;
      max-width:62mm;
    }
    .event-sub{
      margin-top:2px;
      font-size:9.3px;
      font-weight:800;
      opacity:.92;
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:62mm;
    }
    .status{
      font-size:9px;
      font-weight:900;
      padding:6px 9px;
      border-radius:999px;
      background: rgba(255,255,255,.16);
      border:1px solid rgba(255,255,255,.25);
      text-transform:uppercase;
      white-space:nowrap;
    }

    /* ================= BODY AREA ================= */
    .front-body,
    .back-body{
      position:absolute;
      left:12px;
      right:12px;
      top: calc(16mm + 10px); 
      bottom: 12px;          
    }

    /* ================= FRONT ================= */
    .idrow{
      display:grid;
      grid-template-columns: 30mm 1fr;
      gap:10px;
      align-items:start;
    }
    .photo{
      width:30mm;
      height: 38mm;
      border-radius: 14px;
      border:1px solid rgba(226,232,240,.95);
      background: linear-gradient(180deg, #ffffff, var(--soft));
      overflow:hidden;
      display:flex;
      align-items:center;
      justify-content:center;
      color: rgba(15,23,42,.32);
      font-weight:900;
      font-size:9px;
      letter-spacing:.3px;
      text-transform:uppercase;
    }
    .photo img{ width:100%; height:100%; object-fit:cover; display:block; }

    .name{
      margin:1px 0 0;
      font-size:13.2px;
      font-weight:900;
      line-height:1.15;
      letter-spacing:.1px;
      display:-webkit-box;
      -webkit-line-clamp:2;
      -webkit-box-orient:vertical;
      overflow:hidden;
    }
    .position{
      margin:3px 0 0;
      font-size:10.3px;
      font-weight:800;
      color: rgba(15,23,42,.72);
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
    }

    .meta{
      margin-top:7px;
      display:flex;
      flex-wrap:wrap;
      gap:6px;
      align-items:center;
    }
    .pill{
      font-size:9px;
      font-weight:900;
      padding:5px 8px;
      border-radius:999px;
      border:1px solid rgba(15,23,42,.10);
      background:#fff;
      color: rgba(15,23,42,.70);
      white-space:nowrap;
      max-width:100%;
      overflow:hidden;
      text-overflow:ellipsis;
    }
    .mono{
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono","Courier New", monospace;
    }

    .classbox{
      margin-top:10px;
      border:1px solid var(--line);
      border-radius: 16px;
      background:
        radial-gradient(120px 60px at 12% 20%, rgba(225,29,72,.10), transparent 70%),
        linear-gradient(90deg, rgba(225,29,72,.06), rgba(248,250,252,.88));
      padding: 10px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
    }
    .classbox .k{
      font-size:9px;
      font-weight:900;
      color: var(--muted);
      text-transform:uppercase;
      letter-spacing:.2px;
      margin-bottom:3px;
    }
    .classbox .v{
      font-size:10.4px;
      font-weight:900;
      color: rgba(15,23,42,.82);
      white-space:nowrap;
      overflow:hidden;
      text-overflow:ellipsis;
      max-width:56mm;
    }
    .classbox .big{
      width: 26mm;
      height: 14mm;
      border-radius: 14px;
      border:1px solid rgba(15,23,42,.10);
      background:#fff;
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:15px;
      font-weight:900;
      color: rgba(15,23,42,.90);
      letter-spacing:.5px;
    }

    .qrrow{
      margin-top:10px;
      display:grid;
      grid-template-columns: 1fr 34mm;
      gap:10px;
      align-items:stretch;
    }
    .reg{
      border:1px solid var(--line);
      border-radius:16px;
      padding:10px;
      background:#fff;
      display:flex;
      flex-direction:column;
      justify-content:space-between;
      min-height:34mm;
    }
    .reg .k{
      font-size:9px;
      font-weight:900;
      color: var(--muted);
      text-transform:uppercase;
      letter-spacing:.2px;
    }
    .reg .v{
      margin-top:6px;
      font-size:11px;
      font-weight:900;
      color: rgba(15,23,42,.88);
      word-break:break-all;
    }
    .qr{
      width: 34mm;
      height: 34mm;
      border-radius: 16px;
      border:1px solid var(--line);
      background:#fff;
      display:flex;
      align-items:center;
      justify-content:center;
      overflow:hidden;
    }
    .qr svg{ width:100%; height:100%; display:block; }

    /* FRONT footer: fixed bawah */
    .codesbar{
      position:absolute;
      left:12px;
      right:12px;
      bottom:12px;
      border-top:1px dashed rgba(226,232,240,.95);
      padding-top:10px;
      background: transparent;
    }
    /* kasih ruang supaya konten front tidak ketiban footer */
    .front-body{ bottom: 28mm; }

    .codesbar .title{
      font-size:9px;
      font-weight:900;
      color: var(--muted);
      text-transform:uppercase;
      letter-spacing:.2px;
      margin-bottom:7px;
    }
    .chips{
      display:flex;
      flex-wrap:wrap;
      gap:6px;
      max-height: 18mm;
      overflow:hidden;
    }
    .chip{
      display:inline-flex;
      align-items:center;
      gap:6px;
      font-size:9px;
      font-weight:900;
      padding:5px 8px;
      border-radius:999px;
      border:1px solid rgba(15,23,42,.10);
      background:#fff;
      color: rgba(15,23,42,.80);
      white-space:nowrap;
    }
    .dot{
      width:8px; height:8px; border-radius:999px;
      border:1px solid rgba(15,23,42,.10);
      background:#e2e8f0;
      flex:0 0 auto;
    }

    /* ================= BACK ================= */
    .legendCard{
      border:1px solid var(--line);
      border-radius: 16px;
      background: linear-gradient(180deg, rgba(255,255,255,1), rgba(248,250,252,.92));
      padding:10px;
    }
    .legendCard .h{
      margin:0;
      font-size:11.6px;
      font-weight:900;
      letter-spacing:.2px;
      color: rgba(15,23,42,.92);
    }
    .legendCard .p{
      margin:6px 0 0;
      font-size:9.6px;
      font-weight:700;
      color:var(--muted);
      line-height:1.35;
    }

    .lists{
      margin-top:10px;
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap:10px;
      height: 86mm; /* biar penuh & konsisten */
    }
    .list{
      border:1px solid var(--line);
      border-radius: 16px;
      overflow:hidden;
      background:#fff;
      display:flex;
      flex-direction:column;
      height:100%;
    }
    .list .head{
      padding:8px 10px;
      border-bottom:1px solid var(--line);
      background: rgba(248,250,252,.98);
      font-size:9px;
      font-weight:900;
      color: var(--muted);
      text-transform:uppercase;
      letter-spacing:.25px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:8px;
    }
    .list .head .tiny{
      font-size:8.5px;
      font-weight:900;
      color: rgba(15,23,42,.55);
      text-transform:none;
      letter-spacing:0;
      white-space:nowrap;
    }
    .list .body{
      overflow:hidden;
      padding:2px 0;
      flex:1;
    }

    .item{
      display:flex;
      align-items:flex-start;
      gap:8px;
      padding:8px 10px;
      border-bottom:1px solid rgba(226,232,240,.85);
      font-size:9.7px;
    }
    .item:last-child{ border-bottom:none; }

    .codepill{
      width: 16mm;
      height: 7.6mm;
      border-radius: 12px;
      border:1px solid rgba(15,23,42,.12);
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight:900;
      font-size:9.6px;
      background:#fff;
      flex:0 0 auto;
      letter-spacing:.6px;
    }
    .desc{
      flex:1;
      min-width:0;
      color: rgba(15,23,42,.76);
      font-weight:800;
      line-height:1.25;
      display:-webkit-box;
      -webkit-line-clamp:2;
      -webkit-box-orient:vertical;
      overflow:hidden;
    }

    /* BACK footer fixed bawah */
    .back-foot{
      position:absolute;
      left:12px;
      right:12px;
      bottom:12px;
      border-top:1px dashed rgba(226,232,240,.95);
      padding-top:10px;
      display:flex;
      justify-content:space-between;
      gap:10px;
      font-size:9px;
      font-weight:900;
      color: rgba(15,23,42,.58);
    }
    /* kasih ruang supaya list tidak ketiban footer */
    .back-body{ bottom: 20mm; }

        @media screen{
    .badge{ margin-bottom: 120px; }
    }


.list .head{
  font-size: 6.8px;
  padding: 4px 8px;
}

.list .head .tiny{
  font-size: 6.5px;
}

.item{
  padding: 4px 8px;
  gap: 5px;
}

.codepill{
  width: 11.5mm;
  height: 5.2mm;
  font-size: 6.4px;    
  font-weight: 900;
  letter-spacing: .25px;
  border-radius: 999px;
}

.desc{
  font-size: 7px;
  line-height: 1.05;
  -webkit-line-clamp: 2;
}
/
.list .body{
  padding: 0;
}


.reg{
  justify-content: flex-start; 
}

.reg .k{
  margin-bottom: 6px;           
}

.reg .v{
  margin-top: 0;               
}
/* ================= FRONT: REGISTRATION CODE EXTRA BESAR ================= */

.reg{
  justify-content: flex-start; /* tetap di atas */
}

.reg .v{
  font-size: 20px;           
  font-weight: 900;
  letter-spacing: 1.6px;     
  line-height: 1.15;
  margin-top: 2px;
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
}



  </style>
</head>

<body>
@php
  /** @var \App\Models\AccessCard $accessCard */
  $wo    = $accessCard->workerOpening ?? null;
  $event = $wo->event ?? null;

  $ownerName = $accessCard->owner_name
    ?? ($accessCard->user->name ?? $accessCard->participant_name ?? '-');

  $position = $accessCard->role_label
    ?? ($wo->title ?? $accessCard->participant_role ?? 'Participant');

  $eventName  = $event->title ?? ($accessCard->event_name ?? 'Event');
  $eventStage = $event->stage ?? null;
  $eventVenue = $event->venue ?? null;

  $cardNo = $accessCard->card_no ?? ('AC-' . str_pad((string)$accessCard->id, 6, '0', STR_PAD_LEFT));
  $status = strtoupper($accessCard->status ?? 'active');

  $photoPath = $accessCard->photo_path
    ?? ($accessCard->user->profile->profile_photo ?? null);

  $codes = ($accessCard->accessCodes ?? collect())
    ->sortBy(fn($c) => (string)($c->code ?? $c->id))
    ->values();

  $regCode = $accessCard->registration_code ?? null;

  $verifyUrl = $accessCard->qr_token
    ? route('access-cards.verify', $accessCard->qr_token)
    : null;

  $bigClass = strtoupper((string)($eventStage ?? ($codes->first()->code ?? '—')));

  $left  = $codes->values()->filter(fn($v,$i)=> $i % 2 === 0)->values();
  $right = $codes->values()->filter(fn($v,$i)=> $i % 2 === 1)->values();
@endphp

<div class="wrap">
  <div class="topbar">
    <button class="btn" onclick="history.back()">Back</button>
    <button class="btn primary" onclick="window.print()">Print</button>
  </div>

  <div class="sheet">
    {{-- ================= FRONT ================= --}}
    <section class="badge">
      <div class="strip"></div>
      <div class="pad">
        <div class="hdr">
          <div class="hdr-left">
            <div class="event-title">{{ $eventName }}</div>
            <div class="event-sub">
              {{ $eventVenue ? $eventVenue : '—' }}
              @if($eventStage) • Stage {{ $eventStage }} @endif
            </div>
          </div>
          <div class="status">{{ $status }}</div>
        </div>

        <div class="front-body">
          <div class="idrow">
            <div class="photo">
              @if($photoPath)
                <img src="{{ asset('storage/' . ltrim($photoPath,'/')) }}" alt="Photo">
              @else
                NO PHOTO
              @endif
            </div>

            <div>
              <div class="name">{{ $ownerName }}</div>
              <div class="position">{{ $position }}</div>

              <div class="meta">
                @if($eventStage)
                  <span class="pill">Stage: {{ $eventStage }}</span>
                @endif
                @if($eventVenue)
                  <span class="pill">{{ $eventVenue }}</span>
                @endif
                <span class="pill mono">{{ $cardNo }}</span>
              </div>
            </div>
          </div>

          <div class="classbox">
            <div style="min-width:0">
              <div class="k">Kategori / Kelas</div>
              <div class="v">{{ $position }}</div>
            </div>
            <div class="big">{{ $bigClass }}</div>
          </div>

          <div class="qrrow">
            <div class="reg">
              <div class="k">Registration Code</div>
              <div class="v mono">{{ $regCode ?? '—' }}</div>
            </div>
            <div class="qr">
              @if($verifyUrl)
                {!! QrCode::format('svg')->size(140)->margin(1)->generate($verifyUrl) !!}
              @else
                <span style="font-weight:900; color:rgba(15,23,42,.35);">QR</span>
              @endif
            </div>
          </div>
        </div>

        <div class="codesbar">
          <div class="title">Access Codes</div>
          <div class="chips">
            @forelse($codes->take(14) as $c)
              @php
                $code = strtoupper((string)($c->code ?? ('CODE-'.$c->id)));
                $hex  = $c->color_hex ?? '#e2e8f0';
                $label = $c->label ?? $c->name ?? $c->description ?? $code;
              @endphp
              <span class="chip" title="{{ $label }}">
                <span class="dot" style="background: {{ $hex }}; border-color: {{ $hex }}55;"></span>
                {{ $code }}
              </span>
            @empty
              <span class="chip"><span class="dot"></span>NO-CODE</span>
            @endforelse

            @if($codes->count() > 14)
              <span class="chip" title="More codes">
                <span class="dot" style="background:#0f172a;"></span>
                +{{ $codes->count() - 14 }}
              </span>
            @endif
          </div>
        </div>
      </div>
    </section>

    {{-- ================= BACK ================= --}}
    <section class="badge back">
      <div class="strip"></div>
      <div class="pad">
        <div class="hdr">
          <div class="hdr-left">

        <div class="back-body">
          <div class="legendCard">
            <p class="h">Legenda Access Code</p>
            <p class="p">
              Tunjukkan kartu saat masuk. Petugas memverifikasi akses berdasarkan daftar kode di bawah.
            </p>
          </div>

          <div class="lists">
            <div class="list">
              <div class="head">
                <span>Daftar Kode</span>
              </div>
              <div class="body">
                @forelse($left->take(14) as $c)
                  @php
                    $code = strtoupper((string)($c->code ?? ('CODE-'.$c->id)));
                    $hex  = $c->color_hex ?? '#e2e8f0';
                    $label = $c->label ?? $c->name ?? $c->description ?? '—';
                  @endphp
                  <div class="item">
                    <div class="codepill" style="background: {{ $hex }}14; border-color: {{ $hex }}55; color: {{ $hex }};">
                      {{ $code }}
                    </div>
                    <div class="desc">{{ $label }}</div>
                  </div>
                @empty
                  <div class="item"><div class="desc">Tidak ada access code.</div></div>
                @endforelse
              </div>
            </div>

            <div class="list">
              <div class="head">
                <span>Daftar Kode</span>
              </div>
              <div class="body">
                @forelse($right->take(14) as $c)
                  @php
                    $code = strtoupper((string)($c->code ?? ('CODE-'.$c->id)));
                    $hex  = $c->color_hex ?? '#e2e8f0';
                    $label = $c->label ?? $c->name ?? $c->description ?? '—';
                  @endphp
                  <div class="item">
                    <div class="codepill" style="background: {{ $hex }}14; border-color: {{ $hex }}55; color: {{ $hex }};">
                      {{ $code }}
                    </div>
                    <div class="desc">{{ $label }}</div>
                  </div>
                @empty
                  <div class="item"><div class="desc">—</div></div>
                @endforelse
              </div>
            </div>
          </div>
        </div>

        <div class="back-foot">
          <div class="mono">{{ $regCode ?? '' }}</div>
          <div>{{ $eventVenue ?? '' }}</div>
        </div>
      </div>
    </section>
  </div>
</div>
</body>
</html>
