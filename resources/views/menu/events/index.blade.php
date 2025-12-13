@extends('layouts.app') {{-- Memperluas master layout --}}

@section('title', 'Events & Competitions - KOI')
@section('page-title')
    Events & Competitions <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-6">
    
    {{-- Header with Add Button --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manage Events</h2>
            <p class="text-gray-600 mt-1">Organize sports events and competitions</p>
        </div>
        <a href="{{ route('admin.events.create') }}"
           class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Create Event
        </a>
    </div>

    {{-- Search Bar --}}
    <div class="flex items-center justify-between">
        <div class="flex-1 max-w-md">
            <form method="GET" action="{{ route('admin.events.index') }}" id="search-form">
                <div class="relative flex items-center border border-gray-300 rounded-lg py-2 px-4 pl-10 bg-white">
                    <i class="fas fa-search absolute left-3 text-gray-400"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ $searchQuery ?? '' }}" 
                           placeholder="Search events (title, penyelenggara, venue, dll)..." 
                           class="focus:outline-none w-full ml-2"
                           id="search-input">
                    @if(!empty($searchQuery))
                        <button type="button" 
                                onclick="clearSearch()" 
                                class="absolute right-2 text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100"
                                title="Clear search">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>



    {{-- Event stats --}}
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Active Events</p>
            <p class="text-3xl font-semibold text-green-600 mt-2">{{ $stats['active_events'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Upcoming</p>
            <p class="text-3xl font-semibold text-blue-600 mt-2">{{ $stats['upcoming_events'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Planning</p>
            <p class="text-3xl font-semibold text-yellow-600 mt-2">{{ $stats['planning_events'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Completed</p>
            <p class="text-3xl font-semibold text-gray-600 mt-2">{{ $stats['completed_events'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Worker Openings</p>
            <p class="text-3xl font-semibold text-gray-800 mt-2">{{ $stats['worker_openings'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Applications</p>
            <p class="text-3xl font-semibold text-gray-800 mt-2">{{ $stats['total_applications'] }}</p>
        </div>
    </div>

    {{-- Event Filter Tabs --}}
    <div class="bg-white rounded-lg shadow-sm">
        <div class="border-b border-gray-200">
            <div class="relative">
                {{-- Desktop tabs (hidden on mobile) --}}
                <nav class="hidden md:flex space-x-8 px-6" aria-label="Tabs">
                    <a href="{{ route('admin.events.index', ['status' => 'active']) }}"
                       class="{{ (!$showCompleted && ($statusFilter ?? 'active') === 'active') ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-play-circle mr-2"></i>Active Events
                        @if($stats['active_events'] > 0)
                            <span class="ml-2 bg-green-100 text-green-600 py-0.5 px-2.5 rounded-full text-xs">{{ $stats['active_events'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.events.index', ['status' => 'upcoming']) }}"
                       class="{{ (!$showCompleted && ($statusFilter ?? '') === 'upcoming') ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-clock mr-2"></i>Upcoming Events
                        @if($stats['upcoming_events'] > 0)
                            <span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2.5 rounded-full text-xs">{{ $stats['upcoming_events'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.events.index', ['status' => 'planning']) }}"
                       class="{{ (!$showCompleted && ($statusFilter ?? '') === 'planning') ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-drafting-compass mr-2"></i>Planning Events
                        @if($stats['planning_events'] > 0)
                            <span class="ml-2 bg-yellow-100 text-yellow-600 py-0.5 px-2.5 rounded-full text-xs">{{ $stats['planning_events'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.events.index', ['show_completed' => 'true']) }}"
                       class="{{ ($showCompleted ?? false) ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-check-circle mr-2"></i>Completed Events
                        @if($stats['completed_events'] > 0)
                            <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">{{ $stats['completed_events'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.events.index', ['status' => 'all']) }}"
                       class="{{ (!$showCompleted && ($statusFilter ?? 'all') === 'all') ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-list mr-2"></i>All Events
                        <span class="ml-2 bg-red-100 text-red-600 py-0.5 px-2.5 rounded-full text-xs">
                            {{ $stats['active_events'] + $stats['upcoming_events'] + $stats['planning_events'] + $stats['completed_events'] }}
                        </span>
                    </a>
                </nav>

                {{-- Mobile tabs (horizontal scroll) --}}
                <div class="md:hidden">
                    {{-- Scroll indicator arrows for mobile --}}
                    <button type="button" 
                            class="absolute left-0 top-0 z-10 h-full px-2 bg-gradient-to-r from-white to-transparent pointer-events-none"
                            id="scroll-left-indicator">
                        <i class="fas fa-chevron-left text-gray-400"></i>
                    </button>
                    <button type="button" 
                            class="absolute right-0 top-0 z-10 h-full px-2 bg-gradient-to-l from-white to-transparent pointer-events-none"
                            id="scroll-right-indicator">
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </button>

                    <div class="flex overflow-x-auto scrollbar-hide px-8 py-2 space-x-4" 
                         style="scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;"
                         id="mobile-tabs-container">
                        <a href="{{ route('admin.events.index', ['status' => 'active']) }}"
                           class="{{ (!$showCompleted && ($statusFilter ?? 'active') === 'active') ? 'bg-red-50 border-red-500 text-red-600' : 'bg-gray-50 border-gray-200 text-gray-500' }} flex-shrink-0 scroll-snap-start flex flex-col items-center justify-center py-3 px-4 rounded-lg border-2 min-w-[120px] transition-all duration-200 hover:shadow-md"
                           style="scroll-snap-align: start;">
                            <i class="fas fa-play-circle text-lg mb-1"></i>
                            <span class="text-xs font-medium">Active</span>
                            @if($stats['active_events'] > 0)
                                <span class="mt-1 bg-green-100 text-green-600 py-0.5 px-2 rounded-full text-xs font-medium">{{ $stats['active_events'] }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route('admin.events.index', ['status' => 'upcoming']) }}"
                           class="{{ (!$showCompleted && ($statusFilter ?? '') === 'upcoming') ? 'bg-red-50 border-red-500 text-red-600' : 'bg-gray-50 border-gray-200 text-gray-500' }} flex-shrink-0 scroll-snap-start flex flex-col items-center justify-center py-3 px-4 rounded-lg border-2 min-w-[120px] transition-all duration-200 hover:shadow-md"
                           style="scroll-snap-align: start;">
                            <i class="fas fa-clock text-lg mb-1"></i>
                            <span class="text-xs font-medium">Upcoming</span>
                            @if($stats['upcoming_events'] > 0)
                                <span class="mt-1 bg-blue-100 text-blue-600 py-0.5 px-2 rounded-full text-xs font-medium">{{ $stats['upcoming_events'] }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route('admin.events.index', ['status' => 'planning']) }}"
                           class="{{ (!$showCompleted && ($statusFilter ?? '') === 'planning') ? 'bg-red-50 border-red-500 text-red-600' : 'bg-gray-50 border-gray-200 text-gray-500' }} flex-shrink-0 scroll-snap-start flex flex-col items-center justify-center py-3 px-4 rounded-lg border-2 min-w-[120px] transition-all duration-200 hover:shadow-md"
                           style="scroll-snap-align: start;">
                            <i class="fas fa-drafting-compass text-lg mb-1"></i>
                            <span class="text-xs font-medium">Planning</span>
                            @if($stats['planning_events'] > 0)
                                <span class="mt-1 bg-yellow-100 text-yellow-600 py-0.5 px-2 rounded-full text-xs font-medium">{{ $stats['planning_events'] }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route('admin.events.index', ['show_completed' => 'true']) }}"
                           class="{{ ($showCompleted ?? false) ? 'bg-red-50 border-red-500 text-red-600' : 'bg-gray-50 border-gray-200 text-gray-500' }} flex-shrink-0 scroll-snap-start flex flex-col items-center justify-center py-3 px-4 rounded-lg border-2 min-w-[120px] transition-all duration-200 hover:shadow-md"
                           style="scroll-snap-align: start;">
                            <i class="fas fa-check-circle text-lg mb-1"></i>
                            <span class="text-xs font-medium">Completed</span>
                            @if($stats['completed_events'] > 0)
                                <span class="mt-1 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs font-medium">{{ $stats['completed_events'] }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route('admin.events.index', ['status' => 'all']) }}"
                           class="{{ (!$showCompleted && ($statusFilter ?? 'all') === 'all') ? 'bg-red-50 border-red-500 text-red-600' : 'bg-gray-50 border-gray-200 text-gray-500' }} flex-shrink-0 scroll-snap-start flex flex-col items-center justify-center py-3 px-4 rounded-lg border-2 min-w-[120px] transition-all duration-200 hover:shadow-md"
                           style="scroll-snap-align: start;">
                            <i class="fas fa-list text-lg mb-1"></i>
                            <span class="text-xs font-medium">All Events</span>
                            <span class="mt-1 bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs font-medium">
                                {{ $stats['active_events'] + $stats['upcoming_events'] + $stats['planning_events'] + $stats['completed_events'] }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Area --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Event List --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow space-y-6">
            @php
                $statusColors = [
                    'active' => 'bg-green-500 text-white',
                    'upcoming' => 'bg-blue-500 text-white',
                    'planning' => 'bg-yellow-500 text-white',
                    'completed' => 'bg-gray-400 text-white',
                ];
                
                $stageColors = [
                    'province' => 'bg-green-100 text-green-800 border-green-200',
                    'national' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'asean/sea' => 'bg-orange-100 text-orange-800 border-orange-200',
                    'asia' => 'bg-red-100 text-red-800 border-red-200',
                    'world' => 'bg-purple-100 text-purple-800 border-purple-200',
                ];
            @endphp
            @forelse ($events as $event)
                <div class="{{ !$loop->last ? 'border-b border-gray-200 pb-6' : '' }}">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-3">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-1">{{ $event->title }}</h4>
                            <h5 class="text-md font-medium text-gray-600 mb-2">{{ $event->penyelenggara ?? 'Penyelenggara TBA' }}</h5>
                            <p class="text-gray-600 text-sm">{{ $event->venue ?? 'Venue TBA' }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs px-2 py-1 rounded {{ $statusColors[$event->status] ?? 'bg-gray-300 text-gray-700' }}">
                                {{ ucfirst($event->status) }}
                            </span>
                            <span class="text-xs px-2 py-1 rounded-full border {{ $stageColors[$event->stage] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                @if($event->stage === 'province') Daerah
                                @elseif($event->stage === 'national') Nasional
                                @elseif($event->stage === 'asean/sea') SEA Games
                                @elseif($event->stage === 'asia') Asia
                                @elseif($event->stage === 'world') Dunia
                                @else {{ ucfirst($event->stage) }} @endif
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-2">Sports Available:</p>
                        <div class="flex flex-wrap gap-2">
                            @forelse ($event->sports as $sport)
                                <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full">{{ $sport->name }}</span>
                            @empty
                                <span class="text-xs text-gray-500">Belum ada cabang olahraga dipilih.</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 text-gray-600 text-sm mb-4">
                        <span class="flex items-center">
                            <i class="fas fa-clock mr-2 text-gray-400"></i>
                            {{ optional($event->start_at)->translatedFormat('d M Y') }} &ndash;
                            {{ optional($event->end_at)->translatedFormat('d M Y') ?? 'TBD' }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                            {{ $event->city_name }}
                            @if($event->city_province)
                                <span class="text-xs text-gray-500 ml-1">({{ $event->city_province }})</span>
                            @endif
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm text-gray-600 mb-4">
                        <div>
                            <p class="text-xs uppercase text-gray-400">Worker Roles</p>
                            <p class="font-semibold">{{ $event->worker_openings_count }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Slots Total</p>
                            <p class="font-semibold">{{ $event->slots_total_sum ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Applications</p>
                            <p class="font-semibold">{{ $event->applications_count }}</p>
                        </div>
                    </div>

                    @if($event->instagram || $event->email)
                        <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <h6 class="text-sm font-medium text-blue-800 mb-2">Informasi Kontak:</h6>
                            <div class="flex flex-wrap gap-4 text-sm text-blue-700">
                                @if($event->instagram)
                                    <span class="flex items-center">
                                        <i class="fab fa-instagram mr-2"></i>
                                        {{ $event->instagram }}
                                    </span>
                                @endif
                                @if($event->email)
                                    <span class="flex items-center">
                                        <i class="fas fa-envelope mr-2"></i>
                                        <a href="mailto:{{ $event->email }}" class="hover:underline">{{ $event->email }}</a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.events.show', $event) }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-eye mr-1"></i> View Details
                        </a>
                        @if($event->status !== 'completed')
                            <a href="{{ route('admin.events.edit', $event) }}"
                               class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                                Edit
                            </a>
                        @else
                            <div class="bg-gray-100 text-gray-500 px-4 py-2 rounded-lg text-sm border border-gray-200" title="Completed events cannot be edited">
                                <i class="fas fa-lock mr-1"></i> Completed
                            </div>
                        @endif
                        @if($event->applications_count == 0 && $event->worker_openings_count == 0)
                            <button onclick="deleteEvent({{ $event->id }}, '{{ addslashes($event->title) }}')" 
                                    data-event-id="{{ $event->id }}"
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        @else
                            <div class="bg-gray-100 text-gray-500 px-4 py-2 rounded-lg text-sm border border-gray-200" title="Cannot delete - currently has applications or worker openings">
                                <i class="fas fa-lock mr-1"></i> In Use
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-12">
                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                    <p>Belum ada event terdaftar. Mulai dengan membuat event baru.</p>
                </div>
            @endforelse
        </div>

        {{-- Right Sidebar --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Event Calendar --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Event Calendar</h3>
                    <span class="text-sm text-gray-500">{{ $calendarMonth->translatedFormat('F Y') }}</span>
                </div>
                
                <div class="calendar-widget">
                    {{-- Calendar Header --}}
                    <div class="grid grid-cols-7 gap-1 text-xs text-gray-600 mb-2 text-center font-medium">
                        <div class="py-2">Min</div>
                        <div class="py-2">Sen</div>
                        <div class="py-2">Sel</div>
                        <div class="py-2">Rab</div>
                        <div class="py-2">Kam</div>
                        <div class="py-2">Jum</div>
                        <div class="py-2">Sab</div>
                    </div>
                    
                    {{-- Calendar Days --}}
                    @php 
                        $firstDayOfMonth = $calendarMonth->copy()->startOfMonth();
                        $startDayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 = Sunday
                        $daysInMonth = $calendarMonth->daysInMonth;
                        $today = now();
                        $currentMonth = $calendarMonth->month;
                        $currentYear = $calendarMonth->year;
                    @endphp
                    
                    <div class="grid grid-cols-7 gap-1 text-sm">
                        {{-- Empty cells untuk first week --}}
                        @for ($i = 0; $i < $startDayOfWeek; $i++)
                            <div class="py-2"></div>
                        @endfor
                        
                        {{-- Calendar days --}}
                        @for ($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $dateString = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                                $hasEvents = isset($eventsByDate[$dateString]);
                                $isToday = $today->format('Y-m-d') === $dateString;
                                $dayEvents = $eventsByDate[$dateString] ?? [];
                                $eventCount = count($dayEvents);
                            @endphp
                            
                            <div class="relative py-2 text-center cursor-pointer hover:bg-gray-50 transition-colors
                                {{ $isToday ? 'bg-blue-100 rounded-lg border-2 border-blue-500' : 'hover:rounded-lg' }}"
                                onclick="{{ $hasEvents ? 'showDayEvents(\'' . $dateString . '\')' : '' }}"
                                title="{{ $hasEvents ? $eventCount . ' event(s) on ' . date('d M Y', strtotime($dateString)) : 'No events' }}">
                                
                                <span class="{{ $isToday ? 'font-bold text-blue-700' : 'text-gray-700' }}">
                                    {{ $day }}
                                </span>
                                
                                {{-- Event dots --}}
                                @if($hasEvents)
                                    <div class="absolute bottom-1 left-1/2 transform -translate-x-1/2 flex space-x-1">
                                        @if($eventCount <= 3)
                                            {{-- Show individual dots --}}
                                            @for($i = 0; $i < min($eventCount, 3); $i++)
                                                <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                                            @endfor
                                        @else
                                            {{-- Show one dot untuk multiple events --}}
                                            <div class="w-2 h-2 bg-red-500 rounded-full flex items-center justify-center">
                                                <span class="text-xs text-white font-bold">{{ $eventCount }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                    
                    {{-- Calendar Legend --}}
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                <span>Event(s)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-blue-100 border border-blue-500 rounded"></div>
                                <span>Today</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="bg-white p-6 rounded-lg shadow space-y-4">
                <h3 class="text-xl font-bold text-gray-800">Quick Stats</h3>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Total Applications</span>
                    <span class="font-semibold text-gray-900">{{ $stats['total_applications'] }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Events w/ Workers</span>
                    <span class="font-semibold text-gray-900">
                        {{ $events->where('worker_openings_count', '>', 0)->count() }}
                    </span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Avg Slots per Event</span>
                    <span class="font-semibold text-gray-900">
                        {{ $events->count() ? number_format(($events->sum('slots_total_sum') ?? 0) / $events->count(), 1) : 0 }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Modal management functions
    function showConfirmModal(title, message, details, onConfirm) {
        const modal = document.getElementById('confirm-modal');
        const titleEl = document.getElementById('confirm-title');
        const messageEl = document.getElementById('confirm-message');
        const detailsEl = document.getElementById('confirm-details');
        const yesBtn = document.getElementById('confirm-yes');
        const cancelBtn = document.getElementById('confirm-cancel');

        titleEl.textContent = title;
        messageEl.textContent = message;
        detailsEl.textContent = details || '';
        
        // Remove previous event listeners
        yesBtn.replaceWith(yesBtn.cloneNode(true));
        cancelBtn.replaceWith(cancelBtn.cloneNode(true));
        
        // Get new references after cloning
        const newYesBtn = document.getElementById('confirm-yes');
        const newCancelBtn = document.getElementById('confirm-cancel');
        
        // Add event listeners
        newYesBtn.addEventListener('click', () => {
            hideConfirmModal();
            onConfirm();
        });
        
        newCancelBtn.addEventListener('click', hideConfirmModal);
        
        // Show modal
        modal.classList.remove('hidden');
        
        // Focus management for accessibility
        newYesBtn.focus();
    }
    
    function hideConfirmModal() {
        const modal = document.getElementById('confirm-modal');
        modal.classList.add('hidden');
    }
    
    function showLoading() {
        const overlay = document.getElementById('loading-overlay');
        overlay.classList.remove('hidden');
    }
    
    function hideLoading() {
        const overlay = document.getElementById('loading-overlay');
        overlay.classList.add('hidden');
    }
    
    function showFlashMessage(message, type = 'status') {
        // Create flash message directly in DOM
        const flashContainer = document.getElementById('flash-container') || createFlashContainer();
        
        // Prevent duplicate messages
        const existingMessages = flashContainer.querySelectorAll('.flash-message');
        for (let msg of existingMessages) {
            if (msg.textContent.trim() === message.trim()) {
                return; // Don't show duplicate
            }
        }
        
        const iconMap = {
            'status': 'fas fa-check-circle',
            'error': 'fas fa-exclamation-circle',
            'warning': 'fas fa-exclamation-triangle'
        };
        
        const classMap = {
            'status': 'bg-green-500 text-white',
            'error': 'bg-red-500 text-white',
            'warning': 'bg-yellow-500 text-white'
        };
        
        const flashMessage = document.createElement('div');
        flashMessage.className = `flash-message ${classMap[type]} shadow-lg rounded-lg px-4 py-3 text-sm flex items-start gap-3 transition duration-300 ease-out`;
        flashMessage.setAttribute('data-timeout', '4500');
        flashMessage.setAttribute('role', 'alert');
        flashMessage.innerHTML = `
            <i class="${iconMap[type]} mt-0.5"></i>
            <div class="flex-1">${message}</div>
            <button type="button" class="text-white/70 hover:text-white transition" data-flash-close aria-label="Close notification">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Set initial styles for animation
        flashMessage.style.opacity = '0';
        flashMessage.style.transform = 'translateX(100%)';
        
        flashContainer.appendChild(flashMessage);
        
        // Auto hide after timeout
        setTimeout(() => hideFlashMessage(flashMessage), 4500);
        
        // Manual close button
        flashMessage.querySelector('[data-flash-close]').addEventListener('click', () => {
            hideFlashMessage(flashMessage);
        });
        
        // Show with animation
        requestAnimationFrame(() => {
            flashMessage.style.opacity = '1';
            flashMessage.style.transform = 'translateX(0)';
        });
    }
    
    function createFlashContainer() {
        // Use the existing flash container from server-side, don't create new one
        const existingContainer = document.getElementById('flash-container');
        if (existingContainer) {
            return existingContainer;
        }
        
        // If no existing container, create one
        const container = document.createElement('div');
        container.id = 'flash-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
        return container;
    }
    
    function hideFlashMessage(element) {
        element.style.opacity = '0';
        element.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 300);
    }
    
    function deleteEvent(id, eventTitle) {
        const details = eventTitle ? `Event: "${eventTitle}"` : `This action cannot be undone.`;
        
        showConfirmModal(
            'Delete Event',
            'Are you sure you want to delete this event?',
            details,
            () => performDelete(id)
        );
    }
    
    function performDelete(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showFlashMessage('Security token not found. Please refresh the page and try again.', 'error');
            return;
        }

        showLoading();
        
        fetch(`/events/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            hideLoading();
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showFlashMessage('Event deleted successfully!', 'status');
                
                // Auto refresh page after successful deletion
                setTimeout(() => {
                    window.location.reload();
                }, 500);
                
            } else {
                showFlashMessage(data.message || 'Failed to delete event', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showFlashMessage('Error deleting event: ' + error.message, 'error');
        });
    }
    
    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('confirm-modal');
        if (e.target === modal && !modal.classList.contains('hidden')) {
            hideConfirmModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideConfirmModal();
        }
    });
    
    // Check for URL flash parameters
    function checkUrlFlashMessages() {
        const urlParams = new URLSearchParams(window.location.search);
        const flash = urlParams.get('flash');
        const name = urlParams.get('name');
        
        if (flash === 'created' && name) {
            showFlashMessage(`Event "${name}" created successfully!`, 'status');
            // Remove parameters from URL and refresh page
            setTimeout(() => {
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
                window.location.reload();
            }, 500);
        } else if (flash === 'updated' && name) {
            showFlashMessage(`Event "${name}" updated successfully!`, 'status');
            // Remove parameters from URL and refresh page
            setTimeout(() => {
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
                window.location.reload();
            }, 500);
        }
    }
    
    // Search functionality
    function clearSearch() {
        document.getElementById('search-input').value = '';
        window.location.href = '{{ route("admin.events.index") }}';
    }

    // Calendar functionality
    function showDayEvents(dateString) {
        // Find events untuk date tersebut dari PHP data
        const eventsData = @json($eventsByDate);
        const dayEvents = eventsData[dateString] || [];
        
        if (dayEvents.length === 0) return;
        
        // Format date untuk display
        const date = new Date(dateString);
        const formattedDate = date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        // Create modal content
        let eventsList = '';
        dayEvents.forEach(event => {
            const statusColor = {
                'active': 'bg-green-100 text-green-800',
                'upcoming': 'bg-blue-100 text-blue-800',
                'planning': 'bg-yellow-100 text-yellow-800',
                'completed': 'bg-gray-100 text-gray-800'
            };
            
            eventsList += `
                <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">${event.title}</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-clock mr-1"></i> ${event.time}
                            </p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusColor[event.status] || 'bg-gray-100 text-gray-800'}">
                            ${event.status.charAt(0).toUpperCase() + event.status.slice(1)}
                        </span>
                    </div>
                </div>
            `;
        });
        
        // Create dan show modal
        const modalHtml = `
            <div id="day-events-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-xl shadow-2xl border border-gray-200 max-w-md w-full max-h-96 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-calendar-day mr-2 text-blue-600"></i>
                                Events on ${formattedDate}
                            </h3>
                            <button onclick="closeDayEventsModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-white hover:bg-opacity-50 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">${dayEvents.length} event(s) scheduled</p>
                    </div>
                    <div class="p-4 max-h-80 overflow-y-auto">
                        <div class="space-y-3">
                            ${eventsList}
                        </div>
                    </div>
                    <div class="p-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
                        <button onclick="closeDayEventsModal()" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal jika ada
        const existingModal = document.getElementById('day-events-modal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Add new modal
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Add escape key listener
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDayEventsModal();
            }
        });
    }
    
    function closeDayEventsModal() {
        const modal = document.getElementById('day-events-modal');
        if (modal) {
            modal.remove();
        }
    }

    // Show search results as toast notification
    function showSearchToast(resultsCount, query) {
        // Remove existing search toast
        const existingToast = document.getElementById('search-results-toast');
        if (existingToast) {
            existingToast.remove();
        }

        // Create new toast
        const toast = document.createElement('div');
        toast.id = 'search-results-toast';
        toast.className = 'fixed top-4 right-4 z-50 max-w-sm bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 shadow-lg transform transition-all duration-300 ease-out';
        toast.innerHTML = `
            <div class="flex items-start gap-3">
                <i class="fas fa-search text-blue-600 mt-0.5"></i>
                <div class="flex-1">
                    <p class="text-sm text-blue-800">
                        Found <strong>${resultsCount}</strong> event${resultsCount !== 1 ? 's' : ''} for "<strong>${query}</strong>"
                    </p>
                </div>
                <button type="button" onclick="clearSearch()" class="text-blue-600 hover:text-blue-800 p-1">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(toast);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }
        }, 5000);
    }

    // Real-time search with debounce
    let searchTimeout;
    document.getElementById('search-input')?.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();
        const form = document.getElementById('search-form');
        
        // Auto-submit after 500ms pause in typing
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                form.submit();
            }, 500);
        } else if (query.length === 0) {
            // Clear search immediately when input is empty
            searchTimeout = setTimeout(() => {
                window.location.href = '{{ route("admin.events.index") }}';
            }, 300);
        }
    });

    // Handle Enter key to submit immediately
    document.getElementById('search-input')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            document.getElementById('search-form').submit();
        }
    });

    // Show search results toast on page load if there's a search query
    document.addEventListener('DOMContentLoaded', function() {
        const searchQuery = '{{ $searchQuery ?? '' }}';
        const resultsCount = {{ $events->count() }};
        
        if (searchQuery && resultsCount > 0) {
            // Small delay to ensure page is fully loaded
            setTimeout(() => {
                showSearchToast(resultsCount, searchQuery);
            }, 300);
        }
    });

    // Check URL flash messages on page load
    document.addEventListener('DOMContentLoaded', checkUrlFlashMessages);

    // Mobile tabs functionality
    function initMobileTabs() {
        const container = document.getElementById('mobile-tabs-container');
        const leftIndicator = document.getElementById('scroll-left-indicator');
        const rightIndicator = document.getElementById('scroll-right-indicator');
        
        if (!container || !leftIndicator || !rightIndicator) return;

        // Update scroll indicators
        function updateIndicators() {
            const scrollLeft = container.scrollLeft;
            const scrollWidth = container.scrollWidth;
            const clientWidth = container.clientWidth;
            
            // Show/hide left indicator
            leftIndicator.style.opacity = scrollLeft > 0 ? '1' : '0.3';
            
            // Show/hide right indicator  
            rightIndicator.style.opacity = scrollLeft < scrollWidth - clientWidth ? '1' : '0.3';
        }

        // Smooth scroll to tab
        function scrollToTab(tabElement) {
            const containerRect = container.getBoundingClientRect();
            const tabRect = tabElement.getBoundingClientRect();
            const scrollLeft = tabElement.offsetLeft - (container.clientWidth - tabElement.clientWidth) / 2;
            
            container.scrollTo({
                left: scrollLeft,
                behavior: 'smooth'
            });
        }

        // Update indicators initially and on scroll
        updateIndicators();
        container.addEventListener('scroll', updateIndicators);
        window.addEventListener('resize', updateIndicators);

        // Add click handlers for better UX
        const tabLinks = container.querySelectorAll('a');
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Add visual feedback
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });

        // Keyboard navigation for accessibility
        container.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                e.preventDefault();
                const currentTab = document.querySelector('#mobile-tabs-container a[style*="border-red-500"]') || tabLinks[0];
                const currentIndex = Array.from(tabLinks).indexOf(currentTab);
                let nextIndex;
                
                if (e.key === 'ArrowLeft') {
                    nextIndex = currentIndex > 0 ? currentIndex - 1 : tabLinks.length - 1;
                } else {
                    nextIndex = currentIndex < tabLinks.length - 1 ? currentIndex + 1 : 0;
                }
                
                scrollToTab(tabLinks[nextIndex]);
                tabLinks[nextIndex].focus();
            }
        });
    }

    // Initialize mobile tabs
    if (window.innerWidth < 768) {
        initMobileTabs();
    }

    // Re-initialize on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth < 768) {
            setTimeout(initMobileTabs, 100);
        }
    });
</script>

<style>
    /* Hide scrollbar but keep functionality */
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    /* Smooth scrolling for mobile tabs */
    #mobile-tabs-container {
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    /* Touch-friendly improvements */
    @media (max-width: 767px) {
        #mobile-tabs-container a {
            min-height: 80px;
            touch-action: manipulation;
        }
    }
</style>

{{-- Include Confirm Modal Component --}}
@include('components.confirm-modal')

@endsection
