@php
    $category = $category ?? new \App\Models\JobCategory();
    $workerTypes = $workerTypes ?? \App\Models\WorkerType::orderBy('name')->get();
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Nama Kategori Pekerjaan <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="name" 
                   value="{{ old('name', $category->name) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('name') border-red-500 @enderror"
                   placeholder="Contoh: LO - Akomodasi, Konsumsi, VIP, Keamanan"
                   required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-xs mt-1">Nama posisi / kategori pekerjaan untuk event {{ $event->title }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Tipe Pekerja (Worker Type) <span class="text-red-500">*</span>
            </label>
            <select name="worker_type_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('worker_type_id') border-red-500 @enderror"
                    required>
                <option value="">-- Pilih Tipe Pekerja --</option>
                @foreach($workerTypes as $workerType)
                    <option value="{{ $workerType->id }}" 
                            {{ old('worker_type_id', $category->worker_type_id) == $workerType->id ? 'selected' : '' }}>
                        {{ $workerType->name }} - {{ $workerType->description }}
                    </option>
                @endforeach
            </select>
            @error('worker_type_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-xs mt-1">Pilih klasifikasi tipe pekerja (misal: VO, LO)</p>
        </div>
    </div>
    
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
        <textarea name="description" 
                  rows="3"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('description') border-red-500 @enderror"
                  placeholder="Jelaskan ruang lingkup tugas kategori ini...">{{ old('description', $category->description) }}</textarea>
        @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <div>
        <label class="flex items-center cursor-pointer">
            <input type="checkbox" 
                   name="requires_certification" 
                   value="1"
                   {{ old('requires_certification', $category->requires_certification) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-red-600 focus:ring-red-500 h-4 w-4">
            <span class="ml-2 text-sm font-medium text-gray-700">Memerlukan Sertifikasi Khusus</span>
        </label>
        <p class="text-gray-500 text-xs mt-1 ml-6">Centang jika pendaftar di posisi ini diwajibkan memiliki sertifikat keahlian</p>
    </div>
</div>
