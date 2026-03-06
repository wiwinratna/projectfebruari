@extends('layouts.app')

@section('title', 'Konfigurasi Kartu Akses')

@php
    $eventName = session('admin_event_name') ?? 'Event';
@endphp

@section('page-title')
Konfigurasi Kartu Akses
<span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $eventName }}</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manage Konfigurasi Kartu Akses</h2>
            <p class="text-gray-600 mt-1">
                Atur paket akses (venue/zona/transport/akomodasi) berdasarkan
                <span class="font-semibold">Accreditation Mapping</span>
            </p>
        </div>

        <a href="{{ route('admin.card-configs.create') }}"
           class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center font-semibold">
            <i class="fas fa-plus mr-2"></i> Tambah Konfigurasi
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-start gap-2">
            <i class="fas fa-check-circle mt-0.5"></i>
            <div class="font-semibold">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-start gap-2">
            <i class="fas fa-exclamation-circle mt-0.5"></i>
            <div class="font-semibold">{{ session('error') }}</div>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-start gap-2">
            <i class="fas fa-exclamation-triangle mt-0.5"></i>
            <div class="font-semibold">{{ $errors->first() }}</div>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Semua Konfigurasi</h3>
            <div class="text-sm text-gray-600 font-semibold">Total: {{ $configs->count() }}</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mapping</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Transport</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Akomodasi</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Akses Venue</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Akses Zona</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($configs as $cfg)
                        @php
                            $mappingName  = optional($cfg->mapping)->nama_akreditasi ?? '-';
                            $mappingColor = optional($cfg->mapping)->warna;

                            $venues = $cfg->venueAccesses ?? collect();
                            $zones  = $cfg->zoneAccessCodes ?? collect();

                            $venueCodes = $venues->pluck('nama_vanue')->filter()->values();
                            $zoneCodes  = $zones->pluck('kode_zona')->filter()->values();

                            $venueCount = $venueCodes->count();
                            $zoneCount  = $zoneCodes->count();

                            $venueExamples = $venueCodes->take(3);
                            $zoneExamples  = $zoneCodes->take(3);
                            $accommodationCodes = collect($cfg->accommodation_code_id ?: [])
                                ->map(fn($id) => $accommodationCodesMap[$id]->kode ?? null);
                            $accommodationCodes = $accommodationCodes->filter()->values();
                            $accommodationExamples = $accommodationCodes->take(3);
                            $accommodationCount = $accommodationCodes->count();

                            $venueModalId = 'venue_'.$cfg->id;
                            $zoneModalId  = 'zone_'.$cfg->id;
                        @endphp

                        <tr class="hover:bg-gray-50">
                            {{-- Mapping --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $mappingName }}</div>

                                @if($mappingColor)
                                    <div class="flex items-center mt-1">
                                        <span class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $mappingColor }}"></span>
                                        <span class="text-xs text-gray-600">{{ $mappingColor }}</span>
                                    </div>
                                @endif
                            </td>

                            {{-- Transport --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700">{{ $cfg->transportationCode->kode ?? '-' }}</span>
                            </td>

                            {{-- Akomodasi --}}
                            <td class="px-6 py-4">
                                @if($accommodationCount === 0)
                                    <span class="text-sm text-gray-400">-</span>
                                @else
                                    <div class="text-sm text-gray-800">
                                        <span class="font-semibold">{{ $accommodationCount }} kode</span>
                                        <span class="text-gray-300 mx-1">•</span>
                                        <span class="text-gray-700">
                                            <span class="text-gray-900">{{ $accommodationExamples->implode(', ') }}</span>
                                            @if($accommodationCount > $accommodationExamples->count())
                                                <span class="text-gray-400">...</span>
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </td>

                            {{-- Akses Venue --}}
                            <td class="px-6 py-4">
                                @if($venueCount === 0)
                                    <span class="text-sm text-gray-400">-</span>
                                @else
                                    <div class="text-sm text-gray-800">
                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                            <span class="font-semibold">{{ $venueCount }} venue</span>
                                            <span class="text-gray-300">•</span>
                                            <span class="text-gray-700">
                                                <span class="text-gray-900">{{ $venueExamples->implode(', ') }}</span>
                                                @if($venueCount > $venueExamples->count())
                                                    <span class="text-gray-400">…</span>
                                                @endif
                                            </span>
                                        </div>

                                        <button type="button"
                                                class="mt-2 inline-flex items-center text-blue-600 hover:text-blue-900 font-semibold"
                                                data-open-modal="{{ $venueModalId }}">
                                            <i class="fas fa-list-ul mr-2"></i> View all
                                        </button>
                                    </div>

                                    {{-- Venue Modal --}}
                                    <div id="modal-{{ $venueModalId }}" class="fixed inset-0 z-50 hidden">
                                        <div class="absolute inset-0 bg-black/40" data-close-modal="{{ $venueModalId }}"></div>

                                        <div class="relative mx-auto mt-16 w-[95%] max-w-3xl">
                                            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                                    <div>
                                                        <div class="text-lg font-bold text-gray-800">Akses Venue</div>
                                                        <div class="text-sm text-gray-600">
                                                            Mapping: <span class="font-semibold text-gray-800">{{ $mappingName }}</span>
                                                            • Total: <span class="font-semibold text-gray-800">{{ $venueCount }}</span>
                                                        </div>
                                                    </div>

                                                    <button type="button" class="text-gray-500 hover:text-gray-700" data-close-modal="{{ $venueModalId }}">
                                                        <i class="fas fa-times text-xl"></i>
                                                    </button>
                                                </div>

                                                <div class="p-6">
                                                    <div class="mb-4">
                                                        <input type="text"
                                                               placeholder="Search venue..."
                                                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-200 focus:border-red-300"
                                                               data-search="{{ $venueModalId }}">
                                                    </div>

                                                    <div class="max-h-[420px] overflow-auto border border-gray-100 rounded-lg">
                                                        <ul class="divide-y divide-gray-100" data-list="{{ $venueModalId }}">
                                                            @foreach($venues as $v)
                                                                <li class="px-4 py-3 text-sm text-gray-800">
                                                                    <span class="font-semibold">{{ $v->nama_vanue }}</span>
                                                                    @if(!empty($v->keterangan))
                                                                        <div class="text-xs text-gray-500 mt-1">{{ $v->keterangan }}</div>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end">
                                                    <button type="button"
                                                            class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold"
                                                            data-close-modal="{{ $venueModalId }}">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            {{-- Akses Zona --}}
                            <td class="px-6 py-4">
                                @if($zoneCount === 0)
                                    <span class="text-sm text-gray-400">-</span>
                                @else
                                    <div class="text-sm text-gray-800">
                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                            <span class="font-semibold">{{ $zoneCount }} zona</span>
                                            <span class="text-gray-300">•</span>
                                            <span class="text-gray-700">
                                                <span class="text-gray-900">{{ $zoneExamples->implode(', ') }}</span>
                                                @if($zoneCount > $zoneExamples->count())
                                                    <span class="text-gray-400">…</span>
                                                @endif
                                            </span>
                                        </div>

                                        <button type="button"
                                                class="mt-2 inline-flex items-center text-blue-600 hover:text-blue-900 font-semibold"
                                                data-open-modal="{{ $zoneModalId }}">
                                            <i class="fas fa-list-ul mr-2"></i> View all
                                        </button>
                                    </div>

                                    {{-- Zone Modal --}}
                                    <div id="modal-{{ $zoneModalId }}" class="fixed inset-0 z-50 hidden">
                                        <div class="absolute inset-0 bg-black/40" data-close-modal="{{ $zoneModalId }}"></div>

                                        <div class="relative mx-auto mt-16 w-[95%] max-w-3xl">
                                            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                                    <div>
                                                        <div class="text-lg font-bold text-gray-800">Akses Zona</div>
                                                        <div class="text-sm text-gray-600">
                                                            Mapping: <span class="font-semibold text-gray-800">{{ $mappingName }}</span>
                                                            • Total: <span class="font-semibold text-gray-800">{{ $zoneCount }}</span>
                                                        </div>
                                                    </div>

                                                    <button type="button" class="text-gray-500 hover:text-gray-700" data-close-modal="{{ $zoneModalId }}">
                                                        <i class="fas fa-times text-xl"></i>
                                                    </button>
                                                </div>

                                                <div class="p-6">
                                                    <div class="mb-4">
                                                        <input type="text"
                                                               placeholder="Search zona..."
                                                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-200 focus:border-red-300"
                                                               data-search="{{ $zoneModalId }}">
                                                    </div>

                                                    <div class="max-h-[420px] overflow-auto border border-gray-100 rounded-lg">
                                                        <ul class="divide-y divide-gray-100" data-list="{{ $zoneModalId }}">
                                                            @foreach($zones as $z)
                                                                <li class="px-4 py-3 text-sm text-gray-800">
                                                                    <span class="font-semibold">{{ $z->kode_zona }}</span>
                                                                    @if(!empty($z->keterangan))
                                                                        <div class="text-xs text-gray-500 mt-1">{{ $z->keterangan }}</div>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end">
                                                    <button type="button"
                                                            class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold"
                                                            data-close-modal="{{ $zoneModalId }}">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            {{-- Keterangan --}}
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700">
                                    {{ \Illuminate\Support\Str::limit($cfg->keterangan, 40) ?: '-' }}
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.card-configs.edit', $cfg->id) }}"
                                   class="text-blue-600 hover:text-blue-900 mr-3 font-semibold">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>

                                <button type="button"
                                        onclick="deleteItem({{ $cfg->id }}, '{{ addslashes($mappingName) }}')"
                                        class="text-red-600 hover:text-red-900 font-semibold">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-id-badge text-4xl mb-3"></i>
                                    <p class="text-lg font-medium">No configs found</p>
                                    <p class="text-sm">Buat konfigurasi kartu akses untuk mapping terlebih dahulu</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // open modal
    document.querySelectorAll('[data-open-modal]').forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.getAttribute('data-open-modal');
            const modal = document.getElementById(`modal-${key}`);
            if (modal) modal.classList.remove('hidden');
        });
    });

    // close modal
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.getAttribute('data-close-modal');
            const modal = document.getElementById(`modal-${key}`);
            if (modal) modal.classList.add('hidden');
        });
    });

    // ESC close
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('[id^="modal-"]').forEach(m => m.classList.add('hidden'));
    });

    // search in modal
    document.querySelectorAll('[data-search]').forEach(input => {
        input.addEventListener('input', () => {
            const key = input.getAttribute('data-search');
            const list = document.querySelector(`[data-list="${key}"]`);
            if (!list) return;

            const q = input.value.toLowerCase().trim();
            list.querySelectorAll('li').forEach(li => {
                li.classList.toggle('hidden', !li.textContent.toLowerCase().includes(q));
            });
        });
    });
});
</script>

<script>
    function deleteItem(id, name) {
        const details = name ? `Config untuk: "${name}"` : 'This action cannot be undone.';
        showConfirmModal('Delete Konfigurasi', 'Are you sure you want to delete this config?', details, () => performDelete(id));
    }

    function performDelete(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showFlashMessage('Security token not found.', 'error');
            return;
        }

        showLoading();

        fetch(`{{ url('/admin/card-configs') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
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
                showFlashMessage('Config deleted successfully!', 'status');
                setTimeout(() => window.location.reload(), 500);
            } else {
                showFlashMessage(data.message || 'Failed to delete', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showFlashMessage('Error: ' + error.message, 'error');
        });
    }

    document.addEventListener('click', function(e) {
        const modal = document.getElementById('confirm-modal');
        if (e.target === modal && !modal.classList.contains('hidden')) hideConfirmModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') hideConfirmModal();
    });
</script>

@include('components.confirm-modal')
@endsection
