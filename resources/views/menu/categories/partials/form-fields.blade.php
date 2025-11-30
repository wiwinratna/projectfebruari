@php
    $category = $category ?? new \App\Models\JobCategory();
    $workerTypes = \App\Models\WorkerType::orderBy('name')->get();
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Category Name <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="name" 
                   value="{{ old('name', $category->name) }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('name') border-red-500 @enderror"
                   placeholder="e.g., Konsumsi, Keamanan, Transportasi"
                   required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-sm mt-1">Name of the job category</p>
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Worker Type <span class="text-red-500">*</span>
            </label>
            <select name="worker_type_id" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('worker_type_id') border-red-500 @enderror"
                    required>
                <option value="">Select Worker Type</option>
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
            <p class="text-gray-500 text-sm mt-1">Select the worker type for this category</p>
        </div>
    </div>
    
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
        <textarea name="description" 
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('description') border-red-500 @enderror"
                  placeholder="Describe this job category...">{{ old('description', $category->description) }}</textarea>
        @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-500 text-sm mt-1">Detailed description of the job category (optional)</p>
    </div>
    
    <div>
        <label class="flex items-center">
            <input type="checkbox" 
                   name="requires_certification" 
                   value="1"
                   {{ old('requires_certification', $category->requires_certification) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
            <span class="ml-2 text-sm text-gray-700">Requires Certification</span>
        </label>
        <p class="text-gray-500 text-sm mt-1">Check if this position requires special certification</p>
    </div>
</div>