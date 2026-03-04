@extends('layouts.app')

@section('title', 'Accreditation Mapping - ARISE Admin')
@section('page-title')
    Accreditation Mapping <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Accreditation Mapping</h2>
            <p class="text-gray-600 mt-1">Map job categories ke setiap mapping (many-to-many)</p>
        </div>

        <a href="{{ route('admin.accreditation-mapping.create') }}"
           class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center font-semibold">
            <i class="fas fa-plus mr-2"></i> Add Mapping
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
            <h3 class="text-lg font-semibold text-gray-800">All Mappings</h3>
            <div class="text-sm text-gray-600 font-semibold">Total: {{ $mappings->count() }}</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Color</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Job Categories</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mappings as $mapping)
                        @php
                            $names = collect($mapping->jobCategories?->pluck('name') ?? []);
                            $count = $names->count();
                            $examples = $names->take(2);
                            $rowId = 'map_'.$mapping->id;
                        @endphp

                        <tr class="hover:bg-gray-50">
                            {{-- Name --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ $mapping->nama_akreditasi }}
                                </div>
                                @if(!empty($mapping->keterangan))
                                    <div class="text-xs text-gray-500 font-semibold">
                                        {{ $mapping->keterangan }}
                                    </div>
                                @endif
                            </td>

                            {{-- Color --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(!empty($mapping->warna))
                                    <div class="inline-flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full border border-gray-200"
                                              style="background-color: {{ $mapping->warna }}"></span>
                                        <span class="text-sm font-semibold text-gray-700">{{ $mapping->warna }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400 font-semibold">-</span>
                                @endif
                            </td>

                            {{-- Job Categories Summary --}}
                            <td class="px-6 py-4">
                                @if($count === 0)
                                    <div class="text-sm text-gray-500 font-semibold">No mapping yet</div>
                                @else
                                    <div class="text-sm text-gray-800">
                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                            <span class="font-semibold">{{ $count }} categories</span>
                                            <span class="text-gray-400">•</span>
                                            <span class="text-gray-700">
                                                <span class="text-gray-900">{{ $examples->join(', ') }}</span>
                                                @if($count > $examples->count())
                                                    <span class="text-gray-400">…</span>
                                                @endif
                                            </span>
                                        </div>

                                        <button type="button"
                                                class="mt-2 inline-flex items-center text-blue-600 hover:text-blue-900 font-semibold"
                                                data-open-modal="{{ $rowId }}">
                                            <i class="fas fa-list-ul mr-2"></i> View all
                                        </button>
                                    </div>

                                    {{-- Modal --}}
                                    <div id="modal-{{ $rowId }}" class="fixed inset-0 z-50 hidden">
                                        <div class="absolute inset-0 bg-black/40" data-close-modal="{{ $rowId }}"></div>

                                        <div class="relative mx-auto mt-16 w-[95%] max-w-3xl">
                                            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                                    <div>
                                                        <div class="text-lg font-bold text-gray-800">Mapped Job Categories</div>
                                                        <div class="text-sm text-gray-600">
                                                            Mapping:
                                                            <span class="font-semibold text-gray-800">{{ $mapping->nama_akreditasi }}</span>
                                                            • Total:
                                                            <span class="font-semibold text-gray-800">{{ $count }}</span>
                                                        </div>
                                                    </div>

                                                    <button type="button"
                                                            class="text-gray-500 hover:text-gray-700"
                                                            data-close-modal="{{ $rowId }}">
                                                        <i class="fas fa-times text-xl"></i>
                                                    </button>
                                                </div>

                                                <div class="p-6">
                                                    <div class="mb-4">
                                                        <input type="text"
                                                               placeholder="Search job category..."
                                                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-200 focus:border-red-300"
                                                               data-search="{{ $rowId }}">
                                                    </div>

                                                    <div class="max-h-[420px] overflow-auto border border-gray-100 rounded-lg">
                                                        <ul class="divide-y divide-gray-100" data-list="{{ $rowId }}">
                                                            @foreach($names as $name)
                                                                <li class="px-4 py-3 text-sm text-gray-800">{{ $name }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                                                    <a href="{{ route('admin.accreditation-mapping.edit', $mapping->id) }}"
                                                       class="text-blue-600 hover:text-blue-900 font-semibold">
                                                        <i class="fas fa-edit mr-1"></i> Edit Mapping
                                                    </a>

                                                    <button type="button"
                                                            class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold"
                                                            data-close-modal="{{ $rowId }}">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.accreditation-mapping.edit', $mapping->id) }}"
                                   class="text-blue-600 hover:text-blue-900 font-semibold">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="text-gray-500 font-semibold">
                                    Belum ada mapping untuk event ini.
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
    document.querySelectorAll('[data-open-modal]').forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.getAttribute('data-open-modal');
            const modal = document.getElementById(`modal-${key}`);
            if (modal) modal.classList.remove('hidden');
        });
    });

    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.getAttribute('data-close-modal');
            const modal = document.getElementById(`modal-${key}`);
            if (modal) modal.classList.add('hidden');
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('[id^="modal-"]').forEach(m => m.classList.add('hidden'));
    });

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
@endsection