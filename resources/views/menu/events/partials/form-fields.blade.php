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

<div class="mt-8 mb-4 border-t border-gray-100 pt-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center"><i class="fas fa-map-marker-alt text-red-500 mr-2"></i> Event Location</h3>
    
    <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name', $event->province_name) }}">
    <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name', $event->city_name) }}">
    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $event->latitude) }}">
    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $event->longitude) }}">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Province</label>
            <select name="province_code" id="sel-province" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">-- Select Province --</option>
            </select>
            @error('province_code')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">City / Regency</label>
            <select name="city_code" id="sel-city" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" disabled>
                <option value="">-- Select City --</option>
            </select>
            @error('city_code')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Venue Name / Detail</label>
            <input type="text" name="venue" value="{{ old('venue', $event->venue) }}" placeholder="e.g. Gelora Bung Karno" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
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
        const provinceSelect = document.getElementById('sel-province');
        const citySelect = document.getElementById('sel-city');
        const provinceNameInput = document.getElementById('province_name');
        const cityNameInput = document.getElementById('city_name');
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        const currentProvince = "{{ old('province_code', $event->province_code ?? '') }}";
        const currentCity = "{{ old('city_code', $event->city_code ?? '') }}";

        let regionData = { provinces: [], cities: [] };

        async function loadProvinces() {
            try {
                const res = await fetch('/api/indonesia/provinces');
                regionData.provinces = await res.json();
                
                regionData.provinces.forEach(p => {
                    const option = new Option(p.nama, p.id, false, p.id === currentProvince);
                    provinceSelect.appendChild(option);
                });

                if (currentProvince) {
                    await loadCities(currentProvince, currentCity);
                }
            } catch(e) { console.error("Error loading provinces:", e); }
        }

        async function loadCities(provinceId, selectedCityId = '') {
            citySelect.innerHTML = '<option value="">-- Select City --</option>';
            citySelect.disabled = true;
            
            if(!provinceId) return;

            try {
                const res = await fetch(`/api/indonesia/cities/${provinceId}`);
                regionData.cities = await res.json();
                
                regionData.cities.forEach(c => {
                    const option = new Option(c.nama, c.id, false, c.id === selectedCityId);
                    citySelect.appendChild(option);
                });
                
                citySelect.disabled = false;
            } catch(e) { console.error("Error loading cities", e); }
        }

        async function geocodeLocation(cityName, provinceName) {
            try {
                // Remove prefixes like "Kabupaten " or "Kota " for better geocoding results
                let cleanCity = cityName.replace(/^(Kabupaten|Kota)\s+/i, '');
                const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(cleanCity)},${encodeURIComponent(provinceName)},Indonesia&format=json&limit=1`);
                const data = await res.json();
                
                if (data && data.length > 0) {
                    latInput.value = data[0].lat;
                    lngInput.value = data[0].lon;
                } else {
                    // Fallback to province level if city not found
                    const resProv = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(provinceName)},Indonesia&format=json&limit=1`);
                    const dataProv = await resProv.json();
                    if (dataProv && dataProv.length > 0) {
                        latInput.value = dataProv[0].lat;
                        lngInput.value = dataProv[0].lon;
                    }
                }
            } catch(e) { console.error("Geocoding failed", e); }
        }

        provinceSelect.addEventListener('change', async (e) => {
            const val = e.target.value;
            const text = e.target.options[e.target.selectedIndex].text;
            provinceNameInput.value = val ? text : '';
            
            // Reset city and coords
            cityNameInput.value = '';
            latInput.value = '';
            lngInput.value = '';
            
            await loadCities(val);
        });

        citySelect.addEventListener('change', async (e) => {
            const val = e.target.value;
            if(!val) {
                cityNameInput.value = '';
                latInput.value = '';
                lngInput.value = '';
                return;
            }
            
            const text = e.target.options[e.target.selectedIndex].text;
            cityNameInput.value = text;
            
            // Fetch coordinates via Nominatim API when city changes
            if (provinceNameInput.value && text) {
                await geocodeLocation(text, provinceNameInput.value);
            }
        });

        // Initialize
        loadProvinces();
    });
</script>
