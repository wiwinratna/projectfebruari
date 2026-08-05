@extends('layouts.app')

@section('title', 'Preview Data Import')
@section('page-title')
    <div class="flex items-center gap-2">
        Preview Import Excel
    </div>
@endsection

@section('content')
<div class="space-y-6">

    @php
        $totalValid = collect($data)->where('status', 'valid')->count();
        $totalError = collect($data)->where('status', 'error')->count();
        $totalSkip = collect($data)->where('status', 'skip')->count();
    @endphp

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Preview Data Kartu</h2>
                <p class="text-sm text-gray-500 mt-1">
                    @if($totalError > 0)
                        <span class="text-red-600 font-bold"><i class="fas fa-exclamation-triangle mr-1"></i> Ada {{ $totalError }} baris error.</span> Baris error akan diabaikan.
                    @else
                        Semua data valid dan siap digenerate.
                    @endif
                </p>
                <div class="mt-3 flex gap-4 text-sm font-semibold">
                    <span class="text-green-600 bg-green-50 px-2 py-1 rounded-md">{{ $totalValid }} Valid</span>
                    @if($totalError > 0)
                        <span class="text-red-600 bg-red-50 px-2 py-1 rounded-md">{{ $totalError }} Akan dilewati (Error)</span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.cards.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-sm">
                    Batal
                </a>
                <form action="{{ route('admin.cards.import.process') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl {{ $totalValid > 0 ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }} font-bold text-sm" {{ $totalValid > 0 ? '' : 'disabled' }}>
                        <i class="fas fa-check-circle mr-1"></i> Generate {{ $totalValid }} Kartu
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 font-bold text-left">#</th>
                        <th class="px-4 py-3 font-bold text-left">Nama</th>
                        <th class="px-4 py-3 font-bold text-left">Population</th>
                        <th class="px-4 py-3 font-bold text-left">Category (Akreditasi)</th>
                        <th class="px-4 py-3 font-bold text-left">Venue</th>
                        <th class="px-4 py-3 font-bold text-left">Zone</th>
                        <th class="px-4 py-3 font-bold text-left">Transport</th>
                        <th class="px-4 py-3 font-bold text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach(array_slice($data, 0, 100) as $index => $row)
                        @if($row['status'] === 'skip') @continue @endif

                        @php
                            $isValid = $row['status'] === 'valid';
                            $bgClass = $isValid ? 'hover:bg-green-50/50' : 'bg-red-50/50 hover:bg-red-50';
                        @endphp
                        
                        <tr class="{{ $bgClass }}">
                            <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-bold {{ $isValid ? 'text-gray-900' : 'text-red-900' }}">{{ $row['name'] }}</td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $row['population'] ?: '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($isValid)
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $row['mapping_color'] }}"></div>
                                        <span class="font-bold text-gray-700">{{ $row['mapping_label'] }}</span>
                                    </div>
                                @else
                                    <div class="text-red-700 font-bold flex flex-col">
                                        <span>{{ $row['category'] ?: '(Kosong)' }}</span>
                                        <span class="text-xs text-red-500 font-normal">Tidak ditemukan</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $row['venue_access'] ?: '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $row['zone_access'] ?: '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $row['transport'] ?: '-' }}</td>
                            <td class="px-4 py-3">
                                @if($isValid)
                                    <span class="inline-flex items-center gap-1 text-green-700 bg-green-100 px-2 py-1 rounded text-xs font-bold">
                                        <i class="fas fa-check"></i> Valid
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-red-700 bg-red-100 px-2 py-1 rounded text-xs font-bold" title="Category tidak ditemukan di master data event">
                                        <i class="fas fa-times"></i> Error
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if(collect($data)->where('status', '!=', 'skip')->count() > 100)
                        <tr>
                            <td colspan="8" class="px-4 py-4 text-center text-gray-500 font-semibold bg-gray-50">
                                Dan data lainnya...
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
