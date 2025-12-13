@extends('layouts.app') {{-- Memperluas master layout --}}

@section('title', 'General Dashboard - KOI')
@section('page-title')
    General Dashboard
@endsection

@section('content')
<div class="space-y-8">
    {{-- Greeting + highlight cards --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h1 class="text-2xl font-semibold text-gray-900 mb-2">Hi, Admin. Welcome to Dashboard!</h1>
        <p class="text-gray-500 mb-6">Monitor event summaries and committee performance here.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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
                    <i class="fas fa-bullseye text-red-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $activeEvents }}</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Active Events</p>
                    <p class="text-xs text-red-500 mt-1">Ongoing</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-users text-red-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $totalCandidates }}</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Total Candidates</p>
                    <p class="text-xs text-emerald-500 mt-1">Registered Users</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-briefcase text-red-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ $openJobs }}</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Job Open</p>
                    <p class="text-xs text-emerald-500 mt-1">Positions Available</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Upcoming events & review panels --}}
    {{-- Upcoming events & applications panels --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Upcoming Events --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                        <i class="fas fa-align-left mr-2"></i> Upcoming Events
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Schedule List</h3>
                </div>
                <a href="{{ route('admin.events.index') }}" class="text-sm text-red-500 font-semibold hover:underline">View All</a>
            </div>
            <div class="space-y-4">
                @forelse ($upcomingEvents as $event)
                    <a href="{{ route('admin.events.show', $event) }}" class="block group">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 group-hover:bg-red-50 rounded-2xl px-4 py-3 border border-gray-100 group-hover:border-red-100 transition-all space-y-2 sm:space-y-0">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 group-hover:text-red-700 text-sm sm:text-base transition-colors">{{ $event->title }}</p>
                                <div class="text-xs sm:text-sm text-gray-500 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-1 sm:space-y-0">
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $event->start_at->format('Y-m-d') }}</span>
                                    <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->city->name ?? 'Location' }}</span>
                                    {{-- <span class="hidden sm:inline"><i class="fas fa-users mr-1"></i>{{ $event->members ?? 0 }} Staff</span> --}}
                                </div>
                            </div>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-red-100 text-red-600 self-start sm:self-center">Upcoming</span>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-4 text-gray-500">No upcoming events found.</div>
                @endforelse
            </div>
        </div>

        {{-- Recent Applications --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                        <i class="fas fa-pen mr-2"></i> Recent Applications
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Latest Applicants</h3>
                </div>
                <a href="{{ route('admin.reviews.index') }}" class="text-sm text-red-500 font-semibold hover:underline">View All</a>
            </div>
            <div class="space-y-4">
                @forelse ($recentApplications as $application)
                    <a href="{{ route('admin.applications.show', $application->id) }}" class="block group">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 group-hover:bg-red-50 rounded-2xl px-4 py-3 border border-gray-100 group-hover:border-red-100 transition-all space-y-2 sm:space-y-0">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 group-hover:text-red-700 text-sm sm:text-base transition-colors">{{ $application->user->username ?? 'Unknown User' }}</p>
                                <div class="text-xs sm:text-sm text-gray-500 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-1 sm:space-y-0">
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $application->created_at->format('Y-m-d') }}</span>
                                    <span><i class="fas fa-briefcase mr-1"></i>{{ Str::limit($application->opening->title ?? 'Job', 20) }}</span>
                                    <span class="hidden sm:inline"><i class="fas fa-map-marker-alt mr-1"></i>{{ Str::limit($application->opening->event->city->name ?? 'City', 15) }}</span>
                                </div>
                            </div>
                            <span class="text-xs font-semibold px-4 py-1 rounded-full {{ $application->status === 'approved' ? 'bg-green-100 text-green-600' : ($application->status === 'rejected' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }} self-start sm:self-center">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-4 text-gray-500">No recent applications found.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection