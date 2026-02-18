@extends('layouts.app')

@section('title', 'Tambah Jabatan - ' . $event->title)
@section('page-title')
Tambah Jabatan <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Create New Jabatan</h2>
            <p class="text-gray-600 mt-1">Add a new jabatan/position for this event</p>
        </div>
        <a href="{{ route('admin.master-data.jabatan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Jabatan
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Jabatan Information</h3>
        </div>

        <form action="{{ route('admin.master-data.jabatan.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="nama_jabatan" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Jabatan <span class="text-red-500">*</span>
                </label>
                <input type="text"
                    id="nama_jabatan"
                    name="nama_jabatan"
                    value="{{ old('nama_jabatan') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('nama_jabatan') border-red-500 @enderror"
                    placeholder="e.g., Ketua Umum, Pelatih, Wasit"
                    required>
                @error('nama_jabatan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Position/role name for this event</p>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.master-data.jabatan.index') }}"
                    class="px-6 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i> Create Jabatan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection