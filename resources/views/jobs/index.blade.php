@extends('layouts.public')

@section('title', 'Job Opportunities - NOCIS')

@section('content')
<!-- Modern Web3 Job Opportunities Page -->
<div class="min-h-screen bg-gray-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <!-- Main Content Wrapper with Top Padding -->
    <div class="relative z-10 pt-32 pb-16">
        <div class="container mx-auto px-4 lg:px-6 max-w-7xl">
            
            <!-- Page Title & Mobile Filter Toggle -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2 tracking-tight">Job Opportunities</h1>
                    <p class="text-gray-600">Find and apply for the best sports event roles in Indonesia.</p>
                </div>
                <!-- Mobile Filter Toggle -->
                <button onclick="document.getElementById('filter-sidebar').classList.toggle('hidden')" 
                        class="lg:hidden w-full md:w-auto flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-medium shadow-sm hover:bg-gray-50 transition-colors">
                    <i class="fas fa-filter text-red-500"></i>
                    Filters
                </button>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Main Content: Job List (Left) -->
                <div class="flex-1 order-2 lg:order-1">
                    <!-- Job Stack -->
                    <div class="space-y-4">
                        @forelse($jobs as $job)
                        <a href="{{ route('jobs.show', $job) }}" class="group block bg-white/80 backdrop-blur-md rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-lg hover:border-red-100 transition-all duration-300 relative overflow-hidden">
                            <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                                <!-- Icon/Logo -->
                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-red-50 to-white border border-red-50 flex items-center justify-center shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                                    <i class="fas fa-trophy text-2xl text-red-500"></i>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <!-- Event & Category (Top Row) -->
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span class="text-xs font-bold text-red-600 tracking-wide uppercase">
                                            {{ $job->event->title ?? 'NOC Indonesia' }}
                                        </span>
                                        <span class="text-gray-300">•</span>
                                        <span class="text-xs text-gray-500 font-medium bg-gray-100 px-2 py-0.5 rounded-md">
                                            {{ $job->jobCategory->name ?? 'General' }}
                                        </span>
                                    </div>

                                    <!-- Job Title -->
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition-colors mb-2 truncate">
                                        {{ $job->title }}
                                    </h3>

                                    <!-- Meta Info (Bottom Row) -->
                                    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 font-medium">
                                        <span class="flex items-center gap-1.5" title="Location">
                                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                                            {{ $job->event->city->name ?? 'Jakarta' }}
                                        </span>
                                        <span class="flex items-center gap-1.5" title="Application Deadline">
                                            <i class="fas fa-calendar text-gray-400"></i>
                                            {{ $job->application_deadline->format('d M Y') }}
                                        </span>
                                        <span class="flex items-center gap-1.5" title="Slots Filled">
                                            <i class="fas fa-users text-gray-400"></i>
                                            {{ $job->slots_filled }}/{{ $job->slots_total }} Slots
                                        </span>
                                    </div>
                                </div>

                                <!-- Action / Status -->
                                <div class="hidden md:flex flex-col items-end gap-2 pl-4 border-l border-gray-100 h-full justify-center min-w-[100px]">
                                    @if($job->application_deadline->isPast())
                                        <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-full">
                                            Closed
                                        </span>
                                    @else
                                        @php
                                            $isSaved = isset($savedJobIds) && in_array($job->id, $savedJobIds);
                                        @endphp
                                        <button onclick="event.preventDefault(); toggleSaveJob({{ $job->id }})" id="saveBtn-{{ $job->id }}" 
                                            class="w-10 h-10 rounded-full flex items-center justify-center transition-colors {{ $isSaved ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600' }}">
                                            <i class="{{ $isSaved ? 'fas' : 'far' }} fa-bookmark"></i>
                                        </button>
                                        <span class="text-xs text-gray-400">
                                            Apply Now <i class="fas fa-arrow-right ml-1 -rotate-45 group-hover:rotate-0 transition-transform"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="bg-white/60 backdrop-blur-xl rounded-2xl p-12 text-center border-2 border-dashed border-gray-200">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                <i class="fas fa-search text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">No jobs found</h3>
                            <p class="text-sm text-gray-500 mt-2">Try adjusting your search or filters.</p>
                            <a href="{{ route('jobs.index') }}" class="inline-block mt-4 text-sm font-semibold text-red-600 hover:text-red-700">Clear all filters</a>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($jobs->hasPages())
                    <div class="mt-8">
                        {{ $jobs->withQueryString()->links('pagination::tailwind') }}
                    </div>
                    @endif
                </div>

                <!-- Right Sidebar: Filters -->
                <div id="filter-sidebar" class="hidden lg:block w-full lg:w-80 flex-shrink-0 order-1 lg:order-2 space-y-6">
                    <form action="{{ route('jobs.index') }}" method="GET" id="filter-form">
                        
                        <!-- Search Box -->
                        <div class="bg-white/80 backdrop-blur-md rounded-2xl p-5 border border-white/60 shadow-lg">
                            <h3 class="font-bold text-gray-900 mb-4">Search</h3>
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all placeholder-gray-400"
                                       placeholder="Job title, keyword...">
                            </div>
                        </div>

                        <!-- Role Filter -->
                        <div class="bg-white/80 backdrop-blur-md rounded-2xl p-5 border border-white/60 shadow-lg mt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-gray-900">Job Roles</h3>
                                <i class="fas fa-chevron-up text-gray-400 text-xs"></i>
                            </div>
                            <div class="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($categories as $category)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                               {{ in_array($category->id, (array)request('categories')) ? 'checked' : '' }}
                                               class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded md:rounded-md bg-white checked:bg-red-600 checked:border-red-600 transition-all">
                                        <i class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-xs opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                                    </div>
                                    <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">{{ $category->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Location Filter -->
                        <div class="bg-white/80 backdrop-blur-md rounded-2xl p-5 border border-white/60 shadow-lg mt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-gray-900">Location</h3>
                                <i class="fas fa-chevron-up text-gray-400 text-xs"></i>
                            </div>
                            <div class="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($cities as $city)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="cities[]" value="{{ $city->name }}"
                                               {{ in_array($city->name, (array)request('cities')) ? 'checked' : '' }}
                                               class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded md:rounded-md bg-white checked:bg-red-600 checked:border-red-600 transition-all">
                                        <i class="fas fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-xs opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                                    </div>
                                    <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">{{ $city->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Apply Button -->
                        <button type="submit" class="w-full mt-6 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-red-500/20 active:scale-95">
                            Apply Filters
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>

