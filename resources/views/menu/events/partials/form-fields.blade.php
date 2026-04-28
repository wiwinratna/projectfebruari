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
        <label class="block text-sm font-semibold text-gray-700 mb-1">Organizer *</label>
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

<div class="mt-8 mb-4 border-t border-gray-100 pt-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center"><i class="fas fa-map-marker-alt text-red-500 mr-2"></i> Event Location</h3>
    
    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $event->latitude) }}">
    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $event->longitude) }}">

    {{-- REAL HIDDEN INPUTS FOR BACKEND --}}
    <input type="hidden" name="province_name" id="final_province_name" value="{{ old('province_name', $event->province_name) }}">
    <input type="hidden" name="city_name" id="final_city_name" value="{{ old('city_name', $event->city_name) }}">

    @php
        $currentCountry = old('country', $event->country ?? 'Indonesia');
        $isIndo = $currentCountry === 'Indonesia';
        $countries = [
            'Indonesia','Afghanistan','Albania','Algeria','Andorra','Angola','Antigua and Barbuda','Argentina','Armenia',
            'Australia','Austria','Azerbaijan','Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium',
            'Belize','Benin','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','Brunei','Bulgaria',
            'Burkina Faso','Burundi','Cabo Verde','Cambodia','Cameroon','Canada','Central African Republic','Chad',
            'Chile','China','Colombia','Comoros','Congo','Costa Rica','Croatia','Cuba','Cyprus','Czech Republic',
            'Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt','El Salvador','Equatorial Guinea',
            'Eritrea','Estonia','Eswatini','Ethiopia','Fiji','Finland','France','Gabon','Gambia','Georgia','Germany',
            'Ghana','Greece','Grenada','Guatemala','Guinea','Guinea-Bissau','Guyana','Haiti','Honduras','Hungary',
            'Iceland','India','Iran','Iraq','Ireland','Israel','Italy','Jamaica','Japan','Jordan','Kazakhstan',
            'Kenya','Kiribati','Kuwait','Kyrgyzstan','Laos','Latvia','Lebanon','Lesotho','Liberia','Libya',
            'Liechtenstein','Lithuania','Luxembourg','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta',
            'Marshall Islands','Mauritania','Mauritius','Mexico','Micronesia','Moldova','Monaco','Mongolia',
            'Montenegro','Morocco','Mozambique','Myanmar','Namibia','Nauru','Nepal','Netherlands','New Zealand',
            'Nicaragua','Niger','Nigeria','North Korea','North Macedonia','Norway','Oman','Pakistan','Palau',
            'Palestine','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal','Qatar',
            'Romania','Russia','Rwanda','Saint Kitts and Nevis','Saint Lucia','Saint Vincent and the Grenadines',
            'Samoa','San Marino','Sao Tome and Principe','Saudi Arabia','Senegal','Serbia','Seychelles',
            'Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa',
            'South Korea','South Sudan','Spain','Sri Lanka','Sudan','Suriname','Sweden','Switzerland','Syria',
            'Taiwan','Tajikistan','Tanzania','Thailand','Timor-Leste','Togo','Tonga','Trinidad and Tobago',
            'Tunisia','Turkey','Turkmenistan','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom',
            'United States','Uruguay','Uzbekistan','Vanuatu','Vatican City','Venezuela','Vietnam','Yemen',
            'Zambia','Zimbabwe'
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Searchable Country Dropdown --}}
        <div class="lg:col-span-2 md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Country *</label>
            <input type="hidden" name="country" id="sel-country" value="{{ $currentCountry }}">
            <div class="relative" id="country-dropdown-wrapper">
                <div class="relative">
                    <input type="text"
                           id="country-search"
                           value="{{ $currentCountry }}"
                           placeholder="Search country..."
                           autocomplete="off"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </span>
                </div>
                <ul id="country-list" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg" style="max-height: 200px; overflow-y: auto;">
                    @foreach($countries as $c)
                        <li data-value="{{ $c }}" class="country-option px-3 py-2 text-sm cursor-pointer hover:bg-red-50 hover:text-red-700 transition-colors {{ $currentCountry === $c ? 'bg-red-50 text-red-700 font-semibold' : 'text-gray-700' }}">
                            {{ $c }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- INDONESIA FIELDS --}}
        <div class="indo-field lg:col-span-1 {{ !$isIndo ? 'hidden' : '' }}">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Province</label>
            <input type="hidden" name="province_code" id="sel-province" value="{{ old('province_code', $event->province_code ?? '') }}">
            <div class="relative" id="province-dropdown-wrapper">
                <div class="relative">
                    <input type="text" id="province-search" placeholder="Search province..." autocomplete="off"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </span>
                </div>
                <ul id="province-list" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg" style="max-height: 200px; overflow-y: auto;">
                </ul>
            </div>
        </div>
        
        <div class="indo-field lg:col-span-1 {{ !$isIndo ? 'hidden' : '' }}">
            <label class="block text-sm font-semibold text-gray-700 mb-1">City / Regency</label>
            <input type="hidden" name="city_code" id="sel-city" value="{{ old('city_code', $event->city_code ?? '') }}">
            <div class="relative" id="city-dropdown-wrapper">
                <div class="relative">
                    <input type="text" id="city-search" placeholder="Select province first..." autocomplete="off" disabled
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm disabled:bg-gray-50 disabled:text-gray-400">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </span>
                </div>
                <ul id="city-list" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg" style="max-height: 200px; overflow-y: auto;">
                </ul>
            </div>
        </div>

        {{-- INTERNATIONAL FIELDS --}}
        <div class="intl-field lg:col-span-1 {{ $isIndo ? 'hidden' : '' }}">
            <label class="block text-sm font-semibold text-gray-700 mb-1">State / Province / Region</label>
            <input type="text" id="intl_province_name" value="{{ !$isIndo ? old('province_name', $event->province_name) : '' }}" placeholder="e.g. Selangor, Texas" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
        </div>
        
        <div class="intl-field lg:col-span-1 {{ $isIndo ? 'hidden' : '' }}">
            <label class="block text-sm font-semibold text-gray-700 mb-1">City</label>
            <input type="text" id="intl_city_name" value="{{ !$isIndo ? old('city_name', $event->city_name) : '' }}" placeholder="e.g. Kuala Lumpur, Paris" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
        </div>

        <div class="lg:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Venue Name / Detail</label>
            <input type="text" name="venue" id="venue_input" value="{{ old('venue', $event->venue) }}" placeholder="e.g. National Stadium, Convention Center" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
            @error('venue')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
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
                    @if($stage === 'province') Regional / Inter-City
                    @elseif($stage === 'national') National Championship
                    @elseif($stage === 'asean/sea') Southeast Asia (SEA Games)
                    @elseif($stage === 'asia') Asian Games
                    @elseif($stage === 'world') World Championship / Olympic
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
@php
  $codesOld = old('access_codes');

  $codesDb = [];
  if (isset($event)) {
    $list = $event->relationLoaded('accessCodes')
      ? $event->accessCodes
      : $event->accessCodes()->get();

    $codesDb = $list->map(fn($c) => [
      'code' => $c->code,
      'label' => $c->label,
      'color_hex' => $c->color_hex,
    ])->values()->toArray();
  }

  $rows = is_array($codesOld) ? $codesOld : $codesDb;

  $rowsToRender = count($rows)
    ? $rows
    : [['code' => '', 'label' => '', 'color_hex' => '#EF4444']];
@endphp



  @error('access_codes')
    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
  @enderror
  @error('access_codes.*.code')
    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
  @enderror
  @error('access_codes.*.label')
    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
  @enderror
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        // ── Searchable Country Dropdown ──────────────────────────
        const countryHidden = document.getElementById('sel-country');   // hidden input (real value)
        const countrySearch = document.getElementById('country-search');
        const countryList   = document.getElementById('country-list');
        const countryItems  = countryList.querySelectorAll('.country-option');
        const wrapper       = document.getElementById('country-dropdown-wrapper');

        let dropdownOpen = false;

        function openDropdown() {
            countryList.classList.remove('hidden');
            dropdownOpen = true;
            countrySearch.select(); // highlight text so user can type immediately
        }
        function closeDropdown() {
            countryList.classList.add('hidden');
            dropdownOpen = false;
            // Reset display to current selected value
            countrySearch.value = countryHidden.value;
            filterList('');
        }

        function filterList(query) {
            const q = query.toLowerCase();
            let hasVisible = false;
            countryItems.forEach(li => {
                const match = li.dataset.value.toLowerCase().includes(q);
                li.classList.toggle('hidden', !match);
                if (match) hasVisible = true;
            });
        }

        function selectCountry(value) {
            countryHidden.value = value;
            countrySearch.value = value;
            // Update highlight
            countryItems.forEach(li => {
                const isActive = li.dataset.value === value;
                li.classList.toggle('bg-red-50', isActive);
                li.classList.toggle('text-red-700', isActive);
                li.classList.toggle('font-semibold', isActive);
            });
            closeDropdown();
            // Trigger field visibility + geocode
            updateFieldsVisibility();
            triggerGeocode();
        }

        countrySearch.addEventListener('focus', openDropdown);
        countrySearch.addEventListener('input', () => {
            if (!dropdownOpen) openDropdown();
            filterList(countrySearch.value);
        });

        countryItems.forEach(li => {
            li.addEventListener('mousedown', (e) => {
                e.preventDefault(); // prevent blur before click registers
                selectCountry(li.dataset.value);
            });
        });

        countrySearch.addEventListener('blur', () => {
            // Small delay to allow click to register
            setTimeout(closeDropdown, 150);
        });

        // Keyboard navigation
        countrySearch.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeDropdown();
                countrySearch.blur();
            }
            if (e.key === 'Enter') {
                e.preventDefault();
                const visible = [...countryItems].filter(li => !li.classList.contains('hidden'));
                if (visible.length === 1) {
                    selectCountry(visible[0].dataset.value);
                }
            }
        });

        // ── Reusable Searchable Dropdown Factory ────────────────
        function createSearchableDropdown(hiddenInput, searchInput, listEl, { placeholder = '', onSelect = null } = {}) {
            let isOpen = false;

            function open() {
                listEl.classList.remove('hidden');
                isOpen = true;
                searchInput.select();
            }
            function close() {
                listEl.classList.add('hidden');
                isOpen = false;
                // Reset display — find selected item text
                const selected = listEl.querySelector(`[data-value="${hiddenInput.value}"]`);
                searchInput.value = selected ? selected.textContent.trim() : (hiddenInput.value ? hiddenInput.value : '');
                filterItems('');
            }
            function filterItems(query) {
                const q = query.toLowerCase();
                listEl.querySelectorAll('li').forEach(li => {
                    li.classList.toggle('hidden', !li.textContent.trim().toLowerCase().includes(q));
                });
            }
            function select(value, text) {
                hiddenInput.value = value;
                searchInput.value = text;
                // Highlight
                listEl.querySelectorAll('li').forEach(li => {
                    const active = li.dataset.value === value;
                    li.classList.toggle('bg-red-50', active);
                    li.classList.toggle('text-red-700', active);
                    li.classList.toggle('font-semibold', active);
                });
                close();
                if (onSelect) onSelect(value, text);
            }
            function populate(items, selectedValue = '') {
                listEl.innerHTML = '';
                items.forEach(item => {
                    const li = document.createElement('li');
                    li.dataset.value = item.value;
                    li.textContent = item.label;
                    li.className = 'px-3 py-2 text-sm cursor-pointer hover:bg-red-50 hover:text-red-700 transition-colors ' +
                        (item.value === selectedValue ? 'bg-red-50 text-red-700 font-semibold' : 'text-gray-700');
                    li.addEventListener('mousedown', (e) => { e.preventDefault(); select(item.value, item.label); });
                    listEl.appendChild(li);
                });
                // Update search display
                const selected = items.find(i => i.value === selectedValue);
                if (selected) searchInput.value = selected.label;
            }
            function clear() {
                hiddenInput.value = '';
                searchInput.value = '';
                listEl.innerHTML = '';
            }
            function setDisabled(disabled) {
                searchInput.disabled = disabled;
                if (disabled) {
                    searchInput.placeholder = 'Select province first...';
                    searchInput.classList.add('bg-gray-50', 'text-gray-400');
                } else {
                    searchInput.placeholder = placeholder;
                    searchInput.classList.remove('bg-gray-50', 'text-gray-400');
                }
            }

            searchInput.addEventListener('focus', open);
            searchInput.addEventListener('input', () => { if (!isOpen) open(); filterItems(searchInput.value); });
            searchInput.addEventListener('blur', () => setTimeout(close, 150));
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') { close(); searchInput.blur(); }
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const visible = [...listEl.querySelectorAll('li')].filter(li => !li.classList.contains('hidden'));
                    if (visible.length === 1) select(visible[0].dataset.value, visible[0].textContent.trim());
                }
            });

            return { open, close, select, populate, clear, setDisabled };
        }

        // ── Province / City / Location Logic ────────────────────
        const provinceHidden    = document.getElementById('sel-province');
        const cityHidden        = document.getElementById('sel-city');
        const intlProvinceInput = document.getElementById('intl_province_name');
        const intlCityInput     = document.getElementById('intl_city_name');
        const venueInput        = document.getElementById('venue_input');
        const finalProvinceName = document.getElementById('final_province_name');
        const finalCityName     = document.getElementById('final_city_name');
        const latInput          = document.getElementById('latitude');
        const lngInput          = document.getElementById('longitude');

        const currentProvince = "{{ old('province_code', $event->province_code ?? '') }}";
        const currentCity     = "{{ old('city_code', $event->city_code ?? '') }}";

        let regionData = { provinces: [], cities: [] };

        // Province dropdown
        const provinceDd = createSearchableDropdown(
            provinceHidden,
            document.getElementById('province-search'),
            document.getElementById('province-list'),
            {
                placeholder: 'Search province...',
                onSelect: async (value, text) => {
                    finalProvinceName.value = text;
                    finalCityName.value = '';
                    latInput.value = '';
                    lngInput.value = '';
                    cityDd.clear();
                    cityDd.setDisabled(true);
                    if (value) {
                        await loadCities(value, '');
                    }
                    triggerGeocode();
                }
            }
        );

        // City dropdown
        const cityDd = createSearchableDropdown(
            cityHidden,
            document.getElementById('city-search'),
            document.getElementById('city-list'),
            {
                placeholder: 'Search city...',
                onSelect: (value, text) => {
                    finalCityName.value = text;
                    triggerGeocode();
                }
            }
        );
        cityDd.setDisabled(!currentProvince);

        function updateFieldsVisibility() {
            const isIndo = countryHidden.value === 'Indonesia';
            document.querySelectorAll('.indo-field').forEach(el => el.classList.toggle('hidden', !isIndo));
            document.querySelectorAll('.intl-field').forEach(el => el.classList.toggle('hidden', isIndo));
            
            if (isIndo) {
                intlProvinceInput.value = '';
                intlCityInput.value = '';
            } else {
                provinceDd.clear();
                cityDd.clear();
                cityDd.setDisabled(true);
            }
        }

        async function loadProvinces() {
            try {
                const res = await fetch('/api/indonesia/provinces');
                regionData.provinces = await res.json();
                
                const items = regionData.provinces.map(p => ({ value: p.id, label: p.nama }));
                provinceDd.populate(items, currentProvince);

                if (currentProvince) {
                    await loadCities(currentProvince, currentCity);
                }
            } catch(e) { console.error("Error loading provinces:", e); }
        }

        async function loadCities(provinceId, selectedCityId = '') {
            if(!provinceId) return;

            try {
                const res = await fetch(`/api/indonesia/cities/${provinceId}`);
                regionData.cities = await res.json();
                
                const items = regionData.cities.map(c => ({ value: c.id, label: c.nama }));
                cityDd.populate(items, selectedCityId);
                cityDd.setDisabled(false);
            } catch(e) { console.error("Error loading cities", e); }
        }

        async function geocodeLocation(searchQuery, expectedCountry = '') {
            try {
                // Build Nominatim URL with country constraint for Indonesia
                let url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(searchQuery)}&format=json&limit=1&addressdetails=1`;
                if (expectedCountry === 'Indonesia') {
                    url += '&countrycodes=id';
                }

                const res = await fetch(url);
                const data = await res.json();
                
                if (data && data.length > 0) {
                    const result = data[0];
                    // Verify country matches (if we have addressdetails)
                    if (expectedCountry && result.address && result.address.country) {
                        const resultCountry = result.address.country.toLowerCase();
                        const expected = expectedCountry.toLowerCase();
                        // Accept if the result country contains the expected name or vice versa
                        if (!resultCountry.includes(expected) && !expected.includes(resultCountry)) {
                            console.warn(`Geocode mismatch: expected "${expectedCountry}" but got "${result.address.country}". Clearing coordinates.`);
                            latInput.value = '';
                            lngInput.value = '';
                            return;
                        }
                    }
                    latInput.value = result.lat;
                    lngInput.value = result.lon;
                } else {
                    // No result — clear stale coordinates
                    latInput.value = '';
                    lngInput.value = '';
                }
            } catch(e) {
                console.error("Geocoding failed", e);
                latInput.value = '';
                lngInput.value = '';
            }
        }

        function triggerGeocode() {
            const isIndo = countryHidden.value === 'Indonesia';
            let query = '';
            const country = countryHidden.value;
            
            if (isIndo) {
                const provText = finalProvinceName.value || '';
                const cityText = finalCityName.value || '';
                const cleanCity = cityText.replace(/^(Kabupaten|Kota)\s+/i, '');
                
                // Only geocode when we have at least a province
                if (cleanCity && provText) query = `${cleanCity}, ${provText}, Indonesia`;
                else if (provText) query = `${provText}, Indonesia`;
                else {
                    // Not enough data — clear coordinates
                    latInput.value = '';
                    lngInput.value = '';
                    return;
                }
            } else {
                finalProvinceName.value = intlProvinceInput.value;
                finalCityName.value = intlCityInput.value;
                
                // International: require at least city OR region + country
                if (!intlCityInput.value && !intlProvinceInput.value) {
                    latInput.value = '';
                    lngInput.value = '';
                    return;
                }
                
                const parts = [intlCityInput.value, intlProvinceInput.value, country].filter(p => p);
                query = parts.join(', ');
            }
            
            if (query) {
                geocodeLocation(query, country);
            }
        }

        intlProvinceInput.addEventListener('blur', triggerGeocode);
        intlCityInput.addEventListener('blur', triggerGeocode);
        venueInput.addEventListener('blur', triggerGeocode);

        // Initialize
        updateFieldsVisibility();
        loadProvinces();
    });
</script>
