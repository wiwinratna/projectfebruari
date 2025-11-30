{{-- Confirm Modal Component --}}
<div id="confirm-modal" class="fixed inset-0 flex items-center justify-center z-[9999] hidden" onclick="hideConfirmModal()">
    <!-- Modal Content -->
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 border border-gray-200" onclick="event.stopPropagation()">
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
                        id="confirm-cancel" 
                        class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200 font-medium">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="button" 
                        id="confirm-yes" 
                        class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Loading Overlay --}}
<div id="loading-overlay" class="fixed inset-0 flex items-center justify-center z-[9998] hidden">
    <!-- Loading Content -->
    <div class="bg-white rounded-lg shadow-lg px-6 py-4 flex items-center space-x-3 shadow-2xl border border-gray-200">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-red-500"></div>
        <span class="text-gray-700 font-medium">Deleting...</span>
    </div>
</div>