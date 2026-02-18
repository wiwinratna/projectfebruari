@extends('layouts.public')

@section('title', 'NOCIS - News')

@section('content')
<section class="bg-white">
  <div class="mx-auto max-w-7xl px-6 py-14">

    {{-- Header --}}
    <div class="text-center max-w-3xl mx-auto">
      <div class="inline-flex items-center gap-2 bg-white/70 border border-red-100 px-4 py-2 rounded-full shadow-sm">
        <span class="w-2 h-2 rounded-full bg-red-500"></span>
        <span class="text-sm font-semibold text-gray-600">Updates</span>
      </div>

      <h1 class="mt-6 text-3xl md:text-5xl font-extrabold tracking-tight text-gray-900">
        NOCIS <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-red-400">News</span>
      </h1>
      <p class="mt-3 text-gray-600">
        Informasi terbaru seputar event, workforce, serta berita olahraga dari sumber eksternal.
      </p>
    </div>

    {{-- SECTION: INTERNAL (NOCIS) --}}
    <div class="mt-12">
      <div class="flex items-center justify-between gap-4">
        <h2 class="text-xl md:text-2xl font-extrabold text-gray-900">NOCIS Updates</h2>
        <span class="text-sm text-gray-500">
          {{ $posts->total() }} berita
        </span>
      </div>

      <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($posts as $post)
          <a href="{{ route('news.show', $post) }}"
             class="group bg-white rounded-2xl border border-gray-200 hover:border-red-200 shadow-sm hover:shadow-lg transition-all overflow-hidden">

            {{-- Cover --}}
            <div class="relative h-44 bg-gray-50">
              @if($post->cover_image)
                <img src="{{ asset('storage/'.$post->cover_image) }}"
                     alt="cover"
                     class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
              @else
                <div class="w-full h-full flex items-center justify-center">
                  <div class="text-center">
                    <div class="mx-auto w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center">
                      <i class="fas fa-newspaper text-xl"></i>
                    </div>
                    <div class="mt-3 text-xs text-gray-400">No Cover</div>
                  </div>
                </div>
              @endif

              {{-- Badge --}}
              <div class="absolute top-3 left-3">
                <span class="inline-flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-wide
                             px-3 py-1 rounded-full bg-white/90 border border-gray-200 text-gray-700">
                  <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                  {{ $post->source_name ?? 'NOCIS' }}
                </span>
              </div>
            </div>

            {{-- Body --}}
            <div class="p-6">
              <div class="text-xs text-gray-500">
                {{ optional($post->published_at)->format('d M Y, H:i') ?? optional($post->created_at)->format('d M Y, H:i') }}
              </div>

              <h3 class="mt-2 text-lg font-bold text-gray-900 leading-snug group-hover:text-red-600 transition-colors">
                {{ $post->title }}
              </h3>

              <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 110, '...') }}
              </p>

              <div class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-red-600">
                Read More
                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
              </div>
            </div>
          </a>
        @empty
          <div class="md:col-span-2 lg:col-span-3">
            <div class="bg-white rounded-3xl border border-gray-200 p-10 text-center shadow-sm">
              <div class="mx-auto w-16 h-16 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center">
                <i class="fas fa-search text-2xl"></i>
              </div>
              <h3 class="mt-4 text-xl font-bold text-gray-900">Belum ada berita NOCIS</h3>
              <p class="mt-2 text-gray-600">Nanti kalau admin publish, bakal muncul di sini.</p>
              <a href="{{ route('landing') }}"
                 class="inline-flex mt-6 items-center gap-2 px-6 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold transition">
                <i class="fas fa-home"></i> Back to Landing
              </a>
            </div>
          </div>
        @endforelse
      </div>

      {{-- Pagination internal --}}
      @if($posts->hasPages())
        <div class="mt-10">
          {{ $posts->links() }}
        </div>
      @endif
    </div>

    {{-- Divider --}}
    <div class="my-14 border-t border-gray-200"></div>

    {{-- SECTION: EXTERNAL (RSS / SPORTS) --}}
    <div>
      <div class="flex items-center justify-between gap-4">
        <h2 class="text-xl md:text-2xl font-extrabold text-gray-900">Sports Headlines</h2>
        <span class="text-sm text-gray-500">
          Sumber eksternal (RSS)
        </span>
      </div>

      <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($apiNews as $item)
          @php
            $hrefRaw = $item['url'] ?? '#';
            $href = (is_string($hrefRaw) && preg_match('/^https?:\/\//i', $hrefRaw)) ? $hrefRaw : '#';
            $title = $item['title'] ?? '-';
            $excerpt = $item['excerpt'] ?? '';
            $source = $item['source'] ?? 'Sports';
            $published = !empty($item['published_at']) ? \Carbon\Carbon::parse($item['published_at'])->format('d M Y') : null;
            $img = $item['image'] ?? null;
          @endphp

          <a href="{{ $href }}"
             target="_blank" rel="noopener noreferrer"
             class="group bg-white rounded-2xl border border-gray-200 hover:border-red-200 shadow-sm hover:shadow-lg transition-all overflow-hidden">

            {{-- Cover --}}
            <div class="relative h-44 bg-gray-50">
              @if($img)
                <img src="{{ $img }}"
                     alt="cover"
                     class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
              @else
                <div class="w-full h-full flex items-center justify-center">
                  <div class="text-center">
                    <div class="mx-auto w-12 h-12 rounded-2xl bg-gray-100 text-gray-500 flex items-center justify-center">
                      <i class="fas fa-globe text-xl"></i>
                    </div>
                    <div class="mt-3 text-xs text-gray-400">No Image</div>
                  </div>
                </div>
              @endif

              {{-- Badge --}}
              <div class="absolute top-3 left-3">
                <span class="inline-flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-wide
                             px-3 py-1 rounded-full bg-white/90 border border-gray-200 text-gray-700">
                  <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                  {{ $source }}
                </span>
              </div>
            </div>

            {{-- Body --}}
            <div class="p-6">
              <div class="text-xs text-gray-500">
                {{ $published ?? '—' }}
              </div>

              <h3 class="mt-2 text-lg font-bold text-gray-900 leading-snug group-hover:text-red-600 transition-colors">
                {{ $title }}
              </h3>

              <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                {{ \Illuminate\Support\Str::limit(strip_tags($excerpt), 110, '...') }}
              </p>

              <div class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-red-600">
                Read More
                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
              </div>
            </div>
          </a>
        @empty
          <div class="md:col-span-2 lg:col-span-3">
            <div class="bg-gray-50 rounded-3xl border border-gray-200 p-10 text-center">
              <div class="mx-auto w-16 h-16 rounded-2xl bg-white text-gray-600 flex items-center justify-center border border-gray-200">
                <i class="fas fa-rss text-2xl"></i>
              </div>
              <h3 class="mt-4 text-xl font-bold text-gray-900">Belum ada berita olahraga</h3>
              <p class="mt-2 text-gray-600">Coba cek RSS URL / koneksi internet server kamu.</p>
            </div>
          </div>
        @endforelse
      </div>
    </div>

  </div>
</section>
@endsection
