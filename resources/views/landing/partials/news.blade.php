<section id="news" class="mx-auto max-w-7xl px-6 py-20">
  <div class="mx-auto max-w-3xl text-center js-reveal">
    <h2 class="text-2xl md:text-3xl font-bold">Updates</h2>
    <p class="mt-3 text-sm md:text-base" style="color: var(--muted);">
      Update resmi NOCIS & berita olahraga terbaru.
    </p>
  </div>

  <div class="mt-12 grid gap-4 md:grid-cols-3 js-stagger">
    @forelse($newsItems as $item)
      <article class="card-surface rounded-2xl overflow-hidden js-scale group">

        {{-- IMAGE --}}
        @if(!empty($item['image']))
          <div class="h-36 overflow-hidden">
            <img src="{{ $item['image'] }}"
                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                 alt="">
          </div>
        @endif

        <div class="p-6">
          <div class="text-xs" style="color: var(--muted);">
            {{ $item['source'] }}
            @if(!empty($item['published_at']))
              • {{ \Carbon\Carbon::parse($item['published_at'])->format('d M Y') }}
            @endif
          </div>

          <div class="mt-2 text-sm font-semibold leading-snug">
            {{ \Str::limit($item['title'], 70) }}
          </div>

          <p class="mt-2 text-sm" style="color: var(--muted);">
            {{ \Str::limit($item['excerpt'] ?? '', 100) }}
          </p>

          @if(!empty($item['url']) && $item['url'] !== '#')
            <a href="{{ $item['url'] }}"
               target="_blank"
               class="inline-flex items-center gap-2 mt-4 btn-ghost px-4 py-2 rounded-xl text-sm font-semibold">
              Read
              <i class="fas fa-arrow-right text-xs"></i>
            </a>
          @endif
        </div>
      </article>
    @empty
      <p class="col-span-3 text-center text-sm" style="color: var(--muted);">
        Belum ada update.
      </p>
    @endforelse
  </div>
</section>
