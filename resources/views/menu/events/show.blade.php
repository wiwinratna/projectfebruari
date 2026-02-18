@extends('layouts.app')

@section('title', 'Event Details - KOI')
@section('page-title')
Event Details <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header with Back Button --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.events.index') }}"
                class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-2">
                <i class="fas fa-arrow-left mr-2"></i> Back to Events
            </a>
            <h2 class="text-2xl font-bold text-gray-800">{{ $event->title }}</h2>
            <p class="text-gray-600 mt-1">Detailed view of event information</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.events.edit', $event) }}"
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-edit mr-2"></i> Edit Event
            </a>
        </div>
    </div>

    {{-- Event Status and Stage Badges --}}
    <div class="bg-white p-4 rounded-lg shadow-sm">
        @php
        $statusColors = [
        'active' => 'bg-green-500 text-white',
        'upcoming' => 'bg-blue-500 text-white',
        'planning' => 'bg-yellow-500 text-white',
        'completed' => 'bg-gray-400 text-white',
        ];

        $stageColors = [
        'province' => 'bg-green-100 text-green-800 border-green-200',
        'national' => 'bg-blue-100 text-blue-800 border-blue-200',
        'asean/sea' => 'bg-orange-100 text-orange-800 border-orange-200',
        'asia' => 'bg-red-100 text-red-800 border-red-200',
        'world' => 'bg-purple-100 text-purple-800 border-purple-200',
        ];
        @endphp
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Status:</span>
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$event->status] ?? 'bg-gray-300 text-gray-700' }}">
                    {{ ucfirst($event->status) }}
                </span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Stage:</span>
                <span class="px-3 py-1 rounded-full border text-sm font-medium {{ $stageColors[$event->stage] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                    @if($event->stage === 'province') Daerah
                    @elseif($event->stage === 'national') Nasional
                    @elseif($event->stage === 'asean/sea') SEA Games
                    @elseif($event->stage === 'asia') Asia
                    @elseif($event->stage === 'world') Dunia
                    @else {{ ucfirst($event->stage) }} @endif
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Basic Information --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Basic Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Title</label>
                        <p class="text-lg text-gray-800 font-semibold mt-1">{{ $event->title }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Penyelenggara</label>
                        <p class="text-lg text-gray-800 mt-1">{{ $event->penyelenggara ?? 'TBA' }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Description</label>
                        <div class="text-gray-800 mt-1">
                            @if($event->description)
                            <p class="whitespace-pre-wrap">{{ $event->description }}</p>
                            @else
                            <p class="text-gray-500 italic">No description provided</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Venue</label>
                        <p class="text-gray-800 mt-1">{{ $event->venue ?? 'TBA' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Location</label>
                        <p class="text-gray-800 mt-1">
                            {{ $event->city_name }}
                            @if($event->city_province)
                            <span class="text-sm text-gray-500">({{ $event->city_province }})</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Date and Time Information --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Schedule</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Start Date & Time</label>
                        <div class="flex items-center mt-1 text-gray-800">
                            <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                            <div>
                                <p class="font-semibold">{{ optional($event->start_at)->translatedFormat('l, d F Y') }}</p>
                                <p class="text-sm text-gray-600">{{ optional($event->start_at)->translatedFormat('H:i') }} WIB</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">End Date & Time</label>
                        <div class="flex items-center mt-1 text-gray-800">
                            <i class="fas fa-calendar-check mr-2 text-gray-400"></i>
                            <div>
                                @if($event->end_at)
                                <p class="font-semibold">{{ $event->end_at->translatedFormat('l, d F Y') }}</p>
                                <p class="text-sm text-gray-600">{{ $event->end_at->translatedFormat('H:i') }} WIB</p>
                                @else
                                <p class="text-gray-500 italic">End date not set</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sports Information --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Sports Categories</h3>

                @if($event->sports->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($event->sports as $sport)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-running text-red-600 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-red-800">{{ $sport->name }}</h4>
                                @if($sport->code)
                                <p class="text-sm text-red-600">{{ $sport->code }}</p>
                                @endif
                            </div>
                        </div>
                        @if($sport->pivot && ($sport->pivot->quota || $sport->pivot->notes))
                        <div class="mt-3 pt-3 border-t border-red-200">
                            @if($sport->pivot->quota)
                            <p class="text-sm text-red-700"><strong>Quota:</strong> {{ $sport->pivot->quota }}</p>
                            @endif
                            @if($sport->pivot->notes)
                            <p class="text-sm text-red-700"><strong>Notes:</strong> {{ $sport->pivot->notes }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-running text-4xl mb-3 text-gray-300"></i>
                    <p>No sports categories selected for this event</p>
                </div>
                @endif
            </div>


            {{-- Contact Information --}}
            @if($event->instagram || $event->email)
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Contact Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($event->instagram)
                    <div class="flex items-center">
                        <i class="fab fa-instagram text-pink-600 text-xl mr-3"></i>
                        <div>
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Instagram</label>
                            <p class="text-gray-800">
                                <a href="https://instagram.com/{{ ltrim($event->instagram, '@') }}"
                                    target="_blank"
                                    class="text-pink-600 hover:text-pink-800 hover:underline">
                                    {{ $event->instagram }}
                                </a>
                            </p>
                        </div>
                    </div>
                    @endif

                    @if($event->email)
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-blue-600 text-xl mr-3"></i>
                        <div>
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Email</label>
                            <p class="text-gray-800">
                                <a href="mailto:{{ $event->email }}"
                                    class="text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $event->email }}
                                </a>
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Quick Stats --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Stats</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Worker Roles</span>
                        <span class="font-semibold text-gray-900">{{ $event->workerOpenings->count() }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Slots</span>
                        <span class="font-semibold text-gray-900">
                            {{ $event->workerOpenings->sum('slots_total') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Filled Slots</span>
                        <span class="font-semibold text-gray-900">
                            {{ $event->workerOpenings->sum('slots_filled') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Applications</span>
                        <span class="font-semibold text-gray-900">{{ $event->applications->count() }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Sports Categories</span>
                        <span class="font-semibold text-gray-900">{{ $event->sports->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- Worker Openings Summary --}}
            @if($event->workerOpenings->count() > 0)
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Worker Roles</h3>

                <div class="space-y-3">
                    @foreach($event->workerOpenings as $opening)
                    <div class="border border-gray-200 rounded-lg p-3">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-medium text-gray-800">
                                {{ $opening->jobCategory->name ?? 'Unknown Role' }}
                            </h4>
                            <span class="text-xs px-2 py-1 rounded-full 
                                        {{ $opening->status === 'open' ? 'bg-green-100 text-green-800' : 
                                           ($opening->status === 'closed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($opening->status) }}
                            </span>
                        </div>

                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>Slots:</strong> {{ $opening->slots_filled }}/{{ $opening->slots_total }}</p>
                            @if($opening->description)
                            <p><strong>Description:</strong> {{ Str::limit($opening->description, 100) }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Recent Applications --}}
            @if($event->applications->count() > 0)
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Recent Applications</h3>

                <div class="space-y-3">
                    @foreach($event->applications->sortByDesc('created_at')->take(5) as $application)
                    <div class="border border-gray-200 rounded-lg p-3">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-medium text-gray-800">
                                {{ $application->user_name ?? 'Anonymous' }}
                            </h4>
                            <span class="text-xs px-2 py-1 rounded-full 
                                        {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>

                        <div class="text-sm text-gray-600">
                            <p><strong>Role:</strong> {{ $application->opening->jobCategory->name ?? 'Unknown' }}</p>
                            <p><strong>Applied:</strong> {{ $application->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach

                    @if($event->applications->count() > 5)
                    <p class="text-sm text-gray-500 text-center pt-2">
                        And {{ $event->applications->count() - 5 }} more applications...
                    </p>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<script>
    // Add any JavaScript for the show page if needed
    document.addEventListener('DOMContentLoaded', function() {
        // You can add any interactivity here for the detail page
    });
</script>
@endsection