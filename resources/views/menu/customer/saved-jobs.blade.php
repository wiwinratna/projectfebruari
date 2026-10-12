@extends('layouts.public')

@section('title', 'Saved Jobs - NOCIS')

@section('content')
<!-- Modern Web3 Saved Jobs -->
<div class="min-h-screen bg-gray-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <!-- Main Content Wrapper with Top Padding -->
    <div class="relative z-10 pt-24 sm:pt-32 pb-20 sm:pb-16">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-5 sm:mb-8">
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-900 mb-1 sm:mb-2 tracking-tight">Saved Jobs</h1>
                <p class="text-xs sm:text-base text-gray-600">Keep track of opportunities you're interested in.</p>
            </div>

            <!-- Navigation Tabs (Hidden on mobile) -->
            @include('partials.customer-nav-tabs')

            <!-- Saved Jobs List -->
            <div class="bg-white/70 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-white/60 shadow-lg sm:shadow-xl overflow-hidden">
                <div class="px-4 sm:px-8 py-3.5 sm:py-6 border-b border-gray-100/50 flex justify-between items-center bg-white/40">
                    <h3 class="text-base sm:text-xl font-bold text-gray-900">Your Watchlist</h3>
                    <div class="bg-white/70 px-2.5 py-1 rounded-lg border border-gray-100 text-xs sm:text-sm font-medium text-gray-600">
                        Total: <span class="font-bold text-gray-900">{{ $savedJobs->total() }}</span>
                    </div>
                </div>

                <div class="p-3.5 sm:p-6 space-y-3 sm:space-y-4">
                    @forelse($savedJobs as $job)
                    <div class="group relative bg-white/80 backdrop-blur-sm border border-gray-100 rounded-xl sm:rounded-2xl p-3.5 sm:p-6 transition-all duration-300 hover:shadow-md hover:border-red-100 hover:bg-white">
                        <div class="flex flex-col lg:flex-row gap-4 sm:gap-6">
                            <!-- Left: Job Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                                    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                        <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                                            <i class="fas fa-briefcase text-lg sm:text-2xl text-red-500/80 group-hover:text-red-600 transition-colors"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-red-600 bg-red-50 px-2 py-0.5 rounded-md border border-red-100 truncate max-w-full">
                                                    {{ $job->event->title }}
                                                </span>
                                            </div>
                                            <h4 class="text-sm sm:text-xl font-bold text-gray-900 group-hover:text-red-600 transition-colors truncate">{{ $job->title }}</h4>
                                        </div>
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    <div class="shrink-0">
                                        @if($job->status === 'open' && $job->application_deadline > now())
                                            <div class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] sm:text-xs font-bold shadow-2xs">
                                                <span class="relative flex h-2 w-2">
                                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                </span>
                                                <span>Applications Open</span>
                                            </div>
                                        @else
                                            <div class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[11px] sm:text-xs font-bold shadow-2xs">
                                                <i class="fas fa-lock text-rose-500 text-[10px]"></i>
                                                <span>Closed</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Job Location & Deadline -->
                                <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs text-gray-500 border-b border-gray-100/70 pb-3 mb-3">
                                    <span class="inline-flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">
                                        <i class="fas fa-map-marker-alt text-red-400 text-xs"></i> {{ $job->event->city->name ?? $job->event->location ?? 'Indonesia' }}{{ $job->event->venue ? ', ' . $job->event->venue : '' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">
                                        <i class="far fa-clock text-red-400 text-xs"></i> Deadline: {{ $job->application_deadline->format('d M Y') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">
                                        <i class="far fa-calendar-alt text-red-400 text-xs"></i> Saved: {{ $job->pivot->created_at->format('d M Y') }}
                                    </span>
                                </div>
                                
                                @if($job->description)
                                <div class="mb-3">
                                    <p class="text-xs sm:text-sm text-gray-600 italic line-clamp-2">
                                        "{{ Str::limit($job->description, 160) }}"
                                    </p>
                                </div>
                                @endif
                            </div>
     
                            <!-- Right: Actions -->
                            <div class="grid grid-cols-2 sm:flex lg:flex-col justify-end gap-2 border-t lg:border-t-0 lg:border-l border-gray-100 pt-3 lg:pt-0 lg:pl-6 min-w-[150px]">
                                <!-- Check if already applied -->
                                @php
                                    $customerId = session('customer_id');
                                    $hasApplied = \App\Models\Application::where('worker_opening_id', $job->id)
                                        ->where('user_id', $customerId)
                                        ->exists();
                                @endphp

                                @if($hasApplied)
                                    <button disabled class="w-full text-center px-3 py-2 rounded-lg sm:rounded-xl bg-gray-100 text-gray-500 font-bold text-xs cursor-not-allowed border border-gray-200">
                                        <i class="fas fa-check mr-1.5"></i> Applied
                                    </button>
                                @elseif($job->status === 'open' && $job->application_deadline > now())
                                    <button onclick="applyForJob({{ $job->id }})" class="w-full text-center px-3 py-2 rounded-lg sm:rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs shadow-sm hover:shadow transition-all">
                                        Apply Now
                                    </button>
                                @else
                                    <button disabled class="w-full text-center px-3 py-2 rounded-lg sm:rounded-xl bg-gray-100 text-gray-400 font-bold text-xs cursor-not-allowed border border-gray-200">
                                        Closed
                                    </button>
                                @endif

                                <a href="{{ route('jobs.show', $job) }}" class="w-full text-center px-3 py-2 rounded-lg sm:rounded-xl bg-white hover:bg-gray-50 text-gray-700 font-bold text-xs border border-gray-200 transition-all">
                                    View Details
                                </a>

                                <button onclick="removeSavedJob({{ $job->id }})" class="col-span-2 sm:col-span-1 w-full text-center px-3 py-2 rounded-lg sm:rounded-xl bg-white hover:bg-red-50 text-gray-400 hover:text-red-500 font-bold text-xs border border-transparent hover:border-red-100 transition-all">
                                    <i class="fas fa-trash-alt mr-1.5"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-16 px-4">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-bookmark text-3xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Saved Jobs Yet</h3>
                        <p class="text-gray-500 mb-8 max-w-sm mx-auto">Found a job you like? Save it to keep track and apply when you're ready.</p>
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 hover:shadow-lg hover:shadow-red-500/30 transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-search mr-2"></i> Find Jobs
                        </a>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($savedJobs->hasPages())
                <div class="bg-gray-50/50 px-8 py-4 border-t border-gray-100">
                    {{ $savedJobs->links('pagination::tailwind') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function removeSavedJob(jobId) {
    if (confirm('Are you sure you want to remove this job from your saved jobs?')) {
        fetch('/dashboard/jobs/' + jobId + '/unsave', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to remove saved job');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
}

function applyForJob(jobId) {
    window.location.href = '/jobs/' + jobId + '#apply';
}
</script>
@endsection