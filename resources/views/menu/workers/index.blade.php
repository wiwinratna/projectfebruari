@extends('layouts.app')

@section('title', 'Worker Job Openings - NOCIS')
@section('page-title')
    Workers <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-6">
    
    {{-- Search Bar & Create Job Opening --}}
    <div class="flex items-center justify-between">
        <div class="relative flex items-center border border-gray-300 rounded-lg py-2 px-4 pl-10 bg-white">
            <i class="fas fa-search absolute left-3 text-gray-400"></i>
            <input type="text" placeholder="Search job openings..." class="focus:outline-none w-64 ml-2" disabled>
        </div>
        <a href="{{ route('workers.create') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Create Job Opening
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Job Openings</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_openings'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Active Openings</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['active_openings'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Applications</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_applications'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-gray-500 text-sm font-semibold mb-2">Positions Filled</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['positions_filled'] }}</p>
        </div>
    </div>

    {{-- Job Openings List --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @php
            $statusMap = [
                'open' => ['label' => 'Active', 'class' => 'bg-green-500 text-white'],
                'planned' => ['label' => 'Planned', 'class' => 'bg-blue-500 text-white'],
                'draft' => ['label' => 'Draft', 'class' => 'bg-gray-500 text-white'],
                'closed' => ['label' => 'Closed', 'class' => 'bg-gray-700 text-white'],
            ];
        @endphp
        @forelse ($openings as $opening)
            <div class="bg-white rounded-lg shadow-sm p-6 flex flex-col space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-1">{{ $opening->title }}</h4>
                        <p class="text-gray-600 text-sm">{{ $opening->event->title ?? 'Event TBA' }}</p>
                        <p class="text-gray-500 text-xs mt-1">{{ $opening->jobCategory->name ?? 'Kategori tidak ada' }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs px-2 py-1 rounded {{ $statusMap[$opening->status]['class'] ?? 'bg-gray-300 text-gray-700' }}">
                            {{ $statusMap[$opening->status]['label'] ?? ucfirst($opening->status) }}
                        </span>
                        <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700">
                            Slots: {{ $opening->slots_filled }}/{{ $opening->slots_total }}
                        </span>
                    </div>
                </div>

                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-calendar mr-2 text-gray-400"></i>
                        <span>
                            Deadline: {{ optional($opening->application_deadline)->translatedFormat('d M Y H:i') ?? 'TBD' }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                        <span>{{ $opening->event->venue ?? 'Lokasi menyusul' }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-users mr-2 text-gray-400"></i>
                        <span>{{ $opening->slots_total }} positions available | {{ $opening->applications_count }} applications</span>
                    </div>
                </div>

                <div>
                    <h5 class="text-sm font-semibold text-gray-700 mb-2">Requirements:</h5>
                    <ul class="text-sm text-gray-600 space-y-1">
                        @forelse ((array) $opening->requirements as $requirement)
                            <li>- {{ $requirement }}</li>
                        @empty
                            <li>- Belum ada requirement spesifik.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="flex space-x-2 pt-2">
                    <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm">View Applications</button>
                    <a href="{{ route('workers.edit', $opening) }}" class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">Edit</a>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center text-gray-500 py-12">
                <i class="fas fa-hands-helping text-4xl mb-3"></i>
                <p>Belum ada lowongan worker. Buat lowongan pertama Anda.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
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
    
    // Check for URL flash parameters
    function checkUrlFlashMessages() {
        const urlParams = new URLSearchParams(window.location.search);
        const flash = urlParams.get('flash');
        const name = urlParams.get('name');
        
        if (flash === 'created' && name) {
            showFlashMessage(`Worker opening "${name}" created successfully!`, 'status');
            // Remove parameters from URL without reload
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        } else if (flash === 'updated' && name) {
            showFlashMessage(`Worker opening "${name}" updated successfully!`, 'status');
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
    }
    
    // Check URL flash messages on page load
    document.addEventListener('DOMContentLoaded', checkUrlFlashMessages);
</script>
@endsection
