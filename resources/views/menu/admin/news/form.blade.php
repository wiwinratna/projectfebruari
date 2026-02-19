@php
  $post = $post ?? null;
  $isEdit = !is_null($post);
@endphp


{{-- Fields --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Title <span class="text-red-500">*</span>
        </label>
        <input type="text"
               name="title"
               value="{{ old('title', $post->title ?? '') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('title') border-red-500 @enderror"
               placeholder="e.g., Indonesia wins gold in..."
               required>
        @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        <p class="text-gray-500 text-sm mt-1">Max 180 characters</p>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Excerpt
        </label>
        <textarea name="excerpt" rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('excerpt') border-red-500 @enderror"
                  placeholder="Short summary to show on landing...">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
        @error('excerpt') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        <p class="text-gray-500 text-sm mt-1">Max 280 characters</p>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Content
        </label>
        <textarea name="content" rows="8"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('content') border-red-500 @enderror"
                  placeholder="Full article (optional)">{{ old('content', $post->content ?? '') }}</textarea>
        @error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        <p class="text-gray-500 text-sm mt-1">Isi panjang boleh kosong kalau hanya butuh card news.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Cover Image
        </label>
        <input type="file"
               name="cover_image"
               accept="image/*"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('cover_image') border-red-500 @enderror">
        @error('cover_image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

        @if($isEdit && $post->cover_image)
            <div class="mt-3 flex items-center gap-3">
                <img src="{{ asset('storage/'.$post->cover_image) }}" class="w-16 h-16 rounded-lg object-cover border border-gray-200" alt="cover">
                <div class="text-sm text-gray-600">
                    Current cover is set.
                </div>
            </div>
        @else
            <p class="text-gray-500 text-sm mt-1">Optional. Max 2MB.</p>
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Source Name
        </label>
        <input type="text"
               name="source_name"
               value="{{ old('source_name', $post->source_name ?? 'NOCIS') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('source_name') border-red-500 @enderror"
               placeholder="e.g., NOCIS / Admin">
        @error('source_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        <p class="text-gray-500 text-sm mt-1">Nama sumber untuk badge.</p>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Source URL (optional)
        </label>
        <input type="url"
               name="source_url"
               value="{{ old('source_url', $post->source_url ?? '') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('source_url') border-red-500 @enderror"
               placeholder="https://...">
        @error('source_url') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        <p class="text-gray-500 text-sm mt-1">Kalau ada link sumber luar.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Publish
        </label>
        <label class="flex items-center">
            <input type="checkbox"
                   name="is_published"
                   value="1"
                   {{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
            <span class="ml-2 text-sm text-gray-700">Published</span>
        </label>
        <p class="text-gray-500 text-sm mt-1">Kalau publish, otomatis tampil di landing.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Published At (optional)
        </label>
        <input type="datetime-local"
               name="published_at"
               value="{{ old('published_at', ($post->published_at ?? null) ? \Carbon\Carbon::parse($post->published_at)->format('Y-m-d\TH:i') : '' ) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent @error('published_at') border-red-500 @enderror">
        @error('published_at') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        <p class="text-gray-500 text-sm mt-1">Boleh kosong, nanti auto isi saat publish.</p>
    </div>

</div>

{{-- Actions --}}
<div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
    <a href="{{ route('super-admin.news.index') }}"
       class="px-6 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
        Cancel
    </a>

    <button type="submit"
            class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
        <i class="fas fa-save mr-2"></i>
        {{ $isEdit ? 'Update News' : 'Create News' }}
    </button>
</div>
