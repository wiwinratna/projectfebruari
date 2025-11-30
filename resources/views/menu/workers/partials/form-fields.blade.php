@php
    $opening = $opening ?? new \App\Models\WorkerOpening();
    $startValue = old(
        'shift_start',
        optional($opening->shift_start ?? null)->format('Y-m-d\TH:i')
    );
    $endValue = old(
        'shift_end',
        optional($opening->shift_end ?? null)->format('Y-m-d\TH:i')
    );
@endphp

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

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Shift Start Date & Time</label>
        <input type="datetime-local" name="shift_start" value="{{ $startValue }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
        @error('shift_start')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Shift End Date & Time</label>
        <input type="datetime-local" name="shift_end" value="{{ $endValue }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
        @error('shift_end')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
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

@if(!isset($opening))
<div class="border border-gray-200 rounded-lg p-4 space-y-4">
    <h4 class="text-sm font-semibold text-gray-700">Event Assignment</h4>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Select Event</label>
        <select name="event_id"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
            <option value="">Choose an event...</option>
            @foreach ($events as $event)
                <option value="{{ $event->id }}" @selected(old('event_id') == $event->id)>
                    {{ $event->title }} - {{ $event->venue ?? 'Venue TBA' }}
                </option>
            @endforeach
        </select>
        @error('event_id')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
@endif