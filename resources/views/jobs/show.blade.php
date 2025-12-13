@extends('layouts.public')

@section('title', $job->title . ' - NOCIS')

@section('content')
<!-- Aurora Header Background -->
<div class="relative bg-white pt-24 pb-12 overflow-hidden">
    <div class="absolute top-0 left-0 right-0 h-[500px] bg-gradient-to-b from-red-50 via-white to-white z-0"></div>
    <div class="absolute top-[-100px] right-[-100px] w-[500px] h-[500px] bg-red-100/50 rounded-full blur-[100px] pointer-events-none mix-blend-multiply opacity-70"></div>
    <div class="absolute top-[-100px] left-[-100px] w-[400px] h-[400px] bg-blue-50/50 rounded-full blur-[100px] pointer-events-none mix-blend-multiply opacity-70"></div>

    <div class="container mx-auto px-4 max-w-7xl relative z-10">
        <!-- Breadcrumb / Back -->
        <div class="mb-8">
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-red-600 transition-colors">
                <i class="fas fa-arrow-left"></i> Back to Jobs
            </a>
        </div>

        <!-- Header Content -->
        <div class="flex flex-col lg:flex-row items-start justify-between gap-8">
            <div>
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">
                        <i class="fas fa-tag text-[10px]"></i> {{ $job->jobCategory->name }}
                    </span>
                    @if($job->status === 'open')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-600 border border-green-100">
                            <i class="fas fa-check-circle text-[10px]"></i> Open for Applications
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                            <i class="fas fa-lock text-[10px]"></i> {{ ucfirst($job->status) }}
                        </span>
                    @endif
                </div>
                
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight mb-4 leading-tight">
                    {{ $job->title }}
                </h1>
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 text-sm text-gray-500 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-trophy text-red-500 text-xs"></i>
                        <span>{{ $job->event->title }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-red-500 text-xs"></i>
                        <span>{{ $job->event->city->name }}, Indonesia</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="far fa-calendar-alt text-red-500 text-xs"></i>
                        <span>{{ $job->event->start_at->format('d M Y') }} - {{ $job->event->end_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions (Mobile Only) - Removed as per feedback -->
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container mx-auto px-4 max-w-7xl pb-20">
    <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">
        
        <!-- Left Column: Details (8 cols) -->
        <div class="lg:col-span-8 space-y-10">
            
            <!-- Overview Grid -->
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-1 tracking-wide">Venue</p>
                    <p class="font-medium text-gray-800">{{ $job->event->venue }}</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-1 tracking-wide">Application Deadline</p>
                    <p class="font-medium text-gray-800">{{ $job->application_deadline->format('d F Y, H:i') }}</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-1 tracking-wide">Slots Available</p>
                    <p class="font-medium text-gray-800">
                        <span class="text-green-600 font-semibold">{{ $job->slots_total - $job->slots_filled }}</span> 
                        <span class="text-gray-400 text-xs font-normal">/ {{ $job->slots_total }} total</span>
                    </p>
                </div>
                <div class="p-4 rounded-xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-1 tracking-wide">Posted On</p>
                    <p class="font-medium text-gray-800">{{ $job->created_at->format('d F Y') }}</p>
                </div>
            </div>

            <!-- Description -->
            <section>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-600">
                        <i class="fas fa-align-left text-sm"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Job Description</h2>
                </div>
                <div class="prose prose-gray max-w-none text-gray-600 leading-relaxed text-base">
                    {!! nl2br(e($job->description)) !!}
                </div>
            </section>
            
            <hr class="border-gray-100">

            <!-- Requirements -->
            @if(!empty($job->requirements))
            <section>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fas fa-clipboard-check text-sm"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Requirements</h2>
                </div>
                <ul class="space-y-3">
                    @foreach($job->requirements as $requirement)
                    <li class="flex items-start gap-3 text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mt-1 flex-shrink-0 text-sm"></i>
                        <span>{{ $requirement }}</span>
                    </li>
                    @endforeach
                </ul>
            </section>
            <hr class="border-gray-100">
            @endif

            <!-- Benefits -->
            @if(!empty($job->benefits))
            <section>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600">
                        <i class="fas fa-gift text-sm"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Benefits</h2>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach(explode("\n", $job->benefits) as $benefit)
                        @if(trim($benefit))
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 border border-gray-100">
                            <i class="fas fa-star text-yellow-500 text-sm"></i>
                            <span class="text-sm font-medium text-gray-700">{{ trim($benefit) }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </section>
            @endif

        </div>

        <!-- Right Column: Sidebar (4 cols) -->
        <div class="lg:col-span-4">
            <div id="action-card" class="bg-white rounded-2xl p-6 shadow-xl shadow-gray-200/50 sticky top-24 lg:-mt-32 relative z-20">
                <h3 class="font-bold text-gray-900 mb-2">Interested?</h3>
                <p class="text-sm text-gray-500 mb-6 leading-relaxed">Don't miss out on this opportunity to be part of the NOCIS team for {{ $job->event->title }}.</p>

                @php
                    $isCustomerAuthenticated = session('customer_authenticated');
                    $customerId = session('customer_id');
                    $hasApplied = false;
                    
                    if ($isCustomerAuthenticated && $customerId) {
                        $hasApplied = \App\Models\Application::where('worker_opening_id', $job->id)
                            ->where('user_id', $customerId)
                            ->exists();
                    }
                @endphp

                <div class="space-y-3">
                    @if($isCustomerAuthenticated)
                        @if($hasApplied)
                            <button disabled class="w-full bg-gray-100 text-gray-500 py-3.5 px-4 rounded-xl font-semibold border border-gray-200 cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fas fa-check"></i> Already Applied
                            </button>
                        @else
                            <button id="applyButton" onclick="showApplyModal()" class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white py-3.5 px-4 rounded-xl font-semibold shadow-lg shadow-red-500/30 hover:shadow-red-500/40 transform transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                                Apply Now <i class="fas fa-arrow-right text-xs"></i>
                            </button>
                        @endif

                        @if($isSaved)
                            <button id="saveBtn" onclick="toggleSaveJob({{ $job->id }})" class="w-full bg-white hover:bg-gray-50 text-red-600 py-3.5 px-4 rounded-xl font-semibold border border-red-200 transition-colors flex items-center justify-center gap-2 group">
                                <i class="fas fa-bookmark text-red-600"></i> Saved
                            </button>
                        @else
                            <button id="saveBtn" onclick="toggleSaveJob({{ $job->id }})" class="w-full bg-white hover:bg-gray-50 text-gray-700 py-3.5 px-4 rounded-xl font-semibold border border-gray-200 transition-colors flex items-center justify-center gap-2 group">
                                <i class="far fa-bookmark group-hover:text-red-500 transition-colors"></i> Save Job
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3.5 px-4 rounded-xl font-semibold shadow-lg shadow-red-500/30 text-center transition-all">
                            Login to Apply
                        </a>
                        <button onclick="window.location.href='{{ route('login') }}'" class="w-full bg-white hover:bg-gray-50 text-gray-700 py-3.5 px-4 rounded-xl font-semibold border border-gray-200 transition-colors flex items-center justify-center gap-2 group">
                            <i class="far fa-bookmark group-hover:text-red-500 transition-colors"></i> Save Job
                        </button>
                    @endif
                </div>

                <!-- Support Text -->
                <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-400">
                        Need help? Contact <a href="#" class="text-red-600 hover:underline">support@nocis.id</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Apply Modal (Preserved Logic, Updated Style) -->
<div id="applyModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="hideApplyModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal Panel -->
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-file-signature text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Apply for {{ $job->title }}</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Please answer a few questions to complete your application.</p>
                                
                                <form id="applicationForm" onsubmit="event.preventDefault(); submitApplication();" class="mt-4 space-y-4">
                                    <div>
                                        <label for="motivation" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Why are you interested using this position? <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="motivation" name="motivation" rows="3" required
                                                  class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all text-sm"
                                                  placeholder="I am passionate about sports..."></textarea>
                                    </div>


                                    
                                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-3">
                                        <button type="submit" id="submitBtn" class="inline-flex w-full justify-center rounded-xl bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 sm:w-auto">
                                            Submit Application
                                        </button>
                                        <button type="button" onclick="hideApplyModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showApplyModal() {
        document.getElementById('applyModal').classList.remove('hidden');
    }

    function hideApplyModal() {
        document.getElementById('applyModal').classList.add('hidden');
    }

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') hideApplyModal();
    });

    function submitApplication() {
        const submitBtn = document.getElementById('submitBtn');
        const applyButton = document.getElementById('applyButton'); // Main page button
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        // Get form data
        const motivation = document.getElementById('motivation').value;

        // Submit via AJAX
        fetch("{{ route('jobs.apply', $job) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                motivation: motivation
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success State
                alert('Application submitted successfully!');
                
                // Update UI without reload
                hideApplyModal();
                if(applyButton) {
                    applyButton.parentElement.innerHTML = `
                        <button disabled class="w-full bg-gray-100 text-gray-500 py-3.5 px-4 rounded-xl font-bold border border-gray-200 cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fas fa-check"></i> Already Applied
                        </button>
                    `;
                }
            } else {
                alert('Error: ' + (data.message || 'Failed to submit application'));
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Application';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Submit Application';
        });
    }

    function toggleSaveJob(jobId) {
        const button = document.getElementById('saveBtn');
        const icon = button.querySelector('i');
        const isSaved = icon.classList.contains('fas'); // simple check state
        
        // Disable button strictly during fetch
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
                    button.innerHTML = '<i class="far fa-bookmark group-hover:text-red-500 transition-colors"></i> Save Job';
                    button.classList.remove('text-red-600', 'border-red-200');
                    button.classList.add('text-gray-700', 'border-gray-200');
                } else {
                    // Was unsaved, now saved
                    button.innerHTML = '<i class="fas fa-bookmark text-red-600"></i> Saved';
                    button.classList.remove('text-gray-700', 'border-gray-200');
                    button.classList.add('text-red-600', 'border-red-200');
                }
            } else {
                alert('Action failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if(error.message !== 'Unauthorized') {
               alert('An error occurred.');
            }
        })
        .finally(() => {
            button.disabled = false;
        });
    }
</script>
@endsection