<section id="features" class="py-20 md:py-28 relative overflow-hidden">
  <div class="mx-auto max-w-7xl px-6">
    <div class="grid items-center gap-10 lg:grid-cols-2">

      {{-- KIRI --}}
      <div class="js-reveal">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full card-surface text-sm">
          <span class="h-2 w-2 rounded-full" style="background: var(--blue);"></span>
          <span style="color: var(--muted);">Our Solutions</span>
        </div>

        <h2 class="mt-6 text-3xl md:text-5xl font-extrabold tracking-tight">
          Semua alur workforce event<br>
          <span class="text-transparent bg-clip-text"
                style="background-image: linear-gradient(90deg, var(--blue), var(--green), var(--red));">
            dalam satu sistem
          </span>
        </h2>

        <p class="mt-5 text-base md:text-lg" style="color: var(--muted);">
          Dari pendaftaran sampai kartu akses QR — rapi, cepat, dan mudah diaudit.
        </p>
      </div>

      {{-- KANAN --}}
      <div class="relative h-[320px] sm:h-[420px] lg:h-[520px] overflow-visible">
        <img
          src="{{ asset('images/landing/athletes/Basket Loncat.png') }}"
          alt="athlete left"
          class="landing-athlete landing-athlete--left js-from-left"
          data-parallax="18"
          loading="lazy"
          decoding="async"
        />

        <img
          src="{{ asset('images/landing/athletes/Miring Biru.png') }}"
          alt="athlete right"
          class="landing-athlete landing-athlete--right js-from-right"
          data-parallax="12"
          loading="lazy"
          decoding="async"
        />
      </div>

    </div>

    <div class="mt-14 grid gap-4 md:grid-cols-2 lg:grid-cols-3 js-stagger">
      {{-- cards --}}
    </div>
  </div>
</section>
