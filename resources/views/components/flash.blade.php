@php
    $messages = [
        'success' => [
            'icon'    => 'fas fa-check-circle',
            'classes' => 'bg-red-500 text-white shadow-lg',
        ],
        'status' => [
            'icon'    => 'fas fa-check-circle',
            'classes' => 'bg-red-500 text-white shadow-lg',
        ],
        'error' => [
            'icon'    => 'fas fa-exclamation-circle',
            'classes' => 'bg-red-600 text-white shadow-lg',
        ],
        'warning' => [
            'icon'    => 'fas fa-exclamation-triangle',
            'classes' => 'bg-orange-500 text-white shadow-lg',
        ],
    ];
@endphp

@if (collect($messages)->keys()->some(fn ($key) => session()->has($key)))
    <div id="flash-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full">
        @foreach ($messages as $key => $meta)
            @if (session()->has($key))
                <div class="flash-message {{ $meta['classes'] }} shadow-lg rounded-lg px-4 py-3 text-sm flex items-start gap-3 transition duration-300 ease-out"
                     data-timeout="5000"
                     role="alert">
                    <i class="{{ $meta['icon'] }} mt-0.5 flex-shrink-0"></i>
                    <div class="flex-1 leading-relaxed">
                        {{ session($key) }}
                    </div>
                    <button type="button"
                            class="text-white/70 hover:text-white transition flex-shrink-0"
                            data-flash-close
                            aria-label="Close notification">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
        @endforeach
    </div>
@endif

