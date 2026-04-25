<div>
    <label for="badge_text" class="block text-sm font-medium text-gray-700 mb-1">Badge Text (ABOUT)</label>
    <input type="text" id="badge_text" name="badge_text" value="{{ old('badge_text', $config->badge_text ?? null) }}"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
</div>

<div>
    <label for="title_text" class="block text-sm font-medium text-gray-700 mb-1">Title Line 1</label>
    <input type="text" id="title_text" name="title_text" value="{{ old('title_text', $config->title_text ?? null) }}"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
</div>

<div>
    <label for="subtitle_text" class="block text-sm font-medium text-gray-700 mb-1">Subtitle / Main Description</label>
    <textarea id="subtitle_text" name="subtitle_text" rows="4"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('subtitle_text', $config->subtitle_text ?? null) }}</textarea>
</div>

<div>
    <label for="extra_text_3" class="block text-sm font-medium text-gray-700 mb-1">Title Line 2 (Highlight)</label>
    <input type="text" id="extra_text_3" name="extra_text_3" value="{{ old('extra_text_3', $config->extra_text_3 ?? null) }}"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label for="chip_text_1" class="block text-sm font-medium text-gray-700 mb-1">Chip 1 Text</label>
        <input type="text" id="chip_text_1" name="chip_text_1" value="{{ old('chip_text_1', $config->chip_text_1 ?? null) }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div>
        <label for="chip_text_2" class="block text-sm font-medium text-gray-700 mb-1">Chip 2 Text</label>
        <input type="text" id="chip_text_2" name="chip_text_2" value="{{ old('chip_text_2', $config->chip_text_2 ?? null) }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div>
        <label for="chip_text_3" class="block text-sm font-medium text-gray-700 mb-1">Chip 3 Text</label>
        <input type="text" id="chip_text_3" name="chip_text_3" value="{{ old('chip_text_3', $config->chip_text_3 ?? null) }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
</div>

<div>
    <label for="cta_text" class="block text-sm font-medium text-gray-700 mb-1">CTA Button Text</label>
    <input type="text" id="cta_text" name="cta_text" value="{{ old('cta_text', $config->cta_text ?? null) }}"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
</div>

<div>
    <label for="mission_title" class="block text-sm font-medium text-gray-700 mb-1">Mission Box Title</label>
    <input type="text" id="mission_title" name="mission_title" value="{{ old('mission_title', $config->mission_title ?? null) }}"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
</div>

<div>
    <label for="extra_text" class="block text-sm font-medium text-gray-700 mb-1">Mission Description</label>
    <textarea id="extra_text" name="extra_text" rows="3"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('extra_text', $config->extra_text ?? null) }}</textarea>
</div>

<div>
    <label for="vision_title" class="block text-sm font-medium text-gray-700 mb-1">Vision Box Title</label>
    <input type="text" id="vision_title" name="vision_title" value="{{ old('vision_title', $config->vision_title ?? null) }}"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
</div>

<div>
    <label for="extra_text_2" class="block text-sm font-medium text-gray-700 mb-1">Vision Description</label>
    <textarea id="extra_text_2" name="extra_text_2" rows="3"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('extra_text_2', $config->extra_text_2 ?? null) }}</textarea>
</div>
