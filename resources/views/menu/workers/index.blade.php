@extends('layouts.app')

@section('title', 'Worker Job Openings - NOCIS')
@section('page-title')
    Workers <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header with Add Button --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manage Recruitments</h2>
            <p class="text-gray-600 mt-1">Oversee job openings and staffing requirements</p>
        </div>
        <a href="{{ route('admin.workers.create') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Create Job Opening
        </a>
    </div>

    {{-- Search Bar --}}
    <div class="flex items-center justify-between">
        <form action="{{ route('admin.workers.index') }}" method="GET" id="search-form" class="relative flex items-center border border-gray-300 rounded-lg py-2 px-4 pl-10 bg-white">
            <i class="fas fa-search absolute left-3 text-gray-400"></i>
            <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Search job openings..." class="focus:outline-none w-64 ml-2">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
        </form>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Job Openings</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_openings'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Active Openings</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['active_openings'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Applications</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_applications'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Positions Filled</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['positions_filled'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Recruitment Status Filter Tabs --}}
    <div class="bg-white rounded-lg shadow-sm">
        <div class="relative">
            {{-- Desktop tabs (hidden on mobile) --}}
            <nav class="hidden md:flex space-x-8 px-6" aria-label="Tabs">
                <a href="{{ route('admin.workers.index', ['status' => 'active']) }}"
                   class="{{ ($statusFilter ?? 'active') === 'active' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-play-circle mr-2"></i>Active Recruitments
                    @if($stats['active_openings'] > 0)
                        <span class="ml-2 bg-red-100 text-red-600 py-0.5 px-2.5 rounded-full text-xs">{{ $stats['active_openings'] }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.workers.index', ['status' => 'closed']) }}"
                   class="{{ ($statusFilter ?? '') === 'closed' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-times-circle mr-2"></i>Closed Recruitments
                    @if(($stats['closed_openings'] ?? 0) > 0)
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">{{ $stats['closed_openings'] ?? 0 }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.workers.index', ['status' => 'all']) }}"
                   class="{{ ($statusFilter ?? 'all') === 'all' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-list mr-2"></i>All Openings
                    @if($stats['total_openings'] > 0)
                        <span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2.5 rounded-full text-xs">{{ $stats['total_openings'] }}</span>
                    @endif
                </a>
            </nav>

            {{-- Mobile tabs (horizontal scroll) --}}
            <div class="md:hidden overflow-x-auto">
                <div class="flex space-x-2 p-4"
                     id="mobile-tabs-container">
                    <a href="{{ route('admin.workers.index', ['status' => 'active']) }}"
                       class="{{ ($statusFilter ?? 'active') === 'active' ? 'bg-red-50 border-red-500 text-red-600' : 'bg-gray-50 border-gray-200 text-gray-500' }} flex-shrink-0 scroll-snap-start flex flex-col items-center justify-center py-3 px-4 rounded-lg border-2 min-w-[120px] transition-all duration-200 hover:shadow-md"
                       style="scroll-snap-align: start;">
                        <i class="fas fa-play-circle mb-1"></i>
                        <span class="text-xs font-medium">Active</span>
                        @if($stats['active_openings'] > 0)
                            <span class="mt-1 bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs">{{ $stats['active_openings'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.workers.index', ['status' => 'closed']) }}"
                       class="{{ ($statusFilter ?? '') === 'closed' ? 'bg-red-50 border-red-500 text-red-600' : 'bg-gray-50 border-gray-200 text-gray-500' }} flex-shrink-0 scroll-snap-start flex flex-col items-center justify-center py-3 px-4 rounded-lg border-2 min-w-[120px] transition-all duration-200 hover:shadow-md"
                       style="scroll-snap-align: start;">
                        <i class="fas fa-times-circle mb-1"></i>
                        <span class="text-xs font-medium">Closed</span>
                        @if(($stats['closed_openings'] ?? 0) > 0)
                            <span class="mt-1 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $stats['closed_openings'] ?? 0 }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.workers.index', ['status' => 'all']) }}"
                       class="{{ ($statusFilter ?? 'all') === 'all' ? 'bg-red-50 border-red-500 text-red-600' : 'bg-gray-50 border-gray-200 text-gray-500' }} flex-shrink-0 scroll-snap-start flex flex-col items-center justify-center py-3 px-4 rounded-lg border-2 min-w-[120px] transition-all duration-200 hover:shadow-md"
                       style="scroll-snap-align: start;">
                        <i class="fas fa-list mb-1"></i>
                        <span class="text-xs font-medium">All</span>
                        @if($stats['total_openings'] > 0)
                            <span class="mt-1 bg-blue-100 text-blue-600 py-0.5 px-2 rounded-full text-xs">{{ $stats['total_openings'] }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Job Openings List --}}
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">
                @if(($statusFilter ?? 'active') === 'active')
                    Active Job Openings
                @elseif(($statusFilter ?? '') === 'closed')
                    Closed Job Openings
                @else
                    All Job Openings
                @endif
                <span class="text-sm text-gray-500 ml-2">({{ $openings->count() }} openings)</span>
            </h2>

            @if($openings->count() > 0)
                <div class="space-y-4">
                    @foreach($openings as $opening)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-all duration-200">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $opening->title }}</h3>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            @if($opening->status === 'open')
                                                bg-green-100 text-green-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif
                                        ">
                                            {{ ucfirst($opening->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 mb-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-building mr-2 text-gray-400"></i>
                                            <span>{{ $opening->event->title ?? 'No Event' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-tag mr-2 text-gray-400"></i>
                                            <span>{{ $opening->jobCategory->name ?? 'No Category' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-users mr-2 text-gray-400"></i>
                                            <span>{{ $opening->slots_filled }}/{{ $opening->slots_total }} filled</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i>
                                            <span>Deadline: {{ \Carbon\Carbon::parse($opening->application_deadline)->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-file-alt mr-1"></i>
                                            <span>{{ $opening->applications_count }} applications</span>
                                        </div>
                                    </div>

                                    @if($opening->description)
                                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($opening->description, 150) }}</p>
                                    @endif
                                </div>

                                <div class="flex lg:flex-col gap-2 mt-4 lg:mt-0 lg:ml-6">
                                    <a href="{{ route('admin.workers.show', $opening) }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm text-center">View Applications</a>
                                    <a href="{{ route('admin.workers.edit', $opening) }}" class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm text-center">Edit</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-briefcase text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-500 mb-2">No job openings found</h3>
                    <p class="text-gray-400">
                        @if(($statusFilter ?? 'active') === 'active')
                            There are no active job openings at the moment.
                        @elseif(($statusFilter ?? '') === 'closed')
                            There are no closed job openings.
                        @else
                            No job openings have been created yet.
                        @endif
                    </p>
                    <a href="{{ route('admin.workers.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Create First Job Opening
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

</script>
<script>
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
                window.location.href = '{{ route("admin.workers.index") }}';
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

    // Search Results Toast Functionality
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
                        Found <strong>${resultsCount}</strong> result${resultsCount !== 1 ? 's' : ''} for "<strong>${query}</strong>"
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

    // Helper to clear search
    function clearSearch() {
        document.getElementById('search-input').value = '';
        window.location.href = '{{ route("admin.workers.index") }}';
    }

    // Show search results toast on page load if there's a search query
    document.addEventListener('DOMContentLoaded', function() {
        const searchQuery = '{{ request('search') }}';
        const resultsCount = {{ $openings->count() }};
        
        if (searchQuery && searchQuery.length > 0) {
            // Small delay to ensure page is fully loaded
            setTimeout(() => {
                showSearchToast(resultsCount, searchQuery);
            }, 300);
        }
    });
</script>
@endsection
