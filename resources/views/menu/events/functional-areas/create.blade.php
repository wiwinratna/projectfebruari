@extends('layouts.app')

@@section('title', 'Tambah Functional Area - ' . $event->title)
@section('page-title')
    Functional Areas <span class="bg-blue-600 text-white text-sm px-2.5 py-0.5 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6 max-w-3xl">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Functional Area</h2>
            <p class="text-sm text-gray-500 mt-1">Tambahkan divisi atau area fungsional baru untuk event</p>
        </div>
        <a href="{{ route('admin.master-data.functional-areas.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors shadow-xs">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar FA
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-xs">
        <form action="{{ route('admin.master-data.functional-areas.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-6">
                
                {{-- Jabatan Selection --}}
                <div>
                    <label for="jabatan_id" class="block text-sm font-semibold text-gray-700 mb-1">
                        Jabatan Utama <span class="text-red-500">*</span>
                    </label>
                    <select class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jabatan_id') border-red-500 @enderror text-sm" 
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
                    <label for="fa_name" class="block text-sm font-semibold text-gray-700 mb-1">
                        Nama Functional Area (Divisi/Bagian) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('fa_name') border-red-500 @enderror text-sm" 
                           id="fa_name" name="fa_name" value="{{ old('fa_name') }}" 
                           placeholder="Contoh: Akomodasi, Konsumsi, VIP" required>
                    <p class="mt-1 text-xs text-gray-500"><i class="fas fa-info-circle mr-1"></i> Nantinya akan digabung menjadi: <span id="preview-name" class="font-semibold text-gray-700 italic">Pilih jabatan dan ketik nama FA</span></p>
                    @error('fa_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Worker Type --}}
                <div>
                    <label for="worker_type_id" class="block text-sm font-semibold text-gray-700 mb-1">
                        Tipe Pekerja (Opsional)
                    </label>
                    <select class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('worker_type_id') border-red-500 @enderror text-sm" 
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
            
            <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex items-center justify-end gap-3 rounded-b-xl">
                <a href="{{ route('admin.master-data.functional-areas.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition-colors text-sm">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors shadow-xs flex items-center text-sm">
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
