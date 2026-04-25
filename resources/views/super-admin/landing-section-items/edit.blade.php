@extends('layouts.app')

@section('title', 'Edit Landing Section Item - NOCIS')
@section('page-title')
    Edit Landing Content <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Landing Page</span>
@endsection

@section('content')
@php
    $sectionLabels = [
        'about' => 'About',
        'flow' => 'Flow',
        'features' => 'Features',
    ];
@endphp

<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('super-admin.landing-section-items.index', ['section' => old('section', $item->section)]) }}" class="text-gray-500 hover:text-gray-700 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Landing Item</h2>
            <p class="text-gray-600 text-sm mt-1">Update content for {{ $sectionLabels[$item->section] ?? ucfirst($item->section) }}</p>
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

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('super-admin.landing-section-items.update', $item) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="section" class="block text-sm font-semibold text-gray-700 mb-1">Section <span class="text-red-500">*</span></label>
                <select name="section" id="section" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach ($sections as $sectionKey)
                        <option value="{{ $sectionKey }}" {{ old('section', $item->section) === $sectionKey ? 'selected' : '' }}>
                            {{ $sectionLabels[$sectionKey] ?? ucfirst($sectionKey) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $item->title) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="4" maxlength="2000"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $item->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="emoji" class="block text-sm font-semibold text-gray-700 mb-1">Emoji / Icon</label>
                    <input type="text" name="emoji" id="emoji" value="{{ old('emoji', $item->emoji) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="highlight" class="block text-sm font-semibold text-gray-700 mb-1">Highlight</label>
                    <input type="text" name="highlight" id="highlight" value="{{ old('highlight', $item->highlight) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-1">Display Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $item->sort_order) }}" min="0"
                    class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    class="w-4 h-4 text-blue-600 rounded"
                    {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm font-semibold text-gray-700">Active (visible on landing page)</label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold text-sm transition-colors">
                    <i class="fas fa-save mr-2"></i> Update Item
                </button>
                <a href="{{ route('super-admin.landing-section-items.index', ['section' => old('section', $item->section)]) }}"
                    class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
