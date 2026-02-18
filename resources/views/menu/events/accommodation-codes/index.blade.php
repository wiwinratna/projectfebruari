@extends('layouts.app')

@section('title', 'Kode Akomodasi - ' . $event->title)
@section('page-title')
Kode Akomodasi <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.events.show', $event) }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-2">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Event
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Manage Kode Akomodasi</h2>
            <p class="text-gray-600 mt-1">Kelola kode akomodasi untuk event ini</p>
        </div>
        <a href="{{ route('admin.master-data.accommodation-codes.create') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Kode
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Semua Kode Akomodasi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($accommodationCodes as $code)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $code->kode }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">{{ $code->keterangan ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.master-data.accommodation-codes.edit', $code) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button onclick="deleteItem({{ $code->id }}, '{{ addslashes($code->kode) }}')" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-bed text-4xl mb-3"></i>
                                <p class="text-lg font-medium">No accommodation codes found</p>
                                <p class="text-sm">Create your first accommodation code to get started</p>
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
        showConfirmModal('Delete Kode Akomodasi', 'Are you sure you want to delete this code?', `Kode: "${name}"`, () => performDelete(id));
    }

    function performDelete(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showFlashMessage('Security token not found.', 'error');
            return;
        }
        showLoading();
        fetch(`/admin/master-data/accommodation-codes/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(r => {
                hideLoading();
                if (!r.ok) throw new Error(`HTTP ${r.status}`);
                return r.json();
            })
            .then(data => {
                if (data.success) {
                    showFlashMessage('Code deleted successfully!', 'status');
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    showFlashMessage(data.message || 'Failed', 'error');
                }
            })
            .catch(e => {
                hideLoading();
                showFlashMessage('Error: ' + e.message, 'error');
            });
    }
    document.addEventListener('click', function(e) {
        const m = document.getElementById('confirm-modal');
        if (e.target === m && !m.classList.contains('hidden')) hideConfirmModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') hideConfirmModal();
    });
</script>
@include('components.confirm-modal')
@endsection