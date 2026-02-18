@extends('layouts.app')

@section('title', 'Venue Access - ' . $event->title)
@section('page-title')
Venue Access <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.events.show', $event) }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-2">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Event
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Manage Venue Access</h2>
            <p class="text-gray-600 mt-1">Kelola akses venue untuk event ini</p>
        </div>
        <a href="{{ route('admin.master-data.venue-accesses.create') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Venue Access
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Semua Venue Access</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Venue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($venueAccesses as $access)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $access->nama_vanue }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">{{ $access->keterangan ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.master-data.venue-accesses.edit', $access) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button onclick="deleteItem({{ $access->id }}, '{{ addslashes($access->nama_vanue) }}')" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-door-open text-4xl mb-3"></i>
                                <p class="text-lg font-medium">No venue accesses found</p>
                                <p class="text-sm">Create your first venue access to get started</p>
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
        showConfirmModal('Delete Venue Access', 'Are you sure you want to delete this venue access?', `Venue: "${name}"`, () => performDelete(id));
    }

    function performDelete(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showFlashMessage('Security token not found.', 'error');
            return;
        }
        showLoading();
        fetch(`/admin/master-data/venue-accesses/${id}`, {
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
                    showFlashMessage('Venue access deleted successfully!', 'status');
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