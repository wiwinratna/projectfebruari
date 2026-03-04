@extends('layouts.app')

@section('title', 'Customize Card Access')
@section('page-title')
Customize Card Access
@endsection

@section('content')
@php
    $venueHistory = $history['venue'] ?? [];
    $zoneHistory  = $history['zone'] ?? [];

    $defaultVenueCount = collect($venueHistory)->filter(fn($v) => ($v['state'] ?? '') === 'owned' && ($v['source'] ?? '') === 'default')->count();
    $customVenueCount  = collect($venueHistory)->filter(fn($v) => ($v['state'] ?? '') === 'owned' && ($v['source'] ?? '') === 'custom')->count();
    $removedVenueCount = collect($venueHistory)->filter(fn($v) => ($v['state'] ?? '') === 'removed')->count();

    $defaultZoneCount = collect($zoneHistory)->filter(fn($v) => ($v['state'] ?? '') === 'owned' && ($v['source'] ?? '') === 'default')->count();
    $customZoneCount  = collect($zoneHistory)->filter(fn($v) => ($v['state'] ?? '') === 'owned' && ($v['source'] ?? '') === 'custom')->count();
    $removedZoneCount = collect($zoneHistory)->filter(fn($v) => ($v['state'] ?? '') === 'removed')->count();

    $isLocked = $card->status === 'issued';
@endphp

