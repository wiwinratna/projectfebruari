@php
    $navTabs = [
        [
            'route' => 'customer.dashboard',
            'label' => 'Dashboard',
            'icon'  => 'fas fa-th-large',
            'active' => request()->routeIs('customer.dashboard'),
        ],
        [
            'route' => 'customer.applications',
            'label' => 'My Applications',
            'icon'  => 'fas fa-file-alt',
            'active' => request()->routeIs('customer.applications*'),
        ],
        [
            'route' => 'customer.saved-jobs',
            'label' => 'Saved Jobs',
            'icon'  => 'fas fa-bookmark',
            'active' => request()->routeIs('customer.saved-jobs*'),
        ],
        [
            'route' => 'customer.materials.index',
            'label' => 'Training Materials',
            'icon'  => 'fas fa-book-reader',
            'active' => request()->routeIs('customer.materials.*'),
        ],
        [
            'route' => 'customer.attendance.index',
            'label' => 'Event Attendance',
            'icon'  => 'fas fa-camera',
            'active' => request()->routeIs('customer.attendance.*'),
        ],
    ];
@endphp

<!-- Centralized Customer Navigation Tabs (Desktop & Tablet Horizontal Bar, Hidden on Mobile) -->
<div class="hidden md:block mb-6 sm:mb-8">
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
        @foreach($navTabs as $tab)
            <a href="{{ route($tab['route']) }}" 
               class="whitespace-nowrap px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl text-xs sm:text-sm transition-all flex items-center gap-2 {{ $tab['active'] ? 'font-bold bg-white text-red-600 border border-red-100 shadow-md shadow-red-500/10' : 'font-medium text-gray-600 bg-white/70 hover:bg-white hover:text-gray-900 border border-gray-200/60 shadow-sm' }}">
                <i class="{{ $tab['icon'] }} text-xs {{ $tab['active'] ? 'text-red-500' : 'text-gray-400' }}"></i>
                <span>{{ $tab['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>
