@extends('layouts.app') {{-- Memperluas master layout --}}

@section('title', 'General Dashboard - KOI')
@section('page-title')
    General Dashboard
@endsection

@section('content')
<div class="space-y-8">
    {{-- Greeting + highlight cards --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h1 class="text-2xl font-semibold text-gray-900 mb-2">Hi, Admin. Selamat datang di Dashboard!</h1>
        <p class="text-gray-500 mb-6">Pantau ringkasan acara dan kinerja panitia di sini.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-red-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">23</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Total Events</p>
                    <p class="text-xs text-emerald-500 mt-1">+3 Acara Baru</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-bullseye text-red-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">8</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Active Events</p>
                    <p class="text-xs text-red-500 mt-1">Sedang Berlangsung</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-users text-red-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">924</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Participants</p>
                    <p class="text-xs text-red-500 mt-1">+7% dari bulan lalu</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-[#FFF8F7] rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#FBDAD4] mr-3 sm:mr-4 flex items-center justify-center">
                    <i class="fas fa-trophy text-red-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-semibold text-gray-900">Rp 4.6B</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Total Budget</p>
                    <p class="text-xs text-red-500 mt-1">Budget Tersedia</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Upcoming events & review panels --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Upcoming Events --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                        <i class="fas fa-align-left mr-2"></i> Acara Mendatang
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Daftar Schedule</h3>
                </div>
                <button class="text-sm text-red-500 font-semibold hover:underline">Lihat semua</button>
            </div>
            <div class="space-y-4">
                @php
                    $upcomingEvents = [
                        ['name' => 'Swimming Championship', 'date' => '2025-11-29', 'city' => 'Jakarta', 'members' => 30, 'status' => 'Aktif', 'status_color' => 'bg-red-100 text-red-600'],
                        ['name' => 'Asian Games', 'date' => '2025-11-29', 'city' => 'Jakarta', 'members' => 30, 'status' => 'Aktif', 'status_color' => 'bg-red-100 text-red-600'],
                        ['name' => 'National Badminton', 'date' => '2025-12-02', 'city' => 'Jakarta', 'members' => 30, 'status' => 'Persiapan', 'status_color' => 'bg-gray-200 text-gray-600'],
                        ['name' => 'National Badminton', 'date' => '2025-12-20', 'city' => 'Jakarta', 'members' => 30, 'status' => 'Persiapan', 'status_color' => 'bg-gray-200 text-gray-600'],
                    ];
                @endphp
                @foreach ($upcomingEvents as $event)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 rounded-2xl px-4 py-3 border border-gray-100 space-y-2 sm:space-y-0">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-sm sm:text-base">{{ $event['name'] }}</p>
                            <div class="text-xs sm:text-sm text-gray-500 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-1 sm:space-y-0">
                                <span><i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($event['date'])->format('Y-m-d') }}</span>
                                <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $event['city'] }}</span>
                                <span class="hidden sm:inline"><i class="fas fa-users mr-1"></i>{{ $event['members'] }} Panitia</span>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $event['status_color'] }} self-start sm:self-center">{{ $event['status'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Review Panitia --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-sm uppercase tracking-wide text-red-500 font-semibold flex items-center">
                        <i class="fas fa-pen mr-2"></i> Review Panitia
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Feedback terbaru</h3>
                </div>
                <button class="text-sm text-red-500 font-semibold hover:underline">Lihat semua</button>
            </div>
            <div class="space-y-4">
                @php
                    $reviews = [
                        ['name' => 'Syifa Aquila', 'date' => '2025-11-29', 'city' => 'Jakarta', 'members' => 30, 'label' => 'VO', 'label_color' => 'bg-red-100 text-red-600'],
                        ['name' => 'Nadya Girsang', 'date' => '2025-11-29', 'city' => 'Jakarta', 'members' => 30, 'label' => 'LO', 'label_color' => 'bg-green-100 text-green-600'],
                        ['name' => 'Harlin', 'date' => '2025-11-29', 'city' => 'Jakarta', 'members' => 30, 'label' => 'LO', 'label_color' => 'bg-green-100 text-green-600'],
                    ];
                @endphp
                @foreach ($reviews as $review)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 rounded-2xl px-4 py-3 border border-gray-100 space-y-2 sm:space-y-0">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-sm sm:text-base">{{ $review['name'] }}</p>
                            <div class="text-xs sm:text-sm text-gray-500 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-1 sm:space-y-0">
                                <span><i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($review['date'])->format('Y-m-d') }}</span>
                                <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $review['city'] }}</span>
                                <span class="hidden sm:inline"><i class="fas fa-users mr-1"></i>{{ $review['members'] }} Panitia</span>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-4 py-1 rounded-full {{ $review['label_color'] }} self-start sm:self-center">{{ $review['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection