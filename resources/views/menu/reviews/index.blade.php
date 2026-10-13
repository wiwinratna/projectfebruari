@extends('layouts.app')

@section('title', 'Committee Registration Review - ARISE')
@section('page-title')
    Committee Registration Review
@endsection

@section('content')
<div class="space-y-4">

    {{-- Flash Alerts --}}
    @if(session('status'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center justify-between shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-600 text-lg"></i>
                <span class="font-medium">{{ session('status') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800"><i class="fas fa-times"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-center justify-between shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-600 text-lg"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800"><i class="fas fa-times"></i></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Manage Reviews</h2>
            <p class="text-xs text-gray-500 mt-0.5">Review and approve committee applications</p>
        </div>
    </div>

    {{-- Search Bar and Filters --}}
    <div class="flex flex-wrap lg:flex-nowrap items-center justify-between mb-4 gap-3">
        <form method="GET" action="{{ route('admin.reviews.index') }}" id="filter-form" class="flex flex-wrap items-center gap-2">
            {{-- Search Input --}}
            <div class="relative flex items-center border border-gray-300 rounded-lg py-1.5 px-3 pl-8 bg-white shadow-xs">
                <i class="fas fa-search absolute left-2.5 text-gray-400 text-xs"></i>
                <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Search Committee..." class="focus:outline-none w-44 sm:w-52 text-xs ml-1">
            </div>

            {{-- Event Filter --}}
            <div class="relative">
                <select name="event_id" id="event-filter" class="border border-gray-300 rounded-lg py-1.5 px-3 pr-8 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none w-44 sm:w-48 text-xs shadow-xs">
                    <option value="">All Events</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                            {{ $event->title }}
                        </option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-2.5 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
            </div>

            {{-- Clear Filters Button --}}
            @if(request('search') || request('event_id'))
                <a href="{{ route('admin.reviews.index') }}" class="text-gray-600 hover:text-gray-800 px-2.5 py-1.5 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 transition text-xs shadow-xs">
                    <i class="fas fa-times mr-1"></i> Clear
                </a>
            @endif
        </form>

        <div class="flex items-center gap-2 flex-shrink-0">
            {{-- Export to Excel Button --}}
            <a href="{{ route('admin.reviews.export', ['event_id' => request('event_id'), 'search' => request('search')]) }}"
               class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg inline-flex items-center gap-1.5 transition text-xs font-semibold shadow-xs whitespace-nowrap flex-shrink-0">
                <i class="fas fa-file-excel text-xs"></i>
                <span>Export Excel</span>
            </a>

            {{-- Download Foto Terpilih (ZIP) --}}
            <button type="button" id="btn-download-selected-admin" onclick="submitBatchDownloadAdmin(false)"
                disabled
                class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-3 py-1.5 rounded-lg inline-flex items-center gap-1.5 transition text-xs font-semibold shadow-xs whitespace-nowrap flex-shrink-0"
                title="Unduh foto dari pelamar yang dipilih (ZIP)">
                <i class="fas fa-file-archive text-xs"></i>
                <span>Download Foto (<span id="count-selected-admin">0</span>)</span>
            </button>

            {{-- Download Semua Foto Diterima (ZIP) --}}
            <button type="button" onclick="submitBatchDownloadAdmin(true)"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg inline-flex items-center gap-1.5 transition text-xs font-semibold shadow-xs whitespace-nowrap flex-shrink-0"
                title="Unduh sekaligus seluruh foto pelamar yang telah disetujui / diterima (ZIP)">
                <i class="fas fa-download text-xs"></i>
                <span>Semua Foto Diterima (ZIP)</span>
            </button>
        </div>
    </div>

    {{-- Hidden Form for Batch Photo Download --}}
    <form id="form-download-photos-admin" method="POST" action="{{ route('admin.reviews.photos.download') }}" class="hidden">
        @csrf
        <input type="hidden" name="all" id="input-download-all-admin" value="0">
        <div id="selected-ids-container-admin"></div>
    </form>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        {{-- Total Applicants --}}
        <div class="bg-white p-3.5 rounded-xl shadow-xs border border-gray-100">
            <h3 class="text-gray-500 text-xs font-medium mb-0.5">Total Applicants</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_applicants'] ?? 0 }}</p>
        </div>

        {{-- Pending Review --}}
        <div class="bg-white p-3.5 rounded-xl shadow-xs border border-gray-100">
            <h3 class="text-gray-500 text-xs font-medium mb-0.5">Pending Review</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_review'] ?? 0 }}</p>
        </div>

        {{-- Approved Members --}}
        <div class="bg-white p-3.5 rounded-xl shadow-xs border border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-gray-500 text-xs font-medium mb-0.5">Approved Members</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['approved_members'] ?? 0 }}</p>
            </div>
            <i class="fas fa-check-circle text-blue-500 text-xl"></i>
        </div>

        {{-- Rejection Members --}}
        <div class="bg-white p-3.5 rounded-xl shadow-xs border border-gray-100">
            <h3 class="text-gray-500 text-xs font-medium mb-0.5">Rejection Members</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['rejected_members'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Applications Table with Categories --}}
    <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="w-9 px-3 py-2.5 text-center">
                            <input type="checkbox" id="check-all-admin"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-3.5 h-3.5 cursor-pointer"
                                title="Pilih semua pelamar berstatus diterima">
                        </th>
                        <th scope="col" class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Applicant</th>
                        <th scope="col" class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Position</th>
                        <th scope="col" class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Event</th>
                        <th scope="col" class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Applied</th>
                        <th scope="col" class="px-4 py-2.5 text-right text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($applications ?? [] as $application)
                    @php
                        $isAccepted = in_array($application->status, ['approved', 'accepted']);
                        $hasPhoto = !empty($application->user?->profile?->profile_photo);
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="w-9 px-3 py-2.5 text-center whitespace-nowrap">
                            @if($isAccepted)
                                <input type="checkbox" value="{{ $application->id }}"
                                    class="check-item-admin rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-3.5 h-3.5 cursor-pointer"
                                    onchange="updateSelectionStateAdmin()">
                            @else
                                <input type="checkbox" disabled
                                    class="rounded border-gray-200 text-gray-300 w-3.5 h-3.5 cursor-not-allowed opacity-30"
                                    title="Hanya pelamar yang berstatus diterima yang dapat diunduh fotonya">
                            @endif
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($application->user->profile && $application->user->profile->profile_photo)
                                    <div class="w-8 h-8 rounded-full mr-2.5 flex-shrink-0 overflow-hidden border border-gray-200 shadow-xs">
                                        <img src="{{ asset('storage/' . $application->user->profile->profile_photo) }}" alt=""
                                             class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2.5 flex-shrink-0 text-gray-500 font-bold text-xs">
                                        <span>{{ strtoupper(substr($application->user->username, 0, 2)) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-xs font-bold text-gray-900">{{ $application->user->name ?? $application->user->username }}</div>
                                    <div class="text-[11px] text-gray-500">{{ $application->user->email }}</div>
                                    @if(optional($application->user->profile)->nik)
                                        <div class="text-[10px] font-mono font-medium text-emerald-700 bg-emerald-50 border border-emerald-100 rounded px-1.5 py-0.5 mt-0.5 inline-flex items-center gap-1">
                                            <i class="fas fa-id-card text-[9px]"></i> NIK: {{ $application->user->profile->nik }}
                                        </div>
                                    @elseif(($application->user->profile->nationality_type ?? '') === 'wna')
                                        <div class="text-[10px] font-medium text-gray-500 bg-gray-100 rounded px-1.5 py-0.5 mt-0.5 inline-flex items-center gap-1">
                                            <i class="fas fa-globe text-[9px]"></i> WNA
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            <div class="text-xs font-medium text-gray-900">{{ $application->opening->title ?? 'N/A' }}</div>
                            <div class="text-[11px] text-gray-500">{{ $application->opening->event->title ?? 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-medium
                                @if($application->opening->jobCategory->name === 'Volunteer') bg-blue-100 text-blue-800
                                @elseif($application->opening->jobCategory->name === 'Organizer') bg-green-100 text-green-800
                                @elseif($application->opening->jobCategory->name === 'Liaison') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $application->opening->jobCategory->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            <div class="text-xs text-gray-900">{{ $application->opening->event->title ?? 'N/A' }}</div>
                            <div class="text-[11px] text-gray-500">{{ $application->opening->event->city->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-medium
                                @if($application->status === 'approved') bg-green-100 text-green-800
                                @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($application->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap">
                            <div class="text-xs text-gray-900">{{ $application->created_at->format('d M Y') }}</div>
                            <div class="text-[11px] text-gray-500">{{ $application->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-4 py-2.5 whitespace-nowrap text-right">
                            <div class="flex justify-end items-center space-x-1.5">
                                @if($isAccepted && $hasPhoto)
                                    <a href="{{ route('admin.applications.photo.download', $application) }}"
                                       class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-800 text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 px-2 py-1 rounded border border-emerald-200 transition"
                                       title="Unduh foto profil pelamar ini">
                                        <i class="fas fa-download text-[10px]"></i> Foto
                                    </a>
                                @endif
                                <a href="{{ route('admin.applications.show', $application->id) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium px-1.5 py-1">
                                    <i class="fas fa-eye mr-0.5"></i> View
                                </a>
                                @if($application->status === 'pending')
                                <a href="{{ route('admin.applications.show', $application->id) }}" class="text-red-600 hover:text-red-800 text-xs font-medium px-1.5 py-1">
                                    <i class="fas fa-edit mr-0.5"></i> Review
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                            <div class="py-4">
                                <i class="fas fa-inbox text-2xl mb-1 text-gray-400"></i>
                                <p class="text-xs">No applications found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-2.5 border-t border-gray-200 bg-gray-50">
            <div class="text-xs text-gray-600">
                Showing {{ count($applications ?? []) }} applications
            </div>
        </div>
    </div>
</div>

<script>
    // Real-time search with debounce
    let searchTimeout;
    document.getElementById('search-input')?.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();
        const form = document.getElementById('filter-form');

        // Auto-submit after 500ms pause in typing
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                form.submit();
            }, 500);
        } else if (query.length === 0) {
            // Clear search immediately when input is empty
            searchTimeout = setTimeout(() => {
                form.submit();
            }, 300);
        }
    });

    // Handle Enter key to submit immediately
    document.getElementById('search-input')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            document.getElementById('filter-form').submit();
        }
    });

    // Auto-submit when event filter changes
    document.getElementById('event-filter')?.addEventListener('change', function() {
        document.getElementById('filter-form').submit();
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

    // Batch Photo Download Handlers for Admin
    const checkAllAdmin = document.getElementById('check-all-admin');
    if (checkAllAdmin) {
        checkAllAdmin.addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.check-item-admin').forEach(cb => {
                cb.checked = isChecked;
            });
            updateSelectionStateAdmin();
        });
    }

    function updateSelectionStateAdmin() {
        const checked = document.querySelectorAll('.check-item-admin:checked');
        const count = checked.length;
        const countEl = document.getElementById('count-selected-admin');
        const btnDownload = document.getElementById('btn-download-selected-admin');
        if (countEl) countEl.textContent = count;
        if (btnDownload) btnDownload.disabled = count === 0;
    }

    function submitBatchDownloadAdmin(all) {
        const form = document.getElementById('form-download-photos-admin');
        const container = document.getElementById('selected-ids-container-admin');
        const inputAll = document.getElementById('input-download-all-admin');
        container.innerHTML = '';

        if (all) {
            inputAll.value = '1';
            form.submit();
            return;
        }

        inputAll.value = '0';
        const checked = document.querySelectorAll('.check-item-admin:checked');
        if (checked.length === 0) {
            alert('Silakan pilih minimal 1 pelamar untuk mengunduh foto.');
            return;
        }

        checked.forEach(cb => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'application_ids[]';
            hidden.value = cb.value;
            container.appendChild(hidden);
        });

        form.submit();
    }
</script>
@endsection
