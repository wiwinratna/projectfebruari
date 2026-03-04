@extends('layouts.app')

@section('title', 'Edit Client - NOCIS')
@section('page-title')
    Edit Client <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Landing Page</span>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('super-admin.clients.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Client</h2>
            <p class="text-gray-600 text-sm mt-1">Update {{ $client->name }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6 space-y-5">
        <form method="POST" action="{{ route('super-admin.clients.update', $client) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                    Client Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Logo --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Logo</label>
                @if ($client->logo_url)
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ $client->logo_url }}" alt="{{ $client->name }}"
                             class="w-20 h-20 object-contain rounded border border-gray-200 bg-gray-50 p-1">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Current logo</p>
                            <label class="flex items-center gap-2 text-xs text-red-600 cursor-pointer">
                                <input type="checkbox" name="remove_logo" value="1" class="w-3 h-3">
                                Remove current logo
                            </label>
                        </div>
                    </div>
                @endif
                <input type="file" name="logo" id="logo" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('logo') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">PNG, JPG, WebP — max 2MB. Leave blank to keep existing logo.</p>
                @error('logo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Website --}}
            <div>
                <label for="website" class="block text-sm font-semibold text-gray-700 mb-1">Website URL</label>
                <input type="url" name="website" id="website" value="{{ old('website', $client->website) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('website') border-red-500 @enderror"
                    placeholder="https://example.com">
                @error('website')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Short Description</label>
                <textarea name="description" id="description" rows="2" maxlength="500"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $client->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Sort Order --}}
            <div>
                <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-1">Display Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $client->sort_order) }}" min="0"
                    class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first.</p>
            </div>

            {{-- Active --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    class="w-4 h-4 text-blue-600 rounded"
                    {{ old('is_active', $client->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm font-semibold text-gray-700">Active (visible on landing page)</label>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold text-sm transition-colors">
                    <i class="fas fa-save mr-2"></i> Update Client
                </button>
                <a href="{{ route('super-admin.clients.index') }}"
                    class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
