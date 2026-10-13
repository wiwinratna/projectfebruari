@extends('layouts.app')

@section('title', 'Edit Sport - ARISE')
@section('page-title')
    Edit Sport <span class="bg-blue-600 text-white text-sm px-2.5 py-0.5 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-6">
    
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Sport</h2>
            <p class="text-sm text-gray-500 mt-1">Update sport information</p>
        </div>
        <a href="{{ route('admin.sports.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors shadow-xs">
            <i class="fas fa-arrow-left mr-2"></i> Back to Sports
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-xs">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Sport Information</h3>
        </div>
        
        <form action="{{ route('admin.sports.update', $sport) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            {{-- Form Fields --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Sport Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           value="{{ old('code', $sport->code) }}"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-500 @enderror text-sm"
                           placeholder="e.g., ATH, SWM, BDM"
                           required>
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">Short code to identify the sport (max 10 characters)</p>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Sport Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $sport->name) }}"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror text-sm"
                           placeholder="e.g., Athletics, Swimming, Badminton"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">Full name of the sport discipline</p>
                </div>
            </div>

            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', $sport->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-xs focus:ring-blue-500 h-4 w-4">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
                <p class="text-gray-500 text-xs mt-1">Enable this sport to be available for events</p>
            </div>

            {{-- Sport Usage Information --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-blue-800 mb-1 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i> Usage Information
                </h4>
                <div class="text-sm text-blue-700">
                    <p><strong>Events using this sport:</strong> {{ $sport->events()->count() }}</p>
                    @if($sport->events()->count() > 0)
                        <p class="mt-1 text-xs text-blue-600">
                            This sport is currently used by {{ $sport->events()->count() }} event(s) and cannot be deleted.
                        </p>
                    @else
                        <p class="mt-1 text-xs text-blue-600">
                            This sport is not currently used by any events and can be safely deleted.
                        </p>
                    @endif
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.sports.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition-colors text-sm">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors shadow-xs flex items-center text-sm">
                    <i class="fas fa-save mr-2"></i> Update Sport
                </button>
            </div>
        </form>
    </div>
</div>
@endsection