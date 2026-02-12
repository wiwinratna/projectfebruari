@extends('layouts.app')

@section('title', 'Disiplin - ' . $event->title)
@section('page-title')
Disiplin <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header with Add Button --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.events.show', $event) }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-2">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Event
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Manage Disiplin</h2>
            <p class="text-gray-600 mt-1">Kelola disiplin olahraga untuk event ini</p>
        </div>
        <a href="{{ route('admin.events.disciplins.create', $event) }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Disiplin
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Semua Disiplin</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Disiplin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Olahraga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($disciplins as $disciplin)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $disciplin->nama_disiplin }}</div>
                            @if($disciplin->keterangan)
                            <div class="text-xs text-gray-500">{{ Str::limit($disciplin->keterangan, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                {{ $disciplin->sport->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">{{ $disciplin->venueLocation->nama ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.events.disciplins.edit', [$event, $disciplin]) }}"
                                class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button onclick="deleteItem({{ $disciplin->id }}, '{{ addslashes($disciplin->nama_disiplin) }}')"
                                class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-trophy text-4xl mb-3"></i>
                                <p class="text-lg font-medium">No disciplins found</p>
                                <p class="text-sm">Create your first disciplin to get started</p>
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
        const details = name ? `Disiplin: "${name}"` : 'This action cannot be undone.';

        showConfirmModal(
            'Delete Disiplin',
            'Are you sure you want to delete this disciplin?',
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

        fetch(`/admin/events/{{ $event->id }}/disciplins/${id}`, {
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
                    showFlashMessage('Disiplin deleted successfully!', 'status');
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    showFlashMessage(data.message || 'Failed to delete disciplin', 'error');
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