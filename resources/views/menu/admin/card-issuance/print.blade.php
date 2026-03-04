<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Batch Print - A5</title>
    <style>
        @page { size: A5; margin: 0; }
        body { margin:0; padding:0; font-family: Arial, sans-serif; }
        .page { width:148mm; height:210mm; page-break-after: always; position: relative; }
        .card { position:absolute; inset:0; background:white; }
        .top-reserved { position:absolute; top:0; left:0; right:0; height:22mm; }
        .bottom-reserved { position:absolute; bottom:0; left:0; right:0; height:22mm; }
        .content { position:absolute; top:22mm; bottom:22mm; left:12mm; right:12mm; }
        .row { display:flex; gap:10mm; }
        .photo { width:32mm; height:40mm; border:1px solid #e5e7eb; border-radius:4mm; overflow:hidden; background:#f3f4f6; display:flex; align-items:center; justify-content:center; }
        .badge { padding:3mm 5mm; border-radius:4mm; background:#111827; color:white; font-weight:900; font-size:12pt; }
        .band { margin-top:10mm; border-top:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; padding:6mm 0; text-align:center; }
        .priv { margin-top:10mm; }
        .pill { border:1px solid #d1d5db; border-radius:2mm; padding:2mm 3mm; font-weight:800; font-size:9pt; display:inline-block; margin-right:3mm; margin-bottom:3mm; }
        .qrwrap { position:absolute; right:0; bottom:0; display:flex; align-items:flex-end; gap:6mm; }
        .qr { width:28mm; height:28mm; border:1px solid #d1d5db; border-radius:3mm; background:#fff; display:flex; align-items:center; justify-content:center; }
        .small { font-size:8pt; color:#6b7280; text-align:right; }
        .name { font-weight:800; font-size:16pt; color:#111827; line-height:1.1; }
        .sub { font-size:10pt; color:#374151; font-weight:700; margin-top:2mm; }
        .meta { margin-top:6mm; font-size:9pt; color:#374151; }
    </style>
</head>
<body onload="window.print()">

@foreach($cards as $card)
    @php
        // Load application/user photo if needed (simple query)
        $app = \App\Models\Application::with(['user.profile'])->find($card->application_id);
        $photo = $app?->user?->profile?->profile_photo;
        $venues = data_get($card->snapshot, 'venues', []);
        $zones = data_get($card->snapshot, 'zones', []);
        $transport = data_get($card->snapshot, 'transport');
        $accom = data_get($card->snapshot, 'accommodation');

        $verifyUrl = route('cards.verify', ['t' => $card->qr_token, 's' => $card->qr_signature]);
        // QR as image using Google Chart (simple & fast) — OK for now. Later we can use a local QR package.
        $qrImg = 'https://chart.googleapis.com/chart?chs=180x180&cht=qr&chl='.urlencode($verifyUrl);
    @endphp

    <div class="page">
        <div class="card">
            <div class="top-reserved"></div>
            <div class="bottom-reserved"></div>

            <div class="content">

                <div class="row">
                    <div class="photo">
                        @if($photo)
                            <img src="{{ asset('storage/'.$photo) }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                            <span style="color:#9ca3af; font-size:12px;">PHOTO</span>
                        @endif
                    </div>

                    <div style="flex:1;">
                        <div style="display:flex; justify-content:space-between; gap:8mm;">
                            <div style="min-width:0;">
                                <div class="name">{{ $app?->user?->name ?? $app?->user?->username ?? 'Applicant' }}</div>
                                <div class="sub">{{ data_get($card->snapshot, 'meta.role_name') ?? 'VOLUNTEER / STAFF' }}</div>
                                <div style="margin-top:1mm; font-size:9pt; color:#6b7280;">
                                    Event: {{ $event->title ?? 'Event' }}
                                </div>
                            </div>

                            <div class="badge">
                                {{ data_get($card->snapshot, 'accreditation.name') ?? ('ACC #'.$card->accreditation_id) }}
                            </div>
                        </div>

                        <div class="meta">
                            <div><b>Card No:</b> {{ $card->card_number }}</div>
                        </div>
                    </div>
                </div>

                <div class="band">
                    <div style="font-weight:900; color:#111827; font-size:14pt;">
                        {{ strtoupper($event->penyelenggara ?? 'ARISE GAMES') }}
                    </div>
                    <div style="font-weight:700; color:#6b7280; font-size:10pt; margin-top:1mm;">
                        {{ strtoupper($event->venue ?? 'VENUE') }}
                    </div>
                </div>

                <div class="priv">
                    <div style="font-size:9pt; color:#6b7280; font-weight:700; margin-bottom:2mm;">PRIVILEGES</div>

                    @if($transport)<span class="pill">TR</span>@endif
                    @if($accom)<span class="pill">AC</span>@endif

                    @foreach(array_slice($venues, 0, 5) as $v)
                        <span class="pill">{{ $v['name'] ?? 'VEN' }}</span>
                    @endforeach
                    @foreach(array_slice($zones, 0, 5) as $z)
                        <span class="pill">{{ $z['code'] ?? 'ZN' }}</span>
                    @endforeach
                </div>

                <div class="qrwrap">
                    <div class="small">
                        <div style="font-weight:800; color:#111827;">SCAN TO VERIFY</div>
                        <div>App: #{{ $card->application_id }}</div>
                    </div>
                    <div class="qr">
                        <img src="{{ $qrImg }}" style="width:100%; height:100%;">
                    </div>
                </div>

            </div>
        </div>
    </div>
@endforeach

</body>
</html>