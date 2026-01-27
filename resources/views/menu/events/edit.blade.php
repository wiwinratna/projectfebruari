@extends('layouts.app')

@section('title', 'Edit Event - KOI')
@section('page-title')
    Edit Event
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <form method="POST" action="{{ route('admin.events.update', $event) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('menu.events.partials.form-fields')

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.events.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Back to Events
                </a>
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="px-5 py-2 rounded-lg bg-red-500 text-white font-semibold hover:bg-red-600">
                        Update Event
                    </button>
                </div>
            </div>
        </form>
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
            showFlashMessage(`Event "${name}" created successfully!`, 'status');
            // Remove parameters from URL without reload
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        } else if (flash === 'updated' && name) {
            showFlashMessage(`Event "${name}" updated successfully!`, 'status');
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
    }
    
    // Check URL flash messages on page load
    document.addEventListener('DOMContentLoaded', checkUrlFlashMessages);
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const wrap = document.getElementById('accessRows');
  const addBtn = document.getElementById('addAccessRow');

  if (!wrap || !addBtn) return;

  function reindex() {
    const rows = wrap.querySelectorAll('.access-row');
    rows.forEach((row, idx) => {
      row.querySelectorAll('input').forEach((inp) => {
        inp.name = inp.name.replace(/access_codes\[\d+\]/g, `access_codes[${idx}]`);
      });
    });
  }

  function rowTemplate(idx) {
    return `
      <div class="flex items-center gap-2 access-row">
        <div class="w-56 shrink-0">
          <input
            name="access_codes[${idx}][code]"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
            placeholder="VIP / GATE-A / ROOM-1">
        </div>

        <div class="flex-1 min-w-0">
          <input
            name="access_codes[${idx}][label]"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
            placeholder="Arti / Deskripsi kode">
        </div>

        <div class="w-10 shrink-0 flex justify-center">
          <input
            type="color"
            name="access_codes[${idx}][color_hex]"
            value="#EF4444"
            class="w-9 h-9 p-0 border border-gray-300 rounded-md cursor-pointer bg-white">
        </div>

        <div class="w-10 shrink-0 flex justify-center">
          <button type="button"
            class="removeAccessRow text-gray-400 hover:text-red-500 px-2 py-2"
            title="Hapus baris">✕</button>
        </div>
      </div>
    `;
  }

  addBtn.addEventListener('click', () => {
    const idx = wrap.querySelectorAll('.access-row').length;
    wrap.insertAdjacentHTML('beforeend', rowTemplate(idx));
  });

  wrap.addEventListener('click', (e) => {
    const btn = e.target.closest('.removeAccessRow');
    if (!btn) return;

    btn.closest('.access-row')?.remove();
    reindex();
  });
});
</script>

@endsection

