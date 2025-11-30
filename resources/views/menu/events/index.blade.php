@extends('layouts.app') {{-- Memperluas master layout --}}

@section('title', 'Events & Competitions - KOI')
@section('page-title')
    Events & Competitions <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-6">
    
    {{-- Search Bar --}}
    <div class="flex items-center justify-between">
        <div class="relative flex items-center border border-gray-300 rounded-lg py-2 px-4 pl-10 bg-white">
            <i class="fas fa-search absolute left-3 text-gray-400"></i>
            <input type="text" placeholder="Search events..." class="focus:outline-none w-64 ml-2" disabled>
        </div>
        <a href="{{ route('events.create') }}"
           class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold flex items-center">
            <i class="fas fa-plus mr-2"></i> Create Event
        </a>
    </div>

    {{-- Event stats --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Total Events</p>
            <p class="text-3xl font-semibold text-gray-800 mt-2">{{ $stats['total_events'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Active Events</p>
            <p class="text-3xl font-semibold text-gray-800 mt-2">{{ $stats['active_events'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Upcoming</p>
            <p class="text-3xl font-semibold text-gray-800 mt-2">{{ $stats['upcoming_events'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Planning</p>
            <p class="text-3xl font-semibold text-gray-800 mt-2">{{ $stats['planning_events'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-500">Worker Openings</p>
            <p class="text-3xl font-semibold text-gray-800 mt-2">{{ $stats['worker_openings'] }}</p>
        </div>
    </div>

    {{-- Main Content Area --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Event List --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow space-y-6">
            @php
                $statusColors = [
                    'active' => 'bg-green-500 text-white',
                    'upcoming' => 'bg-blue-500 text-white',
                    'planning' => 'bg-yellow-500 text-white',
                    'draft' => 'bg-gray-400 text-white',
                ];

                $priorityColors = [
                    'high' => 'bg-red-500 text-white',
                    'medium' => 'bg-yellow-500 text-white',
                    'low' => 'bg-gray-400 text-white',
                ];
            @endphp
            @forelse ($events as $event)
                <div class="{{ !$loop->last ? 'border-b border-gray-200 pb-6' : '' }}">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-3">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-1">{{ $event->title }}</h4>
                            <p class="text-gray-600 text-sm">{{ $event->venue ?? 'Venue TBA' }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs px-2 py-1 rounded {{ $statusColors[$event->status] ?? 'bg-gray-300 text-gray-700' }}">
                                {{ ucfirst($event->status) }}
                            </span>
                            <span class="text-xs px-2 py-1 rounded {{ $priorityColors[$event->priority] ?? 'bg-gray-300 text-gray-700' }}">
                                {{ ucfirst($event->priority) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 text-gray-600 text-sm mb-4">
                        <span class="flex items-center">
                            <i class="fas fa-clock mr-2 text-gray-400"></i>
                            {{ optional($event->start_at)->translatedFormat('d M Y') }} &ndash;
                            {{ optional($event->end_at)->translatedFormat('d M Y') ?? 'TBD' }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                            {{ $event->city ?? 'Lokasi belum ditentukan' }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-users mr-2 text-gray-400"></i>
                            Capacity: {{ number_format($event->capacity) }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-2">Sports:</p>
                        <div class="flex flex-wrap gap-2">
                            @forelse ($event->sports as $sport)
                                <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full">{{ $sport->name }}</span>
                            @empty
                                <span class="text-xs text-gray-500">Belum ada cabang olahraga.</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm text-gray-600 mb-4">
                        <div>
                            <p class="text-xs uppercase text-gray-400">Worker Roles</p>
                            <p class="font-semibold">{{ $event->worker_openings_count }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Slots Total</p>
                            <p class="font-semibold">{{ $event->slots_total_sum ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Applications</p>
                            <p class="font-semibold">{{ $event->applications_count }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Contact PIC</p>
                            <p class="font-semibold">
                                {{ data_get($event->contact_info, 'pic', 'TBD') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('events.edit', $event) }}"
                           class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                            Edit
                        </a>
                        @if($event->applications_count == 0 && $event->worker_openings_count == 0)
                            <button onclick="deleteEvent({{ $event->id }}, '{{ addslashes($event->title) }}')" 
                                    data-event-id="{{ $event->id }}"
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        @else
                            <div class="bg-gray-100 text-gray-500 px-4 py-2 rounded-lg text-sm border border-gray-200" title="Cannot delete - currently has applications or worker openings">
                                <i class="fas fa-lock mr-1"></i> In Use
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-12">
                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                    <p>Belum ada event terdaftar. Mulai dengan membuat event baru.</p>
                </div>
            @endforelse
        </div>

        {{-- Right Sidebar --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Event Calendar --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Event Calendar</h3>
                <div class="calendar-widget">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-semibold text-lg text-gray-800">{{ $calendarMonth }}</span>
                        <span class="text-sm text-gray-400">Auto-generated</span>
                    </div>
                    <div class="grid grid-cols-7 gap-1 text-xs text-gray-600 mb-2 text-center">
                        <div class="py-1">Su</div>
                        <div class="py-1">Mo</div>
                        <div class="py-1">Tu</div>
                        <div class="py-1">We</div>
                        <div class="py-1">Th</div>
                        <div class="py-1">Fr</div>
                        <div class="py-1">Sa</div>
                    </div>
                    @php $today = now()->day; @endphp
                    <div class="grid grid-cols-7 gap-1 text-sm text-center">
                        @foreach ($calendarDays as $day)
                            <div class="py-2 rounded-full {{ $day === $today ? 'bg-blue-600 text-white font-semibold' : 'hover:bg-gray-100 cursor-pointer' }}">
                                {{ $day }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="bg-white p-6 rounded-lg shadow space-y-4">
                <h3 class="text-xl font-bold text-gray-800">Quick Stats</h3>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Total Applications</span>
                    <span class="font-semibold text-gray-900">{{ $stats['total_applications'] }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Events w/ Workers</span>
                    <span class="font-semibold text-gray-900">
                        {{ $events->where('worker_openings_count', '>', 0)->count() }}
                    </span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Avg Slots per Event</span>
                    <span class="font-semibold text-gray-900">
                        {{ $events->count() ? number_format(($events->sum('slots_total_sum') ?? 0) / $events->count(), 1) : 0 }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Modal management functions
    function showConfirmModal(title, message, details, onConfirm) {
        const modal = document.getElementById('confirm-modal');
        const titleEl = document.getElementById('confirm-title');
        const messageEl = document.getElementById('confirm-message');
        const detailsEl = document.getElementById('confirm-details');
        const yesBtn = document.getElementById('confirm-yes');
        const cancelBtn = document.getElementById('confirm-cancel');

        titleEl.textContent = title;
        messageEl.textContent = message;
        detailsEl.textContent = details || '';
        
        // Remove previous event listeners
        yesBtn.replaceWith(yesBtn.cloneNode(true));
        cancelBtn.replaceWith(cancelBtn.cloneNode(true));
        
        // Get new references after cloning
        const newYesBtn = document.getElementById('confirm-yes');
        const newCancelBtn = document.getElementById('confirm-cancel');
        
        // Add event listeners
        newYesBtn.addEventListener('click', () => {
            hideConfirmModal();
            onConfirm();
        });
        
        newCancelBtn.addEventListener('click', hideConfirmModal);
        
        // Show modal
        modal.classList.remove('hidden');
        
        // Focus management for accessibility
        newYesBtn.focus();
    }
    
    function hideConfirmModal() {
        const modal = document.getElementById('confirm-modal');
        modal.classList.add('hidden');
    }
    
    function showLoading() {
        const overlay = document.getElementById('loading-overlay');
        overlay.classList.remove('hidden');
    }
    
    function hideLoading() {
        const overlay = document.getElementById('loading-overlay');
        overlay.classList.add('hidden');
    }
    
    function showFlashMessage(message, type = 'status') {
        // Create flash message directly in DOM
        const flashContainer = document.getElementById('flash-container') || createFlashContainer();
        
        // Prevent duplicate messages
        const existingMessages = flashContainer.querySelectorAll('.flash-message');
        for (let msg of existingMessages) {
            if (msg.textContent.trim() === message.trim()) {
                return; // Don't show duplicate
            }
        }
        
        const iconMap = {
            'status': 'fas fa-check-circle',
            'error': 'fas fa-exclamation-circle',
            'warning': 'fas fa-exclamation-triangle'
        };
        
        const classMap = {
            'status': 'bg-green-500 text-white',
            'error': 'bg-red-500 text-white',
            'warning': 'bg-yellow-500 text-white'
        };
        
        const flashMessage = document.createElement('div');
        flashMessage.className = `flash-message ${classMap[type]} shadow-lg rounded-lg px-4 py-3 text-sm flex items-start gap-3 transition duration-300 ease-out`;
        flashMessage.setAttribute('data-timeout', '4500');
        flashMessage.setAttribute('role', 'alert');
        flashMessage.innerHTML = `
            <i class="${iconMap[type]} mt-0.5"></i>
            <div class="flex-1">${message}</div>
            <button type="button" class="text-white/70 hover:text-white transition" data-flash-close aria-label="Close notification">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Set initial styles for animation
        flashMessage.style.opacity = '0';
        flashMessage.style.transform = 'translateX(100%)';
        
        flashContainer.appendChild(flashMessage);
        
        // Auto hide after timeout
        setTimeout(() => hideFlashMessage(flashMessage), 4500);
        
        // Manual close button
        flashMessage.querySelector('[data-flash-close]').addEventListener('click', () => {
            hideFlashMessage(flashMessage);
        });
        
        // Show with animation
        requestAnimationFrame(() => {
            flashMessage.style.opacity = '1';
            flashMessage.style.transform = 'translateX(0)';
        });
    }
    
    function createFlashContainer() {
        // Use the existing flash container from server-side, don't create new one
        const existingContainer = document.getElementById('flash-container');
        if (existingContainer) {
            return existingContainer;
        }
        
        // If no existing container, create one
        const container = document.createElement('div');
        container.id = 'flash-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
        return container;
    }
    
    function hideFlashMessage(element) {
        element.style.opacity = '0';
        element.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 300);
    }
    
    function deleteEvent(id, eventTitle) {
        const details = eventTitle ? `Event: "${eventTitle}"` : `This action cannot be undone.`;
        
        showConfirmModal(
            'Delete Event',
            'Are you sure you want to delete this event?',
            details,
            () => performDelete(id)
        );
    }
    
    function performDelete(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showFlashMessage('Security token not found. Please refresh the page and try again.', 'error');
            return;
        }

        showLoading();
        
        fetch(`/events/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            hideLoading();
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showFlashMessage('Event deleted successfully!', 'status');
                
                // Auto refresh page after successful deletion
                setTimeout(() => {
                    window.location.reload();
                }, 500);
                
            } else {
                showFlashMessage(data.message || 'Failed to delete event', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showFlashMessage('Error deleting event: ' + error.message, 'error');
        });
    }
    
    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('confirm-modal');
        if (e.target === modal && !modal.classList.contains('hidden')) {
            hideConfirmModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideConfirmModal();
        }
    });
    
    // Check for URL flash parameters
    function checkUrlFlashMessages() {
        const urlParams = new URLSearchParams(window.location.search);
        const flash = urlParams.get('flash');
        const name = urlParams.get('name');
        
        if (flash === 'created' && name) {
            showFlashMessage(`Event "${name}" created successfully!`, 'status');
            // Remove parameters from URL and refresh page
            setTimeout(() => {
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
                window.location.reload();
            }, 500);
        } else if (flash === 'updated' && name) {
            showFlashMessage(`Event "${name}" updated successfully!`, 'status');
            // Remove parameters from URL and refresh page
            setTimeout(() => {
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
                window.location.reload();
            }, 500);
        }
    }
    
    // Check URL flash messages on page load
    document.addEventListener('DOMContentLoaded', checkUrlFlashMessages);
</script>

{{-- Include Confirm Modal Component --}}
@include('components.confirm-modal')

@endsection
