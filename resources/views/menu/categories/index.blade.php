@extends('layouts.app')

@section('title', 'Job Categories - ARISE Super Admin')
@section('page-title')
    Job Categories <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Super Admin</span>
@endsection

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manage Job Categories</h2>
            <p class="text-gray-600 mt-1">Organize worker positions by category</p>
        </div>
        <a href="{{ route('super-admin.job-categories.create') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center font-semibold">
            <i class="fas fa-plus mr-2"></i> Add Category
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">All Categories</h3>
                <p class="text-xs text-gray-500 mt-0.5">Filter berdasarkan event atau cari kategori</p>
            </div>

            {{-- Filter by Event & Search --}}
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <select id="filter-event" class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium text-gray-700">
                        <option value="">Semua Event & Global</option>
                        <option value="__global__">Hanya Global (Master)</option>
                        @foreach($events as $ev)
                            <option value="{{ $ev->id }}">{{ $ev->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="text" id="search-category" placeholder="Cari nama kategori..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="categories-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Openings</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($categories as $category)
                        @php
                            $openingsData = $category->workerOpenings->map(function($op) {
                                return [
                                    'title' => $op->title,
                                    'event_title' => $op->event?->title ?? 'N/A',
                                    'slots' => ($op->slots_filled ?? 0) . ' / ' . ($op->slots_available ?? 0),
                                    'deadline' => $op->application_deadline ? \Carbon\Carbon::parse($op->application_deadline)->format('d M Y') : '-',
                                    'status' => ucfirst($op->status ?? 'planned'),
                                ];
                            });
                        @endphp
                        <tr class="category-row hover:bg-gray-50"
                            data-event-id="{{ $category->event_id ?? '__global__' }}"
                            data-name="{{ strtolower($category->name) }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $category->name }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->event)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                        <i class="fas fa-calendar-alt mr-1"></i> {{ $category->event->title }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        <i class="fas fa-globe mr-1"></i> Global / Master
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">{{ $category->description ?? 'No description' }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->workerType)
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                        {{ $category->workerType->name === 'VO' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                        {{ $category->workerType->name }} - {{ $category->workerType->description }}
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                        No Type
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->workerOpenings->count() > 0)
                                    <button type="button"
                                            onclick='showOpeningsModal(@json($category->name), @json($category->event?->title ?? "Global / Master"), @json($openingsData))'
                                            class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 hover:bg-blue-200 transition cursor-pointer"
                                            title="Klik untuk melihat detail lowongan">
                                        <i class="fas fa-briefcase"></i>
                                        <span>{{ $category->workerOpenings->count() }} openings</span>
                                        <i class="fas fa-eye text-[10px] ml-1"></i>
                                    </button>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
                                        0 openings
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('super-admin.job-categories.edit', $category->id) }}"
                                   class="text-blue-600 hover:text-blue-900 mr-3 font-semibold">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>

                                @if($category->workerOpenings->count() == 0)
                                    <button onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                            data-category-id="{{ $category->id }}"
                                            class="text-red-600 hover:text-red-900 font-semibold">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                @else
                                    <span class="text-gray-400 text-sm font-semibold" title="Cannot delete - currently in use">
                                        <i class="fas fa-lock mr-1"></i> In Use
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-tags text-4xl mb-3"></i>
                                    <p class="text-lg font-medium">No categories found</p>
                                    <p class="text-sm">Create your first job category to get started</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    <tr id="no-filter-match" class="hidden">
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            <i class="fas fa-search text-3xl mb-2 text-gray-400"></i>
                            <p class="font-medium">Tidak ada kategori yang sesuai dengan filter.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Detail Lowongan --}}
