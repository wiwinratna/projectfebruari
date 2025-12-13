@extends('layouts.app')

@section('title', 'Committee Registration Review - NOCIS')
@section('page-title')
    Committee Registration Review
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manage Reviews</h2>
            <p class="text-gray-600 mt-1">Review and approve committee applications</p>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="flex items-center justify-between mb-6">
        <form method="GET" action="{{ route('admin.reviews.index') }}" id="search-form" class="relative flex items-center border border-gray-300 rounded-lg py-2 px-4 pl-10 bg-white">
            <i class="fas fa-search absolute left-3 text-gray-400"></i>
            <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Search Committee..." class="focus:outline-none w-64 ml-2">
        </form>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        {{-- Total Applicants --}}
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Applicants</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_applicants'] ?? 0 }}</p>
        </div>

        {{-- Pending Review --}}
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Pending Review</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_review'] ?? 0 }}</p>
        </div>

        {{-- Approved Members --}}
        <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between">
            <div>
                <h3 class="text-gray-500 text-sm font-semibold mb-2">Approved Members</h3>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['approved_members'] ?? 0 }}</p>
            </div>
            <i class="fas fa-check-circle text-blue-500 text-2xl"></i>
        </div>

        {{-- Rejection Members --}}
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Rejection Members</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['rejected_members'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Applications Table with Categories --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($applications ?? [] as $application)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($application->user->profile && $application->user->profile->profile_photo)
                                    <div class="w-10 h-10 rounded-full mr-3 flex-shrink-0">
                                        <img src="{{ asset('storage/' . $application->user->profile->profile_photo) }}" alt="" 
                                             class="w-full h-full rounded-full object-cover border border-gray-200">
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3 flex-shrink-0">
                                        <span class="text-gray-500 font-bold text-sm">{{ strtoupper(substr($application->user->username, 0, 2)) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $application->user->username }}</div>
                                    <div class="text-xs text-gray-500">{{ $application->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $application->opening->title ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $application->opening->event->title ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($application->opening->jobCategory->name === 'Volunteer') bg-blue-100 text-blue-800
                                @elseif($application->opening->jobCategory->name === 'Organizer') bg-green-100 text-green-800
                                @elseif($application->opening->jobCategory->name === 'Liaison') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $application->opening->jobCategory->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $application->opening->event->title ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $application->opening->event->city->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($application->status === 'approved') bg-green-100 text-green-800
                                @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($application->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $application->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $application->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.applications.show', $application->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                @if($application->status === 'pending')
                                <a href="{{ route('admin.applications.show', $application->id) }}" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i> Review
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <div class="py-8">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>No applications found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination would go here if needed -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="text-sm text-gray-600">
                Showing {{ count($applications ?? []) }} applications
            </div>
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
                window.location.href = '{{ route("admin.reviews.index") }}';
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
        window.location.href = '{{ route("admin.reviews.index") }}';
    }

    // Show search results toast on page load if there's a search query
    document.addEventListener('DOMContentLoaded', function() {
        const searchQuery = '{{ request('search') }}';
        const resultsCount = {{ $applications->count() }};
        
        if (searchQuery && searchQuery.length > 0) {
            // Small delay to ensure page is fully loaded
            setTimeout(() => {
                showSearchToast(resultsCount, searchQuery);
            }, 300);
        }
    });
</script>
@endsection