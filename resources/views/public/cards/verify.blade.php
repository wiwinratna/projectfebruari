<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Card Verification</title>
  <style>
    @keyframes blobFloatA {
      0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
      50% { transform: translate3d(24px, -18px, 0) scale(1.08); }
    }
    @keyframes blobFloatB {
      0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
      50% { transform: translate3d(-28px, 22px, 0) scale(1.06); }
    }
    @keyframes blobFloatC {
      0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
      50% { transform: translate3d(16px, 20px, 0) scale(1.07); }
    }
    .blob-a { animation: blobFloatA 14s ease-in-out infinite; }
    .blob-b { animation: blobFloatB 16s ease-in-out infinite; }
    .blob-c { animation: blobFloatC 18s ease-in-out infinite; }
    @media (prefers-reduced-motion: reduce) {
      .blob-a, .blob-b, .blob-c { animation: none; }
    }
  </style>
</head>

<body class="min-h-screen bg-[#0b0f19] text-gray-100 relative overflow-x-hidden">
  <div aria-hidden="true" class="pointer-events-none absolute inset-0">
    <div class="blob-a absolute -top-24 -left-20 h-72 w-72 rounded-full bg-sky-500/10 blur-3xl"></div>
    <div class="blob-b absolute top-1/3 -right-20 h-80 w-80 rounded-full bg-amber-400/10 blur-3xl"></div>
    <div class="blob-c absolute -bottom-20 left-1/3 h-72 w-72 rounded-full bg-emerald-400/10 blur-3xl"></div>
  </div>
  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-3xl">

      {{-- Flash --}}
      @if(session('success'))
        <div class="mb-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
          {{ session('success') }}
        </div>
      @endif
      @if(session('error'))
        <div class="mb-4 rounded-xl border border-rose-500/20 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
          {{ session('error') }}
        </div>
      @endif

      <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-slate-900/95 via-[#101828]/95 to-slate-950/95 shadow-xl overflow-hidden">
        <div class="p-6 sm:p-7">
          @php
            $eventData = $event ?? ($card->event ?? null);
            $eventTitle = $eventData->title ?? 'Event';
            $eventLogoPath = $eventData->logo_path ?? null;
            $eventLogoExists = $eventLogoPath ? \Illuminate\Support\Facades\Storage::disk('public')->exists($eventLogoPath) : false;
            $eventLogoUrl = $eventLogoExists ? asset('storage/' . ltrim($eventLogoPath, '/')) : null;
            $eventInitials = collect(preg_split('/\s+/', trim((string)$eventTitle)) ?: [])
                ->filter()
                ->take(2)
                ->map(fn($part) => strtoupper(\Illuminate\Support\Str::substr($part, 0, 1)))
                ->implode('');
            $eventInitials = $eventInitials !== '' ? $eventInitials : 'EV';
          @endphp

          {{-- Branded Header --}}
          <div class="relative mb-6 overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-r from-sky-500/10 via-emerald-400/10 to-rose-400/10 p-4 sm:p-5">
            <svg aria-hidden="true" class="pointer-events-none absolute -right-6 -top-8 h-28 w-56 opacity-25" viewBox="0 0 220 110" fill="none">
              <circle cx="30" cy="28" r="20" stroke="#60a5fa" stroke-width="2"/>
              <circle cx="76" cy="18" r="20" stroke="#f59e0b" stroke-width="2"/>
              <circle cx="125" cy="33" r="20" stroke="#34d399" stroke-width="2"/>
              <circle cx="171" cy="20" r="20" stroke="#f87171" stroke-width="2"/>
              <circle cx="110" cy="72" r="20" stroke="#f472b6" stroke-width="2"/>
            </svg>
            <svg aria-hidden="true" class="pointer-events-none absolute -left-8 -bottom-8 h-24 w-48 opacity-15" viewBox="0 0 220 110" fill="none">
              <circle cx="28" cy="72" r="18" stroke="#60a5fa" stroke-width="1.5"/>
              <circle cx="68" cy="58" r="18" stroke="#f59e0b" stroke-width="1.5"/>
              <circle cx="108" cy="74" r="18" stroke="#34d399" stroke-width="1.5"/>
              <circle cx="148" cy="60" r="18" stroke="#f87171" stroke-width="1.5"/>
              <circle cx="188" cy="76" r="18" stroke="#f472b6" stroke-width="1.5"/>
            </svg>

            <div class="relative flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
              <div class="flex min-w-0 items-center gap-3">
                @if($eventLogoUrl)
                  <img src="{{ $eventLogoUrl }}" alt="{{ $eventTitle }} logo" class="h-12 w-12 rounded-xl bg-white/10 p-1 object-contain border border-white/10">
                @else
                  <div class="h-12 w-12 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-sm font-extrabold tracking-wide text-white">
                    {{ $eventInitials }}
                  </div>
                @endif
                <div class="min-w-0">
                  <div class="truncate text-lg sm:text-xl font-extrabold text-white">{{ $eventTitle }}</div>
                  <div class="text-xs sm:text-sm text-gray-300">Card Verification</div>
                </div>
              </div>

              @if($valid)
                <span class="inline-flex items-center self-start sm:self-auto gap-2 px-3 py-1.5 rounded-full text-sm font-bold bg-emerald-500/10 text-emerald-200 border border-emerald-500/20">
                  <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                  VALID
                </span>
              @else
                <span class="inline-flex items-center self-start sm:self-auto gap-2 px-3 py-1.5 rounded-full text-sm font-bold bg-rose-500/10 text-rose-200 border border-rose-500/20">
                  <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                  INVALID
                </span>
              @endif
            </div>
          </div>

          @if(!$valid)
            <div class="rounded-xl border border-rose-500/20 bg-rose-500/10 p-4">
              <div class="font-bold text-rose-100">Card not valid</div>
              <div class="text-sm text-rose-200 mt-1">
                Reason: <b>{{ $reason ?? 'Unknown' }}</b>
              </div>
              <div class="text-xs text-rose-200 mt-2">
                Please re-scan or contact event staff.
              </div>
            </div>
          @else
            @php
              $snap = $snap ?? (is_array($card->snapshot) ? $card->snapshot : json_decode($card->snapshot, true));
              $mappingName = $snap['mapping_name'] ?? '-';
              $mappingColor = $snap['mapping_color'] ?? '#ffffff';

              $venues = $final['venues'] ?? [];
              $zones  = $final['zones'] ?? [];
              $tId = $final['transportation_id'] ?? null;
              $aId = $final['accommodation_id'] ?? null;

              $t = $tId ? ($transportMap[$tId] ?? null) : null;
              $a = $aId ? ($accomMap[$aId] ?? null) : null;
            @endphp

            {{-- Owner summary --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
              <div class="min-w-0">
                <div class="mt-2 text-2xl font-extrabold text-white break-words">
                  {{ $snap['name'] ?? '-' }}
                </div>
                <div class="mt-2 text-sm text-gray-300">
                  {{ $snap['job_category_name'] ?? '-' }}
                </div>

                <div class="mt-3 text-sm text-gray-300">
                  <span class="text-gray-400">Card Number:</span>
                  <span class="font-extrabold text-white">{{ $card->card_number }}</span>
                </div>
              </div>

              <div class="flex-shrink-0">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest sm:text-right">Accreditation</div>
                <div class="mt-2 inline-flex items-center px-4 py-2 rounded-xl border text-lg font-extrabold"
                     style="border-color: {{ $mappingColor }}66; background: {{ $mappingColor }}22; color: #fff;">
                  {{ $mappingName }}
                </div>
              </div>
            </div>

            <hr class="my-6 border-white/10">

            {{-- Access summary --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="rounded-2xl border border-sky-300/20 bg-sky-500/5 p-4">
                <div class="flex items-center justify-between">
                  <div class="font-extrabold text-white">Venue Access</div>
                  <div class="text-xs text-gray-400">{{ count($venues) }} code(s)</div>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                  @forelse($venues as $vid)
                    @php $v = $venueMap[$vid] ?? null; @endphp
                    <span class="inline-flex items-center min-h-8 px-2.5 py-1 rounded-lg border border-sky-300/30 bg-sky-400/10 text-xs font-bold leading-tight">
                      {{ $v['code'] ?? ('V'.$vid) }}
                    </span>
                  @empty
                    <span class="text-sm text-gray-400">-</span>
                  @endforelse
                </div>
              </div>

              <div class="rounded-2xl border border-emerald-300/20 bg-emerald-500/5 p-4">
                <div class="flex items-center justify-between">
                  <div class="font-extrabold text-white">Zone Access</div>
                  <div class="text-xs text-gray-400">{{ count($zones) }} code(s)</div>
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                  @forelse($zones as $zid)
                    @php $z = $zoneMap[$zid] ?? null; @endphp
                    <span class="inline-flex items-center min-h-8 px-2.5 py-1 rounded-lg border border-emerald-300/30 bg-emerald-400/10 text-xs font-bold leading-tight">
                      {{ $z['code'] ?? ('Z'.$zid) }}
                    </span>
                  @empty
                    <span class="text-sm text-gray-400">-</span>
                  @endforelse
                </div>
              </div>

              <div class="rounded-2xl border border-amber-300/20 bg-amber-500/5 p-4">
                <div class="flex items-center justify-between">
                  <div class="font-extrabold text-white">Transportation</div>
                  <div class="text-xs text-gray-400">{{ $t ? 1 : 0 }} code(s)</div>
                </div>
                <div class="mt-2 text-sm text-gray-300">
                  @if($t)
                    <span class="font-extrabold text-white">{{ $t['code'] ?? '-' }}</span>
                    <span class="text-gray-400">- {{ $t['desc'] ?? '' }}</span>
                  @else
                    <span class="text-gray-400">-</span>
                  @endif
                </div>
              </div>

              <div class="rounded-2xl border border-rose-300/20 bg-rose-500/5 p-4">
                <div class="flex items-center justify-between">
                  <div class="font-extrabold text-white">Accommodation</div>
                  <div class="text-xs text-gray-400">{{ $a ? 1 : 0 }} code(s)</div>
                </div>
                <div class="mt-2 text-sm text-gray-300">
                  @if($a)
                    <span class="font-extrabold text-white">{{ $a['code'] ?? '-' }}</span>
                    <span class="text-gray-400">- {{ $a['desc'] ?? '' }}</span>
                  @else
                    <span class="text-gray-400">-</span>
                  @endif
                </div>
              </div>
            </div>

            {{-- Explanation section --}}
            <div class="mt-6 rounded-2xl border border-violet-300/20 bg-violet-500/5 p-4">
              <div class="font-extrabold text-white">What these codes mean</div>
              <p class="text-sm text-gray-300 mt-1">
                Codes below describe the access rights attached to this card.
              </p>

              <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Venue Codes --}}
                <div class="rounded-xl border border-white/10 bg-black/20 p-4">
                  <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">Venue Codes</div>

                  <div class="mt-3 space-y-3">
                    @forelse($venues as $vid)
                    @php $v = $venueMap[$vid] ?? null; @endphp

                    <div class="flex items-start gap-3">
                      <span class="mt-0.5 inline-flex items-center justify-center min-w-[44px] px-2 py-1 rounded-lg border border-white/10 bg-white/5 text-xs font-extrabold text-white">
                        {{ $v['code'] ?? ('V'.$vid) }}
                      </span>

                      <div class="min-w-0">
                        <div class="flex items-baseline gap-2">
                          <div class="font-extrabold text-white truncate">
                            {{ $v['name'] ?? ('Venue #'.$vid) }}
                          </div>
                        </div>

                        @if(!empty($v['desc']))
                          <div class="text-xs text-gray-400 mt-0.5 leading-relaxed">
                            {{ $v['desc'] }}
                          </div>
                        @endif
                      </div>
                    </div>
                    @empty
                    <div class="text-sm text-gray-400">-</div>
                    @endforelse
                  </div>
                </div>

                {{-- Zone Codes --}}
                <div class="rounded-xl border border-white/10 bg-black/20 p-4">
                  <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">Zone Codes</div>

                  <div class="mt-3 space-y-3">
                    @forelse($zones as $zid)
                    @php $z = $zoneMap[$zid] ?? null; @endphp

                    <div class="flex items-start gap-3">
                      <span class="mt-0.5 inline-flex items-center justify-center min-w-[44px] px-2 py-1 rounded-lg border border-white/10 bg-white/5 text-xs font-extrabold text-white">
                        {{ $z['code'] ?? ('Z'.$zid) }}
                      </span>

                      <div class="min-w-0">
                        <div class="font-extrabold text-white truncate">
                          {{ $z['name'] ?? ('Zone #'.$zid) }}
                        </div>

                        @if(!empty($z['desc']))
                          <div class="text-xs text-gray-400 mt-0.5 leading-relaxed">
                            {{ $z['desc'] }}
                          </div>
                        @endif
                      </div>
                    </div>
                    @empty
                    <div class="text-sm text-gray-400">-</div>
                    @endforelse
                  </div>
                </div>

              </div>
            </div>

            <hr class="my-6 border-white/10">

            {{-- Form + Logs --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {{-- Form --}}
              <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <div class="font-extrabold text-white">Verification Log</div>
                <p class="text-sm text-gray-300 mt-1">Record who checked this card.</p>

                <form method="POST" action="{{ route('cards.verify.store', $card->qr_token) }}" class="mt-4 space-y-3">
                  @csrf

                  <div>
                    <label class="text-sm font-bold text-gray-200">Visitor Name</label>
                    <input name="visitor_name" required
                      class="mt-1 w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2 text-gray-100 placeholder:text-gray-500
                             focus:outline-none focus:ring-2 focus:ring-white/10"
                      placeholder="Your name">
                  </div>

                  <div>
                    <label class="text-sm font-bold text-gray-200">Phone (optional)</label>
                    <input name="phone"
                      class="mt-1 w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2 text-gray-100 placeholder:text-gray-500
                             focus:outline-none focus:ring-2 focus:ring-white/10"
                      placeholder="+62...">
                  </div>

                  <div>
                    <label class="text-sm font-bold text-gray-200">Note (optional)</label>
                    <textarea name="note" rows="3"
                      class="mt-1 w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2 text-gray-100 placeholder:text-gray-500
                             focus:outline-none focus:ring-2 focus:ring-white/10"
                      placeholder="e.g. checked at gate A"></textarea>
                  </div>

                  <button class="w-full py-2.5 rounded-xl bg-white text-black font-extrabold hover:bg-gray-100 transition">
                    Submit
                  </button>
                </form>
              </div>

              {{-- Logs table --}}
              <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <div class="flex items-center justify-between">
                  <div class="font-extrabold text-white">Recent Checks</div>
                  <div class="text-xs text-gray-400">{{ isset($logs) ? $logs->count() : 0 }} record(s)</div>
                </div>

                <div class="mt-3 overflow-x-auto rounded-xl border border-white/10">
                  <table class="min-w-full text-sm">
                    <thead class="bg-black/30 text-gray-300">
                      <tr>
                        <th class="text-left px-3 py-2 font-bold">Time</th>
                        <th class="text-left px-3 py-2 font-bold">Visitor</th>
                        <th class="text-left px-3 py-2 font-bold">Phone</th>
                        <th class="text-left px-3 py-2 font-bold">Note</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                      @if(isset($logs) && $logs->count())
                        @foreach($logs as $log)
                          <tr class="bg-white/0">
                            <td class="px-3 py-2 text-gray-300 whitespace-nowrap">
                              {{ $log->created_at->format('d M H:i') }}
                            </td>
                            <td class="px-3 py-2 text-white font-bold">
                              {{ $log->visitor_name }}
                            </td>
                            <td class="px-3 py-2 text-gray-300">
                              {{ $log->phone ?? '-' }}
                            </td>
                            <td class="px-3 py-2 text-gray-300">
                              {{ $log->note ?? '-' }}
                            </td>
                          </tr>
                        @endforeach
                      @else
                        <tr>
                          <td colspan="4" class="px-3 py-8 text-center text-gray-400">
                            No verification logs yet.
                          </td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                </div>

              </div>
            </div>

          @endif
        </div>
      </div>

      <div class="mt-4 text-center text-xs text-gray-500 flex items-center justify-center gap-2">
        <span class="inline-flex items-center gap-1.5 opacity-80" aria-hidden="true">
          <span class="h-2 w-2 rounded-full border border-sky-300/70"></span>
          <span class="h-2 w-2 rounded-full border border-amber-300/70"></span>
          <span class="h-2 w-2 rounded-full border border-emerald-300/70"></span>
          <span class="h-2 w-2 rounded-full border border-rose-300/70"></span>
          <span class="h-2 w-2 rounded-full border border-pink-300/70"></span>
        </span>
        <span>ARISE Games - Card Verification</span>
      </div>

    </div>
  </div>
</body>
</html>
