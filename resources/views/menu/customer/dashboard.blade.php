@extends('layouts.public')

@section('title', 'Dashboard - NOCIS')

@section('content')
<!-- Modern Web3 Customer Dashboard -->
<div class="min-h-screen bg-gray-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <!-- Main Content Wrapper with Top Padding -->
    <div class="relative z-10 pt-32 pb-16">
        <div class="container mx-auto px-4 lg:px-6 max-w-7xl">
            
            <!-- Welcome Header -->
            <div class="mb-10">
                <h1 class="text-4xl font-bold text-gray-900 mb-2 tracking-tight">
                    Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-red-400">{{ session('customer_username') }}!</span>
                </h1>
                <p class="text-gray-600 text-lg">Here's your career activity overview.</p>
            </div>

            <!-- Navigation Tabs -->
            <div class="bg-white/60 backdrop-blur-xl rounded-2xl p-2 border border-white/50 shadow-sm mb-10 inline-flex flex-wrap gap-2">
                <a href="{{ route('customer.dashboard') }}" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-white text-red-600 shadow-md transition-all">
                    <i class="fas fa-th-large mr-2"></i> Dashboard
                </a>
                <a href="{{ route('customer.applications') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-white/50 transition-all">
                    <i class="fas fa-file-alt mr-2"></i> My Applications
                </a>
                <a href="{{ route('customer.saved-jobs') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-white/50 transition-all">
                    <i class="fas fa-bookmark mr-2"></i> Saved Jobs
                </a>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <!-- Total Applications -->
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white/60 shadow-lg relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <i class="fas fa-file-alt text-6xl text-blue-600"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-1">Total Applied</p>
                        <h3 class="text-4xl font-extrabold text-gray-900">{{ $totalApplications }}</h3>
                        <div class="w-12 h-1 bg-blue-500 rounded-full mt-4"></div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white/60 shadow-lg relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <i class="fas fa-clock text-6xl text-yellow-600"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-1">Pending</p>
                        <h3 class="text-4xl font-extrabold text-gray-900">{{ $pendingApplications }}</h3>
                        <div class="w-12 h-1 bg-yellow-500 rounded-full mt-4"></div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white/60 shadow-lg relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <i class="fas fa-check-circle text-6xl text-green-600"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-1">Approved</p>
                        <h3 class="text-4xl font-extrabold text-gray-900">{{ $approvedApplications }}</h3>
                        <div class="w-12 h-1 bg-green-500 rounded-full mt-4"></div>
                    </div>
                </div>

                <!-- Rejected -->
                <div class="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-white/60 shadow-lg relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <i class="fas fa-times-circle text-6xl text-red-600"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-1">Rejected</p>
                        <h3 class="text-4xl font-extrabold text-gray-900">{{ $rejectedApplications }}</h3>
                        <div class="w-12 h-1 bg-red-500 rounded-full mt-4"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Applications -->
                <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/60 shadow-xl overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100/50 flex justify-between items-center bg-white/40">
                        <h3 class="text-xl font-bold text-gray-900">Recent Activity</h3>
                        <a href="{{ route('customer.applications') }}" class="text-sm font-bold text-red-600 hover:text-red-700 transition-colors">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($applications as $application)
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/60 border border-gray-100 hover:border-red-100 hover:bg-white transition-all duration-300 group">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0 group-hover:bg-red-50 transition-colors">
                                <i class="fas fa-briefcase text-gray-400 group-hover:text-red-500"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-gray-900 truncate">{{ $application->opening->title }}</h4>
                                <p class="text-xs text-gray-500">{{ $application->opening->event->title }}</p>
                            </div>
                            <div class="text-right">
                                @if($application->status === 'pending')
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">Pending</span>
                                @elseif($application->status === 'approved')
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">Approved</span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">Rejected</span>
                                @endif
                                <p class="text-[10px] text-gray-400 mt-1 font-medium">{{ $application->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10">
                            <i class="fas fa-clipboard-list text-4xl text-gray-200 mb-3"></i>
                            <p class="text-gray-500 font-medium">No applications found.</p>
                            <a href="{{ route('jobs.index') }}" class="text-red-600 text-sm font-bold hover:underline mt-2 inline-block">Start Applying</a>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recommended Jobs -->
                <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/60 shadow-xl overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100/50 flex justify-between items-center bg-white/40">
                        <h3 class="text-xl font-bold text-gray-900">Recommended For You</h3>
                        <a href="{{ route('jobs.index') }}" class="text-sm font-bold text-red-600 hover:text-red-700 transition-colors">
                            Browse All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($recommendedJobs as $job)
                        <div class="relative p-5 rounded-2xl bg-gradient-to-br from-white to-gray-50 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="text-[10px] font-bold text-red-500 uppercase tracking-wider">{{ $job->event->title ?? 'NOC Indonesia' }}</span>
                                    <h4 class="font-bold text-gray-900 mt-1">{{ $job->title }}</h4>
                                </div>
                                <a href="{{ route('jobs.show', $job) }}" class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 border border-gray-200 transition-colors shadow-sm">
                                    <i class="fas fa-arrow-right -rotate-45"></i>
                                </a>
                            </div>
                            <div class="flex items-center gap-4 text-xs text-gray-500 font-medium mt-3">
                                <span class="flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-gray-400"></i> {{ $job->event->city->name ?? 'Jakarta' }}</span>
                                <span class="flex items-center gap-1.5"><i class="fas fa-clock text-gray-400"></i> {{ $job->application_deadline->format('d M') }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10">
                            <i class="fas fa-search text-4xl text-gray-200 mb-3"></i>
                            <p class="text-gray-500 font-medium">No recommendations yet.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection