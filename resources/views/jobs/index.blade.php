@extends('layouts.public')

@section('title', 'Job Opportunities - NOCIS')

@section('content')
<!-- Modern Web3 Job Opportunities Page -->
<div class="min-h-screen bg-gray-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <!-- Main Content Wrapper with Top Padding -->
    <div class="relative z-10 pt-24 sm:pt-32 pb-20 sm:pb-16">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-6 sm:mb-10">
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-900 mb-1 sm:mb-2 tracking-tight">
                    Job <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-red-400">Opportunities</span>
                </h1>
                <p class="text-gray-600 text-xs sm:text-lg">Find and apply for the best sports event roles in Indonesia.</p>
            </div>

            <!-- Top Metric Cards / Quick Filter Pills -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-6 mb-6 sm:mb-8">
                <!-- All opportunities -->
                <a href="{{ route('jobs.index') }}" 
                   class="metric-card-link bg-white rounded-xl sm:rounded-2xl p-3 sm:p-5 border transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2.5 sm:gap-4 relative overflow-hidden group focus:outline-none focus:ring-0 active:outline-none select-none {{ !request('filter') || request('filter') === 'all' ? 'border-red-300 bg-red-50/20 shadow-sm' : 'border-gray-200/80 hover:border-gray-300 shadow-sm' }}">
                    <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl flex items-center justify-center text-sm sm:text-lg shrink-0 {{ !request('filter') || request('filter') === 'all' ? 'bg-red-50 text-red-600' : 'bg-gray-100 text-gray-500 group-hover:bg-red-50 group-hover:text-red-600' }} transition-colors">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-semibold text-gray-500 truncate">All Opportunities</p>
                        <h3 class="text-lg sm:text-2xl font-black text-gray-900 mt-0.5">{{ $allOpportunitiesCount ?? $jobs->total() }}</h3>
                    </div>
                </a>

                <!-- Open -->
                <a href="{{ route('jobs.index', array_merge(request()->except(['page']), ['filter' => 'open'])) }}" 
                   class="metric-card-link bg-white rounded-xl sm:rounded-2xl p-3 sm:p-5 border transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2.5 sm:gap-4 relative overflow-hidden group focus:outline-none focus:ring-0 active:outline-none select-none {{ request('filter') === 'open' ? 'border-emerald-300 bg-emerald-50/20 shadow-sm' : 'border-gray-200/80 hover:border-gray-300 shadow-sm' }}">
                    <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <span class="w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 rounded-full bg-emerald-500"></span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-semibold text-gray-500 truncate">Open Jobs</p>
                        <h3 class="text-lg sm:text-2xl font-black text-gray-900 mt-0.5">{{ $openCount ?? $jobs->total() }}</h3>
                    </div>
                </a>

                <!-- Applied -->
                <a href="{{ route('jobs.index', array_merge(request()->except(['page']), ['filter' => 'applied'])) }}" 
                   class="metric-card-link bg-white rounded-xl sm:rounded-2xl p-3 sm:p-5 border transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2.5 sm:gap-4 relative overflow-hidden group focus:outline-none focus:ring-0 active:outline-none select-none {{ request('filter') === 'applied' ? 'border-blue-300 bg-blue-50/20 shadow-sm' : 'border-gray-200/80 hover:border-gray-300 shadow-sm' }}">
                    <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm sm:text-lg shrink-0">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-semibold text-gray-500 truncate">Applied Jobs</p>
                        <h3 class="text-lg sm:text-2xl font-black text-gray-900 mt-0.5">{{ $appliedCount ?? 0 }}</h3>
                    </div>
                </a>

                <!-- Saved -->
                <a href="{{ route('jobs.index', array_merge(request()->except(['page']), ['filter' => 'saved'])) }}" 
                   class="metric-card-link bg-white rounded-xl sm:rounded-2xl p-3 sm:p-5 border transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2.5 sm:gap-4 relative overflow-hidden group focus:outline-none focus:ring-0 active:outline-none select-none {{ request('filter') === 'saved' ? 'border-red-300 bg-red-50/20 shadow-sm' : 'border-gray-200/80 hover:border-gray-300 shadow-sm' }}">
                    <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-sm sm:text-lg shrink-0">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-semibold text-gray-500 truncate">Saved Jobs</p>
                        <h3 class="text-lg sm:text-2xl font-black text-gray-900 mt-0.5">{{ $savedCount ?? 0 }}</h3>
                    </div>
                </a>
            </div>

            @php
                $dateLabels = [
                    '' => 'All Dates',
                    'today' => 'Today',
                    'this_week' => 'This Week',
                    'this_month' => 'This Month'
                ];
                $selectedDateLabel = $dateLabels[request('date', '')] ?? 'All Dates';

                $selectedCategoryName = 'All Job Roles';
                if (request('category')) {
                    $foundCategory = $categories->firstWhere('id', request('category'));
                    if ($foundCategory) {
                        $selectedCategoryName = $foundCategory->name;
                    }
                }
            @endphp

            <!-- Consolidated Horizontal Search & Filter Bar -->
            <form id="jobsFilterForm" action="{{ route('jobs.index') }}" method="GET" class="bg-white rounded-2xl p-2.5 sm:p-3 border border-gray-200/80 shadow-sm mb-8">
                @if(request('filter'))
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif
                @if(request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif
                <div class="flex flex-col lg:flex-row items-center gap-2.5">
                    <!-- Search Input -->
                    <div class="flex items-center gap-2.5 h-10 px-3.5 bg-gray-50/70 hover:bg-gray-50 focus-within:bg-white border border-gray-200 focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/20 rounded-xl transition-all w-full lg:flex-1">
                        <i class="fas fa-search text-gray-400 text-xs shrink-0 pointer-events-none"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search job title, event name, or keyword..." 
                               class="w-full bg-transparent text-xs text-gray-900 placeholder-gray-400 focus:outline-none border-0 p-0 leading-none">
                        @if(request('search'))
                            <a href="{{ route('jobs.index', request()->except('search')) }}" class="text-gray-400 hover:text-gray-600 text-xs shrink-0" title="Clear search">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Filter Dropdowns Row -->
                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-auto">
                        <!-- Location Dropdown (Searchable, max 4 items scrollable) -->
                        <div class="relative custom-search-dropdown w-full sm:w-auto">
                            <input type="hidden" name="city" value="{{ request('city') }}" class="dropdown-hidden-input">
                            
                            <button type="button" 
                                    class="dropdown-trigger h-10 px-3.5 bg-white hover:bg-gray-50 border {{ request('city') ? 'border-red-300 text-red-600 bg-red-50/30' : 'border-gray-200 text-gray-700' }} rounded-xl text-xs font-semibold flex items-center justify-between gap-2.5 transition-all shadow-sm focus:outline-none cursor-pointer w-full sm:w-auto sm:min-w-[145px]">
                                <span class="flex items-center gap-2 truncate">
                                    <i class="fas fa-map-marker-alt {{ request('city') ? 'text-red-500' : 'text-gray-400' }} text-xs shrink-0"></i>
                                    <span class="truncate dropdown-label">{{ request('city') ?: 'All Locations' }}</span>
                                </span>
                                <i class="fas fa-chevron-down text-gray-400 text-[10px] shrink-0 chevron-icon transition-transform duration-200"></i>
                            </button>

                            <div class="dropdown-menu hidden absolute left-0 top-full mt-1.5 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                                <div class="p-2 border-b border-gray-100 bg-gray-50/70">
                                    <div class="flex items-center gap-2 px-2.5 py-1.5 bg-white border border-gray-200 rounded-lg focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/20 transition-all">
                                        <i class="fas fa-search text-gray-400 text-xs shrink-0"></i>
                                        <input type="text" 
                                               placeholder="Cari lokasi..." 
                                               class="dropdown-search-input w-full bg-transparent text-xs text-gray-800 placeholder-gray-400 focus:outline-none border-0 p-0 leading-none">
                                    </div>
                                </div>
                                <div class="dropdown-options-list p-1 custom-scrollbar space-y-0.5" style="max-height: 130px; overflow-y: auto;">
                                    <button type="button" 
                                            data-value="" 
                                            data-search="all locations semua lokasi"
                                            class="dropdown-item w-full text-left px-2.5 py-1.5 text-xs rounded-lg flex items-center justify-between {{ !request('city') ? 'bg-red-50 text-red-600 font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }} transition-colors cursor-pointer">
                                        <span>All Locations</span>
                                        @if(!request('city'))
                                            <i class="fas fa-check text-[10px] text-red-600"></i>
                                        @endif
                                    </button>

                                    @foreach($cities as $city)
                                    <button type="button" 
                                            data-value="{{ $city->name }}" 
                                            data-search="{{ $city->name }}"
                                            class="dropdown-item w-full text-left px-2.5 py-1.5 text-xs rounded-lg flex items-center justify-between {{ request('city') == $city->name ? 'bg-red-50 text-red-600 font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }} transition-colors cursor-pointer">
                                        <span class="truncate">{{ $city->name }}</span>
                                        @if(request('city') == $city->name)
                                            <i class="fas fa-check text-[10px] text-red-600"></i>
                                        @endif
                                    </button>
                                    @endforeach

                                    <div class="dropdown-empty-msg hidden px-3 py-3 text-center text-xs text-gray-400">
                                        Lokasi tidak ditemukan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date Dropdown (Searchable, max 4 items scrollable) -->
                        <div class="relative custom-search-dropdown w-full sm:w-auto">
                            <input type="hidden" name="date" value="{{ request('date') }}" class="dropdown-hidden-input">
                            
                            <button type="button" 
                                    class="dropdown-trigger h-10 px-3.5 bg-white hover:bg-gray-50 border {{ request('date') ? 'border-red-300 text-red-600 bg-red-50/30' : 'border-gray-200 text-gray-700' }} rounded-xl text-xs font-semibold flex items-center justify-between gap-2.5 transition-all shadow-sm focus:outline-none cursor-pointer w-full sm:w-auto sm:min-w-[130px]">
                                <span class="flex items-center gap-2 truncate">
                                    <i class="fas fa-calendar-alt {{ request('date') ? 'text-red-500' : 'text-gray-400' }} text-xs shrink-0"></i>
                                    <span class="truncate dropdown-label">{{ $selectedDateLabel }}</span>
                                </span>
                                <i class="fas fa-chevron-down text-gray-400 text-[10px] shrink-0 chevron-icon transition-transform duration-200"></i>
                            </button>

                            <div class="dropdown-menu hidden absolute left-0 top-full mt-1.5 w-52 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                                <div class="p-2 border-b border-gray-100 bg-gray-50/70">
                                    <div class="flex items-center gap-2 px-2.5 py-1.5 bg-white border border-gray-200 rounded-lg focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/20 transition-all">
                                        <i class="fas fa-search text-gray-400 text-xs shrink-0"></i>
                                        <input type="text" 
                                               placeholder="Cari waktu..." 
                                               class="dropdown-search-input w-full bg-transparent text-xs text-gray-800 placeholder-gray-400 focus:outline-none border-0 p-0 leading-none">
                                    </div>
                                </div>
                                <div class="dropdown-options-list p-1 custom-scrollbar space-y-0.5" style="max-height: 130px; overflow-y: auto;">
                                    @foreach($dateLabels as $dateVal => $dateText)
                                    <button type="button" 
                                            data-value="{{ $dateVal }}" 
                                            data-search="{{ $dateText }}"
                                            class="dropdown-item w-full text-left px-2.5 py-1.5 text-xs rounded-lg flex items-center justify-between {{ request('date') == $dateVal ? 'bg-red-50 text-red-600 font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }} transition-colors cursor-pointer">
                                        <span>{{ $dateText }}</span>
                                        @if(request('date') == $dateVal)
                                            <i class="fas fa-check text-[10px] text-red-600"></i>
                                        @endif
                                    </button>
                                    @endforeach

                                    <div class="dropdown-empty-msg hidden px-3 py-3 text-center text-xs text-gray-400">
                                        Waktu tidak ditemukan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Job Roles Dropdown (Searchable, max 4 items scrollable) -->
                        <div class="relative custom-search-dropdown w-full sm:w-auto">
                            <input type="hidden" name="category" value="{{ request('category') }}" class="dropdown-hidden-input">
                            
                            <button type="button" 
                                    class="dropdown-trigger h-10 px-3.5 bg-white hover:bg-gray-50 border {{ request('category') ? 'border-red-300 text-red-600 bg-red-50/30' : 'border-gray-200 text-gray-700' }} rounded-xl text-xs font-semibold flex items-center justify-between gap-2.5 transition-all shadow-sm focus:outline-none cursor-pointer w-full sm:w-auto sm:min-w-[145px]">
                                <span class="flex items-center gap-2 truncate">
                                    <i class="fas fa-briefcase {{ request('category') ? 'text-red-500' : 'text-gray-400' }} text-xs shrink-0"></i>
                                    <span class="truncate dropdown-label">{{ $selectedCategoryName }}</span>
                                </span>
                                <i class="fas fa-chevron-down text-gray-400 text-[10px] shrink-0 chevron-icon transition-transform duration-200"></i>
                            </button>

                            <div class="dropdown-menu hidden absolute sm:right-0 sm:left-auto left-0 top-full mt-1.5 w-60 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                                <div class="p-2 border-b border-gray-100 bg-gray-50/70">
                                    <div class="flex items-center gap-2 px-2.5 py-1.5 bg-white border border-gray-200 rounded-lg focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/20 transition-all">
                                        <i class="fas fa-search text-gray-400 text-xs shrink-0"></i>
                                        <input type="text" 
                                               placeholder="Cari peran..." 
                                               class="dropdown-search-input w-full bg-transparent text-xs text-gray-800 placeholder-gray-400 focus:outline-none border-0 p-0 leading-none">
                                    </div>
                                </div>
                                <div class="dropdown-options-list p-1 custom-scrollbar space-y-0.5" style="max-height: 130px; overflow-y: auto;">
                                    <button type="button" 
                                            data-value="" 
                                            data-search="all job roles semua posisi peran kategori"
                                            class="dropdown-item w-full text-left px-2.5 py-1.5 text-xs rounded-lg flex items-center justify-between {{ !request('category') ? 'bg-red-50 text-red-600 font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }} transition-colors cursor-pointer">
                                        <span>All Job Roles</span>
                                        @if(!request('category'))
                                            <i class="fas fa-check text-[10px] text-red-600"></i>
                                        @endif
                                    </button>

                                    @foreach($categories as $category)
                                    <button type="button" 
                                            data-value="{{ $category->id }}" 
                                            data-search="{{ $category->name }}"
                                            class="dropdown-item w-full text-left px-2.5 py-1.5 text-xs rounded-lg flex items-center justify-between {{ request('category') == $category->id ? 'bg-red-50 text-red-600 font-bold' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }} transition-colors cursor-pointer">
                                        <span class="truncate">{{ $category->name }}</span>
                                        @if(request('category') == $category->id)
                                            <i class="fas fa-check text-[10px] text-red-600"></i>
                                        @endif
                                    </button>
                                    @endforeach

                                    <div class="dropdown-empty-msg hidden px-3 py-3 text-center text-xs text-gray-400">
                                        Posisi tidak ditemukan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Clear / Reset Button -->
                        <a href="{{ route('jobs.index') }}" 
                           class="h-10 px-3.5 inline-flex items-center justify-center gap-1.5 bg-gray-100 hover:bg-red-50 text-gray-600 hover:text-red-600 border border-gray-200 hover:border-red-200 font-semibold text-xs rounded-xl transition-all shrink-0 shadow-sm"
                           title="Clear all filters">
                            <i class="fas fa-undo text-[10px]"></i>
                            <span>Clear</span>
                        </a>
                    </div>
                </div>
            </form>

            <!-- 3-Column Job Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @forelse($jobs as $job)
                @php
                    $categoryName = strtolower($job->jobCategory->name ?? '');
                    $jobTitle = strtolower($job->title ?? '');
                    if (str_contains($categoryName, 'sport') || str_contains($jobTitle, 'atlet') || str_contains($jobTitle, 'athlet')) {
                        $iconClass = 'fas fa-running';
                        $iconBg = 'bg-red-50 text-red-500';
                    } elseif (str_contains($categoryName, 'swim') || str_contains($jobTitle, 'swim') || str_contains($jobTitle, 'renang')) {
                        $iconClass = 'fas fa-swimmer';
                        $iconBg = 'bg-blue-50 text-blue-500';
                    } elseif (str_contains($categoryName, 'media') || str_contains($jobTitle, 'media') || str_contains($jobTitle, 'game') || str_contains($jobTitle, 'esport')) {
                        $iconClass = 'fas fa-gamepad';
                        $iconBg = 'bg-red-50 text-red-500';
                    } elseif (str_contains($categoryName, 'operation') || str_contains($jobTitle, 'operat') || str_contains($jobTitle, 'volunteer')) {
                        $iconClass = 'fas fa-users';
                        $iconBg = 'bg-red-50 text-red-500';
                    } elseif (str_contains($jobTitle, 'ketua') || str_contains($jobTitle, 'lead') || str_contains($jobTitle, 'manager')) {
                        $iconClass = 'fas fa-trophy';
                        $iconBg = 'bg-red-50 text-red-500';
                    } else {
                        $iconClass = 'fas fa-briefcase';
                        $iconBg = 'bg-red-50 text-red-500';
                    }

                    $isSaved = in_array($job->id, $savedJobIds ?? []);
                    $isApplied = in_array($job->id, $appliedJobIds ?? []);

                    // Date formatting
                    if ($job->event && $job->event->start_at) {
                        if ($job->event->end_at) {
                            if ($job->event->start_at->format('M Y') === $job->event->end_at->format('M Y')) {
                                $dateStr = $job->event->start_at->format('d') . ' - ' . $job->event->end_at->format('d M Y');
                            } else {
                                $dateStr = $job->event->start_at->format('d M') . ' - ' . $job->event->end_at->format('d M Y');
                            }
                        } else {
                            $dateStr = $job->event->start_at->format('d M Y');
                        }
                    } else {
                        $dateStr = $job->application_deadline->format('d M Y');
                    }
                @endphp
                <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-gray-200/80 shadow-sm hover:shadow-md hover:border-red-200 transition-all duration-300 flex flex-col justify-between group relative">
                    <!-- Top Part: Icon, Tag & Title, Bookmark -->
                    <div>
                        <div class="flex items-start gap-3 sm:gap-3.5 mb-3.5 sm:mb-4">
                            <!-- Icon Box -->
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl {{ $iconBg }} flex items-center justify-center text-lg sm:text-xl shrink-0 group-hover:scale-105 transition-transform duration-300 shadow-sm">
                                <i class="{{ $iconClass }}"></i>
                            </div>

                            <!-- Tag & Title -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5 mb-1 sm:mb-1.5 flex-wrap">
                                    <span class="inline-block text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-red-600 bg-red-50 px-2 py-0.5 rounded-md border border-red-100 truncate max-w-full">
                                        {{ $job->event->title ?? 'EVENT' }}
                                    </span>
                                    @if($job->jobCategory)
                                    <span class="inline-block text-[9px] sm:text-[10px] font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md truncate max-w-full">
                                        {{ $job->jobCategory->name }}
                                    </span>
                                    @endif
                                </div>
                                <h3 class="text-sm sm:text-base font-bold text-gray-900 group-hover:text-red-600 transition-colors truncate">
                                    {{ $job->title }}
                                </h3>
                            </div>

                            <!-- Bookmark Button -->
                            <button onclick="event.preventDefault(); toggleSaveJob({{ $job->id }})" id="saveBtn-{{ $job->id }}" 
                                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl border flex items-center justify-center transition-all shrink-0 {{ $isSaved ? 'bg-red-50 border-red-200 text-red-600 shadow-sm' : 'bg-white border-gray-200 text-gray-400 hover:border-red-200 hover:bg-red-50 hover:text-red-600' }}"
                                    title="{{ $isSaved ? 'Hapus dari simpanan' : 'Simpan lowongan' }}">
                                <i class="{{ $isSaved ? 'fas' : 'far' }} fa-bookmark text-xs sm:text-sm"></i>
                            </button>
                        </div>

                        <!-- Meta Info Row (Location, Date, Slots) as Clean Pills -->
                        <div class="py-2.5 sm:py-3 border-t border-gray-100 flex flex-wrap items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs">
                            <span class="inline-flex items-center gap-1.5 px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-md sm:rounded-lg bg-gray-50 border border-gray-100 font-medium text-gray-600" title="{{ $job->event->city->name ?? 'Jakarta' }}">
                                <i class="fas fa-map-marker-alt text-red-500 text-[10px] sm:text-[11px] shrink-0"></i>
                                <span class="truncate">{{ $job->event->city->name ?? 'Jakarta' }}</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-md sm:rounded-lg bg-gray-50 border border-gray-100 font-medium text-gray-600" title="{{ $dateStr }}">
                                <i class="fas fa-calendar-alt text-red-500 text-[10px] sm:text-[11px] shrink-0"></i>
                                <span>{{ $dateStr }}</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-md sm:rounded-lg bg-gray-50 border border-gray-100 font-medium text-gray-600" title="{{ $job->slots_filled }}/{{ $job->slots_total }} Slots">
                                <i class="fas fa-users text-red-500 text-[10px] sm:text-[11px] shrink-0"></i>
                                <span>{{ $job->slots_filled }}/{{ $job->slots_total }} Slots</span>
                            </span>
                        </div>
                    </div>

                    <!-- Bottom Part: Status Badge & View details -->
                    <div class="flex items-center justify-between pt-3 sm:pt-3.5 mt-1 border-t border-gray-100">
                        @if($isApplied)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 shadow-sm">
                                <i class="fas fa-clock text-blue-500 text-[10px]"></i> Applied
                            </span>
                        @elseif($job->application_deadline->isPast())
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Closed
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-bold bg-green-50 text-green-700 border border-green-200 shadow-sm">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-500"></span>
                                </span>
                                <span>Open</span>
                            </span>
                        @endif

                        <a href="{{ route('jobs.show', $job) }}" 
                           class="inline-flex items-center gap-1.5 text-[11px] sm:text-xs font-bold text-red-600 hover:text-white bg-red-50 hover:bg-red-600 border border-red-200 rounded-lg sm:rounded-xl px-3 sm:px-4 py-1.5 sm:py-2 transition-all duration-200 shadow-sm group/btn">
                            <span>View details</span>
                            <i class="fas fa-arrow-right text-[9px] sm:text-[10px] group-hover/btn:translate-x-0.5 transition-transform"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white rounded-2xl p-12 text-center border-2 border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fas fa-search text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">No jobs found</h3>
                    <p class="text-sm text-gray-500 mt-2">Try adjusting your search or filters.</p>
                    <a href="{{ route('jobs.index') }}" class="inline-block mt-4 text-sm font-semibold text-red-600 hover:text-red-700">Clear all filters</a>
                </div>
                @endforelse
            </div>

            <!-- Bottom Pagination & Per Page Selector -->
            <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 mt-8">
                <!-- Left Showing Count -->
                <div class="text-xs sm:text-sm text-gray-500 font-medium">
                    Showing <span class="font-bold text-gray-800">{{ $jobs->firstItem() ?? 0 }}</span> – <span class="font-bold text-gray-800">{{ $jobs->lastItem() ?? 0 }}</span> of <span class="font-bold text-gray-800">{{ $jobs->total() }}</span> opportunities
                </div>

                <!-- Center Pagination Buttons -->
                @if($jobs->hasPages())
                <div class="flex items-center gap-1.5">
                    {{-- Previous Page Link --}}
                    @if ($jobs->onFirstPage())
                        <span class="w-8 h-8 rounded-xl border border-gray-200 bg-gray-50 text-gray-300 flex items-center justify-center text-xs cursor-not-allowed">
                            <i class="fas fa-arrow-left"></i>
                        </span>
                    @else
                        <a href="{{ $jobs->previousPageUrl() }}" class="w-8 h-8 rounded-xl border border-gray-200 bg-white text-gray-600 hover:border-red-400 hover:text-red-600 flex items-center justify-center text-xs transition-colors">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    @endif

                    {{-- Numbered Page Links --}}
                    @foreach ($jobs->getUrlRange(max(1, $jobs->currentPage() - 2), min($jobs->lastPage(), $jobs->currentPage() + 2)) as $page => $url)
                        @if ($page == $jobs->currentPage())
                            <span class="w-8 h-8 rounded-xl bg-red-600 text-white font-bold flex items-center justify-center text-xs shadow-sm shadow-red-500/30">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 rounded-xl border border-gray-200 bg-white text-gray-700 hover:border-red-400 hover:text-red-600 font-semibold flex items-center justify-center text-xs transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($jobs->hasMorePages())
                        <a href="{{ $jobs->nextPageUrl() }}" class="w-8 h-8 rounded-xl border border-gray-200 bg-white text-gray-600 hover:border-red-400 hover:text-red-600 flex items-center justify-center text-xs transition-colors">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    @else
                        <span class="w-8 h-8 rounded-xl border border-gray-200 bg-gray-50 text-gray-300 flex items-center justify-center text-xs cursor-not-allowed">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    @endif
                </div>
                @endif

                <!-- Right Per Page Selector -->
                <form method="GET" action="{{ route('jobs.index') }}" class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                    @foreach(request()->except(['per_page', 'page']) as $k => $v)
                        @if(is_array($v))
                            @foreach($v as $val)<input type="hidden" name="{{ $k }}[]" value="{{ $val }}">@endforeach
                        @else
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endif
                    @endforeach
                    <span>Show</span>
                    <select name="per_page" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-lg px-2.5 py-1 text-xs font-bold text-gray-800 focus:outline-none focus:border-red-500 cursor-pointer">
                        <option value="6" {{ request('per_page', 6) == 6 ? 'selected' : '' }}>6</option>
                        <option value="9" {{ request('per_page', 6) == 9 ? 'selected' : '' }}>9</option>
                        <option value="12" {{ request('per_page', 6) == 12 ? 'selected' : '' }}>12</option>
                        <option value="24" {{ request('per_page', 6) == 24 ? 'selected' : '' }}>24</option>
                    </select>
                    <span>per page</span>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Metric Cards - Eliminate black focus ring and tap highlight */
    .metric-card-link,
    .metric-card-link:focus,
    .metric-card-link:active,
    .metric-card-link:focus-visible {
        outline: none !important;
        -webkit-tap-highlight-color: transparent !important;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>

<!-- Quick Apply Modal -->
<div id="quickApplyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 relative shadow-2xl">
        <button onclick="hideQuickApplyModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>

        <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Application</h3>
        <p class="text-gray-600 mb-6">Submit your application for this position.</p>

        <form id="quickApplyForm" onsubmit="event.preventDefault(); submitQuickApplication();">
            <div class="space-y-4 mb-6">
                <div>
                    <label for="quickMotivation" class="block text-sm font-medium text-gray-700 mb-2">
                        Why are you interested? *
                    </label>
                    <textarea id="quickMotivation" name="motivation" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Briefly explain your interest..."></textarea>
                </div>

                <div>
                    <label for="quickExperience" class="block text-sm font-medium text-gray-700 mb-2">
                        Relevant Experience
                    </label>
                    <textarea id="quickExperience" name="experience" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Optional: Describe your experience..."></textarea>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="hideQuickApplyModal()" 
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                    Submit Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentJobId = null;

    function quickApply(jobId) {
        currentJobId = jobId;
        document.getElementById('quickApplyModal').classList.remove('hidden');
        document.getElementById('quickApplyModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function hideQuickApplyModal() {
        document.getElementById('quickApplyModal').classList.add('hidden');
        document.getElementById('quickApplyModal').classList.remove('flex');
        document.body.style.overflow = '';
        currentJobId = null;
    }

    function submitQuickApplication() {
        if (!currentJobId) return;

        const motivation = document.getElementById('quickMotivation').value;
        const experience = document.getElementById('quickExperience').value;

        const submitButton = document.querySelector('#quickApplyForm button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...';

        fetch(`/jobs/${currentJobId}/apply`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                motivation: motivation,
                experience: experience
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Application submitted successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to submit application'));
                submitButton.disabled = false;
                submitButton.textContent = 'Submit Application';
            }
            hideQuickApplyModal();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            submitButton.disabled = false;
            submitButton.textContent = 'Submit Application';
            hideQuickApplyModal();
        });
    }

    function toggleSaveJob(jobId) {
        const button = document.getElementById(`saveBtn-${jobId}`);
        const icon = button.querySelector('i');
        const isSaved = icon.classList.contains('fas'); // check current state
        
        // Prevent multiple clicks
        if(button.disabled) return;
        button.disabled = true;

        const url = isSaved 
            ? `/dashboard/jobs/${jobId}/unsave` 
            : `/dashboard/jobs/${jobId}/save`;
        
        const method = isSaved ? 'DELETE' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    window.location.href = '{{ route("login") }}';
                    throw new Error('Unauthorized');
                }
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (isSaved) {
                    // Was saved, now unsaved
                    icon.classList.remove('fas', 'text-red-600');
                    icon.classList.add('far');
                    button.classList.remove('bg-red-50', 'text-red-600');
                    button.classList.add('bg-gray-50', 'text-gray-400');
                } else {
                    // Was unsaved, now saved
                    icon.classList.remove('far');
                    icon.classList.add('fas', 'text-red-600');
                    button.classList.remove('bg-gray-50', 'text-gray-400');
                    button.classList.add('bg-red-50', 'text-red-600');
                }
            } else {
                alert('Action failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if(error.message !== 'Unauthorized') {
               // alert('An error occurred.'); // Optional: suppress to avoid annoyance
            }
        })
        .finally(() => {
            button.disabled = false;
        });
    }

    // Close modal on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideQuickApplyModal();
        }
    });

    // Close modal on outside click
    document.getElementById('quickApplyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideQuickApplyModal();
        }
    });

    // Custom Searchable Dropdowns Handling
    document.addEventListener('DOMContentLoaded', function () {
        const dropdowns = document.querySelectorAll('.custom-search-dropdown');

        dropdowns.forEach(dropdown => {
            const trigger = dropdown.querySelector('.dropdown-trigger');
            const menu = dropdown.querySelector('.dropdown-menu');
            const searchInput = dropdown.querySelector('.dropdown-search-input');
            const optionsContainer = dropdown.querySelector('.dropdown-options-list');
            const hiddenInput = dropdown.querySelector('.dropdown-hidden-input');
            const chevron = trigger ? trigger.querySelector('.chevron-icon') : null;
            const emptyMsg = dropdown.querySelector('.dropdown-empty-msg');
            const form = document.getElementById('jobsFilterForm') || dropdown.closest('form');

            if (!trigger || !menu) return;

            // Toggle open / close
            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                const isCurrentlyOpen = !menu.classList.contains('hidden');

                // Close all others first
                closeAllDropdowns();

                if (!isCurrentlyOpen) {
                    menu.classList.remove('hidden');
                    if (chevron) chevron.classList.add('rotate-180');
                    if (searchInput) {
                        searchInput.value = '';
                        filterOptions('');
                        setTimeout(() => searchInput.focus(), 60);
                    }
                }
            });

            // Prevent dropdown menu click from bubbling to document (except options)
            menu.addEventListener('click', function (e) {
                e.stopPropagation();
            });

            // Live search filter inside dropdown
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    filterOptions(this.value.trim().toLowerCase());
                });
            }

            function filterOptions(query) {
                if (!optionsContainer) return;
                const options = optionsContainer.querySelectorAll('.dropdown-item');
                let matchCount = 0;
                options.forEach(opt => {
                    const searchData = (opt.getAttribute('data-search') || opt.textContent).toLowerCase();
                    if (!query || searchData.includes(query)) {
                        opt.classList.remove('hidden');
                        matchCount++;
                    } else {
                        opt.classList.add('hidden');
                    }
                });

                if (emptyMsg) {
                    if (matchCount === 0) {
                        emptyMsg.classList.remove('hidden');
                    } else {
                        emptyMsg.classList.add('hidden');
                    }
                }
            }

            // Option item selection
            if (optionsContainer) {
                optionsContainer.querySelectorAll('.dropdown-item').forEach(item => {
                    item.addEventListener('click', function (e) {
                        e.stopPropagation();
                        const val = this.getAttribute('data-value') || '';
                        if (hiddenInput) {
                            hiddenInput.value = val;
                        }
                        closeAllDropdowns();
                        if (form) {
                            form.submit();
                        }
                    });
                });
            }
        });

        function closeAllDropdowns() {
            dropdowns.forEach(d => {
                const menu = d.querySelector('.dropdown-menu');
                const chevron = d.querySelector('.chevron-icon');
                if (menu) menu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            });
        }

        // Close when clicking anywhere outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.custom-search-dropdown')) {
                closeAllDropdowns();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAllDropdowns();
            }
        });
    });
</script>
@endsection