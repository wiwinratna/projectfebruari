<section id="news" class="mx-auto max-w-7xl px-6 py-20">
  {{-- HEADER --}}
  <div class="mx-auto max-w-3xl text-center js-reveal">
    <h2 class="text-2xl md:text-3xl font-bold">Updates</h2>
    <p class="mt-3 text-sm md:text-base" style="color: var(--muted);">
      Update resmi NOCIS & berita olahraga terbaru.
    </p>
  </div>

  {{-- GRID --}}
  <div class="mt-12 grid gap-6 md:grid-cols-3 js-stagger">
    @forelse($newsItems as $item)
      @php
        $type = $item['type'] ?? 'api'; // default aman
        // internal => route show, api/rss => url luar
        $href = $item['url'] ?? '#';
        $isExternal = ($type !== 'internal');

        $isExternal = $type !== 'internal';
      @endphp

      <article class="card-surface rounded-2xl overflow-hidden js-scale group flex flex-col h-full">
        {{-- IMAGE (SELALU ADA SLOT) --}}
        <div class="h-40 overflow-hidden bg-gray-100">
          @if(!empty($item['image']))
            <img src="{{ $item['image'] }}"
                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                 alt="">
          @else
            <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">
              No Image
            </div>
          @endif
        </div>

        {{-- CONTENT --}}
        <div class="p-6 flex flex-col flex-1">
          <div class="text-xs mb-2" style="color: var(--muted);">
            {{ $item['source'] ?? 'NOCIS' }}
            @if(!empty($item['published_at']))
              • {{ \Carbon\Carbon::parse($item['published_at'])->format('d M Y') }}
            @endif
          </div>

          <h3 class="text-sm font-semibold leading-snug line-clamp-2">
            {{ $item['title'] ?? '-' }}
          </h3>

          <p class="mt-2 text-sm line-clamp-3" style="color: var(--muted);">
            {{ $item['excerpt'] ?? '' }}
          </p>

          {{-- CTA --}}
          @if(!empty($href) && $href !== '#')
            <div class="mt-auto pt-4">
              <a href="{{ $href }}"
                 @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
                 class="inline-flex items-center gap-2 text-sm font-semibold btn-ghost px-4 py-2 rounded-xl">
                Read more
                <i class="fas fa-arrow-right text-xs"></i>
              </a>
            </div>
          @endif
        </div>
      </article>
    @empty
      <p class="col-span-3 text-center text-sm" style="color: var(--muted);">
        Belum ada update.
      </p>
    @endforelse
  </div>

  {{-- BUTTON LIHAT LAINNYA --}}
  <div class="mt-10 flex justify-center">
    <a href="{{ route('news.index') }}"
       class="px-6 py-3 rounded-xl font-semibold text-sm btn-primary inline-flex items-center gap-2">
      Lihat lainnya
      <i class="fas fa-arrow-right text-xs"></i>
    </a>
  </div>
</section>
