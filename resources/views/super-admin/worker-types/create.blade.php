@extends('layouts.app')

@section('title', 'Create Worker Type - ARISE Super Admin')
@section('page-title')
    Create Worker Type <span class="bg-blue-600 text-white text-sm px-2.5 py-0.5 rounded-full ml-2">Super Admin</span>
@endsection

@section('content')
<div class="space-y-6 max-w-3xl">
    {{-- Header with Back Button --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Create Worker Type</h2>
            <p class="text-sm text-gray-500 mt-1">Add a new worker classification type to the system</p>
        </div>
        <a href="{{ route('super-admin.worker-types.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors shadow-xs">
            <i class="fas fa-arrow-left mr-2"></i> Back to Worker Types
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-200 shadow-xs">
        <form method="POST" action="{{ route('super-admin.worker-types.store') }}" class="space-y-6">
            @csrf

            {{-- Name / Code Field --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                    Code / Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="e.g. VO, LO, STAFF, MEDIA"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror text-sm uppercase"
                       required>
                <p class="text-xs text-gray-500 mt-1.5">Short unique classification code or identifier.</p>
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description Field --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">
                    Description
                </label>
                <input type="text"
                       id="description"
                       name="description"
                       value="{{ old('description') }}"
                       placeholder="e.g. Volunteer, Liaison Officer, Event Staff"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror text-sm">
                <p class="text-xs text-gray-500 mt-1.5">Full title or human-readable explanation of this worker type.</p>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Active Status Checkbox --}}
            <div class="pt-2">
                <label class="flex items-center cursor-pointer select-none">
                    <input type="checkbox"
                           id="is_active"
                           name="is_active"
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="ml-2.5 text-sm font-medium text-gray-700">Active</span>
                </label>
                <p class="text-xs text-gray-500 mt-1 ml-6.5">Active worker types can be selected when creating job categories and functional areas.</p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('super-admin.worker-types.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition-colors text-sm">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors flex items-center shadow-xs text-sm">
                    <i class="fas fa-save mr-2"></i> Save Worker Type
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
