@extends('layouts.app')

@section('title', 'Edit Disiplin - ' . $event->title)
@section('page-title')
    Edit Disiplin <span class="bg-blue-600 text-white text-sm px-2.5 py-0.5 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Disiplin</h2>
            <p class="text-sm text-gray-500 mt-1">Update discipline information</p>
        </div>
        <a href="{{ route('admin.master-data.disciplins.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors shadow-xs">
            <i class="fas fa-arrow-left mr-2"></i> Back to Disiplin
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-xs">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Disiplin Information</h3>
        </div>

        <form action="{{ route('admin.master-data.disciplins.update', $disciplin) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Form Fields --}}
            <div>
                <label for="nama_disiplin" class="block text-sm font-semibold text-gray-700 mb-1">
                    Nama Disiplin <span class="text-red-500">*</span>
                </label>
                <input type="text"
                    id="nama_disiplin"
                    name="nama_disiplin"
                    value="{{ old('nama_disiplin', $disciplin->nama_disiplin) }}"
                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_disiplin') border-red-500 @enderror text-sm"
                    placeholder="e.g., 100m Sprint, Men Singles"
                    required>
                @error('nama_disiplin')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">Name of the sport discipline</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sport_id" class="block text-sm font-semibold text-gray-700 mb-1">
                        Olahraga <span class="text-red-500">*</span>
                    </label>
                    <select name="sport_id" id="sport_id" required
                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sport_id') border-red-500 @enderror text-sm">
                        <option value="">Pilih Olahraga</option>
                        @foreach($sports as $sport)
                        <option value="{{ $sport->id }}" {{ old('sport_id', $disciplin->sport_id) == $sport->id ? 'selected' : '' }}>
                            {{ $sport->name }} ({{ $sport->code }})
                        </option>
                        @endforeach
                    </select>
                    @error('sport_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">Parent sport for this discipline</p>
                </div>

                <div>
                    <label for="venue_location_id" class="block text-sm font-semibold text-gray-700 mb-1">
                        Venue Location <span class="text-red-500">*</span>
                    </label>
                    <select name="venue_location_id" id="venue_location_id" required
                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('venue_location_id') border-red-500 @enderror text-sm">
                        <option value="">Pilih Venue</option>
                        @foreach($venueLocations as $venue)
                        <option value="{{ $venue->id }}" {{ old('venue_location_id', $disciplin->venue_location_id) == $venue->id ? 'selected' : '' }}>
                            {{ $venue->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('venue_location_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">Venue where this discipline takes place</p>
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-1">
                    Keterangan
                </label>
                <textarea name="keterangan" id="keterangan" rows="3" maxlength="1000"
                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror text-sm"
                    placeholder="Additional notes about this discipline">{{ old('keterangan', $disciplin->keterangan) }}</textarea>
                @error('keterangan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">Optional description or notes</p>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.master-data.disciplins.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition-colors text-sm">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors shadow-xs flex items-center text-sm">
                    <i class="fas fa-save mr-2"></i> Update Disiplin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection