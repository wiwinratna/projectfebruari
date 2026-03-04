@php
    $mode = $mode ?? 'create'; // create | edit
    $isEdit = $mode === 'edit';

    $acc = $acc ?? null;
    $selectedIds = $selectedIds ?? old('job_category_ids', []);
    $selectedIds = is_array($selectedIds) ? $selectedIds : [];

    // safety: jobCategories harus dikirim dari controller
    $jobCategories = $jobCategories ?? collect();
@endphp

<div class="space-y-6">

    {{-- EDIT: field akreditasi mapping (nama/warna/keterangan) --}}
    @if($isEdit && $acc)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Akreditasi <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="nama_akreditasi"
                    value="{{ old('nama_akreditasi', $acc->nama_akreditasi) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-300 @error('nama_akreditasi') border-red-500 @enderror"
                    placeholder="contoh: VIP / A / B / C / D"
                    required
                >
                @error('nama_akreditasi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Kode/nama akreditasi untuk event ini</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Warna <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <input
                        type="color"
                        name="warna"
                        value="{{ old('warna', $acc->warna ?? '#ef4444') }}"
                        class="h-10 w-16 px-1 py-1 border border-gray-300 rounded-lg @error('warna') border-red-500 @enderror"
                        required
                    >
                    <input
                        type="text"
                        name="warna_text"
                        value="{{ old('warna', $acc->warna ?? '#ef4444') }}"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-300"
                        placeholder="#ef4444"
                        oninput="document.querySelector('input[name=warna]').value = this.value"
                    >
                </div>
                @error('warna')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Warna badge/identifier akreditasi</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
            <input
                type="text"
                name="keterangan"
                value="{{ old('keterangan', $acc->keterangan) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-300 @error('keterangan') border-red-500 @enderror"
                placeholder="Optional notes"
            >
            @error('keterangan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="border-t border-gray-200 pt-4"></div>
    @endif


    {{-- Search + Filter + Actions --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
        <div class="relative w-full lg:w-[420px]">
            <i class="fas fa-search text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
            <input
                type="text"
                id="jc-search"
                placeholder="Search job categories..."
                class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg font-semibold focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-300"
            >
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

                <label
                    class="jc-item flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer"
                    data-name="{{ strtolower($jc->name) }}"
                    data-type="{{ $type }}"
                >
                    <input
                        type="checkbox"
                        name="job_category_ids[]"
                        value="{{ $jc->id }}"
                        class="rounded border-gray-300 jc-checkbox"
                        @checked($checked)
                    >

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