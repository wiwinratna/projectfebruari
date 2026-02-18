@extends('layouts.app')

@section('title', 'Tambah Venue Location - ' . $event->title)
@section('page-title')
Tambah Venue Location <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Create New Venue Location</h2>
            <p class="text-gray-600 mt-1">Add a new venue location for this event</p>
        </div>
        <a href="{{ route('admin.master-data.venue-locations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Venue Locations
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Venue Location Information</h3>
        </div>

        <form action="{{ route('admin.master-data.venue-locations.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            {{-- Form Fields --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="gugus" class="block text-sm font-medium text-gray-700 mb-2">
                        Gugus <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        id="gugus"
                        name="gugus"
                        value="{{ old('gugus') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('gugus') border-red-500 @enderror"
                        placeholder="e.g., Gugus A, Gugus B"
                        required>
                    @error('gugus')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Cluster/group for this venue</p>
                </div>

                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                        placeholder="e.g., GBK Stadium Utama"
                        required>
                    @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Full name of the venue location</p>
                </div>
            </div>

            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                    Alamat
                </label>
                <input type="text"
                    id="alamat"
                    name="alamat"
                    value="{{ old('alamat') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('alamat') border-red-500 @enderror"
                    placeholder="e.g., Jl. Pintu Satu Senayan, Jakarta"
                    maxlength="500">
                @error('alamat')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Full address of the venue (optional)</p>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.master-data.venue-locations.index') }}"
                    class="px-6 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i> Create Venue Location
                </button>
            </div>
        </form>
    </div>
</div>
@endsection