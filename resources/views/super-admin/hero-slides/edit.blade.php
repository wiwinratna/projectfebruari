@extends('layouts.app')

@section('title', 'Edit Hero Slide - NOCIS')
@section('page-title')
    Edit Hero Slide <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Super Admin</span>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('super-admin.hero-slides.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Hero Slide</h2>
            <p class="text-gray-600 text-sm mt-1">Update {{ $heroSlide->title }}</p>
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
        <form method="POST" action="{{ route('super-admin.hero-slides.update', $heroSlide) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">
                    Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title', $heroSlide->title) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="subtitle" class="block text-sm font-semibold text-gray-700 mb-1">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $heroSlide->subtitle) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3" maxlength="500"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $heroSlide->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Image</label>
                @if ($heroSlide->image_url)
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ $heroSlide->image_url }}" alt="{{ $heroSlide->title }}"
                             class="w-28 h-16 object-cover rounded border border-gray-200 bg-gray-50 p-1">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Current image</p>
                            <label class="flex items-center gap-2 text-xs text-red-600 cursor-pointer">
                                <input type="checkbox" name="remove_image" value="1" class="w-3 h-3">
                                Remove current image
                            </label>
                        </div>
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Leave blank to keep existing image. Max 4MB.</p>
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-1">Display Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $heroSlide->sort_order) }}" min="0"
                    class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    class="w-4 h-4 text-blue-600 rounded"
                    {{ old('is_active', $heroSlide->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm font-semibold text-gray-700">Active (visible on landing page)</label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold text-sm transition-colors">
                    <i class="fas fa-save mr-2"></i> Update Slide
                </button>
                <a href="{{ route('super-admin.hero-slides.index') }}"
                    class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
