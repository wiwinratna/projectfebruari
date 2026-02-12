@extends('layouts.app')

@section('title', 'Jabatan - ' . $event->title)
@section('page-title')
Jabatan <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header with Add Button --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.events.show', $event) }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-2">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Event
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Manage Jabatan</h2>
            <p class="text-gray-600 mt-1">Kelola jabatan/posisi untuk event ini</p>
        </div>
        <a href="{{ route('admin.events.jabatan.create', $event) }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Jabatan
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Semua Jabatan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akreditasi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($jabatanList as $jabatan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $jabatan->nama_jabatan }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $jabatan->accreditations_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $jabatan->accreditations_count }} akreditasi
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.events.jabatan.edit', [$event, $jabatan]) }}"
                                class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            @if($jabatan->accreditations_count == 0)
                            <button onclick="deleteItem({{ $jabatan->id }}, '{{ addslashes($jabatan->nama_jabatan) }}')"
                                class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                            @else
                            <span class="text-gray-400 text-sm" title="Cannot delete - currently in use">
                                <i class="fas fa-lock mr-1"></i> In Use
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-id-badge text-4xl mb-3"></i>
                                <p class="text-lg font-medium">No jabatan found</p>
                                <p class="text-sm">Create your first jabatan to get started</p>
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
        const details = name ? `Jabatan: "${name}"` : 'This action cannot be undone.';

        showConfirmModal(
            'Delete Jabatan',
            'Are you sure you want to delete this jabatan?',
            details,
            () => performDelete(id)
        );
    }

    function performDelete(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showFlashMessage('Security token not found. Please refresh the page.', 'error');
            return;
        }

        showLoading();

        fetch(`/admin/events/{{ $event->id }}/jabatan/${id}`, {
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
                    showFlashMessage('Jabatan deleted successfully!', 'status');
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    showFlashMessage(data.message || 'Failed to delete jabatan', 'error');
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