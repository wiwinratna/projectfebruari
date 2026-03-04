@extends('layouts.app')

@section('title', 'Card Preview - Admin')
@section('page-title')
    Card Preview <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Default Layout</span>
@endsection

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Card Preview</h2>
            <p class="text-gray-600 mt-1">Default layout (middle area only). Top & bottom are reserved.</p>
        </div>
        <a href="{{ route('admin.card-issuance.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center font-semibold">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        @if(!$card)
            <div class="text-gray-600">
                No card found yet. Please set accreditation first.
            </div>
        @else

            <div class="flex flex-col lg:flex-row gap-6">

                {{-- Card Canvas --}}
                <div class="border rounded-lg p-4 bg-gray-50">
                    <div class="mx-auto"
                         style="width: 148mm; height: 210mm; background: white; border:1px solid #e5e7eb; position:relative;">
                        {{-- Reserved Top (empty) --}}
                        <div style="position:absolute; top:0; left:0; right:0; height:22mm;"></div>

                        {{-- Reserved Bottom (empty) --}}
                        <div style="position:absolute; bottom:0; left:0; right:0; height:22mm;"></div>

                        {{-- Middle Content Area --}}
                        <div style="position:absolute; top:22mm; bottom:22mm; left:12mm; right:12mm;">

                            {{-- Header row: Photo + Identity --}}
                            <div style="display:flex; gap:10mm;">
                                {{-- Photo --}}
                                <div style="width:32mm; height:40mm; border:1px solid #e5e7eb; border-radius:4mm; overflow:hidden; background:#f3f4f6; display:flex; align-items:center; justify-content:center;">
                                    @php
                                        $photo = $application->user->profile->profile_photo ?? null;
                                    @endphp
                                    @if($photo)
                                        <img src="{{ asset('storage/'.$photo) }}" style="width:100%; height:100%; object-fit:cover;" />
                                    @else
                                        <div style="color:#9ca3af; font-size:12px;">PHOTO</div>
                                    @endif
                                </div>

                                {{-- Identity --}}
                                <div style="flex:1;">
                                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:8mm;">
                                        <div style="min-width:0;">
                                            <div style="font-weight:800; font-size:16pt; color:#111827; line-height:1.1;">
                                                {{ $application->user->name ?? $application->user->username ?? 'Applicant' }}
                                            </div>
                                            <div style="margin-top:2mm; font-size:10pt; color:#374151; font-weight:700;">
                                                {{ data_get($card->snapshot, 'meta.role_name') ?? 'VOLUNTEER / STAFF' }}
                                            </div>
                                            <div style="margin-top:1mm; font-size:9pt; color:#6b7280;">
                                                Event: {{ $event->title ?? 'Event' }}
                                            </div>
                                        </div>

                                        {{-- Accreditation badge --}}
                                        <div style="padding:3mm 5mm; border-radius:4mm; background:#111827; color:white; font-weight:900; font-size:12pt;">
                                            {{ data_get($card->snapshot, 'accreditation.name') ?? ('ACC #'.$card->accreditation_id) }}
                                        </div>
                                    </div>

                                    <div style="margin-top:6mm; font-size:9pt; color:#374151;">
                                        <div><b>Card No:</b> {{ $card->card_number ?? '-' }}</div>
                                        <div><b>Status:</b> {{ strtoupper($card->status) }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Mid title band --}}
                            <div style="margin-top:10mm; border-top:1px solid #e5e7eb; border-bottom:1px solid #e5e7eb; padding:6mm 0;">
                                <div style="font-weight:900; color:#111827; font-size:14pt; text-align:center;">
                                    {{ strtoupper($event->penyelenggara ?? 'ARISE GAMES') }}
                                </div>
                                <div style="font-weight:700; color:#6b7280; font-size:10pt; text-align:center; margin-top:1mm;">
                                    {{ strtoupper($event->venue ?? 'VENUE') }} • {{ strtoupper($event->city->name ?? '') }}
                                </div>
                            </div>

                            {{-- Privileges row (icons like TR, LOC etc) --}}
                            <div style="margin-top:10mm;">
                                <div style="font-size:9pt; color:#6b7280; font-weight:700; margin-bottom:2mm;">PRIVILEGES</div>
                                <div style="display:flex; flex-wrap:wrap; gap:3mm;">
                                    @php
                                        $transport = data_get($card->snapshot, 'transport');
                                        $accom = data_get($card->snapshot, 'accommodation');
                                        $zones = data_get($card->snapshot, 'zones', []);
                                        $venues = data_get($card->snapshot, 'venues', []);
                                    @endphp

                                    {{-- Transport --}}
                                    @if($transport)
                                        <div style="border:1px solid #d1d5db; border-radius:2mm; padding:2mm 3mm; font-weight:800; font-size:9pt;">
                                            TR
                                        </div>
                                    @endif

                                    {{-- Accommodation --}}
                                    @if($accom)
                                        <div style="border:1px solid #d1d5db; border-radius:2mm; padding:2mm 3mm; font-weight:800; font-size:9pt;">
                                            AC
                                        </div>
                                    @endif

                                    {{-- Venues (show first 4) --}}
                                    @foreach(array_slice($venues, 0, 4) as $v)
                                        <div style="border:1px solid #d1d5db; border-radius:2mm; padding:2mm 3mm; font-weight:800; font-size:9pt;">
                                            {{ $v['name'] ?? 'VEN' }}
                                        </div>
                                    @endforeach

                                    {{-- Zones (show first 4) --}}
                                    @foreach(array_slice($zones, 0, 4) as $z)
                                        <div style="border:1px solid #d1d5db; border-radius:2mm; padding:2mm 3mm; font-weight:800; font-size:9pt;">
                                            {{ $z['code'] ?? 'ZN' }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- QR block (bottom right of content area) --}}
                            <div style="position:absolute; right:0; bottom:0; display:flex; align-items:flex-end; gap:6mm;">
                                <div style="font-size:8pt; color:#6b7280; text-align:right;">
                                    <div style="font-weight:800; color:#111827;">SCAN TO VERIFY</div>
                                    <div>App: #{{ $application->id }}</div>
                                </div>

                                <div style="width:28mm; height:28mm; border:1px solid #d1d5db; border-radius:3mm; display:flex; align-items:center; justify-content:center; background:#f9fafb;">
                                    {{-- Placeholder for QR image: we’ll render real QR in print page --}}
                                    <div style="font-size:8pt; color:#9ca3af;">QR</div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Info panel --}}
                <div class="flex-1">
                    <div class="border rounded-lg p-4">
                        <div class="font-bold text-gray-800">Data</div>
                        <div class="text-sm text-gray-600 mt-2">
                            <div><b>Application ID:</b> {{ $application->id }}</div>
                            <div><b>Issued Card ID:</b> {{ $card->id }}</div>
                            <div><b>Card No:</b> {{ $card->card_number ?? '-' }}</div>
                            <div><b>QR Verify URL:</b> {{ $qrUrl ?? '-' }}</div>
                        </div>

                        <div class="mt-4 text-xs text-gray-500">
                            Note: Top & bottom areas are intentionally empty for future uploaded templates.
                        </div>
                    </div>
                </div>

            </div>

        @endif
    </div>
</div>
@endsection