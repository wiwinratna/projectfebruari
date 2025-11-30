@extends('layouts.app')

@section('title', 'Edit Sport - NOCIS')
@section('page-title')
    Edit Sport <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-6">
    
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Sport</h2>
            <p class="text-gray-600 mt-1">Update sport information</p>
        </div>
        <a href="{{ route('sports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Sports
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Sport Information</h3>
        </div>
        
        <form action="{{ route('sports.update', $sport) }}" method="POST" class="p-6 space-y-6">
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('code') border-red-500 @enderror"
                           placeholder="e.g., ATH, SWM, BDM"
                           required>
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Short code to identify the sport (max 10 characters)</p>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Sport Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $sport->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="e.g., Athletics, Swimming, Badminton"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Full name of the sport discipline</p>
                </div>
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', $sport->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
                <p class="text-gray-500 text-sm mt-1">Enable this sport to be available for events</p>
            </div>

            {{-- Sport Usage Information --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-800 mb-2">Usage Information</h4>
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
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('sports.index') }}" 
                   class="px-6 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i> Update Sport
                </button>
            </div>
        </form>
    </div>
</div>
@endsection