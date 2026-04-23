@extends('layouts.public')

@section('title', 'Certificate Search - Arise Games')

@section('content')
<div class="min-h-screen bg-gray-50 text-gray-800 relative pt-32 pb-20">
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto">
            
            {{-- Header section --}}
            <div class="text-center mb-12">

                <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 tracking-tight">Search Certificate</h1>
                <p class="text-gray-500 text-base md:text-lg max-w-2xl mx-auto">Verify and find your volunteer participation certificates across all Arise Games events.</p>
            </div>

            {{-- Mega Search Box --}}
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden mb-12 p-6 md:p-8">
                <form method="GET" action="{{ route('public.certificates.lookup') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-700">Volunteer Name</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="fas fa-user"></i>
                                </div>
                                <input type="text" name="name" value="{{ $name }}"
                                    placeholder="Type full name..."
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-11 pr-4 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all text-sm font-medium">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-700">Choose Event</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <select name="event" 
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-11 pr-10 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all text-sm font-medium appearance-none">
                                    <option value="">All Events</option>
                                    @foreach($events as $ev)
                                        <option value="{{ $ev->title }}" {{ $event == $ev->title ? 'selected' : '' }}>
                                            {{ $ev->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 items-end">
                        <div class="col-span-2 space-y-1.5">
                            <label class="text-xs font-bold text-gray-700">Time Range (Optional)</label>
                            <div class="flex items-center gap-2">
                                <input type="date" name="from" value="{{ $from }}"
                                    class="flex-1 bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all">
                                <span class="text-gray-400 text-xs font-medium">to</span>
                                <input type="date" name="to" value="{{ $to }}"
                                    class="flex-1 bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all">
                            </div>
                        </div>
                        <div class="col-span-2">
                            <button type="submit" class="w-full py-3 bg-gray-900 hover:bg-black text-white font-bold rounded-xl transition-all text-sm flex justify-center items-center gap-2">
                                <i class="fas fa-search"></i> Find Certificate
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Results Area --}}
            <div id="results" class="min-h-[200px]">
                @if($searched)
                    @if($certificates->isEmpty())
                        <div class="text-center py-16 bg-white rounded-3xl border border-gray-200 border-dashed animate-fade-in shadow-sm">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                <i class="fas fa-search text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">No Results Found</h3>
                            <p class="text-gray-500 text-sm">Try a different keyword or check the spelling of your name.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            <div class="flex items-center justify-between px-2">
                                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">{{ $certificates->count() }} Data Found</span>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                @foreach($certificates as $cert)
                                    @php $payload = $cert->payload ?? []; @endphp
                                    <div class="group bg-white border border-gray-200 hover:border-gray-300 rounded-2xl p-5 transition-all shadow-sm hover:shadow">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
                                            <div class="flex gap-4 items-center">
                                                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0">
                                                    <i class="fas fa-medal text-xl"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <h4 class="text-lg font-bold text-gray-900 leading-none mb-2">{{ $payload['volunteer_name'] ?? 'Volunteer' }}</h4>
                                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mb-1">
                                                        <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-100 uppercase tracking-wide">Valid & Issued</span>
                                                        <span class="text-[11px] font-medium text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md">{{ $payload['role_label'] ?? 'Worker' }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5 text-xs text-gray-500 mt-1.5">
                                                        <i class="fas fa-calendar-day text-gray-400"></i>
                                                        <span class="font-medium">{{ $payload['event_title'] ?? 'Arise Event' }}</span>
                                                        <span class="mx-1">•</span>
                                                        <span>{{ $payload['event_start_at'] ?? '—' }} to {{ $payload['event_end_at'] ?? '—' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center md:justify-end">
                                                @if($cert->qr_token)
                                                    <a href="{{ url('/sertifikat/verify/' . $cert->qr_token) }}" 
                                                       class="w-full md:w-auto px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold text-xs rounded-xl hover:bg-gray-50 hover:text-blue-600 transition-all flex items-center justify-center gap-2">
                                                        <i class="fas fa-external-link-alt"></i> View Certificate
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