<div class="space-y-6">
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center justify-between gap-3">
            <div>
                <div class="text-lg font-semibold text-gray-800">
                    Card #{{ $card->id }}
                    <span class="ml-2 text-xs px-2 py-1 rounded-full
                        {{ $isLocked ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-yellow-50 text-yellow-700 border border-yellow-200' }}">
                        {{ strtoupper($card->status) }}
                    </span>
                </div>
                <div class="text-sm text-gray-500 mt-1">
                    Application ID: {{ $card->application_id }} • Mapping ID: {{ $card->accreditation_mapping_id }}
                </div>
            </div>
            <a href="{{ url()->previous() }}" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                Back
            </a>
        </div>

        @if($isLocked)
            <div class="mt-4 text-sm bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                Card sudah <b>ISSUED</b>, akses hanya bisa dilihat (read-only).
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.cards.access.update', $card) }}" class="space-y-6">
        @csrf

        {{-- Venues --}}
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="font-semibold text-gray-800">Venue Access</h3>
                    <div class="text-xs text-gray-500 mt-1">
                        Owned: <b>{{ count($final['venues']) }}</b> •
                        Default: <b>{{ $defaultVenueCount }}</b> •
                        Custom: <b>{{ $customVenueCount }}</b> •
                        Removed: <b>{{ $removedVenueCount }}</b>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">DEFAULT</span>
                    <span class="text-xs px-2 py-1 rounded-full bg-green-50 text-green-700 border border-green-200">CUSTOM</span>
                    <span class="text-xs px-2 py-1 rounded-full bg-red-50 text-red-700 border border-red-200">REMOVED</span>
                </div>
            </div>

            <input type="text" id="venueSearch"
                   class="mt-4 w-full border rounded-lg p-2"
                   placeholder="Search venue...">

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3" id="venueGrid">
                @foreach($venues as $v)
                    @php
                        $id = $v->id;
                        $label = $v->nama_vanue ?? $v->keterangan ?? ('Venue #'.$id);

                        $isChecked = in_array($id, $final['venues']);
                        $h = $history['venue'][$id] ?? null;

                        $state  = $h['state'] ?? null;   // owned/removed
                        $source = $h['source'] ?? null;  // default/custom

                        $badgeClass = 'bg-gray-100 text-gray-700';
                        $badgeText  = 'NOT OWNED';

                        if ($state === 'removed') {
                            $badgeClass = 'bg-red-50 text-red-700 border border-red-200';
                            $badgeText  = 'REMOVED';
                        } elseif ($state === 'owned' && $source === 'default') {
                            $badgeClass = 'bg-blue-50 text-blue-700 border border-blue-200';
                            $badgeText  = 'DEFAULT';
                        } elseif ($state === 'owned' && $source === 'custom') {
                            $badgeClass = 'bg-green-50 text-green-700 border border-green-200';
                            $badgeText  = 'CUSTOM';
                        }
                    @endphp

                    <label data-venue-name="{{ strtolower($label) }}"
                           class="flex items-center gap-3 p-3 rounded-lg border hover:bg-gray-50">
                        <input type="checkbox"
                               name="venues[]"
                               value="{{ $id }}"
                               class="rounded"
                               {{ $isChecked ? 'checked' : '' }}
                               {{ $isLocked ? 'disabled' : '' }}>

                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-800 truncate">{{ $label }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                <span class="px-2 py-0.5 rounded-full text-xs {{ $badgeClass }}">
                                    {{ $badgeText }}
                                </span>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Zones --}}
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="font-semibold text-gray-800">Zone Access</h3>
                    <div class="text-xs text-gray-500 mt-1">
                        Owned: <b>{{ count($final['zones']) }}</b> •
                        Default: <b>{{ $defaultZoneCount }}</b> •
                        Custom: <b>{{ $customZoneCount }}</b> •
                        Removed: <b>{{ $removedZoneCount }}</b>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">DEFAULT</span>
                    <span class="text-xs px-2 py-1 rounded-full bg-green-50 text-green-700 border border-green-200">CUSTOM</span>
                    <span class="text-xs px-2 py-1 rounded-full bg-red-50 text-red-700 border border-red-200">REMOVED</span>
                </div>
            </div>

            <input type="text" id="zoneSearch"
                   class="mt-4 w-full border rounded-lg p-2"
                   placeholder="Search zone...">

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3" id="zoneGrid">
                @foreach($zones as $z)
                    @php
                        $id = $z->id;
                        $label = $z->kode_zona ?? $z->keterangan ?? ('Zone #'.$id);

                        $isChecked = in_array($id, $final['zones']);
                        $h = $history['zone'][$id] ?? null;

                        $state  = $h['state'] ?? null;
                        $source = $h['source'] ?? null;

                        $badgeClass = 'bg-gray-100 text-gray-700';
                        $badgeText  = 'NOT OWNED';

                        if ($state === 'removed') {
                            $badgeClass = 'bg-red-50 text-red-700 border border-red-200';
                            $badgeText  = 'REMOVED';
                        } elseif ($state === 'owned' && $source === 'default') {
                            $badgeClass = 'bg-blue-50 text-blue-700 border border-blue-200';
                            $badgeText  = 'DEFAULT';
                        } elseif ($state === 'owned' && $source === 'custom') {
                            $badgeClass = 'bg-green-50 text-green-700 border border-green-200';
                            $badgeText  = 'CUSTOM';
                        }
                    @endphp

                    <label data-zone-name="{{ strtolower($label) }}"
                           class="flex items-center gap-3 p-3 rounded-lg border hover:bg-gray-50">
                        <input type="checkbox"
                               name="zones[]"
                               value="{{ $id }}"
                               class="rounded"
                               {{ $isChecked ? 'checked' : '' }}
                               {{ $isLocked ? 'disabled' : '' }}>

                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-800 truncate">{{ $label }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                <span class="px-2 py-0.5 rounded-full text-xs {{ $badgeClass }}">
                                    {{ $badgeText }}
                                </span>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Transport + Accommodation --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-semibold text-gray-800">Transportation</h3>

                <select name="transportation_id" class="mt-3 w-full border rounded-lg p-2"
                        {{ $isLocked ? 'disabled' : '' }}>
                    <option value="">— None —</option>
                    @foreach($transportations as $t)
                        <option value="{{ $t->id }}" {{ (($final['transportation_id'] ?? null) == $t->id) ? 'selected' : '' }}>
                            {{ $t->kode ?? $t->keterangan ?? ('Transport #'.$t->id) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-semibold text-gray-800">Accommodation</h3>

                <select name="accommodation_id" class="mt-3 w-full border rounded-lg p-2"
                        {{ $isLocked ? 'disabled' : '' }}>
                    <option value="">— None —</option>
                    @foreach($accommodations as $a)
                        <option value="{{ $a->id }}" {{ (($final['accommodation_id'] ?? null) == $a->id) ? 'selected' : '' }}>
                            {{ $a->kode ?? $a->keterangan ?? ('Accommodation #'.$a->id) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if(!$isLocked)
            <div class="flex justify-end">
                <button class="px-5 py-2.5 rounded-lg bg-red-600 text-white hover:bg-red-700">
                    Save Changes
                </button>
            </div>
        @endif
    </form>
</div>

<script>
(function () {
  const venueSearch = document.getElementById('venueSearch');
  const zoneSearch  = document.getElementById('zoneSearch');

  if (venueSearch) {
    venueSearch.addEventListener('input', function () {
      const q = this.value.toLowerCase();
      document.querySelectorAll('#venueGrid [data-venue-name]').forEach(el => {
        el.style.display = el.getAttribute('data-venue-name').includes(q) ? '' : 'none';
      });
    });
  }

  if (zoneSearch) {
    zoneSearch.addEventListener('input', function () {
      const q = this.value.toLowerCase();
      document.querySelectorAll('#zoneGrid [data-zone-name]').forEach(el => {
        el.style.display = el.getAttribute('data-zone-name').includes(q) ? '' : 'none';
      });
    });
  }
})();
</script>
@endsection