<div id="openings-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="openings-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideOpeningsModal()"></div>

        <div class="relative inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <div>
                    <h3 class="text-lg font-bold text-gray-900" id="openings-modal-title">Daftar Lowongan Pekerjaan</h3>
                    <p class="text-xs text-gray-500 mt-0.5" id="openings-modal-event">-</p>
                </div>
                <button type="button" onclick="hideOpeningsModal()" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <div class="mt-4 max-h-96 overflow-y-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Posisi / Judul</th>
                            <th class="px-4 py-3">Event</th>
                            <th class="px-4 py-3 text-center">Terisi / Kuota</th>
                            <th class="px-4 py-3">Deadline</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody id="openings-table-body" class="divide-y divide-gray-100">
                        {{-- Populated by JS --}}
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" onclick="hideOpeningsModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg text-sm font-semibold transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
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

        yesBtn.replaceWith(yesBtn.cloneNode(true));
        cancelBtn.replaceWith(cancelBtn.cloneNode(true));

        const newYesBtn = document.getElementById('confirm-yes');
        const newCancelBtn = document.getElementById('confirm-cancel');

        newYesBtn.addEventListener('click', () => {
            hideConfirmModal();
            onConfirm();
        });

        newCancelBtn.addEventListener('click', hideConfirmModal);

        modal.classList.remove('hidden');
        newYesBtn.focus();
    }

    function hideConfirmModal() {
        document.getElementById('confirm-modal').classList.add('hidden');
    }

    function showLoading() {
        document.getElementById('loading-overlay').classList.remove('hidden');
    }

    function hideLoading() {
        document.getElementById('loading-overlay').classList.add('hidden');
    }

    function showFlashMessage(message, type = 'status') {
        const flashContainer = document.getElementById('flash-container') || createFlashContainer();

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
        flashMessage.setAttribute('role', 'alert');
        flashMessage.innerHTML = `
            <i class="${iconMap[type]} mt-0.5"></i>
            <div class="flex-1">${message}</div>
            <button type="button" class="text-white/70 hover:text-white transition" data-flash-close aria-label="Close notification">
                <i class="fas fa-times"></i>
            </button>
        `;

        flashMessage.style.opacity = '0';
        flashMessage.style.transform = 'translateX(100%)';

        flashContainer.appendChild(flashMessage);

        setTimeout(() => hideFlashMessage(flashMessage), 4500);

        flashMessage.querySelector('[data-flash-close]').addEventListener('click', () => {
            hideFlashMessage(flashMessage);
        });

        requestAnimationFrame(() => {
            flashMessage.style.opacity = '1';
            flashMessage.style.transform = 'translateX(0)';
        });
    }

    function createFlashContainer() {
        const existingContainer = document.getElementById('flash-container');
        if (existingContainer) return existingContainer;

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
            element.parentNode?.removeChild(element);
        }, 300);
    }

    function deleteCategory(id, categoryName) {
        const details = categoryName ? `Category: "${categoryName}"` : `This action cannot be undone.`;
        showConfirmModal(
            'Delete Category',
            'Are you sure you want to delete this category?',
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

        fetch(`/super-admin/job-categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            hideLoading();
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showFlashMessage('Category deleted successfully!', 'status');
                setTimeout(() => window.location.reload(), 500);
            } else {
                showFlashMessage(data.message || 'Failed to delete category', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showFlashMessage('Error deleting category: ' + error.message, 'error');
        });
    }

    // Openings Modal Functions
    function showOpeningsModal(categoryName, eventTitle, openings) {
        document.getElementById('openings-modal-title').textContent = 'Lowongan: ' + categoryName;
        document.getElementById('openings-modal-event').textContent = 'Kategori pada: ' + (eventTitle || 'Global / Master');
        
        const tbody = document.getElementById('openings-table-body');
        tbody.innerHTML = '';

        if (!openings || openings.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-2 text-gray-300"></i>
                        <p class="font-medium">Belum ada lowongan pekerjaan untuk kategori ini.</p>
                    </td>
                </tr>
            `;
        } else {
            openings.forEach(op => {
                const statusColor = op.status === 'Open' 
                    ? 'bg-green-100 text-green-700' 
                    : (op.status === 'Closed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');

                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50';
                tr.innerHTML = `
                    <td class="px-4 py-3 font-semibold text-gray-900">${op.title}</td>
                    <td class="px-4 py-3 text-gray-600">${op.event_title}</td>
                    <td class="px-4 py-3 text-center font-medium text-gray-700">${op.slots}</td>
                    <td class="px-4 py-3 text-gray-500">${op.deadline}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold ${statusColor}">
                            ${op.status}
                        </span>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        document.getElementById('openings-modal').classList.remove('hidden');
    }

    function hideOpeningsModal() {
        document.getElementById('openings-modal').classList.add('hidden');
    }

    // Client-side Filter by Event & Search
    document.addEventListener('DOMContentLoaded', function() {
        const eventSelect = document.getElementById('filter-event');
        const searchInput = document.getElementById('search-category');
        const rows = document.querySelectorAll('.category-row');
        const noMatchRow = document.getElementById('no-filter-match');

        function applyFilter() {
            const selectedEvent = eventSelect ? eventSelect.value : '';
            const searchVal = searchInput ? searchInput.value.toLowerCase().trim() : '';

            let visibleCount = 0;

            rows.forEach(row => {
                const rowEvent = row.getAttribute('data-event-id');
                const rowName = row.getAttribute('data-name');

                let matchEvent = false;
                if (!selectedEvent) {
                    matchEvent = true;
                } else if (selectedEvent === '__global__') {
                    matchEvent = (rowEvent === '__global__');
                } else {
                    matchEvent = (rowEvent === selectedEvent);
                }

                const matchSearch = !searchVal || rowName.includes(searchVal);

                if (matchEvent && matchSearch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noMatchRow) {
                if (visibleCount === 0 && rows.length > 0) {
                    noMatchRow.classList.remove('hidden');
                } else {
                    noMatchRow.classList.add('hidden');
                }
            }
        }

        if (eventSelect) eventSelect.addEventListener('change', applyFilter);
        if (searchInput) searchInput.addEventListener('input', applyFilter);
    });

    document.addEventListener('click', function(e) {
        const modal = document.getElementById('confirm-modal');
        if (modal && e.target === modal && !modal.classList.contains('hidden')) {
            hideConfirmModal();
        }

        const openingsModal = document.getElementById('openings-modal');
        if (openingsModal && e.target === openingsModal && !openingsModal.classList.contains('hidden')) {
            hideOpeningsModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideConfirmModal();
            hideOpeningsModal();
        }
    });
</script>
@endsection