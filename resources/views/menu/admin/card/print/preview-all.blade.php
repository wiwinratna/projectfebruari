@extends('layouts.app')

@section('title', 'Preview All Cards')
@section('page-title')
Preview All Cards
@endsection

@section('content')
<div class="space-y-6">

  <div class="flex items-center justify-between">
    <div class="text-sm text-gray-500">Menampilkan max 50 cards (biar ringan).</div>
    <a href="{{ route('admin.cards.index', request()->query()) }}"
       class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm">
      Back
    </a>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach($cards as $card)
      @php
        $snap = is_array($card->snapshot) ? $card->snapshot : json_decode($card->snapshot, true);
        $acc = $snap['mapping_name'] ?? ('Mapping #'.$card->accreditation_mapping_id);
        $color = $snap['mapping_color'] ?? '#ef4444';
      @endphp

      <div class="bg-white rounded-xl shadow p-4">
        <div class="flex items-center justify-between">
          <div class="font-semibold text-gray-800">
            {{ $snap['name'] ?? ('App #'.$card->application_id) }}
          </div>
          <span class="text-xs px-2 py-1 rounded-full
            {{ $card->status==='issued'
                ? 'bg-green-50 text-green-700 border border-green-200'
                : 'bg-yellow-50 text-yellow-700 border border-yellow-200' }}">
            {{ strtoupper($card->status) }}
          </span>
        </div>

        <div class="text-xs text-gray-500 mt-1">
          {{ $snap['opening_title'] ?? '-' }}
        </div>

        <div class="mt-2">
          <span class="px-2 py-1 rounded-lg text-xs border"
                style="background: {{ $color }}20; border-color: {{ $color }};">
            ACC: <b>{{ $acc }}</b>
          </span>
        </div>

        <div class="mt-3 flex gap-2">
          <a href="{{ route('admin.cards.preview', $card) }}"
             class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs">
            Preview
          </a>
        </div>

        @if($card->status==='issued' && $card->card_number)
          <div class="mt-2 text-xs text-gray-400">No: {{ $card->card_number }}</div>
        @endif
      </div>
    @endforeach
  </div>

</div>
@endsection