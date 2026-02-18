@extends('layouts.app')

@section('title', 'Tambah Disiplin - ' . $event->title)
@section('page-title')
Tambah Disiplin <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Create New Disiplin</h2>
            <p class="text-gray-600 mt-1">Add a new sport discipline for this event</p>
        </div>
        <a href="{{ route('admin.master-data.disciplins.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Disiplin
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Disiplin Information</h3>
        </div>

        <form action="{{ route('admin.master-data.disciplins.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            {{-- Form Fields --}}
            <div>
                <label for="nama_disiplin" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Disiplin <span class="text-red-500">*</span>
                </label>
                <input type="text"
                    id="nama_disiplin"
                    name="nama_disiplin"
                    value="{{ old('nama_disiplin') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('nama_disiplin') border-red-500 @enderror"
                    placeholder="e.g., 100m Sprint, Men Singles"
                    required>
                @error('nama_disiplin')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Name of the sport discipline</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sport_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Olahraga <span class="text-red-500">*</span>
                    </label>
                    <select name="sport_id" id="sport_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('sport_id') border-red-500 @enderror">
                        <option value="">Pilih Olahraga</option>
                        @foreach($sports as $sport)
                        <option value="{{ $sport->id }}" {{ old('sport_id') == $sport->id ? 'selected' : '' }}>
                            {{ $sport->name }} ({{ $sport->code }})
                        </option>
                        @endforeach
                    </select>
                    @error('sport_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Parent sport for this discipline</p>
                </div>

                <div>
                    <label for="venue_location_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Venue Location <span class="text-red-500">*</span>
                    </label>
                    <select name="venue_location_id" id="venue_location_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('venue_location_id') border-red-500 @enderror">
                        <option value="">Pilih Venue</option>
                        @foreach($venueLocations as $venue)
                        <option value="{{ $venue->id }}" {{ old('venue_location_id') == $venue->id ? 'selected' : '' }}>
                            {{ $venue->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('venue_location_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Venue where this discipline takes place</p>
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                    Keterangan
                </label>
                <textarea name="keterangan" id="keterangan" rows="3" maxlength="1000"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('keterangan') border-red-500 @enderror"
                    placeholder="Additional notes about this discipline">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Optional description or notes</p>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.master-data.disciplins.index') }}"
                    class="px-6 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i> Create Disiplin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection