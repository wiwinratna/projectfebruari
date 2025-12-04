@php
    $startValue = old(
        'start_at',
        optional($event->start_at)->format('Y-m-d\TH:i')
    );
    $endValue = old(
        'end_at',
        optional($event->end_at)->format('Y-m-d\TH:i')
    );
    
    // Get all available sports
    $availableSports = \App\Models\Sport::where('is_active', true)->orderBy('name')->get();
    
    // Get currently selected sports for edit mode
    $selectedSports = old('sports', $event->sports->pluck('id')->toArray() ?? []);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Event Title *</label>
        <input type="text" name="title" value="{{ old('title', $event->title) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
        @error('title')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Penyelenggara *</label>
        <input type="text" name="penyelenggara" value="{{ old('penyelenggara', $event->penyelenggara) }}"
               placeholder="e.g., PBSI, PSSI, KONI"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
        @error('penyelenggara')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
    <textarea name="description" rows="4"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('description', $event->description) }}</textarea>
    @error('description')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Start Date & Time *</label>
        <input type="datetime-local" name="start_at" value="{{ $startValue }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
        @error('start_at')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">End Date & Time</label>
        <input type="datetime-local" name="end_at" value="{{ $endValue }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
        @error('end_at')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Venue</label>
        <input type="text" name="venue" value="{{ old('venue', $event->venue) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
        @error('venue')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">City</label>
        <select name="city_id"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
            <option value="">-- Select City --</option>
            @php
                $currentProvince = '';
            @endphp
            @foreach ($cities as $city)
                @if($city->province !== $currentProvince)
                    @if($currentProvince !== '')
                        </optgroup>
                    @endif
                    <optgroup label="{{ $city->province }}">
                    @php $currentProvince = $city->province; @endphp
                @endif
                <option value="{{ $city->id }}" 
                        @selected(old('city_id', $event->city_id) == $city->id)>
                    {{ $city->name }}
                </option>
            @endforeach
            @if($currentProvince !== '')
                </optgroup>
            @endif
        </select>
        @error('city_id')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Status *</label>
        <select name="status"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $event->status) === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
        @error('status')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Stage *</label>
        <select name="stage"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
            @foreach ($stages as $stage)
                <option value="{{ $stage }}" @selected(old('stage', $event->stage) === $stage)>
                    @if($stage === 'province') Daerah/antar kota kabupaten
                    @elseif($stage === 'national') 02SN,PON,Pekan olahraga nasional
                    @elseif($stage === 'asean/sea') Southeast Asia (SEA Games)
                    @elseif($stage === 'asia') Asian Games
                    @elseif($stage === 'world') World Cup, Olympic
                    @else {{ ucfirst($stage) }} @endif
                </option>
            @endforeach
        </select>
        @error('stage')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Instagram</label>
        <input type="text" name="instagram" value="{{ old('instagram', $event->instagram) }}"
               placeholder="@username or full URL"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
        @error('instagram')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $event->email) }}"
               placeholder="contact@example.com"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
        @error('email')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">Sports *</label>
    <div class="border border-gray-300 rounded-lg p-4 max-h-48 overflow-y-auto">
        @foreach ($availableSports as $sport)
            <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded cursor-pointer">
                <input type="checkbox" 
                       name="sports[]" 
                       value="{{ $sport->id }}"
                       @checked(in_array($sport->id, $selectedSports))
                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                <div class="flex-1">
                    <span class="text-sm font-medium text-gray-700">{{ $sport->name }}</span>
                    @if($sport->code)
                        <span class="text-xs text-gray-500 ml-2">({{ $sport->code }})</span>
                    @endif
                </div>
            </label>
        @endforeach
    </div>
    @error('sports')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
    @error('sports.*')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
