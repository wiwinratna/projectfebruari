@extends('layouts.app')

@section('title', 'Job Categories - ' . $event->title)
@section('page-title')
    Job Categories <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Job Categories</h2>
            <p class="text-gray-600 mt-1">Kelola kategori posisi/pekerjaan khusus untuk event <strong>{{ $event->title }}</strong></p>
        </div>
        <a href="{{ route('admin.job-categories.create') }}"
           class="bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-lg flex items-center font-semibold shadow-sm w-fit transition">
            <i class="fas fa-plus mr-2"></i> Tambah Kategori
        </a>
    </div>

    @if(session('status'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
                <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    {{-- Kategori Khusus Event Ini --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Kategori Event: {{ $event->title }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">Total: {{ $categories->count() }} kategori pekerjaan</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe Pekerja</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lowongan</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($categories as $category)
                        @php
                            $openingsData = $category->workerOpenings->map(function($op) use ($event) {
                                return [
                                    'title' => $op->title,
                                    'event_title' => $event->title,
                                    'slots' => ($op->slots_filled ?? 0) . ' / ' . ($op->slots_available ?? 0),
                                    'deadline' => $op->application_deadline ? \Carbon\Carbon::parse($op->application_deadline)->format('d M Y') : '-',
                                    'status' => ucfirst($op->status ?? 'planned'),
                                ];
                            });
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $category->name }}</div>
                                @if($category->requires_certification)
                                    <span class="inline-flex items-center text-[11px] text-amber-700 bg-amber-50 px-2 py-0.5 rounded font-medium mt-1">
                                        <i class="fas fa-certificate mr-1"></i> Perlu Sertifikasi
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->workerType)
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                        {{ $category->workerType->name === 'VO' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $category->workerType->name }} - {{ $category->workerType->description }}
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                        -
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 max-w-xs truncate">{{ $category->description ?? '-' }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->workerOpenings->count() > 0)
                                    <button type="button"
                                            onclick='showOpeningsModal(@json($category->name), @json($openingsData))'
                                            class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 hover:bg-blue-200 transition cursor-pointer"
                                            title="Klik untuk melihat lowongan">
                                        <i class="fas fa-briefcase"></i>
                                        <span>{{ $category->workerOpenings->count() }} Lowongan</span>
                                        <i class="fas fa-eye text-[10px] ml-1"></i>
                                    </button>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-500">
                                        0 Lowongan
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.job-categories.edit', $category->id) }}"
                                   class="text-blue-600 hover:text-blue-900 mr-3 font-semibold">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>

                                @if($category->workerOpenings->count() == 0)
                                    <button onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                            class="text-red-600 hover:text-red-900 font-semibold">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                @else
                                    <span class="text-gray-400 text-xs font-medium cursor-not-allowed" title="Sedang digunakan di lowongan">
                                        <i class="fas fa-lock mr-1"></i> Terpakai
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-tags text-4xl mb-3 text-gray-300"></i>
                                <p class="text-base font-semibold text-gray-700">Belum ada Job Category khusus untuk event ini</p>
                                <p class="text-sm text-gray-500 mt-1">Klik tombol "+ Tambah Kategori" di atas untuk menambahkan posisi baru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Kategori Global / Master dari Super Admin --}}
    @if($globalCategories->count() > 0)
    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h4 class="text-base font-bold text-gray-800">
                    <i class="fas fa-globe text-gray-500 mr-2"></i> Kategori Master / Global
                </h4>
                <p class="text-xs text-gray-500 mt-0.5">Kategori umum dari Super Admin yang juga bisa digunakan di lowongan event kamu</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-gray-200 text-gray-700 rounded-full">
                {{ $globalCategories->count() }} Kategori
            </span>
        </div>

        <div class="flex flex-wrap gap-2 pt-2">
            @foreach($globalCategories as $gc)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-white border border-gray-200 text-gray-700 shadow-sm">
                    <i class="fas fa-tag text-gray-400"></i>
                    <strong class="font-semibold text-gray-900">{{ $gc->name }}</strong>
                    @if($gc->workerType)
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 font-bold">
                            {{ $gc->workerType->name }}
                        </span>
                    @endif
                </span>
            @endforeach
        </div>
    </div>
    @endif

</div>

{{-- Modal Detail Lowongan --}}
<div id="openings-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="hideOpeningsModal()"></div>

        <div class="relative inline-block w-full max-w-xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <div>
                    <h3 class="text-lg font-bold text-gray-900" id="openings-modal-title">Lowongan Pekerjaan</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Event: {{ $event->title }}</p>
                </div>
                <button type="button" onclick="hideOpeningsModal()" class="text-gray-400 hover:text-gray-600 p-1">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <div class="mt-4 max-h-80 overflow-y-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Posisi Lowongan</th>
                            <th class="px-4 py-3 text-center">Terisi / Kuota</th>
                            <th class="px-4 py-3">Deadline</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody id="openings-table-body" class="divide-y divide-gray-100">
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
    function showOpeningsModal(categoryName, openings) {
        document.getElementById('openings-modal-title').textContent = 'Lowongan: ' + categoryName;
        const tbody = document.getElementById('openings-table-body');
        tbody.innerHTML = '';

        if (!openings || openings.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                        Belum ada lowongan untuk kategori ini.
                    </td>
                </tr>
            `;
        } else {
            openings.forEach(op => {
                const statusColor = op.status === 'Open' 
                    ? 'bg-green-100 text-green-700' 
                    : (op.status === 'Closed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-4 py-3 font-semibold text-gray-900">${op.title}</td>
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

    function deleteCategory(id, name) {
        if (!confirm(`Yakin ingin menghapus kategori "${name}"?`)) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/job-categories/${id}`;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);

        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection
