@extends('layouts.app')

@section('title', 'Super Admin Dashboard - NOCIS')
@section('page-title')
    Super Admin Dashboard <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">System Administrator</span>
@endsection

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<!-- MarkerCluster CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<style>
    .leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    .leaflet-popup-content { margin: 0; }
</style>
<div class="pb-12">
    {{-- Greeting + highlight cards --}}
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 mb-12">
        <h1 class="text-2xl font-semibold text-gray-900 mb-2">Welcome, Super Admin!</h1>
        <p class="text-gray-500 mb-6">Manage system administrators, events, and monitor overall platform performance.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex items-center p-4 bg-[#F0F4FF] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#DBEAFE] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-user-tie text-blue-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $totalAdmins }}</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Total Admins</p>
                    <p class="text-xs text-blue-600 mt-1">System Managers</p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $unassignedAdmins }}</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Unassigned Admins</p>
                    <p class="text-xs text-red-600 mt-1">Need Event Assignment</p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-red-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $totalEvents }}</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Total Events</p>
                    <p class="text-xs text-emerald-500 mt-1">Registered</p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-users text-red-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $totalCustomers }}</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Total Customers</p>
                    <p class="text-xs text-emerald-500 mt-1">Registered Users</p>
                </div>
            </div>
        </div>
        </div>
    </div>

    {{-- Volunteer & Platform Insights --}}
    @php
        $totalApps = \App\Models\Application::count();
        $acceptedApps = \App\Models\Application::where('status', 'accepted')->count();
        $activeVols = \App\Models\User::where('role', 'customer')->has('applications')->count();
        
        $volPercentage = $totalCustomers > 0 ? round(($activeVols / $totalCustomers) * 100) : 0;
        $appPercentage = $totalApps > 0 ? round(($acceptedApps / $totalApps) * 100) : 0;
    @endphp
    <div class="mb-16">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-chart-line text-blue-600 mr-3"></i> Volunteer & Platform Insights
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Volunteer Engagement --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-col justify-center">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm uppercase tracking-wide text-gray-500 font-semibold">Volunteer Engagement</p>
                        <h4 class="text-3xl font-bold text-gray-900 mt-2">{{ $activeVols }}</h4>
                        <p class="text-sm text-gray-500 mt-1">Active volunteers</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fas fa-users-cog text-xl"></i>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 mt-4">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $volPercentage }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-2">{{ $volPercentage }}% of total registered users</p>
            </div>

            {{-- Application Success Rate --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-col justify-center">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm uppercase tracking-wide text-gray-500 font-semibold">Application Success</p>
                        <h4 class="text-3xl font-bold text-gray-900 mt-2">{{ $acceptedApps }}</h4>
                        <p class="text-sm text-gray-500 mt-1">Total accepted applications</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 mt-4">
                    <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $appPercentage }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-2">{{ $appPercentage }}% acceptance rate</p>
            </div>

            {{-- System Content --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex flex-col justify-center">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm uppercase tracking-wide text-gray-500 font-semibold">Platform Content</p>
                        <h4 class="text-3xl font-bold text-gray-900 mt-2">{{ array_sum($contentStats) }}</h4>
                        <p class="text-sm text-gray-500 mt-1">Total published assets</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                        <i class="fas fa-photo-video text-xl"></i>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-gray-50">
                    <div class="text-center">
                        <p class="text-lg font-semibold text-gray-800">{{ $contentStats['news'] ?? 0 }}</p>
                        <p class="text-[10px] text-gray-500 uppercase">News</p>
                    </div>
                    <div class="text-center border-l border-r border-gray-100">
                        <p class="text-lg font-semibold text-gray-800">{{ $contentStats['partners'] ?? 0 }}</p>
                        <p class="text-[10px] text-gray-500 uppercase">Partners</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-semibold text-gray-800">{{ $contentStats['slides'] ?? 0 }}</p>
                        <p class="text-[10px] text-gray-500 uppercase">Slides</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Admin Management & Events panels --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        {{-- Recent Admins --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <p class="text-sm uppercase tracking-wide text-blue-600 font-semibold flex items-center">
                        <i class="fas fa-user-tie mr-2"></i> SYSTEM ADMINISTRATORS
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Admin Users</h3>
                </div>
                <a href="{{ route('super-admin.admins.index') }}" class="text-sm text-blue-600 font-semibold hover:underline">View All</a>
            </div>
            <div class="space-y-6">
                @forelse ($admins->take(5) as $admin)
                    <a href="{{ route('super-admin.admins.edit', $admin) }}" class="block group">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 group-hover:bg-blue-50 rounded-2xl px-6 py-5 border border-gray-100 group-hover:border-blue-100 transition-all space-y-4 sm:space-y-0">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 group-hover:text-blue-700 text-sm sm:text-base transition-colors">{{ $admin->name }}</p>
                                <div class="text-xs sm:text-sm text-gray-500 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-1 sm:space-y-0">
                                    <span><i class="fas fa-user mr-1"></i>{{ $admin->username }}</span>
                                    <span><i class="fas fa-calendar-check mr-1"></i>{{ $admin->event?->title ?? 'Unassigned' }}</span>
                                </div>
                            </div>
                            @if (!$admin->event_id)
                                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-red-100 text-red-600 self-start sm:self-center">Unassigned</span>
                            @else
                                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-600 self-start sm:self-center">Assigned</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="text-center py-4 text-gray-500">No admins found.</div>
                @endforelse
            </div>
            <a href="{{ route('super-admin.admins.create') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                <i class="fas fa-plus mr-2"></i> Add New Admin
            </a>
        </div>

        {{-- Events Overview --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i> EVENTS OVERVIEW
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">All Events</h3>
                </div>
                <a href="{{ route('super-admin.events.index') }}" class="text-sm text-red-500 font-semibold hover:underline">View All</a>
            </div>
            <div class="space-y-6">
                @forelse ($events ?? collect()->take(5) as $event)
                    <a href="{{ route('super-admin.events.show', $event) }}" class="block group">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 group-hover:bg-red-50 rounded-2xl px-6 py-5 border border-gray-100 group-hover:border-red-100 transition-all space-y-4 sm:space-y-0">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 group-hover:text-red-700 text-sm sm:text-base transition-colors">{{ $event->title }}</p>
                                <div class="text-xs sm:text-sm text-gray-500 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-1 sm:space-y-0">
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $event->start_at->format('Y-m-d') }}</span>
                                    <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->city->name ?? 'Location' }}</span>
                                </div>
                            </div>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full
                                @if($event->status === 'active') bg-green-100 text-green-600
                                @elseif($event->status === 'upcoming') bg-blue-100 text-blue-600
                                @else bg-gray-100 text-gray-600
                                @endif
                                self-start sm:self-center">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-4 text-gray-500">No events found.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Event Analytics --}}
    <div class="mb-16">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-chart-bar text-red-500 mr-3"></i> Event Analytics
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Event Status --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-gray-900 font-semibold mb-8 flex items-center">
                    <i class="fas fa-chart-pie text-blue-500 w-5"></i> EVENT STATUS
                </h3>
                <div class="space-y-6">
                    @php
                        $statusColors = [
                            'active' => 'bg-green-500', 'upcoming' => 'bg-blue-500',
                            'planning' => 'bg-red-500', 'completed' => 'bg-gray-500'
                        ];
                        $total = $eventsByStatus->sum();
                    @endphp
                    @foreach($eventsByStatus as $status => $count)
                        @php $percentage = $total > 0 ? round(($count / $total) * 100) : 0; @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 capitalize">{{ $status }}</span>
                                <span class="text-gray-500">{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="{{ $statusColors[$status] ?? 'bg-gray-400' }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                    @if($total === 0)
                        <p class="text-sm text-gray-500 italic text-center py-4">No events data available.</p>
                    @endif
                </div>
            </div>

            {{-- Top Provinces --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-gray-900 font-semibold mb-8 flex items-center">
                    <i class="fas fa-map text-emerald-500 w-5"></i> TOP PROVINCES
                </h3>
                <div class="space-y-5">
                    @forelse($topProvinces as $index => $tp)
                        <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl transition-colors border border-transparent hover:border-gray-100">
                            <div class="flex items-center">
                                <span class="text-xs font-bold text-gray-400 w-5 text-center">{{ $index + 1 }}</span>
                                <span class="text-sm font-medium text-gray-800 ml-3">{{ $tp->province }}</span>
                            </div>
                            <span class="text-xs font-semibold px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg">
                                {{ $tp->count }} Events
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic text-center py-6">No location data available.</p>
                    @endforelse
                </div>
            </div>

            {{-- Event Trend / Timeline --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-gray-900 font-semibold mb-4 flex items-center">
                    <i class="fas fa-chart-line text-indigo-500 w-5"></i> 6-Month Trend
                </h3>
                <div class="h-32 flex items-end justify-between space-x-2 pt-4">
                    @php $maxCount = max($eventTrend->max('count'), 1); @endphp
                    @foreach($eventTrend as $trend)
                        @php $height = ($trend['count'] / $maxCount) * 100; @endphp
                        <div class="flex flex-col items-center w-full group">
                            <div class="relative flex justify-center w-full h-24 items-end">
                                <div class="w-full max-w-[24px] bg-indigo-100 group-hover:bg-indigo-500 rounded-t-md transition-all duration-300 relative" style="height: {{ max($height, 5) }}%">
                                    <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 text-xs font-bold text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity">{{ $trend['count'] }}</span>
                                </div>
                            </div>
                            <span class="text-[10px] text-gray-400 mt-2 font-medium uppercase tracking-wider truncate w-full text-center" title="{{ $trend['label'] }}">
                                {{ substr($trend['label'], 0, 3) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Event Location Map --}}
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-map-marked-alt text-emerald-500 mr-3"></i> Global Distribution
        </h2>
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                        <i class="fas fa-map-marked-alt mr-2"></i> LOCATION DISTRIBUTION
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Event Map</h3>
                </div>
                <div class="text-sm text-gray-500 font-medium">
                    <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-1"></span> Active
                    <span class="inline-block w-3 h-3 rounded-full bg-blue-500 ml-4 mr-1"></span> Upcoming
                    <span class="inline-block w-3 h-3 rounded-full bg-gray-500 ml-4 mr-1"></span> Completed
                </div>
            </div>
            <div class="h-[500px] z-0 w-full" id="event-map"></div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const events = @json($allMapEvents);
        
        // Initialize Map centered on Indonesia
        const map = L.map('event-map').setView([-2.548926, 118.0148634], 5);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        const markers = L.markerClusterGroup({
            chunkedLoading: true,
            maxClusterRadius: 50
        });

        // Custom pin icons
        const iconColors = {
            'green': new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            }),
            'blue': new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            }),
            'gray': new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            }),
            'red': new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            })
        };

        events.forEach(ev => {
            const statusColors = {
                'active': 'text-green-600 bg-green-100',
                'upcoming': 'text-blue-600 bg-blue-100',
                'completed': 'text-gray-600 bg-gray-100',
                'planning': 'text-red-600 bg-red-100'
            };
            const statusTextColor = statusColors[ev.status] ? statusColors[ev.status].split(' ')[0] : 'text-red-600';

            if (ev.latitude && ev.longitude) {
                const marker = L.marker([ev.latitude, ev.longitude], { icon: iconColors[ev.status_color] || iconColors['red'] });
                
                const popupContent = `
                    <div class="p-3 max-w-xs">
                        <div class="text-xs uppercase font-bold ${statusTextColor} mb-1">${ev.status}</div>
                        <h4 class="font-bold text-gray-900 text-base mb-1">${ev.title}</h4>
                        <div class="text-sm text-gray-600 space-y-1 mt-2">
                            <p><i class="fas fa-map-marker-alt w-4 text-center mr-1"></i> ${ev.venue}</p>
                            <p><i class="fas fa-city w-4 text-center mr-1"></i> ${ev.city_name}, ${ev.province_name}</p>
                            <p><i class="fas fa-calendar w-4 text-center mr-1"></i> ${ev.start_at} - ${ev.end_at}</p>
                        </div>
                    </div>
                `;
                
                marker.bindPopup(popupContent);
                markers.addLayer(marker);
            }
        });

        map.addLayer(markers);
        
        // Auto-fit bounds if there are markers
        if (markers.getLayers().length > 0) {
            map.fitBounds(markers.getBounds(), { padding: [50, 50], maxZoom: 12 });
        }
    });
</script>
@endsection
