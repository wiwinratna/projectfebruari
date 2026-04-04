@extends('layouts.app')

@section('title', 'Add Hero Slide - NOCIS')
@section('page-title')
    Add Hero Slide <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Super Admin</span>
@endsection

@section('content')
<div class="space-y-6">
    <a href="{{ route('super-admin.hero-slides.index') }}"
       class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold">
        <i class="fas fa-arrow-left mr-2"></i> Back to Hero Slides
    </a>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Hero Slide</h2>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('super-admin.hero-slides.store') }}" enctype="multipart/form-data" class="space-y-6 max-w-2xl">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                    placeholder="e.g. ARISE">
            </div>

            <div>
                <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. 2026">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" maxlength="500"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Short supporting sentence...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                    Slide Image <span class="text-red-500">*</span>
                </label>
                <input type="file" name="image" id="image" accept="image/*" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Recommended wide image. Max 4MB.</p>
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
                <a href="{{ route('super-admin.hero-slides.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-colors">
                    <i class="fas fa-save mr-2"></i> Save Slide
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