<!-- Quick Apply Modal -->
<div id="quickApplyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 relative shadow-2xl">
        <button onclick="hideQuickApplyModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>

        <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Application</h3>
        <p class="text-gray-600 mb-6">Submit your application for this position.</p>

        <form id="quickApplyForm" onsubmit="event.preventDefault(); submitQuickApplication();">
            <div class="space-y-4 mb-6">
                <div>
                    <label for="quickMotivation" class="block text-sm font-medium text-gray-700 mb-2">
                        Why are you interested? *
                    </label>
                    <textarea id="quickMotivation" name="motivation" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Briefly explain your interest..."></textarea>
                </div>

                <div>
                    <label for="quickExperience" class="block text-sm font-medium text-gray-700 mb-2">
                        Relevant Experience
                    </label>
                    <textarea id="quickExperience" name="experience" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Optional: Describe your experience..."></textarea>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="hideQuickApplyModal()" 
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                    Submit Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentJobId = null;

    function quickApply(jobId) {
        currentJobId = jobId;
        document.getElementById('quickApplyModal').classList.remove('hidden');
        document.getElementById('quickApplyModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function hideQuickApplyModal() {
        document.getElementById('quickApplyModal').classList.add('hidden');
        document.getElementById('quickApplyModal').classList.remove('flex');
        document.body.style.overflow = '';
        currentJobId = null;
    }

    function submitQuickApplication() {
        if (!currentJobId) return;

        const motivation = document.getElementById('quickMotivation').value;
        const experience = document.getElementById('quickExperience').value;

        const submitButton = document.querySelector('#quickApplyForm button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...';

        fetch(`/jobs/${currentJobId}/apply`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                motivation: motivation,
                experience: experience
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Application submitted successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to submit application'));
                submitButton.disabled = false;
                submitButton.textContent = 'Submit Application';
            }
            hideQuickApplyModal();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            submitButton.disabled = false;
            submitButton.textContent = 'Submit Application';
            hideQuickApplyModal();
        });
    }

    function toggleSaveJob(jobId) {
        const button = document.getElementById(`saveBtn-${jobId}`);
        const icon = button.querySelector('i');
        const isSaved = icon.classList.contains('fas'); // check current state
        
        // Prevent multiple clicks
        if(button.disabled) return;
        button.disabled = true;

        const url = isSaved 
            ? `/dashboard/jobs/${jobId}/unsave` 
            : `/dashboard/jobs/${jobId}/save`;
        
        const method = isSaved ? 'DELETE' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    window.location.href = '{{ route("login") }}';
                    throw new Error('Unauthorized');
                }
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (isSaved) {
                    // Was saved, now unsaved
                    icon.classList.remove('fas', 'text-red-600');
                    icon.classList.add('far');
                    button.classList.remove('bg-red-50', 'text-red-600');
                    button.classList.add('bg-gray-50', 'text-gray-400');
                } else {
                    // Was unsaved, now saved
                    icon.classList.remove('far');
                    icon.classList.add('fas', 'text-red-600');
                    button.classList.remove('bg-gray-50', 'text-gray-400');
                    button.classList.add('bg-red-50', 'text-red-600');
                }
            } else {
                alert('Action failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if(error.message !== 'Unauthorized') {
               // alert('An error occurred.'); // Optional: suppress to avoid annoyance
            }
        })
        .finally(() => {
            button.disabled = false;
        });
    }

    // Close modal on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideQuickApplyModal();
        }
    });

    // Close modal on outside click
    document.getElementById('quickApplyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideQuickApplyModal();
        }
    });
</script>
@endsection