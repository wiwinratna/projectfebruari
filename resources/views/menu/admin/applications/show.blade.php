@extends('layouts.app')

@section('title', 'Review Application - NOCIS Admin')
@section('page-title')
    <div class="flex items-center">
        <a href="{{ route('admin.workers.show', $application->opening->id) }}" class="mr-4 text-gray-500 hover:text-gray-700 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        Review Application <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">#{{ $application->id }}</span>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: User Profile -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden text-center p-6">
            <!-- Photo -->
            <div class="relative w-32 h-32 mx-auto mb-4">
                @if($application->user->profile && $application->user->profile->profile_photo)
                    <img src="{{ asset('storage/' . $application->user->profile->profile_photo) }}" alt="Profile" class="w-full h-full rounded-full object-cover border-4 border-gray-50 shadow-sm">
                @else
                    <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center text-4xl font-bold text-gray-400">
                        {{ strtoupper(substr($application->user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="absolute bottom-1 right-1 w-8 h-8 bg-green-500 rounded-full border-4 border-white"></div>
            </div>

            <h2 class="text-xl font-bold text-gray-900">{{ $application->user->name }}</h2>
            <p class="text-red-500 font-medium text-sm mb-4">{{ $application->user->profile->professional_headline ?? 'Job Seeker' }}</p>

            <div class="flex justify-center gap-3">
                @if($application->user->profile->linkedin)
                    <a href="{{ $application->user->profile->linkedin }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"><i class="fab fa-linkedin-in"></i></a>
                @endif
                <a href="mailto:{{ $application->user->email }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors"><i class="fas fa-envelope"></i></a>
            </div>
            
            <hr class="border-gray-100 my-6">

            <div class="text-left space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Contact</h3>
                    <p class="text-sm font-medium text-gray-800"><i class="fas fa-phone text-gray-400 mr-2"></i> {{ $application->user->profile->phone ?? '-' }}</p>
                    <p class="text-sm font-medium text-gray-800"><i class="fas fa-map-marker-alt text-gray-400 mr-2"></i> {{ $application->user->profile->address ?? '-' }}</p>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Bio</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $application->user->profile->summary ?? 'No summary provided.' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Education & Skills</h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase mb-1">Education</p>
                    <p class="font-bold text-gray-800">{{ $application->user->profile->university ?? '-' }}</p>
                    <p class="text-sm text-gray-600">{{ $application->user->profile->field_of_study ?? '' }}</p>
                    <p class="text-xs text-gray-400">{{ $application->user->profile->last_education ?? '' }} @if($application->user->profile->graduation_year) • {{ $application->user->profile->graduation_year }} @endif</p>
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase mb-2">Skills</p>
                    <div class="flex flex-wrap gap-2">
                        @forelse(explode(',', $application->user->profile->skills ?? '') as $skill)
                            @if(trim($skill))
                                <span class="px-2.5 py-1 rounded-lg bg-red-50 text-red-600 text-xs font-bold border border-red-100">{{ trim($skill) }}</span>
                            @endif
                        @empty
                            <span class="text-sm text-gray-400">-</span>
                        @endforelse
                    </div>
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase mb-2">Languages</p>
                    <div class="flex flex-wrap gap-2">
                        @forelse(explode(',', $application->user->profile->languages ?? '') as $lang)
                            @if(trim($lang))
                                <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100">{{ trim($lang) }}</span>
                            @endif
                        @empty
                            <span class="text-sm text-gray-400">-</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Application Details & Action -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Job Context -->
        <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm">Applying For</span>
                <span class="text-xs text-gray-300">{{ $application->created_at->format('d M Y, H:i') }}</span>
            </div>
            <h1 class="text-2xl font-bold mb-2">{{ $application->opening->title }}</h1>
            <div class="flex items-center gap-4 text-sm text-gray-300">
                <span><i class="fas fa-calendar mr-1"></i> {{ $application->opening->event->title }}</span>
                <span><i class="fas fa-tag mr-1"></i> {{ $application->opening->jobCategory->name }}</span>
            </div>
        </div>

        <!-- Application Content -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-quote-left text-red-500"></i> Why should we accept you?
                </h3>
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <p class="text-gray-700 leading-relaxed italic text-lg">{{ $application->motivation }}</p>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-history text-red-500"></i> Relevant Experience
                </h3>
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <p class="text-gray-700 leading-relaxed text-sm whitespace-pre-line">{{ $application->experience }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-file-alt text-red-500"></i> Attachments
                </h3>
                @if($application->cv_path)
                    <div class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-red-300 hover:bg-red-50 transition-all group">
                        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center mr-4 group-hover:bg-red-200">
                            <i class="fas fa-file-pdf text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">Curriculum Vitae</p>
                            <p class="text-xs text-gray-500">PDF Document</p>
                        </div>
                        <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-bold text-gray-700 hover:text-red-600 hover:border-red-200 transition-all shadow-sm">
                            <i class="fas fa-download mr-1"></i> Download
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 italic">No CV uploaded.</p>
                @endif

            {{-- ✅ Certificates --}}
            <div class="mt-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-certificate text-red-500"></i> Certificates
                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full font-bold">
                {{ $application->user->certificates?->count() ?? 0 }}
                </span>
            </h3>

            @if($application->user->certificates && $application->user->certificates->count())

                <div class="overflow-x-auto bg-white border border-gray-200 rounded-2xl">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3 font-bold">Title</th>
                        <th class="text-left px-4 py-3 font-bold whitespace-nowrap">Date</th>
                        <th class="text-left px-4 py-3 font-bold">Stage</th>
                        <th class="text-left px-4 py-3 font-bold">File</th>
                        <th class="text-right px-4 py-3 font-bold whitespace-nowrap">Actions</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                    @foreach($application->user->certificates as $cert)
                        @php
                        $displayName = trim(
                            $cert->original_name
                            ?: ($cert->title
                                    ? ($cert->title . '.' . pathinfo($cert->file_path, PATHINFO_EXTENSION))
                                    : basename($cert->file_path))
                        );

                        $ext = strtolower(pathinfo($displayName, PATHINFO_EXTENSION));
                        $icon = match($ext) {
                            'pdf' => 'fa-file-pdf text-red-500',
                            'jpg','jpeg','png' => 'fa-file-image text-blue-500',
                            default => 'fa-file-lines text-gray-400'
                        };
                        @endphp

                        <tr class="hover:bg-gray-50/70">
                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-900 leading-tight line-clamp-2">
                            {{ $cert->title ?? '-' }}
                            </div>
                        </td>

                        <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                            {{ $cert->event_date ? \Carbon\Carbon::parse($cert->event_date)->format('d M Y') : '-' }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center text-[10px] font-extrabold px-2.5 py-1 rounded-full
                                        bg-gray-100 text-gray-700 uppercase tracking-wide">
                            {{ strtoupper(str_replace('_',' ', $cert->stage)) }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2 min-w-0">
                            <i class="fas {{ $icon }} flex-shrink-0"></i>
                            <a href="{{ asset('storage/' . $cert->file_path) }}"
                                target="_blank"
                                class="text-red-600 font-bold hover:underline truncate max-w-[360px] md:max-w-[520px]"
                                title="{{ $displayName }}">
                                {{ $displayName }}
                            </a>
                            </div>
                            <div class="text-[11px] text-gray-400 mt-0.5">{{ strtoupper($ext) }}</div>
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                            <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank"
                                class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center"
                                title="Open">
                                <i class="fas fa-arrow-up-right-from-square"></i>
                            </a>

                            <a href="{{ asset('storage/' . $cert->file_path) }}" download
                                class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center"
                                title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            </div>
                        </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>

            @else
                <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-8 text-center">
                <p class="text-gray-500 font-medium">No certificates uploaded.</p>
                </div>
            @endif
            </div>

        <!-- Review Action -->
        <form action="{{ route('admin.applications.update', $application->id) }}" method="POST">
            @csrf
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6"> <!-- sticky bottom-6 z-10 for floating effect if needed, but simple is better -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Review Decision</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Current Status:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                            @if($application->status == 'approved') bg-green-100 text-green-700
                            @elseif($application->status == 'rejected') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ $application->status }}
                        </span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="review_notes" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Internal Notes (Optional)</label>
                    <textarea name="review_notes" id="review_notes" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm" placeholder="Add notes for other admins...">{{ $application->review_notes }}</textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit" name="status" value="rejected" class="flex-1 py-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl font-bold transition-all border border-red-100">
                        <i class="fas fa-times mr-2"></i> Reject Candidate
                    </button>
                    <button type="submit" name="status" value="approved" class="flex-1 py-3 bg-green-600 text-white hover:bg-green-700 rounded-xl font-bold shadow-lg shadow-green-500/30 transition-all hover:-translate-y-0.5">
                        <i class="fas fa-check mr-2"></i> Approve Candidate
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
