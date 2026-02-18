@extends('layouts.app')

@section('title', 'Akreditasi - ' . $event->title)
@section('page-title')
Akreditasi <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header with Add Button --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.events.show', $event) }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-2">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Event
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Manage Akreditasi</h2>
            <p class="text-gray-600 mt-1">Kelola akreditasi untuk event ini</p>
        </div>
        <a href="{{ route('admin.master-data.accreditations.create') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Akreditasi
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Semua Akreditasi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Akreditasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warna</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($accreditations as $accreditation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $accreditation->nama_akreditasi }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $accreditation->jabatan->nama_jabatan ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($accreditation->warna)
                            <div class="flex items-center">
                                <span class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $accreditation->warna }}"></span>
                                <span class="text-sm text-gray-700">{{ $accreditation->warna }}</span>
                            </div>
                            @else
                            <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">{{ Str::limit($accreditation->keterangan, 40) ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.master-data.accreditations.edit', $accreditation) }}"
                                class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button onclick="deleteItem({{ $accreditation->id }}, '{{ addslashes($accreditation->nama_akreditasi) }}')"
                                class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-id-card text-4xl mb-3"></i>
                                <p class="text-lg font-medium">No accreditations found</p>
                                <p class="text-sm">Create your first accreditation to get started</p>
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
    function deleteItem(id, name) {
        const details = name ? `Akreditasi: "${name}"` : 'This action cannot be undone.';
        showConfirmModal('Delete Akreditasi', 'Are you sure you want to delete this accreditation?', details, () => performDelete(id));
    }

    function performDelete(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showFlashMessage('Security token not found.', 'error');
            return;
        }
        showLoading();
        fetch(`/admin/master-data/accreditations/${id}`, {
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
                    showFlashMessage('Accreditation deleted successfully!', 'status');
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