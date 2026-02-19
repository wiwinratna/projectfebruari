@extends('layouts.app')

@section('title', 'Event Details - NOCIS')
@section('page-title')
    Event Details <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <a href="{{ route('super-admin.events.index') }}"
       class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold">
        <i class="fas fa-arrow-left mr-2"></i> Back to Events
    </a>

    {{-- Event Header --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
                <p class="text-gray-600 mt-2">{{ $event->description }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                @if($event->status === 'active') bg-green-100 text-green-800
                @elseif($event->status === 'upcoming') bg-blue-100 text-blue-800
                @else bg-gray-100 text-gray-800
                @endif">
                {{ ucfirst($event->status) }}
            </span>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500 mb-1"><i class="fas fa-briefcase mr-2"></i>Job Openings</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalWorkerOpenings }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500 mb-1"><i class="fas fa-file-alt mr-2"></i>Total Applications</p>
            <p class="text-3xl font-bold text-purple-600">{{ $totalApplications }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500 mb-1"><i class="fas fa-check-circle mr-2"></i>Accepted</p>
            <p class="text-3xl font-bold text-green-600">{{ $acceptedApplications }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500 mb-1"><i class="fas fa-times-circle mr-2"></i>Rejected</p>
            <p class="text-3xl font-bold text-red-600">{{ $rejectedApplications }}</p>
        </div>
    </div>

    {{-- Tabbed Content --}}
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <div class="flex flex-wrap" role="tablist">
                <button class="px-6 py-4 font-semibold text-gray-800 border-b-2 border-blue-500 tab-button active"
                        data-tab="overview" role="tab">
                    <i class="fas fa-info-circle mr-2"></i> Overview
                </button>
                <button class="px-6 py-4 font-semibold text-gray-600 hover:text-gray-800 tab-button"
                        data-tab="master-data" role="tab">
                    <i class="fas fa-table mr-2"></i> Master Data
                </button>
                <button class="px-6 py-4 font-semibold text-gray-600 hover:text-gray-800 tab-button"
                        data-tab="applications" role="tab">
                    <i class="fas fa-file-alt mr-2"></i> Applications
                </button>
                <button class="px-6 py-4 font-semibold text-gray-600 hover:text-gray-800 tab-button"
                        data-tab="access" role="tab">
                    <i class="fas fa-key mr-2"></i> Access & Codes
                </button>
            </div>
        </div>

        <div class="p-6">
            {{-- Overview Tab --}}
            <div class="tab-content active" id="overview-tab">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Basic Info --}}
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i> Basic Information
                        </h3>
                        <div>
                            <p class="text-sm text-gray-500">Title</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $event->title }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Organizer</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $event->penyelenggara ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Stage</p>
                            <p class="text-lg font-semibold text-gray-900">{{ ucfirst($event->stage) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="text-lg font-semibold text-gray-900">{{ ucfirst($event->status) }}</p>
                        </div>
                    </div>

                    {{-- Location & Dates --}}
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-calendar text-red-600 mr-2"></i> Schedule & Location
                        </h3>
                        <div>
                            <p class="text-sm text-gray-500">Location</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $event->venue ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">City</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $event->city->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Start Date</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $event->start_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">End Date</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $event->end_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-envelope text-green-600 mr-2"></i> Contact Information
                        </h3>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-lg font-semibold text-gray-900">
                                <a href="mailto:{{ $event->email }}" class="text-blue-600 hover:underline">{{ $event->email ?? 'N/A' }}</a>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Instagram</p>
                            <p class="text-lg font-semibold text-gray-900">
                                @if($event->instagram)
                                    <a href="https://instagram.com/{{ ltrim($event->instagram, '@') }}" target="_blank" class="text-blue-600 hover:underline">
                                        {{ $event->instagram }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Assigned Admin --}}
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-tie text-blue-600 mr-2"></i> Assigned Administrator
                    </h3>
                    @if ($event->admins && $event->admins->count() > 0)
                        <div class="space-y-3">
                            @foreach ($event->admins as $admin)
                                <a href="{{ route('super-admin.admins.edit', $admin) }}" class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $admin->name }}</p>
                                            <p class="text-sm text-gray-500">@{{ $admin->username }}</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ $admin->email }}</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-400"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-exclamation-circle mr-2"></i> No administrator assigned to this event yet.
                            </p>
                            <a href="{{ route('super-admin.admins.create') }}" class="text-yellow-600 hover:underline text-sm font-semibold mt-2 inline-block">
                                Create an admin for this event
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Master Data Tab --}}
            <div class="tab-content hidden" id="master-data-tab">
                <div class="space-y-6">
                    {{-- Sports --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-running text-orange-600 mr-2"></i> Sports ({{ $event->sports->count() }})
                        </h3>
                        @if ($event->sports->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                                @foreach ($event->sports as $sport)
                                    <div class="p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                        <p class="font-semibold text-gray-900 text-sm">{{ $sport->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $sport->code }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No sports added</p>
                        @endif
                    </div>

                    {{-- Venue Locations --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i> Venue Locations ({{ $event->venueLocations->count() }})
                        </h3>
                        @if ($event->venueLocations->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($event->venueLocations as $venue)
                                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="font-semibold text-gray-900">{{ $venue->nama }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No venue locations added</p>
                        @endif
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-id-badge text-purple-600 mr-2"></i> Jabatan ({{ $event->jabatan->count() }})
                        </h3>
                        @if ($event->jabatan->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($event->jabatan as $jab)
                                    <div class="p-3 bg-purple-50 border border-purple-200 rounded-lg">
                                        <p class="font-semibold text-gray-900">{{ $jab->nama_jabatan }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No jabatan added</p>
                        @endif
                    </div>

                    {{-- Disiplin --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-trophy text-yellow-600 mr-2"></i> Disiplin ({{ $event->disciplins->count() }})
                        </h3>
                        @if ($event->disciplins->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($event->disciplins as $disc)
                                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p class="font-semibold text-gray-900">{{ $disc->nama_disiplin }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No disiplin added</p>
                        @endif
                    </div>

                    {{-- Accreditations --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-certificate text-blue-600 mr-2"></i> Accreditations ({{ $event->accreditations->count() }})
                        </h3>
                        @if ($event->accreditations->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($event->accreditations as $acc)
                                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="font-semibold text-gray-900">{{ $acc->nama_akreditasi }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No accreditations added</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Applications Tab --}}
            <div class="tab-content hidden" id="applications-tab">
                <div class="space-y-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Applications</h3>
                        <a href="#" class="text-blue-600 hover:underline font-semibold text-sm">View All</a>
                    </div>
                    @if ($recentApplications->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Applicant</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($recentApplications as $app)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 font-medium text-gray-900">{{ $app->user->name }}</td>
                                            <td class="px-4 py-3 text-gray-600">{{ $app->opening->title }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                                    @if($app->status === 'accepted') bg-green-100 text-green-800
                                                    @elseif($app->status === 'rejected') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800
                                                    @endif">
                                                    {{ ucfirst($app->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-600 text-sm">{{ $app->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No applications yet</p>
                    @endif
                </div>
            </div>

            {{-- Access & Codes Tab --}}
            <div class="tab-content hidden" id="access-tab">
                <div class="space-y-6">
                    {{-- Accommodation Codes --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-hotel text-green-600 mr-2"></i> Accommodation Codes ({{ $event->accommodationCodes->count() }})
                        </h3>
                        @if ($event->accommodationCodes->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach ($event->accommodationCodes as $acc)
                                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                        <p class="font-semibold text-gray-900">{{ $acc->kode }}</p>
                                        <p class="text-xs text-gray-500">{{ $acc->keterangan ?? 'N/A' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No accommodation codes added</p>
                        @endif
                    </div>

                    {{-- Transportation Codes --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-bus text-indigo-600 mr-2"></i> Transportation Codes ({{ $event->transportationCodes->count() }})
                        </h3>
                        @if ($event->transportationCodes->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach ($event->transportationCodes as $trans)
                                    <div class="p-3 bg-indigo-50 border border-indigo-200 rounded-lg">
                                        <p class="font-semibold text-gray-900">{{ $trans->kode }}</p>
                                        <p class="text-xs text-gray-500">{{ $trans->keterangan ?? 'N/A' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No transportation codes added</p>
                        @endif
                    </div>

                    {{-- Zone Access Codes --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-shield-alt text-pink-600 mr-2"></i> Zone Access Codes ({{ $event->zoneAccessCodes->count() }})
                        </h3>
                        @if ($event->zoneAccessCodes->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach ($event->zoneAccessCodes as $zone)
                                    <div class="p-3 bg-pink-50 border border-pink-200 rounded-lg">
                                        <p class="font-semibold text-gray-900">{{ $zone->kode_zona }}</p>
                                        <p class="text-xs text-gray-500">{{ $zone->keterangan ?? 'N/A' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No zone access codes added</p>
                        @endif
                    </div>

                    {{-- Venue Accesses --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-door-open text-cyan-600 mr-2"></i> Venue Accesses ({{ $event->venueAccesses->count() }})
                        </h3>
                        @if ($event->venueAccesses->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($event->venueAccesses as $access)
                                    <div class="p-3 bg-cyan-50 border border-cyan-200 rounded-lg">
                                        <p class="font-semibold text-gray-900">{{ $access->nama_vanue }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No venue accesses added</p>
                        @endif
                    </div>

                    {{-- Event Access Codes --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-key text-red-600 mr-2"></i> Event Access Codes ({{ $event->accessCodes->count() }})
                        </h3>
                        @if ($event->accessCodes->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach ($event->accessCodes as $code)
                                    <div class="p-3 border border-gray-200 rounded-lg" style="border-left: 4px solid {{ $code->color_hex ?? '#999' }}">
                                        <p class="font-semibold text-gray-900">{{ $code->code }}</p>
                                        <p class="text-xs text-gray-500">{{ $code->label }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No event access codes added</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');

            // Remove active state from all buttons and contents
            tabButtons.forEach(btn => {
                btn.classList.remove('border-b-2', 'border-blue-500', 'text-gray-800');
                btn.classList.add('text-gray-600', 'hover:text-gray-800');
            });

            tabContents.forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('active');
            });

            // Add active state to clicked button and corresponding content
            this.classList.add('border-b-2', 'border-blue-500', 'text-gray-800');
            this.classList.remove('text-gray-600', 'hover:text-gray-800');

            document.getElementById(tabName + '-tab').classList.remove('hidden');
            document.getElementById(tabName + '-tab').classList.add('active');
        });
    });
});
</script>
@endsection
