@php
    $bottomNavItems = [
        [
            'route'  => route('customer.dashboard'),
            'label'  => 'Home',
            'icon'   => 'fas fa-th-large',
            'active' => request()->routeIs('customer.dashboard'),
        ],
        [
            'route'  => route('customer.applications'),
            'label'  => 'Lamaran',
            'icon'   => 'fas fa-file-alt',
            'active' => request()->routeIs('customer.applications*'),
        ],
        [
            'route'  => route('jobs.index'),
            'label'  => 'Lowongan',
            'icon'   => 'fas fa-briefcase',
            'active' => request()->routeIs('jobs.*'),
        ],
        [
            'route'  => route('customer.saved-jobs'),
            'label'  => 'Disimpan',
            'icon'   => 'fas fa-bookmark',
            'active' => request()->routeIs('customer.saved-jobs*'),
        ],
        [
            'route'  => route('customer.materials.index'),
            'label'  => 'Materi',
            'icon'   => 'fas fa-book-reader',
            'active' => request()->routeIs('customer.materials.*'),
        ],
        [
            'route'  => route('customer.attendance.index'),
            'label'  => 'Absensi',
            'icon'   => 'fas fa-camera',
            'active' => request()->routeIs('customer.attendance.*'),
        ],
    ];
@endphp

<!-- Mobile Bottom Navigation Bar (Semua Fitur Tersedia di Bawah Khusus Layar HP) -->
<nav class="md:hidden fixed bottom-0 inset-x-0 z-50 bg-white/95 backdrop-blur-xl border-t border-gray-200/90 shadow-[0_-4px_25px_rgba(0,0,0,0.07)]">
    <div class="grid grid-cols-6 h-16 max-w-lg mx-auto px-0.5">
        @foreach($bottomNavItems as $item)
            <a href="{{ $item['route'] }}" 
               class="flex flex-col items-center justify-center py-1 select-none transition-all duration-150 active:scale-90 group relative {{ $item['active'] ? 'text-red-600' : 'text-gray-400 hover:text-gray-600' }}">
                
                <!-- Active Indicator Dot -->
                @if($item['active'])
                    <span class="absolute top-1 w-1.5 h-1.5 rounded-full bg-red-600 shadow-sm shadow-red-500/50"></span>
                @endif

                <!-- Icon Container with subtle active pill background -->
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl flex items-center justify-center transition-all {{ $item['active'] ? 'bg-red-50 text-red-600 shadow-sm' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="{{ $item['icon'] }} text-sm sm:text-base"></i>
                </div>

                <!-- Label -->
                <span class="text-[9px] sm:text-[10px] tracking-tight leading-none mt-0.5 truncate max-w-full px-0.5 {{ $item['active'] ? 'font-bold text-red-600' : 'font-medium text-gray-500' }}">
                    {{ $item['label'] }}
                </span>
            </a>
        @endforeach
    </div>
</nav>
