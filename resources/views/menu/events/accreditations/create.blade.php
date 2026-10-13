@extends('layouts.app')

@section('title', 'Tambah Akreditasi - ' . $event->title)
@section('page-title')
    Tambah Akreditasi <span class="bg-blue-600 text-white text-sm px-2.5 py-0.5 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Create New Akreditasi</h2>
            <p class="text-sm text-gray-500 mt-1">Add a new accreditation for this event</p>
        </div>
        <a href="{{ route('admin.master-data.accreditations.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors shadow-xs">
            <i class="fas fa-arrow-left mr-2"></i> Back to Akreditasi
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-xs">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Akreditasi Information</h3>
        </div>
        <form action="{{ route('admin.master-data.accreditations.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama_akreditasi" class="block text-sm font-semibold text-gray-700 mb-1">Nama Akreditasi <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_akreditasi" name="nama_akreditasi" value="{{ old('nama_akreditasi') }}"
                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_akreditasi') border-red-500 @enderror text-sm"
                        placeholder="e.g., Akreditasi Atlet" required>
                    @error('nama_akreditasi') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-xs mt-1">Name of the accreditation type</p>
                </div>
                <div>
                    <label for="jabatan_id" class="block text-sm font-semibold text-gray-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
                    <select name="jabatan_id" id="jabatan_id" required
                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jabatan_id') border-red-500 @enderror text-sm">
                        <option value="">Pilih Jabatan</option>
                        @foreach($jabatanList as $jabatan)
                        <option value="{{ $jabatan->id }}" {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
                        @endforeach
                    </select>
                    @error('jabatan_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-xs mt-1">Position this accreditation applies to</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="warna" class="block text-sm font-semibold text-gray-700 mb-1">Warna</label>
                    <input type="color" id="warna" name="warna" value="{{ old('warna', '#3B82F6') }}"
                        class="w-full h-11 px-1 py-1 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('warna') border-red-500 @enderror cursor-pointer">
                    @error('warna') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-xs mt-1">Color identifier for this accreditation</p>
                </div>
                <div>
                    <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
                    <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan') }}" maxlength="1000"
                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror text-sm"
                        placeholder="Additional notes">
                    @error('keterangan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-gray-500 text-xs mt-1">Optional description</p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.master-data.accreditations.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition-colors text-sm">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors shadow-xs flex items-center text-sm">
                    <i class="fas fa-save mr-2"></i> Create Akreditasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection