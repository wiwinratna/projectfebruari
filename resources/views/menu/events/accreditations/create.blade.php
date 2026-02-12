@extends('layouts.app')

@section('title', 'Tambah Akreditasi - ' . $event->title)
@section('page-title')
Tambah Akreditasi <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Create New Akreditasi</h2>
            <p class="text-gray-600 mt-1">Add a new accreditation for this event</p>
        </div>
        <a href="{{ route('admin.events.accreditations.index', $event) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Akreditasi
        </a>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Akreditasi Information</h3>
        </div>
        <form action="{{ route('admin.events.accreditations.store', $event) }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama_akreditasi" class="block text-sm font-medium text-gray-700 mb-2">Nama Akreditasi <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_akreditasi" name="nama_akreditasi" value="{{ old('nama_akreditasi') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('nama_akreditasi') border-red-500 @enderror"
                        placeholder="e.g., Akreditasi Atlet" required>
                    @error('nama_akreditasi') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-sm mt-1">Name of the accreditation type</p>
                </div>
                <div>
                    <label for="jabatan_id" class="block text-sm font-medium text-gray-700 mb-2">Jabatan <span class="text-red-500">*</span></label>
                    <select name="jabatan_id" id="jabatan_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('jabatan_id') border-red-500 @enderror">
                        <option value="">Pilih Jabatan</option>
                        @foreach($jabatanList as $jabatan)
                        <option value="{{ $jabatan->id }}" {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
                        @endforeach
                    </select>
                    @error('jabatan_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-sm mt-1">Position this accreditation applies to</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="warna" class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
                    <input type="color" id="warna" name="warna" value="{{ old('warna', '#3B82F6') }}"
                        class="w-full h-10 px-1 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('warna') border-red-500 @enderror">
                    @error('warna') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-sm mt-1">Color identifier for this accreditation</p>
                </div>
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan') }}" maxlength="1000"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('keterangan') border-red-500 @enderror"
                        placeholder="Additional notes">
                    @error('keterangan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-sm mt-1">Optional description</p>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.events.accreditations.index', $event) }}" class="px-6 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200"><i class="fas fa-save mr-2"></i> Create Akreditasi</button>
            </div>
        </form>
    </div>
</div>
@endsection