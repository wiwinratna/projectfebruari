@extends('layouts.app')

@section('title', 'Add Client - NOCIS')
@section('page-title')
    Add Client <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Landing Page</span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <a href="{{ route('super-admin.clients.index') }}"
       class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold">
        <i class="fas fa-arrow-left mr-2"></i> Back to Clients
    </a>

    {{-- Form Card --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Client</h2>
        <p class="text-gray-500 text-sm -mt-4 mb-6">This client will appear in the Our Clients section of the landing page.</p>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('super-admin.clients.store') }}" enctype="multipart/form-data" class="space-y-6 max-w-2xl">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Client Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                    placeholder="e.g. Olympic Committee Indonesia">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Logo --}}
            <div>
                <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                <input type="file" name="logo" id="logo" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('logo') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">PNG, JPG, WebP — max 2MB. If no logo is set, initials will be shown.</p>
                @error('logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Website --}}
            <div>
                <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website URL</label>
                <input type="url" name="website" id="website" value="{{ old('website') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('website') border-red-500 @enderror"
                    placeholder="https://example.com">
                @error('website')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                <textarea name="description" id="description" rows="3" maxlength="500"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                    placeholder="Optional short description...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Sort Order --}}
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                    class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first.</p>
            </div>

            {{-- Active --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    class="w-4 h-4 text-blue-600 rounded border-gray-300"
                    {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm font-medium text-gray-700">Active (visible on landing page)</label>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('super-admin.clients.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-colors">
                    <i class="fas fa-save mr-2"></i> Save Client
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
