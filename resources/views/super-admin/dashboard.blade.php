@extends('layouts.app')

@section('title', 'Super Admin Dashboard - NOCIS')
@section('page-title')
    Super Admin Dashboard <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">System Administrator</span>
@endsection

@section('content')
<div class="space-y-8">
    {{-- Greeting + highlight cards --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
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

    {{-- Admin Management & Events panels --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Admins --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-blue-600 font-semibold flex items-center">
                        <i class="fas fa-user-tie mr-2"></i> System Administrators
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Admin Users</h3>
                </div>
                <a href="{{ route('super-admin.admins.index') }}" class="text-sm text-blue-600 font-semibold hover:underline">View All</a>
            </div>
            <div class="space-y-3">
                @forelse ($admins->take(5) as $admin)
                    <a href="{{ route('super-admin.admins.edit', $admin) }}" class="block group">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 group-hover:bg-blue-50 rounded-2xl px-4 py-3 border border-gray-100 group-hover:border-blue-100 transition-all space-y-2 sm:space-y-0">
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
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i> Events Overview
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">All Events</h3>
                </div>
                <a href="{{ route('super-admin.events.index') }}" class="text-sm text-red-500 font-semibold hover:underline">View All</a>
            </div>
            <div class="space-y-3">
                @forelse ($events ?? collect()->take(5) as $event)
                    <a href="{{ route('super-admin.events.show', $event) }}" class="block group">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 group-hover:bg-red-50 rounded-2xl px-4 py-3 border border-gray-100 group-hover:border-red-100 transition-all space-y-2 sm:space-y-0">
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
</div>
@endsection
