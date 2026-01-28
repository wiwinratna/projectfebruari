<section id="jobs" class="py-20 md:py-28">
  <div class="mx-auto max-w-7xl px-6">

    {{-- Header --}}
    <div class="mx-auto max-w-3xl text-center js-reveal">
      <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full card-surface text-sm">
        <span class="h-2 w-2 rounded-full" style="background: var(--red);"></span>
        <span style="color: var(--muted);">Career Opportunities</span>
      </div>

      <h2 class="mt-6 text-3xl md:text-5xl font-extrabold tracking-tight">
        Join the
        <span class="text-transparent bg-clip-text"
              style="background-image: linear-gradient(90deg, var(--red), var(--yellow));">
          Revolution
        </span>
        <br class="hidden md:block">
        in Sports Management
      </h2>

      <p class="mt-5 text-base md:text-lg" style="color: var(--muted);">
        3 lowongan terbaru yang sedang dibuka — cepat cek sebelum kuota penuh.
      </p>
    </div>

    {{-- Content --}}
    <div class="mt-12">
      @if(!empty($recentJobs) && $recentJobs->count())
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 js-stagger">
          @foreach($recentJobs->take(3) as $job)
            <div class="card-surface rounded-2xl p-6 js-scale group"
                 style="transition: transform .2s ease, background .2s ease, border-color .2s ease;">
              
              <div class="flex items-start justify-between gap-4">
                <div class="text-xs px-3 py-1 rounded-full"
                     style="background: rgba(255,255,255,.06); color: var(--muted); border:1px solid rgba(255,255,255,.10);">
                  {{ $job->jobCategory->name ?? 'General' }}
                </div>

                <div class="w-9 h-9 rounded-full flex items-center justify-center"
                     style="background: rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.12);">
                  <i class="fas fa-arrow-right" style="color: var(--red);"></i>
                </div>
              </div>

              <h3 class="mt-4 text-lg font-bold leading-snug">
                {{ \Illuminate\Support\Str::limit($job->title, 46) }}
              </h3>

              <p class="mt-2 text-sm" style="color: var(--muted);">
                {{ \Illuminate\Support\Str::limit($job->description ?? 'Join our dynamic team in the Olympic movement.', 110) }}
              </p>

              <div class="mt-4 flex flex-wrap gap-2 text-xs" style="color: var(--muted);">
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full"
                      style="background: rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.10);">
                  <i class="fas fa-map-marker-alt" style="color: var(--yellow);"></i>
                  {{ $job->event->city->name ?? 'Location' }}
                </span>

                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full"
                      style="background: rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.10);">
                  <i class="fas fa-trophy" style="color: var(--blue);"></i>
                  {{ \Illuminate\Support\Str::limit($job->event->title ?? 'Event', 18) }}
                </span>
              </div>

              <div class="mt-5 pt-5"
                   style="border-top:1px solid rgba(255,255,255,.10);">
                <div class="flex items-center justify-between text-xs" style="color: var(--muted);">
                  <div>
                    <div class="uppercase tracking-wider opacity-70">Deadline</div>
                    <div class="mt-1 font-semibold" style="color: var(--text);">
                      {{ optional($job->application_deadline)->format('M d, Y') ?? '-' }}
                    </div>
                  </div>
                  <div class="text-right">
                    <div class="uppercase tracking-wider opacity-70">Slots</div>
                    <div class="mt-1 font-semibold" style="color: var(--text);">
                      {{ $job->slots_filled ?? 0 }}/{{ $job->slots_total ?? 0 }}
                    </div>
                  </div>
                </div>

                <a href="{{ route('jobs.show', $job) }}"
                   class="mt-4 inline-flex w-full items-center justify-center gap-2 px-5 py-3 rounded-xl font-semibold"
                   style="background: rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.12); color: var(--text);">
                  <span>Apply Now</span>
                  <i class="fas fa-arrow-right" style="color: var(--red);"></i>
                </a>

                <div class="mt-4 h-[3px] w-full rounded-full overflow-hidden"
                     style="background: rgba(255,255,255,.08);">
                  <div class="h-full w-1/2"
                       style="background: linear-gradient(90deg, var(--red), transparent);"></div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-10 text-center js-reveal">
          <a href="{{ route('jobs.index') }}"
             class="inline-flex items-center gap-2 text-sm font-semibold"
             style="color: var(--muted);">
            View all opportunities
            <i class="fas fa-arrow-right" style="color: var(--red);"></i>
          </a>
        </div>

      @else
        {{-- Empty state (dark, nyambung) --}}
        <div class="mt-12 card-surface rounded-3xl p-10 text-center js-reveal">
          <div class="mx-auto w-14 h-14 rounded-2xl flex items-center justify-center"
               style="background: rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.12);">
            <i class="fas fa-search" style="color: var(--red); font-size: 22px;"></i>
          </div>
          <h3 class="mt-4 text-xl font-bold">No Openings Currently</h3>
          <p class="mt-2 text-sm" style="color: var(--muted);">
            Belum ada lowongan yang tersedia. Coba cek lagi nanti ya.
          </p>
          <a href="{{ route('jobs.index') }}"
             class="mt-6 inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold btn-primary">
            View Archive
          </a>
        </div>
      @endif
    </div>

  </div>
</section>
