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

@php
  $selectedCodes = old(
    'access_code_ids',
    isset($opening) ? ($opening->accessCodes->pluck('id')->toArray() ?? []) : []
  );

  $selectedEventId = old('event_id', $opening->event_id ?? null);
@endphp

@php
  $selectedCodes = old(
    'access_code_ids',
    isset($opening) ? ($opening->accessCodes->pluck('id')->toArray() ?? []) : []
  );

  $selectedEventId = old('event_id', $opening->event_id ?? null);
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

@php
    $availableSkills = ['Public Speaking', 'Time Management', 'Leadership', 'Teamwork', 'Problem Solving', 'Data Analysis', 'Event Management', 'First Aid', 'Photography', 'Social Media'];
    $selectedRequiredSkills = old('required_skills', $opening->required_skills ?? []);
    $selectedPreferredSkills = old('preferred_skills', $opening->preferred_skills ?? []);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Required Skills -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Required Professional Skills</label>
        <p class="text-xs text-gray-500 mb-2">Skills that applicants must have.</p>
        
        <div id="req_skills_tags" class="flex flex-wrap gap-2 mb-3"></div>
        <div class="relative">
            <select id="req_skills_selector" onchange="addSkillTag('req', this.value); this.value='';" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium appearance-none">
                <option value="">+ Add Skill</option>
                @foreach($availableSkills as $skill)
                    <option value="{{ $skill }}">{{ $skill }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><i class="fas fa-chevron-down text-xs"></i></div>
        </div>
        
        <select name="required_skills[]" id="req_skills_hidden" multiple class="hidden">
            @foreach($availableSkills as $skill)
                <option value="{{ $skill }}" @selected(in_array($skill, $selectedRequiredSkills))>{{ $skill }}</option>
            @endforeach
        </select>
        
        @error('required_skills')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Preferred Skills -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Preferred Skills (Optional)</label>
        <p class="text-xs text-gray-500 mb-2">Nice-to-have skills for this role.</p>
        
        <div id="pref_skills_tags" class="flex flex-wrap gap-2 mb-3"></div>
        <div class="relative">
            <select id="pref_skills_selector" onchange="addSkillTag('pref', this.value); this.value='';" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium appearance-none">
                <option value="">+ Add Skill</option>
                @foreach($availableSkills as $skill)
                    <option value="{{ $skill }}">{{ $skill }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><i class="fas fa-chevron-down text-xs"></i></div>
        </div>
        
        <select name="preferred_skills[]" id="pref_skills_hidden" multiple class="hidden">
            @foreach($availableSkills as $skill)
                <option value="{{ $skill }}" @selected(in_array($skill, $selectedPreferredSkills))>{{ $skill }}</option>
            @endforeach
        </select>

        @error('preferred_skills')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<script>
    function renderSkillTags(type) {
        const hiddenSelect = document.getElementById(type + '_skills_hidden');
        const container = document.getElementById(type + '_skills_tags');
        container.innerHTML = '';
        
        Array.from(hiddenSelect.options).filter(opt => opt.selected).forEach(opt => {
            const el = document.createElement('span');
            // Using a slightly different color for preferred to distinguish
            const colorClass = type === 'req' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-blue-50 text-blue-700 border-blue-200';
            el.className = `inline-flex items-center px-3 py-1 ${colorClass} text-sm font-medium rounded-full border`;
            el.innerHTML = `${opt.value} <button type="button" onclick="removeSkillTag('${type}', '${opt.value}')" class="ml-2 hover:opacity-75"><i class="fas fa-times"></i></button>`;
            container.appendChild(el);
        });
    }

    function addSkillTag(type, value) {
        if(!value) return;
        const hiddenSelect = document.getElementById(type + '_skills_hidden');
        const opt = Array.from(hiddenSelect.options).find(o => o.value === value);
        if(opt && !opt.selected) {
            opt.selected = true;
            renderSkillTags(type);
        }
    }

    function removeSkillTag(type, value) {
        const hiddenSelect = document.getElementById(type + '_skills_hidden');
        const opt = Array.from(hiddenSelect.options).find(o => o.value === value);
        if(opt && opt.selected) {
            opt.selected = false;
            renderSkillTags(type);
        }
    }

    // Initialize tags on load
    document.addEventListener('DOMContentLoaded', () => {
        renderSkillTags('req');
        renderSkillTags('pref');
    });
</script>

<div class="mb-6">
    <label class="block text-sm font-semibold text-gray-700 mb-1">General Requirements (one per line)</label>
    <p class="text-xs text-gray-500 mb-2">e.g., "Available on event dates", "Able to attend briefing".</p>
    <textarea name="requirements_text" rows="4" placeholder="Enter general requirements (one per line)..."
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

