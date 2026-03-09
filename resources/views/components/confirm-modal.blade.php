{{-- Confirm Modal Component --}}
<div id="confirm-modal" class="fixed inset-0 flex items-center justify-center z-[9999] hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black bg-opacity-40" onclick="hideConfirmModal()"></div>
    <!-- Modal Content -->
    <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 border border-gray-200">
        <div class="p-8">
            <div class="flex items-center mb-6">
                <div class="bg-red-100 rounded-full p-4 mr-5 shadow-lg">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-1" id="confirm-title">Confirm Action</h3>
                    <p class="text-base text-gray-600" id="confirm-message">Are you sure you want to proceed?</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 mb-6 border-l-4 border-red-400">
                <p class="text-sm font-medium text-gray-800" id="confirm-details"></p>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button"
                    onclick="hideConfirmModal()"
                    class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200 font-medium">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="button"
                    onclick="window.__confirmYes()"
                    class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-200 font-medium shadow-lg">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Loading Overlay --}}
<div id="loading-overlay" class="fixed inset-0 flex items-center justify-center z-[9998] hidden">
    <div class="bg-white rounded-lg shadow-lg px-6 py-4 flex items-center space-x-3 border border-gray-200">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-500"></div>
        <span class="text-gray-700 font-medium">Menghapus...</span>
    </div>
</div>

<script>
    window.__deleteCallback = null;

    window.__confirmYes = function() {
        var cb = window.__deleteCallback;
        hideConfirmModal();
        if (typeof cb === 'function') {
            cb();
        }
    };

    function showConfirmModal(title, message, details, onConfirm) {
        window.__deleteCallback = onConfirm;
        document.getElementById('confirm-title').textContent = title || 'Confirm Action';
        document.getElementById('confirm-message').textContent = message || 'Are you sure?';
        var detailsEl = document.getElementById('confirm-details');
        if (detailsEl) detailsEl.textContent = details || '';
        document.getElementById('confirm-modal').classList.remove('hidden');
    }

    function hideConfirmModal() {
        var modal = document.getElementById('confirm-modal');
        if (modal) modal.classList.add('hidden');
        window.__deleteCallback = null;
    }

    function showLoading() {
        var overlay = document.getElementById('loading-overlay');
        if (overlay) overlay.classList.remove('hidden');
    }

    function hideLoading() {
        var overlay = document.getElementById('loading-overlay');
        if (overlay) overlay.classList.add('hidden');
    }

    function showFlashMessage(message, type) {
        var existing = document.getElementById('flash-toast');
        if (existing) existing.remove();
        var isError = type === 'error';
        var toast = document.createElement('div');
        toast.id = 'flash-toast';
        toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:99999;display:flex;align-items:center;gap:10px;padding:14px 20px;border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,0.15);font-size:14px;font-weight:500;max-width:380px;';
        toast.style.background = isError ? '#FEE2E2' : '#D1FAE5';
        toast.style.color = isError ? '#991B1B' : '#065F46';
        toast.style.border = isError ? '1px solid #FECACA' : '1px solid #6EE7B7';
        toast.innerHTML = '<i class="fas ' + (isError ? 'fa-exclamation-circle' : 'fa-check-circle') + '" style="font-size:16px;flex-shrink:0;"></i><span>' + message + '</span>';
        document.body.appendChild(toast);
        setTimeout(function() {
            toast.style.transition = 'opacity 0.4s';
            toast.style.opacity = '0';
            setTimeout(function() {
                if (toast.parentNode) toast.remove();
            }, 400);
        }, 4000);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') hideConfirmModal();
    });
</script>