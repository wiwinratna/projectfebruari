@extends('layouts.app')

@section('title', 'Job Categories - NOCIS')
@section('page-title')
    Job Categories <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-6">
    
    {{-- Header with Add Button --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manage Job Categories</h2>
            <p class="text-gray-600 mt-1">Organize worker positions by category</p>
        </div>
        <a href="{{ route('categories.create') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Category
        </a>
    </div>

    {{-- Categories Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">All Categories</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Openings</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">{{ $category->description ?? 'No description' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->workerType)
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                        {{ $category->workerType->name === 'VO' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                        {{ $category->workerType->name }}
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                        No Type
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $category->workerOpenings->count() > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $category->workerOpenings->count() }} openings
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('categories.edit', $category) }}" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                @if($category->workerOpenings->count() == 0)
                                    <button onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}')" 
                                            data-category-id="{{ $category->id }}"
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                @else
                                    <span class="text-gray-400 text-sm" title="Cannot delete - currently in use">
                                        <i class="fas fa-lock mr-1"></i> In Use
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-tags text-4xl mb-3"></i>
                                    <p class="text-lg font-medium">No categories found</p>
                                    <p class="text-sm">Create your first job category to get started</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
    
    function deleteCategory(id, categoryName) {
        const details = categoryName ? `Category: "${categoryName}"` : `This action cannot be undone.`;
        
        showConfirmModal(
            'Delete Category',
            'Are you sure you want to delete this category?',
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
        
        fetch(`/categories/${id}`, {
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
                showFlashMessage('Category deleted successfully!', 'status');
                
                // Auto refresh page after successful deletion
                setTimeout(() => {
                    window.location.reload();
                }, 500);
                
            } else {
                showFlashMessage(data.message || 'Failed to delete category', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showFlashMessage('Error deleting category: ' + error.message, 'error');
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
            showFlashMessage(`Category "${name}" created successfully!`, 'status');
            // Remove parameters from URL and refresh page
            setTimeout(() => {
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
                window.location.reload();
            }, 500);
        } else if (flash === 'updated' && name) {
            showFlashMessage(`Category "${name}" updated successfully!`, 'status');
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
@endsection