@extends('layouts.app')

@section('title', 'Create Accreditation Mapping - ARISE Admin')

@section('page-title')
    Accreditation Mapping <span class="bg-red-500 text-white px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
@php
    $isEdit = isset($mapping) && !empty($mapping->id);
    $selectedIds = old('job_category_ids', $selectedIds ?? []);
@endphp

<div class="space-y-6">

    <div class="flex items-start justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $isEdit ? 'Edit Mapping' : 'Create Mapping' }}</h2>
            <p class="text-gray-700 font-semibold">
                {{ $isEdit ? 'Update nama/warna dan job categories untuk mapping ini' : 'Buat mapping baru (VIP/A/B/…) lalu pilih banyak job categories' }}
            </p>
        </div>

        <a href="{{ route('admin.accreditation-mapping.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center font-semibold">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg font-semibold">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg font-semibold">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg font-semibold">
            <i class="fas fa-exclamation-triangle mr-2"></i>{{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <form method="POST"
              action="{{ $isEdit ? route('admin.accreditation-mapping.update', $mapping->id) : route('admin.accreditation-mapping.store') }}"
              class="p-6 space-y-6">
            @csrf
            @if($isEdit) @method('PUT') @endif

            {{-- Mapping Info (Nama + Warna + Keterangan) --}}
            <div class="space-y-3">
                <div class="font-bold text-gray-800">Mapping Information</div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-gray-800 mb-2">
                            Nama Akreditasi (Kode) <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_akreditasi"
                               value="{{ old('nama_akreditasi', $mapping->nama_akreditasi ?? '') }}"
                               placeholder="contoh: VIP / A / B / C / D"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-300"
                               required>
                        @error('nama_akreditasi')
                            <div class="text-red-600 font-semibold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-gray-800 mb-2">
                            Warna <span class="text-red-500">*</span>
                        </label>
                        <input type="color"
                               name="warna"
                               value="{{ old('warna', $mapping->warna ?? '#ef4444') }}"
                               class="w-full h-10 px-2 py-1 border border-gray-300 rounded-lg"
                               required>
                        @error('warna')
                            <div class="text-red-600 font-semibold mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-gray-800 mb-2">Keterangan</label>
                    <input type="text"
                           name="keterangan"
                           value="{{ old('keterangan', $mapping->keterangan ?? '') }}"
                           placeholder="optional"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-300">
                    @error('keterangan')
                        <div class="text-red-600 font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Search + Filter + Actions --}}
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <div class="relative w-full lg:w-[420px]">
                    <i class="fas fa-search text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" id="jc-search"
                           placeholder="Search job categories..."
                           class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-300">
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <select id="jc-type" class="px-3 py-2 border border-gray-300 rounded-lg font-semibold">
                        <option value="">All Types</option>
                        <option value="LO">LO</option>
                        <option value="VO">VO</option>
                    </select>

                    <button type="button" id="btn-check-all"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold">
                        Select All
                    </button>

                    <button type="button" id="btn-uncheck-all"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold">
                        Clear All
                    </button>
                </div>
            </div>

            {{-- Job Categories --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="font-bold text-gray-800">Job Categories</div>
                    <div id="selected-count" class="font-semibold text-gray-700">Selected: 0</div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                    @foreach($jobCategories as $jc)
                        @php
                            $type = $jc->workerType?->name ?? '';
                            $checked = in_array($jc->id, $selectedIds);
                        @endphp

                        <label class="jc-item flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer"
                               data-name="{{ strtolower($jc->name) }}"
                               data-type="{{ $type }}">
                            <input type="checkbox"
                                   name="job_category_ids[]"
                                   value="{{ $jc->id }}"
                                   class="rounded border-gray-300 jc-checkbox"
                                   @checked($checked)>

                            <div class="font-semibold text-gray-900">
                                {{ $jc->name }}
                                @if($type)
                                    <span class="ml-2 px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                        {{ $type }}
                                    </span>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.accreditation-mapping.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold">
                    Cancel
                </a>

                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-red-500 text-white font-semibold hover:bg-red-600">
                    {{ $isEdit ? 'Update Mapping' : 'Save Mapping' }}
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchEl = document.getElementById('jc-search');
    const typeEl = document.getElementById('jc-type');
    const countEl = document.getElementById('selected-count');

    const items = () => Array.from(document.querySelectorAll('.jc-item'));
    const boxes = () => Array.from(document.querySelectorAll('.jc-checkbox'));

    function updateCount() {
        const checked = boxes().filter(cb => cb.checked).length;
        if (countEl) countEl.textContent = 'Selected: ' + checked;
    }

    function applyFilter() {
        const q = (searchEl?.value || '').trim().toLowerCase();
        const t = (typeEl?.value || '').trim();

        items().forEach(item => {
            const name = item.dataset.name || '';
            const type = item.dataset.type || '';
            const matchName = q === '' || name.includes(q);
            const matchType = t === '' || type === t;
            item.classList.toggle('hidden', !(matchName && matchType));
        });
    }

    searchEl?.addEventListener('input', applyFilter);
    typeEl?.addEventListener('change', applyFilter);

    document.getElementById('btn-check-all')?.addEventListener('click', () => {
        items().forEach(item => {
            if (!item.classList.contains('hidden')) {
                const cb = item.querySelector('.jc-checkbox');
                if (cb) cb.checked = true;
            }
        });
        updateCount();
    });

    document.getElementById('btn-uncheck-all')?.addEventListener('click', () => {
        items().forEach(item => {
            if (!item.classList.contains('hidden')) {
                const cb = item.querySelector('.jc-checkbox');
                if (cb) cb.checked = false;
            }
        });
        updateCount();
    });

    boxes().forEach(cb => cb.addEventListener('change', updateCount));

    applyFilter();
    updateCount();
});
</script>
@endsection