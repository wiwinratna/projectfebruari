@extends('layouts.app')

@section('title', 'Functional Areas - ' . $event->title)
@section('page-title')
Functional Areas <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header with Add Button --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manage Functional Areas</h2>
            <p class="text-gray-600 mt-1">Kelola pembagian divisi/fa untuk event ini</p>
        </div>
        <a href="{{ route('admin.master-data.functional-areas.create') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah FA
        </a>
    </div>

    @if(session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Functional Area</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan Utama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama FA</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tampil</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($functionalAreas as $fa)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $fa->jabatan->nama_jabatan ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $fa->fa_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $fa->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.master-data.functional-areas.edit', $fa->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3" title="Edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="text-red-600 hover:text-red-900 btn-delete" data-id="{{ $fa->id }}" title="Hapus">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                Belum ada data functional area.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const faId = this.getAttribute('data-id');
                
                if (confirm('Yakin ingin menghapus data ini?')) {
                    fetch(`/admin/master-data/functional-areas/${faId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.reload();
                        } else {
                            alert(data.message || 'Gagal menghapus data.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan sistem.');
                    });
                }
            });
        });
    });
</script>
@endpush
@endsection
