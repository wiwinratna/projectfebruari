@php
    $opening = $opening ?? new \App\Models\WorkerOpening();
    $deadlineValue = old(
        'application_deadline',
        optional($opening->application_deadline ?? null)->format('Y-m-d\TH:i')
    );
@endphp

<div class="mb-6">
    <label class="block text-sm font-semibold text-gray-700 mb-1">Select Event <span class="text-red-500">*</span></label>
    <p class="text-xs text-gray-500 mb-2">
        <i class="fas fa-info-circle mr-1"></i>
        Only active events available.
    </p>
    <select name="event_id"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
        <option value="">Choose an event...</option>
        @foreach ($events as $event)
            <option value="{{ $event->id }}" @selected(old('event_id', $opening->event_id ?? null) == $event->id)>
                {{ $event->title }}
            </option>
        @endforeach
    </select>
    @error('event_id')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Job Title</label>
        <input type="text" name="title" value="{{ old('title', $opening->title ?? '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
        @error('title')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Job Category</label>
        <select name="job_category_id"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
            <option value="">Select category...</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('job_category_id', $opening->job_category_id ?? null) === $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('job_category_id')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">Job Description</label>
    <textarea name="description" rows="4"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('description', $opening->description ?? '') }}</textarea>
    @error('description')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">Application Deadline</label>
    <input type="datetime-local" name="application_deadline" value="{{ $deadlineValue }}"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
    @error('application_deadline')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Total Slots</label>
        <input type="number" name="slots_total" min="1" value="{{ old('slots_total', $opening->slots_total ?? '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
        @error('slots_total')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Slots Filled</label>
        <input type="number" name="slots_filled" min="0" value="{{ old('slots_filled', $opening->slots_filled ?? 0) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
        @error('slots_filled')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
        <select name="status"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
            <option value="planned" @selected(old('status', $opening->status ?? 'planned') === 'planned')>Planned</option>
            <option value="open" @selected(old('status', $opening->status ?? 'planned') === 'open')>Open</option>
            <option value="closed" @selected(old('status', $opening->status ?? 'planned') === 'closed')>Closed</option>
        </select>
        @error('status')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">Requirements (one per line)</label>
    <textarea name="requirements_text" rows="4" placeholder="Enter requirements (one per line)..."
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">@if(isset($opening) && is_array($opening->requirements)){{ implode("\n", $opening->requirements) }}@else{{ old('requirements_text') }}@endif</textarea>
    @error('requirements_text')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">Benefits</label>
    <textarea name="benefits" rows="3" placeholder="Describe benefits for workers..."
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('benefits', $opening->benefits ?? '') }}</textarea>
    @error('benefits')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

