<section id="flow" class="relative overflow-hidden py-20 md:py-28 section-bw">
  {{-- BW background image --}}
  <div class="pointer-events-none absolute inset-0 -z-30 opacity-[0.08] md:opacity-[1]"
       style="background-image: url('{{ asset('images/landing/athletes/Frame 1.png') }}');
              background-size: cover; background-position: center;">
  </div>

  {{-- Overlay --}}
  <div class="pointer-events-none absolute inset-0 -z-20"
       style="background:
          radial-gradient(70% 55% at 50% 0%, rgba(255,255,255,.08), transparent 60%),
          linear-gradient(to bottom, rgba(0,0,0,.20), rgba(0,0,0,.65));">
  </div>

  {{-- Glow blobs halus --}}
  <div class="pointer-events-none absolute inset-0 -z-10 js-fade" data-parallax="40">
    <div class="absolute -top-48 left-1/2 h-[420px] w-[760px] -translate-x-1/2 rounded-full blur-3xl opacity-25"
         style="background:
           radial-gradient(circle at 20% 30%, var(--blue) 0%, transparent 55%),
           radial-gradient(circle at 55% 55%, var(--green) 0%, transparent 55%),
           radial-gradient(circle at 78% 35%, var(--red) 0%, transparent 55%);">
    </div>
  </div>

  <div class="mx-auto max-w-7xl px-6 relative">
    {{-- Atlet kiri/kanan (opsional) --}}
    <img
      src="{{ asset('images/landing/athletes/Basket Berdiri Madep Kiri.png') }}"
      alt=""
      class="hidden lg:block absolute left-[-40px] top-[110px] w-[300px] xl:w-[360px] opacity-90 pointer-events-none select-none js-from-left"
      data-parallax="22"
      style="filter: drop-shadow(0 18px 30px rgba(0,0,0,.35));"
    />

    <img
      src="{{ asset('images/landing/athletes/Kaki Pisah.png') }}"
      alt=""
      class="hidden lg:block absolute right-[-40px] top-[80px] w-[300px] xl:w-[360px] opacity-90 pointer-events-none select-none js-from-right"
      data-parallax="18"
      style="filter: drop-shadow(0 18px 30px rgba(0,0,0,.35));"
    />

    {{-- Header (model Career Opportunities) --}}
    <div class="mx-auto max-w-3xl text-center mb-14 js-reveal">
      <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full card-surface text-sm"
           style="box-shadow:none;">
        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--green);"></span>
        <span style="color: var(--muted);">How It Works</span>
      </div>

      <h2 class="mt-6 text-4xl md:text-5xl font-extrabold tracking-tight leading-[1.1]">
        Alur yang <span class="text-transparent bg-clip-text"
          style="background-image: linear-gradient(90deg, var(--blue), var(--green));">ringkas</span> & rapi<br>
        dari registrasi sampai QR Card
      </h2>

      <p class="mt-5 text-base md:text-lg" style="color: var(--muted);">
        Semua proses terstruktur: data masuk → diverifikasi → approved → kartu otomatis siap cetak.
      </p>
    </div>

    {{-- Cards besar (feel “No Openings Currently” box tapi versi flow) --}}
    <div class="mx-auto max-w-5xl js-reveal">
      <div class="card-surface rounded-3xl p-6 md:p-10">
        <div class="grid gap-4 md:gap-6 md:grid-cols-3 js-stagger">
          @php
            $steps = [
              [
                'n'=>'01',
                't'=>'Registration',
                'd'=>'Peserta buat akun, lengkapi profil, upload CV & sertifikat.',
                'c'=>'var(--blue)',
                'i'=>'fa-user-plus'
              ],
              [
                'n'=>'02',
                't'=>'Accreditation',
                'd'=>'Admin cek kelengkapan (checklist), revisi bila perlu, approve/reject.',
                'c'=>'var(--yellow)',
                'i'=>'fa-clipboard-check'
              ],
              [
                'n'=>'03',
                't'=>'QR Access Card',
                'd'=>'Setelah approved, sistem generate kartu + QR siap print & laminating.',
                'c'=>'var(--green)',
                'i'=>'fa-qrcode'
              ],
            ];
          @endphp

          @foreach($steps as $s)
            <div class="rounded-2xl p-6 js-scale group flow-step"
                 style="background: rgba(255,255,255,.04);
                        border: 1px solid rgba(255,255,255,.10);
                        transition: transform .25s ease, border-color .25s ease, background .25s ease;">
              <div class="flex items-start justify-between gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center"
                     style="background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12);">
                  <i class="fas {{ $s['i'] }}" style="color: {{ $s['c'] }};"></i>
                </div>

                <div class="text-xs font-bold tracking-[0.25em]"
                     style="color: rgba(255,255,255,.55);">
                  {{ $s['n'] }}
                </div>
              </div>

              <h3 class="mt-5 text-lg font-bold">{{ $s['t'] }}</h3>
              <p class="mt-2 text-sm" style="color: var(--muted);">{{ $s['d'] }}</p>

              <div class="mt-5 h-[3px] w-full rounded-full overflow-hidden"
                   style="background: rgba(255,255,255,.08);">
                <div class="h-full w-1/2"
                     style="background: linear-gradient(90deg, {{ $s['c'] }}, transparent);"></div>
              </div>
            </div>
          @endforeach
        </div>

        {{-- mini note bawah (biar “premium”) --}}
        <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-3"
             style="color: rgba(255,255,255,.65);">
          <div class="text-sm">
            <span class="inline-block w-2 h-2 rounded-full mr-2" style="background: var(--blue);"></span>
            End-to-end flow yang bisa diaudit dan cepat.
          </div>

          <div class="text-sm">
            <span class="inline-block w-2 h-2 rounded-full mr-2" style="background: var(--green);"></span>
            Output: QR Access Card siap cetak otomatis.
          </div>
        </div>
      </div>
    </div>

    <div class="mt-12 mx-auto max-w-4xl js-reveal">
      <div class="accent-bar"></div>
    </div>
  </div>
</section>
