<section class="relative overflow-hidden landing-hero section-bw">
  {{-- BW background image (per section) --}}
  <div class="pointer-events-none absolute inset-0 -z-30 opacity-[0.10] md:opacity-[0.14]"
       style="background-image: url('{{ asset('images/landing/athletes/Jadi BG Bagus.png') }}');">
  </div>

  {{-- BW overlay gradient biar elegan --}}
  <div class="pointer-events-none absolute inset-0 -z-20"
       style="background: linear-gradient(to bottom, rgba(0,0,0,.15), rgba(0,0,0,.55));">
  </div>

  <!-- glow background (punya kamu) -->
  <div class="pointer-events-none absolute inset-0 -z-10 js-fade" data-parallax="60">
    <div class="absolute -top-40 left-1/2 h-[520px] w-[920px] -translate-x-1/2 rounded-full blur-3xl opacity-30"
         style="background:
           radial-gradient(circle at 20% 30%, var(--blue) 0%, transparent 55%),
           radial-gradient(circle at 45% 65%, var(--green) 0%, transparent 55%),
           radial-gradient(circle at 70% 35%, var(--red) 0%, transparent 55%),
           radial-gradient(circle at 55% 10%, var(--yellow) 0%, transparent 55%);">
    </div>
    <div class="absolute inset-0"
         style="background: linear-gradient(to bottom, rgba(255,255,255,.04), transparent 45%, rgba(0,0,0,.55));">
    </div>
  </div>

  <div class="mx-auto max-w-7xl px-6 pt-20 pb-16 md:pt-28 md:pb-24 relative">
    {{-- Atlet kiri --}}
    <img
      src="{{ asset('images/landing/athletes/Basket Loncat.png') }}"
      alt=""
      class="hidden md:block absolute left-[-24px] top-[120px] w-[220px] lg:w-[260px] opacity-90 pointer-events-none select-none js-left"
    />

    {{-- Atlet kanan --}}
    <img
      src="{{ asset('images/landing/athletes/Miring Biru.png') }}"
      alt=""
      class="hidden md:block absolute right-[-24px] top-[90px] w-[220px] lg:w-[260px] opacity-90 pointer-events-none select-none js-right"
    />

    <div class="mx-auto max-w-3xl text-center relative z-10">
      <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full card-surface text-sm js-hero" style="box-shadow:none;">
        <span class="h-2 w-2 rounded-full" style="background: var(--green);"></span>
        <span style="color: var(--muted);">Registration • Accreditation • QR Access Card</span>
      </div>

      <h1 class="mt-6 text-4xl md:text-6xl font-extrabold tracking-tight leading-[1.1] js-hero hero-title">
        Sports Event Workforce<br>
        <span class="text-transparent bg-clip-text"
              style="background-image: linear-gradient(90deg, var(--blue), var(--green), var(--red));">
          Information System
        </span>
      </h1>

      <div class="mt-5 flex justify-center js-hero">
        <div class="card-surface inline-flex items-center gap-3 px-5 py-2 rounded-2xl">
          <span class="text-sm md:text-base font-semibold" style="color: var(--muted);">
            <span id="typed-rotating"></span>
            <span id="cursor-rotating" class="inline-block w-[10px] h-[20px] align-middle ml-1 rounded-sm"
                  style="background: var(--red); opacity:.85;"></span>
          </span>
        </div>
      </div>

      <p class="mt-5 text-base md:text-lg js-hero" style="color: var(--muted);">
        Kelola pendaftaran, verifikasi data, dan penerbitan kartu akses berbasis QR Code
        secara cepat, rapi, dan profesional.
      </p>

      <div class="mt-8 mx-auto h-[3px] w-full max-w-[520px] overflow-hidden rounded-full js-hero"
           style="background: rgba(255,255,255,.10);">
        <div class="flex h-full w-full">
          <span class="flex-1" style="background: var(--blue);"></span>
          <span class="flex-1" style="background: var(--yellow);"></span>
          <span class="flex-1" style="background: var(--black);"></span>
          <span class="flex-1" style="background: var(--green);"></span>
          <span class="flex-1" style="background: var(--red);"></span>
        </div>
      </div>

      <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center js-hero">
        <a href="{{ route('login') }}" class="btn-primary px-8 py-3 rounded-xl font-semibold">
          Log In
        </a>
        <a href="{{ route('register') }}" class="btn-ghost px-8 py-3 rounded-xl font-semibold">
          Create Account
        </a>
      </div>

      <div class="mt-12 grid gap-4 md:grid-cols-2 text-left js-stagger">
        <div class="card-surface rounded-2xl p-5 js-scale">
          <div class="text-sm font-semibold">Applicant Portal</div>
          <p class="mt-2 text-sm" style="color: var(--muted);">Upload CV & sertifikat, pantau status akreditasi.</p>
        </div>
        <div class="card-surface rounded-2xl p-5 js-scale">
          <div class="text-sm font-semibold">Admin Accreditation</div>
          <p class="mt-2 text-sm" style="color: var(--muted);">Checklist kelengkapan, approve/reject, output kartu.</p>
        </div>
      </div>
    </div>
  </div>
</section>
