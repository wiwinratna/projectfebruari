@extends('layouts.app')

@section('title', 'Add Landing Section Item - NOCIS')
@section('page-title')
    Add Landing Content <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Landing Page</span>
@endsection

@section('content')
@php
    $sectionLabels = [
        'about' => 'About',
        'flow' => 'Flow',
        'features' => 'Features',
    ];
@endphp

<div class="space-y-6">
    <a href="{{ route('super-admin.landing-section-items.index', ['section' => $section]) }}"
        class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold">
        <i class="fas fa-arrow-left mr-2"></i> Back to Landing Content
    </a>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Item</h2>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('super-admin.landing-section-items.store') }}" class="space-y-6 max-w-2xl">
            @csrf

            <div>
                <label for="section" class="block text-sm font-medium text-gray-700 mb-2">Section <span class="text-red-500">*</span></label>
                <select name="section" id="section" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach ($sections as $sectionKey)
                        <option value="{{ $sectionKey }}" {{ old('section', $section) === $sectionKey ? 'selected' : '' }}>
                            {{ $sectionLabels[$sectionKey] ?? ucfirst($sectionKey) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. Registration">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4" maxlength="2000"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Optional description for this item...">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="emoji" class="block text-sm font-medium text-gray-700 mb-2">Emoji / Icon</label>
                    <input type="text" name="emoji" id="emoji" value="{{ old('emoji') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. 🚀">
                </div>
                <div>
                    <label for="highlight" class="block text-sm font-medium text-gray-700 mb-2">Highlight</label>
                    <input type="text" name="highlight" id="highlight" value="{{ old('highlight') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. 2,500+ (for About stats)">
                </div>
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                    class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    class="w-4 h-4 text-blue-600 rounded border-gray-300"
                    {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm font-medium text-gray-700">Active (visible on landing page)</label>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('super-admin.landing-section-items.index', ['section' => $section]) }}"
                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-colors">
                    <i class="fas fa-save mr-2"></i> Save Item
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
