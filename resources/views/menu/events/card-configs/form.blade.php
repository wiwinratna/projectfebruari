@csrf

@php
    // biar aman kalau variabel belum di-set dari controller
    $selectedVenueAccesses   = old('venue_access_ids', $selectedVenueAccesses ?? []);
    $selectedZoneAccessCodes = old('zone_access_code_ids', $selectedZoneAccessCodes ?? []);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Accreditation Mapping --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">
            Accreditation Mapping <span class="text-red-500">*</span>
        </label>

        @if(isset($config))
            <div class="flex items-center gap-3 py-2">
                <div class="text-sm font-semibold text-gray-900">
                    {{ $config->mapping->nama_akreditasi ?? '-' }}
                </div>

                @if(optional($config->mapping)->warna)
                    <span class="w-4 h-4 rounded-full" style="background-color: {{ $config->mapping->warna }}"></span>
                    <span class="text-xs text-gray-600">{{ $config->mapping->warna }}</span>
                @endif
            </div>

            <input type="hidden" name="accreditation_mapping_id" value="{{ $config->accreditation_mapping_id }}">
        @else
            <select name="accreditation_mapping_id" required
                class="ts-select w-full @error('accreditation_mapping_id') border-red-500 @enderror">
                <option value="" disabled @selected(old('accreditation_mapping_id')==null) hidden>
                    Example: Default access package for Mapping D
                </option>

                @foreach($mappings as $m)
                    <option value="{{ $m->id }}" @selected(old('accreditation_mapping_id') == $m->id)>
                        {{ $m->nama_akreditasi }}
                    </option>
                @endforeach
            </select>

            @error('accreditation_mapping_id')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <p class="text-gray-500 text-sm">
                Mapping (VIP/A/B/...) yang akan punya paket akses ini
            </p>
        @endif
    </div>

    {{-- Transport --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Transport (opsional)</label>
        <select name="transportation_code_id"
                class="ts-select w-full @error('transportation_code_id') border-red-500 @enderror">
            <option value="" @selected(old('transportation_code_id', $config->transportation_code_id ?? null) == null)>
                Example: T2 – City shuttle
            </option>
            @foreach($transportationCodes as $t)
                <option value="{{ $t->id }}"
                    @selected(old('transportation_code_id', $config->transportation_code_id ?? null) == $t->id)>
                    {{ $t->kode }} - {{ $t->keterangan }}
                </option>
            @endforeach
        </select>
        @error('transportation_code_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        <p class="text-gray-500 text-sm">Kode transport default untuk mapping ini</p>
    </div>

    {{-- Akomodasi --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Akomodasi (opsional)</label>
        <select name="accommodation_code_id"
                class="ts-select w-full @error('accommodation_code_id') border-red-500 @enderror">
            <option value="" @selected(old('accommodation_code_id', $config->accommodation_code_id ?? null) == null)>
                Example: A1 – Standard hotel
            </option>
            @foreach($accommodationCodes as $a)
                <option value="{{ $a->id }}"
                    @selected(old('accommodation_code_id', $config->accommodation_code_id ?? null) == $a->id)>
                    {{ $a->kode }} - {{ $a->keterangan }}
                </option>
            @endforeach
        </select>
        @error('accommodation_code_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        <p class="text-gray-500 text-sm">Kode akomodasi default untuk mapping ini</p>
    </div>

    {{-- Keterangan --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
        <input type="text"
               name="keterangan"
               value="{{ old('keterangan', $config->keterangan ?? '') }}"
               maxlength="1000"
               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm
                      focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                      @error('keterangan') border-red-500 @enderror"
               placeholder="Contoh: Default akses untuk Mapping D">
        @error('keterangan') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        <p class="text-gray-500 text-sm">Catatan opsional</p>
    </div>

</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Venue Access --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Akses Venue</label>

        <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input id="venueSearch" type="text"
                   class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm
                          focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                   placeholder="Search venue...">
        </div>

        <div class="border border-gray-200 rounded-lg max-h-64 overflow-y-auto divide-y divide-gray-100">
            @foreach($venueAccesses as $v)
                @php
                    // DB kamu pakai nama_vanue
                    $venueName = $v->nama_vanue ?? ($v->nama_venue ?? ($v->nama ?? ''));
                    $venueDesc = $v->keterangan ?? '';
                @endphp

                <label class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer venue-item"
                       data-search="{{ strtolower($venueName.' '.$venueDesc) }}">
                    <input type="checkbox"
                           name="venue_access_ids[]"
                           value="{{ $v->id }}"
                           class="mt-1 rounded border-gray-300 text-red-500 focus:ring-red-500"
                           @checked(in_array($v->id, $selectedVenueAccesses))>

                    <div class="min-w-0">
                        <div class="text-sm font-medium text-gray-800">{{ $venueName }}</div>
                        <div class="text-xs text-gray-500 leading-relaxed">{{ $venueDesc }}</div>
                    </div>
                </label>
            @endforeach
        </div>

        <div class="flex items-center justify-between">
            <p class="text-xs text-gray-500">Boleh pilih lebih dari satu.</p>
            <button type="button" id="venueClear" class="text-xs text-red-600 hover:text-red-700 font-medium">Clear</button>
        </div>

        @error('venue_access_ids') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

    {{-- Zone Access --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Akses Zona</label>

        <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input id="zoneSearch" type="text"
                   class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm
                          focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                   placeholder="Search zona...">
        </div>

        <div class="border border-gray-200 rounded-lg max-h-64 overflow-y-auto divide-y divide-gray-100">
            @foreach($zoneAccessCodes as $z)
                @php
                    $zoneCode = $z->kode_zona ?? ($z->kode ?? '');
                    $zoneDesc = $z->keterangan ?? '';
                @endphp

                <label class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer zone-item"
                       data-search="{{ strtolower($zoneCode.' '.$zoneDesc) }}">
                    <input type="checkbox"
                           name="zone_access_code_ids[]"
                           value="{{ $z->id }}"
                           class="mt-1 rounded border-gray-300 text-red-500 focus:ring-red-500"
                           @checked(in_array($z->id, $selectedZoneAccessCodes))>

                    <div class="min-w-0">
                        <div class="text-sm font-medium text-gray-800">{{ $zoneCode }}</div>
                        <div class="text-xs text-gray-500 leading-relaxed">{{ $zoneDesc }}</div>
                    </div>
                </label>
            @endforeach
        </div>

        <div class="flex items-center justify-between">
            <p class="text-xs text-gray-500">Boleh pilih lebih dari satu.</p>
            <button type="button" id="zoneClear" class="text-xs text-red-600 hover:text-red-700 font-medium">Clear</button>
        </div>

        @error('zone_access_code_ids') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

</div>

<div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
    <a href="{{ route('admin.card-configs.index') }}"
       class="px-6 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
        Cancel
    </a>
    <button type="submit"
            class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
        <i class="fas fa-save mr-2"></i> Simpan
    </button>
</div>