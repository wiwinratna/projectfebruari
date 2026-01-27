<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Verifikasi Kartu Akses</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
:root{
  --ink:#0f172a;
  --muted:#64748b;
  --muted2:#94a3b8;
  --line:#e2e8f0;
  --bg:#f6f7fb;
  --paper:#ffffff;

  /* elegan (rose kalem, bukan “fun”) */
  --accent:#e11d48;
  --accent2:#fb7185;
  --accentInk:#9f1239;

  --ok:#16a34a;
  --danger:#dc2626;
  --warn:#f59e0b;
}

*{box-sizing:border-box}
html,body{height:100%}
body{
  margin:0;
  font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
  background: radial-gradient(900px 420px at 20% 0%, rgba(225,29,72,.08), transparent 55%),
              radial-gradient(900px 420px at 80% 0%, rgba(251,113,133,.07), transparent 55%),
              var(--bg);
  color:var(--ink);
}

.wrap{
  max-width: 980px;
  margin: 28px auto;
  padding: 0 16px;
}

.shell{
  background: var(--paper);
  border:1px solid var(--line);
  border-radius: 20px;
  overflow:hidden;
  box-shadow: 0 18px 60px rgba(15,23,42,.10);
}

/* ===== Header ===== */
.header{
  padding: 18px 20px;
  background:
    linear-gradient(90deg, rgba(225,29,72,.95), rgba(251,113,133,.92));
  color:#fff;
  display:flex;
  justify-content:space-between;
  gap:14px;
  align-items:flex-start;
}
.header .title{
  font-size:18px;
  font-weight:900;
  letter-spacing:.2px;
  margin:0;
  line-height:1.1;
}
.header .subtitle{
  margin-top:6px;
  font-size:12.5px;
  font-weight:700;
  opacity:.92;
  line-height:1.25;
}
.badgeStatus{
  display:inline-flex;
  align-items:center;
  gap:10px;
  padding: 8px 12px;
  border-radius: 999px;
  border:1px solid rgba(255,255,255,.25);
  background: rgba(255,255,255,.16);
  font-weight:900;
  font-size:12px;
  text-transform:uppercase;
  white-space:nowrap;
}
.dot{
  width:10px;height:10px;border-radius:999px;
  background:#fff;
  box-shadow: 0 0 0 3px rgba(255,255,255,.25);
}
.badgeStatus.ok .dot{ background: #22c55e; }
.badgeStatus.invalid .dot{ background: #ef4444; }
.badgeStatus.warn .dot{ background: #f59e0b; }

/* ===== Body layout ===== */
.body{
  padding: 18px 20px 22px;
}
.gridTop{
  display:grid;
  grid-template-columns: 1.1fr .9fr;
  gap:14px;
}
@media (max-width: 860px){
  .gridTop{ grid-template-columns: 1fr; }
}

.panel{
  border:1px solid var(--line);
  border-radius: 16px;
  background:#fff;
  overflow:hidden;
}
.panel .ph{
  padding: 12px 14px;
  border-bottom:1px solid var(--line);
  background: linear-gradient(90deg, rgba(225,29,72,.06), rgba(248,250,252,.9));
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:10px;
}
.ph .h{
  margin:0;
  font-size:12px;
  font-weight:900;
  color: rgba(15,23,42,.78);
  letter-spacing:.2px;
  text-transform:uppercase;
}
.ph .mini{
  font-size:11px;
  font-weight:800;
  color: var(--muted);
}
.panel .pc{ padding: 14px; }

/* ===== Identity ===== */
.idRow{
  display:flex;
  gap:14px;
  align-items:center;
}
.avatar{
  width:72px;
  height:72px;
  border-radius:16px;
  border:1px solid var(--line);
  background:#f1f5f9;
  overflow:hidden;
  flex:0 0 auto;
}
.avatar img{ width:100%; height:100%; object-fit:cover; display:block; }
.idMain{ min-width:0; }
.person{
  font-size:16px;
  font-weight:900;
  line-height:1.2;
  margin:0;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}
.role{
  margin-top:4px;
  font-size:12.5px;
  font-weight:800;
  color:var(--muted);
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}

.pills{
  margin-top:10px;
  display:flex;
  flex-wrap:wrap;
  gap:8px;
}
.pill{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:6px 10px;
  border-radius:999px;
  border:1px solid rgba(15,23,42,.10);
  background:#fff;
  font-size:11.5px;
  font-weight:900;
  color: rgba(15,23,42,.78);
  max-width:100%;
}
.pill .k{
  font-weight:900;
  color: var(--muted2);
  text-transform:uppercase;
  letter-spacing:.15px;
  font-size:10px;
}
.mono{
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono","Courier New", monospace;
}

/* ===== Key-Value table ===== */
.kv{
  display:grid;
  grid-template-columns: 160px 1fr;
  gap:10px 14px;
  align-items:start;
}
@media (max-width: 520px){
  .kv{ grid-template-columns: 1fr; }
}
.kv .k{
  font-size:11px;
  font-weight:900;
  color: var(--muted2);
  text-transform:uppercase;
  letter-spacing:.18px;
}
.kv .v{
  font-size:12.5px;
  font-weight:800;
  color: rgba(15,23,42,.84);
  line-height:1.35;
}
.sep{
  height:1px;
  background: var(--line);
  margin: 14px 0;
}

/* ===== Access codes ===== */
.codes{
  display:flex;
  flex-wrap:wrap;
  gap:8px;
}
.code{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:6px 10px;
  border-radius:999px;
  border:1px solid rgba(15,23,42,.10);
  background:#fff;
  font-size:12px;
  font-weight:900;
  color: rgba(15,23,42,.78);
}
.code .sw{
  width:10px;height:10px;border-radius:999px;
  border:1px solid rgba(15,23,42,.12);
  background:#e2e8f0;
}
.code .lbl{
  font-weight:800;
  color: var(--muted);
  max-width: 240px;
  overflow:hidden;
  text-overflow:ellipsis;
  white-space:nowrap;
}

/* ===== Footer ===== */
.footer{
  margin-top:14px;
  display:flex;
  justify-content:space-between;
  gap:10px;
  align-items:center;
  font-size:11px;
  color: var(--muted);
}
.note{
  margin-top:10px;
  font-size:11.5px;
  color: var(--muted);
  line-height:1.45;
}
.small{
  font-size:11px;
  color: var(--muted2);
  font-weight:800;
}

/* invalid view */
.center{
  padding: 26px 20px 30px;
}
.reason{
  margin-top:10px;
  font-size:13px;
  color: rgba(15,23,42,.80);
  line-height:1.5;
}
</style>
</head>

<body>
@php
  $ok = (bool)($ok ?? false);
  $card = $card ?? null;

  $event = $card?->workerOpening?->event;
  $wo    = $card?->workerOpening;

  $photoPath = $card?->user?->profile?->profile_photo;
  $cityName  = $event?->city?->name;

  $eventTitle = $event?->title ?? '-';
  $eventStage = $event?->stage ?? null;
  $eventVenue = $event?->venue ?? null;
  $penyelenggara = $event?->penyelenggara ?? null; // dari tabel events kamu
  $instagram = $event?->instagram ?? null;
  $emailEvent = $event?->email ?? null;
  $startAt = $event?->start_at ?? null;
  $endAt = $event?->end_at ?? null;

  // issued_at aman (string => parse)
  $issuedAtFmt = $card?->issued_at
    ? \Carbon\Carbon::parse($card->issued_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB'
    : '-';

  $startFmt = $startAt ? \Carbon\Carbon::parse($startAt)->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' : '-';
  $endFmt   = $endAt   ? \Carbon\Carbon::parse($endAt)->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' : '-';

  // detail orang
  $personName = $card?->user?->name ?? '-';
  $position   = $wo?->title ?? '-';

  // nomor kartu / kode registrasi
  $cardNo = $card?->card_no ?? null; // kalau ada kolomnya
  $regCode = $card?->registration_code ?? '-';

  // status label
  $statusRaw = strtolower((string)($card?->status ?? ($ok ? 'active' : 'invalid')));
  $isActive = $ok && in_array($statusRaw, ['active','issued']);
  $statusText = $isActive ? 'VALID' : 'INVALID';
@endphp

<div class="wrap">
  <div class="shell">

    {{-- HEADER --}}
    <div class="header">
      <div style="min-width:0">
        <h1 class="title">Verifikasi Kartu Akses</h1>
        <div class="subtitle">
          {{ $eventTitle }}
          @if($eventStage) • Stage {{ $eventStage }} @endif
          @if($eventVenue) • {{ $eventVenue }} @endif
          @if($cityName) • {{ $cityName }} @endif
        </div>
      </div>

      <div class="badgeStatus {{ $isActive ? 'ok' : 'invalid' }}">
        <span class="dot"></span>
        {{ $statusText }}
      </div>
    </div>

    {{-- BODY --}}
    @if(!$ok || !$card)
      <div class="center">
        <div class="small">Status</div>
        <div class="reason">
          <strong style="font-weight:900;color:var(--danger)">Kartu tidak dapat diverifikasi.</strong><br>
          {{ $reason ?? 'Token tidak ditemukan / kartu tidak aktif.' }}
        </div>
        <div class="note">
          Jika Anda merasa ini kesalahan, minta panitia untuk mengecek status kartu dan token QR yang tercetak.
        </div>
      </div>
    @else
      <div class="body">

        <div class="gridTop">
          {{-- PANEL: DATA PEMEGANG --}}
          <section class="panel">
            <div class="ph">
              <p class="h">Data Pemegang</p>
              <span class="mini mono">{{ $cardNo ? $cardNo : '' }}</span>
            </div>
            <div class="pc">
              <div class="idRow">
                <div class="avatar">
                  @if($photoPath)
                    <img src="{{ asset('storage/'.ltrim($photoPath,'/')) }}" alt="Photo">
                  @endif
                </div>
                <div class="idMain">
                  <p class="person">{{ $personName }}</p>
                  <div class="role">{{ $position }}</div>

                  <div class="pills">
                    <span class="pill">
                      <span class="k">Registration</span>
                      <span class="mono">{{ $regCode }}</span>
                    </span>

                    <span class="pill">
                      <span class="k">Issued</span>
                      <span>{{ $issuedAtFmt }}</span>
                    </span>

                    <span class="pill">
                      <span class="k">Status</span>
                      <span style="color:{{ $isActive ? 'var(--ok)' : 'var(--danger)' }};font-weight:900">
                        {{ strtoupper($statusRaw) }}
                      </span>
                    </span>
                  </div>
                </div>
              </div>

              <div class="sep"></div>

              <div class="kv">
                <div class="k">Posisi / Job</div>
                <div class="v">{{ $position }}</div>

                <div class="k">Event</div>
                <div class="v">{{ $eventTitle }}</div>

                <div class="k">Stage</div>
                <div class="v">{{ $eventStage ?? '-' }}</div>

                <div class="k">Lokasi</div>
                <div class="v">
                  {{ $eventVenue ?? '-' }}
                  @if($cityName) • {{ $cityName }} @endif
                </div>
              </div>

              <div class="note">
                Kartu bersifat pribadi. Petugas memverifikasi akses berdasarkan daftar kode di bawah.
              </div>
            </div>
          </section>

          {{-- PANEL: DETAIL EVENT --}}
          <section class="panel">
            <div class="ph">
              <p class="h">Detail Event</p>
              <span class="mini">Informasi penyelenggara</span>
            </div>
            <div class="pc">
              <div class="kv">
                <div class="k">Penyelenggara</div>
                <div class="v">{{ $penyelenggara ?? '-' }}</div>

                <div class="k">Mulai</div>
                <div class="v">{{ $startFmt }}</div>

                <div class="k">Selesai</div>
                <div class="v">{{ $endFmt }}</div>

                <div class="k">Instagram</div>
                <div class="v">{{ $instagram ? $instagram : '-' }}</div>

                <div class="k">Email</div>
                <div class="v">{{ $emailEvent ? $emailEvent : '-' }}</div>
              </div>

              <div class="sep"></div>

              <div class="small">Catatan Verifikasi</div>
              <div class="note" style="margin-top:6px">
                Pastikan nama, posisi, event, dan kode akses sesuai dengan area/venue yang dituju.
                Jika ada perbedaan, arahkan peserta ke meja akreditasi/panitia.
              </div>
            </div>
          </section>
        </div>

        {{-- PANEL: HAK AKSES --}}
        <section class="panel" style="margin-top:14px">
          <div class="ph">
            <p class="h">Hak Akses</p>
            <span class="mini">{{ ($card->accessCodes?->count() ?? 0) }} Akses </span>
          </div>
          <div class="pc">
            <div class="codes">
              @forelse($card->accessCodes as $c)
                @php
                  $code = strtoupper((string)($c->code ?? '—'));
                  $hex  = $c->color_hex ?? '#e2e8f0';
                  $label = $c->label ?? $c->name ?? $c->description ?? null;
                @endphp
                <span class="code" title="{{ $label ? $label : $code }}">
                  <span class="sw" style="background: {{ $hex }}; border-color: {{ $hex }}55;"></span>
                  <span class="mono">{{ $code }}</span>
                  @if($label)
                    <span class="lbl"> | {{ $label }}</span>
                  @endif
                </span>
              @empty
                <span class="code"><span class="sw"></span><span class="mono">—</span> <span class="lbl">Tidak ada kode akses</span></span>
              @endforelse
            </div>

            <div class="footer">
              <span class="mono">Token: {{ $card->qr_token ?? '-' }}</span>
              <span>Dicetak/terbit: {{ $issuedAtFmt }}</span>
            </div>
          </div>
        </section>

      </div>
    @endif

  </div>
</div>
</body>
</html>
