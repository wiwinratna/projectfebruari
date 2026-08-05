@extends('layouts.app')

@section('title', 'Tambah Functional Area - ' . $event->title)
@section('page-title')
Functional Areas <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.master-data.functional-areas.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-2">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar FA
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Functional Area</h2>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow max-w-3xl">
        <form action="{{ route('admin.master-data.functional-areas.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6">
                
                {{-- Jabatan Selection --}}
                <div>
                    <label for="jabatan_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Jabatan Utama <span class="text-red-500">*</span>
                    </label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('jabatan_id') border-red-500 @enderror" 
                            id="jabatan_id" name="jabatan_id" required>
                        <option value="">-- Pilih Jabatan Utama --</option>
                        @foreach($jabatans as $jabatan)
                            <option value="{{ $jabatan->id }}" {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>
                                {{ $jabatan->nama_jabatan }}
                            </option>
                        @endforeach
                    </select>
                    @error('jabatan_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Functional Area Name --}}
                <div>
                    <label for="fa_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Functional Area (Divisi/Bagian) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('fa_name') border-red-500 @enderror" 
                           id="fa_name" name="fa_name" value="{{ old('fa_name') }}" 
                           placeholder="Contoh: Akomodasi, Konsumsi, VIP" required>
                    <p class="mt-1 text-sm text-gray-500"><i class="fas fa-info-circle mr-1"></i> Nantinya akan digabung menjadi: <span id="preview-name" class="font-semibold text-gray-700 italic">Pilih jabatan dan ketik nama FA</span></p>
                    @error('fa_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Worker Type --}}
                <div>
                    <label for="worker_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipe Pekerja (Opsional)
                    </label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('worker_type_id') border-red-500 @enderror" 
                            id="worker_type_id" name="worker_type_id">
                        <option value="">-- Tidak Spesifik --</option>
                        @foreach($workerTypes as $wt)
                            <option value="{{ $wt->id }}" {{ old('worker_type_id') == $wt->id ? 'selected' : '' }}>
                                {{ $wt->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('worker_type_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end rounded-b-lg">
                <a href="{{ route('admin.master-data.functional-areas.index') }}" class="text-gray-600 hover:text-gray-900 font-medium px-4 py-2 mr-2">
                    Batal
                </a>
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i> Simpan FA
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jabatanSelect = document.getElementById('jabatan_id');
        const faInput = document.getElementById('fa_name');
        const previewName = document.getElementById('preview-name');

        function updatePreview() {
            let jabatanText = jabatanSelect.options[jabatanSelect.selectedIndex]?.text || '';
            let faText = faInput.value.trim();
            
            if (jabatanSelect.value && faText) {
                previewName.textContent = jabatanText + ' - ' + faText;
                previewName.classList.add('text-blue-600');
            } else {
                previewName.textContent = 'Pilih jabatan dan ketik nama FA';
                previewName.classList.remove('text-blue-600');
            }
        }

        jabatanSelect.addEventListener('change', updatePreview);
        faInput.addEventListener('input', updatePreview);
    });
</script>
@endpush
@endsection
