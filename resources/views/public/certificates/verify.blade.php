<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
  <title>Verifikasi Sertifikat</title>
  <style>
    @keyframes blobFloatA { 0%,100%{transform:translate3d(0,0,0) scale(1)} 50%{transform:translate3d(24px,-18px,0) scale(1.08)} }
    @keyframes blobFloatB { 0%,100%{transform:translate3d(0,0,0) scale(1)} 50%{transform:translate3d(-28px,22px,0) scale(1.06)} }
    @keyframes blobFloatC { 0%,100%{transform:translate3d(0,0,0) scale(1)} 50%{transform:translate3d(16px,20px,0) scale(1.07)} }
    .blob-a{animation:blobFloatA 14s ease-in-out infinite}
    .blob-b{animation:blobFloatB 16s ease-in-out infinite}
    .blob-c{animation:blobFloatC 18s ease-in-out infinite}
    @media(prefers-reduced-motion:reduce){.blob-a,.blob-b,.blob-c{animation:none}}

    /* Print: A4 landscape certificate */
    @media print {
      .no-print { display: none !important; }
      body { background: white !important; }
      .cert-shell {
        width: 297mm; height: 210mm; margin: 0 !important;
        padding: 0 !important; border: none !important;
        box-shadow: none !important; border-radius: 0 !important;
        overflow: hidden !important;
      }
    }
  </style>
</head>

<body class="min-h-screen bg-[#0b0f19] text-gray-100 relative overflow-x-hidden">
  <div aria-hidden="true" class="pointer-events-none absolute inset-0">
    <div class="blob-a absolute -top-24 -left-20 h-72 w-72 rounded-full bg-sky-500/10 blur-3xl"></div>
    <div class="blob-b absolute top-1/3 -right-20 h-80 w-80 rounded-full bg-amber-400/10 blur-3xl"></div>
    <div class="blob-c absolute -bottom-20 left-1/3 h-72 w-72 rounded-full bg-emerald-400/10 blur-3xl"></div>
  </div>

  <div class="relative min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-3xl">

      <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-slate-900/95 via-[#101828]/95 to-slate-950/95 shadow-xl overflow-hidden">
        <div class="p-6 sm:p-7">

          {{-- Branded header (same structure as card verify) --}}
          <div class="relative mb-6 overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-r from-sky-500/10 via-emerald-400/10 to-amber-400/10 p-4 sm:p-5">
            <svg aria-hidden="true" class="pointer-events-none absolute -right-6 -top-8 h-28 w-56 opacity-25" viewBox="0 0 220 110" fill="none">
              <circle cx="30" cy="28" r="20" stroke="#60a5fa" stroke-width="2"/>
              <circle cx="76" cy="18" r="20" stroke="#f59e0b" stroke-width="2"/>
              <circle cx="125" cy="33" r="20" stroke="#34d399" stroke-width="2"/>
              <circle cx="171" cy="20" r="20" stroke="#f87171" stroke-width="2"/>
            </svg>

            <div class="relative flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
              <div class="flex min-w-0 items-center gap-3">
                {{-- Certificate icon --}}
                <div class="h-12 w-12 rounded-xl bg-amber-400/10 border border-amber-400/20 flex items-center justify-center">
                  <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                  </svg>
                </div>
                <div class="min-w-0">
                  <div class="truncate text-lg sm:text-xl font-extrabold text-white">
                    {{ isset($certificate) && $certificate ? ($certificate->payload['event_title'] ?? 'Event') : 'ARISE Games' }}
                  </div>
                  <div class="text-xs sm:text-sm text-gray-300">Verifikasi Sertifikat</div>
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
                  TIDAK VALID
                </span>
              @endif
            </div>
          </div>

          @if(!$valid)
            {{-- Invalid state --}}
            <div class="rounded-xl border border-rose-500/20 bg-rose-500/10 p-4">
              <div class="font-bold text-rose-100">Sertifikat tidak valid</div>
              <div class="text-sm text-rose-200 mt-1">Alasan: <b>{{ $reason ?? 'Tidak diketahui' }}</b></div>
              <div class="text-xs text-rose-300 mt-2">Silakan hubungi panitia event jika ada pertanyaan.</div>
            </div>

          @else
            {{-- Valid — show certificate info --}}
            @php
              $payload = $payload ?? [];
            @endphp

            {{-- Owner info --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
              <div class="min-w-0">
                <div class="mt-1 text-2xl font-extrabold text-white break-words">
                  {{ $payload['volunteer_name'] ?? '—' }}
                </div>
                <div class="mt-2 text-sm text-gray-300">{{ $payload['role_label'] ?? '—' }}</div>
                <div class="mt-3 text-sm text-gray-300">
                  <span class="text-gray-400">Event:</span>
                  <span class="font-bold text-white">{{ $payload['event_title'] ?? '—' }}</span>
                </div>
                <div class="mt-1 text-sm text-gray-300">
                  <span class="text-gray-400">Periode:</span>
                  {{ $payload['event_start_at'] ?? '—' }} – {{ $payload['event_end_at'] ?? '—' }}
                </div>
                <div class="mt-1 text-sm text-gray-300">
                  <span class="text-gray-400">Tanggal Terbit:</span>
                  {{ $payload['issue_date'] ?? '—' }}
                </div>
              </div>
              @if($qrBase64)
                <div class="flex-shrink-0">
                  <div class="text-xs text-gray-500 text-center mb-1">QR Verifikasi</div>
                  <img src="{{ $qrBase64 }}" alt="QR" class="w-24 h-24 rounded-xl border border-white/10 bg-white p-1">
                </div>
              @endif
            </div>

            <hr class="my-6 border-white/10">

            {{-- Certificate preview --}}
            @if($layout)
              <div class="mb-6 w-full" id="certWindow">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Preview Sertifikat</div>
                <div class="relative w-full rounded-xl border border-white/20 bg-white overflow-hidden shadow-xl" style="aspect-ratio: 297 / 210;" id="certWrapper">
                  <div id="certInner" style="transform-origin: top left; width: 297mm; height: 210mm; pointer-events: none;">
                    @include('admin.certificates.preview-content', [
                        'layout'      => $layout,
                        'layoutModel' => $certificate->layout ?? \App\Models\CertificateLayout::find($certificate->layout_id),
                        'payload'     => $payload,
                        'event'       => $certificate->application?->opening?->event ?? (object)[],
                    ])
                  </div>
                </div>
                <div class="mt-2 text-xs text-gray-500 text-center">Tampilan diperkecil — gunakan tombol Download untuk versi penuh</div>

                <script>
                  function scaleCert() {
                      const wrapper = document.getElementById('certWrapper');
                      const inner = document.getElementById('certInner');
                      if(!wrapper || !inner) return;
                      const scale = wrapper.clientWidth / 1122.52; // 297mm in pixels
                      inner.style.transform = 'scale(' + scale + ')';
                  }
                  window.addEventListener('resize', scaleCert);
                  setTimeout(scaleCert, 100);
                </script>
              </div>
            @endif

            {{-- Action buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 no-print">
              @if($certificate->qr_token)
                <a href="{{ url('/sertifikat/verify/' . $certificate->qr_token) }}"
                   class="flex-1 py-3 rounded-xl border border-emerald-500/20 bg-emerald-500/10 text-emerald-200 font-bold text-center text-sm hover:bg-emerald-500/20 transition">
                  🔗 Salin Link Verifikasi
                </a>
              @endif
              @if($certificate->qr_token)
              <a href="{{ route('public.certificates.download', $certificate->qr_token) }}"
                class="flex-1 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 text-white font-black text-center text-sm shadow-lg shadow-blue-500/20 hover:scale-[1.02] transition-all">
                📥 Download Certificate (PDF)
              </a>
              @endif
              <a href="{{ route('public.certificates.lookup') }}"
                 class="flex-1 py-3 rounded-xl border border-white/10 text-gray-300 font-bold text-center text-sm hover:bg-white/5 transition">
                Cari Sertifikat Lain
              </a>
            </div>
          @endif

        </div>
      </div>

      {{-- Footer --}}
      <div class="mt-4 text-center text-xs text-gray-600 flex items-center justify-center gap-2">
        <span class="inline-flex items-center gap-1.5 opacity-80">
          <span class="h-2 w-2 rounded-full border border-sky-300/70"></span>
          <span class="h-2 w-2 rounded-full border border-amber-300/70"></span>
          <span class="h-2 w-2 rounded-full border border-emerald-300/70"></span>
        </span>
        <span>ARISE Games — Verifikasi Sertifikat</span>
      </div>

    </div>
  </div>
</body>
</html>
