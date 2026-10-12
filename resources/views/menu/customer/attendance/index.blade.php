@extends('layouts.public')

@section('title', 'Event Attendance - NOCIS')

@section('content')
<!-- Modern Web3 Customer Dashboard Style -->
<div class="min-h-screen bg-gray-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="relative z-10 pt-24 sm:pt-32 pb-20 sm:pb-16">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            
            <!-- Welcome Header -->
            <div class="mb-5 sm:mb-8">
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-900 mb-1 sm:mb-2 tracking-tight">
                    Event <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-red-400">Attendance</span>
                </h1>
                <p class="text-xs sm:text-base text-gray-600">Lakukan proses check-in dan check-out untuk event yang sedang berlangsung.</p>
            </div>

            <!-- Navigation Tabs (Hidden on mobile) -->
            @include('partials.customer-nav-tabs')

            <!-- Alerts -->
            @if(session('error'))
                <div class="mb-4 sm:mb-6 bg-red-50 border border-red-200 text-red-700 px-3.5 py-2.5 rounded-xl shadow-xs text-xs sm:text-sm font-medium">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4 sm:mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-3.5 py-2.5 rounded-xl shadow-xs text-xs sm:text-sm font-medium">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Events List -->
            <div class="bg-white/70 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-white/60 shadow-lg sm:shadow-xl overflow-hidden">
                <div class="px-4 sm:px-8 py-3.5 sm:py-5 border-b border-gray-100 flex justify-between items-center bg-white/40">
                    <h3 class="text-base sm:text-xl font-bold text-gray-900">Event Aktif Hari Ini</h3>
                </div>
                <div class="p-3.5 sm:p-6 space-y-3">
                    @forelse($events as $event)
                        <div class="bg-white rounded-xl sm:rounded-2xl p-3.5 sm:p-5 border border-gray-100 shadow-xs hover:shadow-md transition-shadow flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4">
                            <div class="flex items-start gap-3 sm:gap-4">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-50 text-red-500 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fas fa-calendar-check text-base sm:text-xl"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm sm:text-lg font-bold text-gray-900 truncate">{{ $event->title }}</h4>
                                    <div class="text-xs text-gray-500 mb-1.5 flex items-center gap-1">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                        <span class="truncate">{{ $event->city_name ?? 'Lokasi tidak ditentukan' }}</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 text-[11px] sm:text-xs font-medium">
                                        <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/80 font-bold">
                                            Status: Active
                                        </span>
                                        <span class="text-gray-500 flex items-center gap-1">
                                            <i class="far fa-clock text-gray-400"></i> 
                                            {{ $event->start_at->format('d M Y H:i') }} - {{ $event->end_at->format('d M Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="shrink-0 md:text-right pt-2 md:pt-0 border-t md:border-t-0 border-gray-100">
                                <a href="{{ route('customer.attendance.show', $event->id) }}" class="inline-flex items-center justify-center px-4 py-2 sm:px-6 sm:py-2.5 bg-gray-900 text-white rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold hover:bg-black transition-colors shadow-sm hover:shadow-md w-full md:w-auto">
                                    Buka Kamera Absensi <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                <i class="fas fa-bed text-3xl text-gray-300"></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Tidak Ada Event Aktif</h4>
                            <p class="text-gray-500 max-w-md mx-auto">
                                Saat ini tidak ada event berstatus "active" di mana Anda memiliki persetujuan (Approved), atau belum memasuki batas periode waktu event.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
