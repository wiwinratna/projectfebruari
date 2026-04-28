{{-- Tab 3: Qualification --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Education --}}
    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4"><i class="fas fa-graduation-cap mr-1"></i> Education History</p>
        @if($user->educationHistories && $user->educationHistories->count() > 0)
            <div class="space-y-4">
                @foreach($user->educationHistories->sortByDesc('sort_order') as $edu)
                    <div class="p-3 bg-white border border-gray-200 rounded-xl relative">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">{{ $edu->institution_name }}</h4>
                                <p class="text-xs font-semibold text-gray-700 mt-0.5">
                                    {{ $edu->education_level }} - {{ $edu->field_of_study }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    @if($edu->is_still_studying)
                                        Present (Masih menempuh pendidikan)
                                    @else
                                        Graduated: {{ $edu->graduation_year }}
                                    @endif
                                </p>
                            </div>
                            @if($edu->proof_document)
                                <a href="{{ $edu->proof_document_url }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 font-semibold bg-blue-50 px-2 py-1 rounded">
                                    <i class="fas fa-file-alt mr-1"></i> Proof
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <dl class="space-y-3">
                <div><dt class="text-xs text-gray-500">Last Education</dt><dd class="text-sm font-semibold text-gray-900 mt-0.5">{{ $user->profile?->last_education ?? '—' }}</dd></div>
                <div><dt class="text-xs text-gray-500">Field of Study</dt><dd class="text-sm font-semibold text-gray-900 mt-0.5">{{ $user->profile?->field_of_study ?? '—' }}</dd></div>
                <div><dt class="text-xs text-gray-500">University</dt><dd class="text-sm font-semibold text-gray-900 mt-0.5">{{ $user->profile?->university ?? '—' }}</dd></div>
                <div><dt class="text-xs text-gray-500">Graduation Year</dt><dd class="text-sm font-semibold text-gray-900 mt-0.5">{{ $user->profile?->graduation_year ?? '—' }}</dd></div>
            </dl>
        @endif
    </div>

    {{-- Skills & Languages --}}
    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4"><i class="fas fa-tools mr-1"></i> Skills & Languages</p>
        <div class="mb-4">
            <p class="text-xs text-gray-500 mb-2">Skills</p>
            @if($user->profile?->skills)
                <div class="flex flex-wrap gap-1.5">
                    @foreach(explode(',', $user->profile->skills) as $skill)
                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-medium">{{ trim($skill) }}</span>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400 italic">Not specified</p>
            @endif
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-2">Languages</p>
            @if($user->profile?->languages)
                <div class="flex flex-wrap gap-1.5">
                    @foreach(explode(',', $user->profile->languages) as $lang)
                        <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium">{{ trim($lang) }}</span>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400 italic">Not specified</p>
            @endif
        </div>
    </div>

    {{-- CV / Resume --}}
    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4"><i class="fas fa-file-pdf mr-1"></i> CV / Resume</p>
        @if($user->profile?->cv_file)
            <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center mr-3"><i class="fas fa-file-pdf text-red-500"></i></div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">CV Document</p>
                        @if($user->profile->cv_updated_at)
                            <p class="text-xs text-gray-500">Updated: {{ $user->profile->cv_updated_at->format('M d, Y') }}</p>
                        @endif
                    </div>
                </div>
                <a href="{{ asset('storage/' . $user->profile->cv_file) }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors"><i class="fas fa-download mr-1"></i> Download</a>
            </div>
        @else
            <p class="text-sm text-gray-400 italic">No CV uploaded</p>
        @endif
    </div>

    {{-- Certificates --}}
    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4"><i class="fas fa-certificate mr-1"></i> Certificates ({{ $user->certificates?->count() ?? 0 }})</p>
        @if($user->certificates && $user->certificates->count() > 0)
            <div class="space-y-2">
                @foreach($user->certificates as $cert)
                    <div class="flex items-center p-3 bg-white rounded-xl border border-gray-200">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center mr-3 flex-shrink-0"><i class="fas fa-award text-amber-500 text-sm"></i></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $cert->title ?? 'Certificate' }}</p>
                            <p class="text-xs text-gray-500">{{ $cert->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-400 italic">No certificates uploaded</p>
        @endif
    </div>
</div>
