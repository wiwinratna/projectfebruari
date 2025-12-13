@extends('layouts.public')

@section('title', 'My Applications - NOCIS')

@section('content')
<!-- Modern Web3 Customer Applications -->
<div class="min-h-screen bg-gray-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <!-- Main Content Wrapper with Top Padding -->
    <div class="relative z-10 pt-32 pb-16">
        <div class="container mx-auto px-4 lg:px-6 max-w-7xl">
            
            <!-- Page Header -->
            <div class="mb-10">
                <h1 class="text-4xl font-bold text-gray-900 mb-2 tracking-tight">My Applications</h1>
                <p class="text-gray-600 text-lg">Track the progress of your career opportunities.</p>
            </div>

            <!-- Navigation Tabs -->
            <div class="bg-white/60 backdrop-blur-xl rounded-2xl p-2 border border-white/50 shadow-sm mb-10 inline-flex flex-wrap gap-2">
                <a href="{{ route('customer.dashboard') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-white/50 transition-all">
                    <i class="fas fa-th-large mr-2"></i> Dashboard
                </a>
                <a href="{{ route('customer.applications') }}" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-white text-red-600 shadow-md transition-all">
                    <i class="fas fa-file-alt mr-2"></i> My Applications
                </a>
                <a href="{{ route('customer.saved-jobs') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-white/50 transition-all">
                    <i class="fas fa-bookmark mr-2"></i> Saved Jobs
                </a>
            </div>

            <!-- Applications List -->
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/60 shadow-xl overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100/50 flex justify-between items-center bg-white/40">
                    <h3 class="text-xl font-bold text-gray-900">Application History</h3>
                    <div class="bg-white/50 px-3 py-1 rounded-lg border border-gray-100 text-sm font-medium text-gray-600">
                        Total: <span class="font-bold text-gray-900">{{ $applications->total() }}</span>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    @forelse($applications as $application)
                    <div class="group relative bg-white/60 backdrop-blur-sm border border-gray-100 rounded-2xl p-6 transition-all duration-300 hover:shadow-lg hover:border-red-100 hover:bg-white">
                        <div class="flex flex-col lg:flex-row gap-6">
                            <!-- Left: Job Info -->
                            <div class="flex-1">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                                            <i class="fas fa-trophy text-2xl text-red-500/80 group-hover:text-red-600 transition-colors"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-red-600 bg-red-50 px-2 py-0.5 rounded-md border border-red-100">
                                                    {{ $application->opening->event->title }}
                                                </span>
                                            </div>
                                            <h4 class="text-xl font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ $application->opening->title }}</h4>
                                        </div>
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    <div class="flex-shrink-0">
                                        @if($application->status === 'pending')
                                            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-yellow-50 text-yellow-700 border border-yellow-100 shadow-sm">
                                                <span class="relative flex h-2.5 w-2.5">
                                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-yellow-500"></span>
                                                </span>
                                                <span class="text-sm font-bold">Pending Review</span>
                                            </div>
                                        @elseif($application->status === 'approved')
                                            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-green-50 text-green-700 border border-green-100 shadow-sm">
                                                <i class="fas fa-check-circle text-green-500"></i>
                                                <span class="text-sm font-bold">Approved</span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 text-red-700 border border-red-100 shadow-sm">
                                                <i class="fas fa-times-circle text-red-500"></i>
                                                <span class="text-sm font-bold">Not Selected</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Grid Details -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50/50 rounded-xl p-4 border border-gray-100/50">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Applied Date</p>
                                        <p class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                            <i class="fas fa-calendar-alt text-gray-400"></i> {{ $application->created_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Motivation</p>
                                        <p class="text-sm font-medium text-gray-700 truncate" title="{{ $application->motivation }}">
                                            "{{ Str::limit($application->motivation, 60) }}"
                                        </p>
                                    </div>
                                </div>
                            </div>
     
                            <!-- Right: Actions -->
                            <div class="flex lg:flex-col justify-end gap-2 border-t lg:border-t-0 lg:border-l border-gray-100 pt-4 lg:pt-0 lg:pl-6 min-w-[140px]">
                                <a href="{{ route('jobs.show', $application->opening) }}" class="w-full text-center px-4 py-2 rounded-xl bg-gray-50 hover:bg-white text-gray-600 hover:text-red-600 font-bold text-sm border border-gray-200 hover:border-red-200 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                                    View Job
                                </a>
                                <button type="button" class="w-full text-center px-4 py-2 rounded-xl bg-white text-gray-400 hover:text-gray-600 font-bold text-sm border border-transparent hover:border-gray-200 transition-all">
                                    Details
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-16 px-4">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-folder-open text-3xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Applications Yet</h3>
                        <p class="text-gray-500 mb-8 max-w-sm mx-auto">You haven't applied for any positions yet. Start your journey by browsing open roles.</p>
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 hover:shadow-lg hover:shadow-red-500/30 transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-search mr-2"></i> Browse Opportunities
                        </a>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($applications->hasPages())
                <div class="bg-gray-50/50 px-8 py-4 border-t border-gray-100">
                    {{ $applications->links('pagination::tailwind') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection