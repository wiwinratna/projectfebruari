@extends('layouts.app')

@section('title', 'Edit Kode Zona Akses - ' . $event->title)
@section('page-title')
Edit Kode Zona <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Kode Zona Akses</h2>
            <p class="text-gray-600 mt-1">Update zone access code information</p>
        </div>
        <a href="{{ route('admin.events.zone-access-codes.index', $event) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Kode Zona
        </a>
    </div>
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Kode Zona Akses Information</h3>
        </div>
        <form action="{{ route('admin.events.zone-access-codes.update', [$event, $zoneAccessCode]) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="kode_zona" class="block text-sm font-medium text-gray-700 mb-2">Kode Zona <span class="text-red-500">*</span></label>
                    <input type="text" id="kode_zona" name="kode_zona" value="{{ old('kode_zona', $zoneAccessCode->kode_zona) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('kode_zona') border-red-500 @enderror"
                        placeholder="e.g., ZONA-1" required>
                    @error('kode_zona') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-sm mt-1">Unique zone code identifier</p>
                </div>
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan', $zoneAccessCode->keterangan) }}" maxlength="1000"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('keterangan') border-red-500 @enderror"
                        placeholder="e.g., Zona Pertandingan (Field of Play)">
                    @error('keterangan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-sm mt-1">Description of this zone</p>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.events.zone-access-codes.index', $event) }}" class="px-6 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200"><i class="fas fa-save mr-2"></i> Update Kode</button>
            </div>
        </form>
    </div>
</div>
@endsection