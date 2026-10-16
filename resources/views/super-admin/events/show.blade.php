@extends('layouts.app')

@section('title', 'Event Details - ARISE')
@section('page-title')
Event Details <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $event->title }}</span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Flash Alerts --}}
    @if(session('status'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center justify-between shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-600 text-lg"></i>
                <span class="font-medium">{{ session('status') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800"><i class="fas fa-times"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-center justify-between shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-600 text-lg"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800"><i class="fas fa-times"></i></button>
        </div>
    @endif

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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200/80 p-4 shadow-xs hover:shadow-md transition flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Job Openings</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalWorkerOpenings }}</p>
                <span class="text-[11px] text-blue-600 font-medium">Posisi Terbuka</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                <i class="fas fa-briefcase"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200/80 p-4 shadow-xs hover:shadow-md transition flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pelamar</p>
                <p class="text-2xl font-bold text-indigo-900 mt-1">{{ $totalApplications }}</p>
                <span class="text-[11px] text-indigo-600 font-medium">Semua Pengajuan</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200/80 p-4 shadow-xs hover:shadow-md transition flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelamar Diterima</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $acceptedApplications }}</p>
                <span class="text-[11px] text-emerald-600 font-medium">Lolos Seleksi</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200/80 p-4 shadow-xs hover:shadow-md transition flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pending / Ditolak</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    <span class="text-amber-500">{{ $pendingApplications }}</span>
                    <span class="text-gray-300 text-lg font-normal">/</span>
                    <span class="text-rose-500">{{ $rejectedApplications }}</span>
                </p>
                <span class="text-[11px] text-gray-500 font-medium">Menunggu / Ditolak</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fas fa-user-clock"></i>
            </div>
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
                <button class="px-6 py-4 font-semibold text-gray-600 hover:text-gray-800 tab-button"
                    data-tab="attendance" role="tab">
                    <i class="fas fa-clipboard-check mr-2"></i> Attendance
                    @if(isset($totalAttendanceRecords) && $totalAttendanceRecords > 0)
                        <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                            {{ $totalAttendanceRecords }}
                        </span>
                    @endif
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
                            <p class="text-lg font-semibold text-gray-900">
                                @if($event->stage === 'province') Regional / Inter-City
                                @elseif($event->stage === 'national') National Championship
                                @elseif($event->stage === 'asean/sea') Southeast Asia (SEA Games)
                                @elseif($event->stage === 'asia') Asian Championship
                                @elseif($event->stage === 'world') World Championship
                                @else {{ ucfirst($event->stage) }} @endif
                            </p>
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
                    <!-- <div>
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
                    </div> -->

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
                <div class="space-y-6">
                    {{-- Header & Action Bar --}}
                    <div class="bg-white rounded-xl p-5 sm:p-6 shadow-xs" style="border: 1px solid #e5e7eb;">
                        {{-- Row 1: Title (Left) & Export/Action Buttons (Right) --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            {{-- Title & Count --}}
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 leading-tight">Rekrutmen & Pelamar Event</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Total {{ $totalApplications }} pelamar terdaftar</p>
                                </div>
                            </div>

                            {{-- Action Buttons (Right) --}}
                            <div class="flex flex-wrap items-center gap-2.5">
                                {{-- Export Excel Button --}}
                                <a href="{{ route('super-admin.events.applications.export', $event) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition whitespace-nowrap cursor-pointer shadow-xs"
                                    title="Download laporan seluruh data pelamar event ini dalam format Excel lengkap dengan Kop surat resmi">
                                    <i class="fas fa-file-excel text-xs"></i>
                                    <span>Export Excel</span>
                                </a>

                                {{-- Form for Batch Photo Download --}}
                                <form id="form-download-photos-sa" method="POST" action="{{ route('super-admin.events.applicant-photos.download', $event) }}" class="inline-flex items-center gap-2.5 m-0 p-0">
                                    @csrf
                                    <input type="hidden" name="all" id="input-download-all-sa" value="0">
                                    <div id="selected-ids-container-sa"></div>

                                    {{-- Download Selected Button (Clean disabled state with readable font) --}}
                                    <button type="button" id="btn-download-selected-sa" onclick="submitBatchDownloadSA(false)"
                                        disabled
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-medium cursor-not-allowed whitespace-nowrap transition"
                                        style="border: 1px solid #e5e7eb;">
                                        <i class="fas fa-images text-xs"></i>
                                        <span>Download Foto Terpilih (<span id="count-selected-sa">0</span>)</span>
                                    </button>

                                    {{-- Download All Accepted Button --}}
                                    @if($acceptedApplicationsList->count() > 0)
                                    <button type="button" id="btn-download-all-sa" onclick="submitBatchDownloadSA(true)"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold cursor-pointer shadow-xs transition whitespace-nowrap"
                                        title="Unduh seluruh foto pelamar berstatus diterima (format ZIP)">
                                        <i class="fas fa-download text-xs"></i>
                                        <span>Unduh Semua Foto Diterima (ZIP)</span>
                                    </button>
                                    @endif
                                </form>
                            </div>
                        </div>

                        {{-- Row 2: Search & Checklist (Left) & Filter Status Buttons (Right) --}}
                        <div class="mt-4 pt-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4" style="border-top: 1px solid #e5e7eb;">
                            {{-- Search Bar & Checklist (Left) --}}
                            <div class="flex flex-wrap items-center gap-3.5">
                                <div class="relative w-full sm:w-64">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 text-xs">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="filter-accepted-sa" placeholder="Cari nama, email, NIK, posisi..."
                                        class="w-full pl-9 pr-3 py-1.5 text-xs bg-gray-50/70 rounded-lg focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-gray-800 placeholder:text-gray-400 transition"
                                        style="border: 1px solid #e5e7eb;">
                                </div>

                                <div class="flex items-center gap-2 text-xs text-gray-600 whitespace-nowrap shrink-0">
                                    <label class="inline-flex items-center gap-2 cursor-pointer font-medium hover:text-gray-900 select-none">
                                        <input type="checkbox" id="check-all-sa" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                        <span>Pilih Semua Foto</span>
                                    </label>
                                    <span class="text-gray-300">|</span>
                                    <span>Menampilkan <strong id="visible-count-sa" class="text-gray-900">{{ $applications->count() }}</strong> pelamar</span>
                                </div>
                            </div>

                            {{-- Filter Status Buttons (Right) --}}
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" onclick="filterByStatusSA('all')" id="btn-filter-all"
                                    class="status-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition bg-blue-600 text-white shadow-xs cursor-pointer">
                                    Semua ({{ $totalApplications }})
                                </button>
                                <button type="button" onclick="filterByStatusSA('accepted')" id="btn-filter-accepted"
                                    class="status-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition bg-white text-gray-700 hover:bg-gray-50 cursor-pointer"
                                    style="border: 1px solid #e5e7eb;">
                                    Diterima ({{ $acceptedApplications }})
                                </button>
                                <button type="button" onclick="filterByStatusSA('pending')" id="btn-filter-pending"
                                    class="status-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition bg-white text-gray-700 hover:bg-gray-50 cursor-pointer"
                                    style="border: 1px solid #e5e7eb;">
                                    Pending ({{ $pendingApplications }})
                                </button>
                                <button type="button" onclick="filterByStatusSA('rejected')" id="btn-filter-rejected"
                                    class="status-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition bg-white text-gray-700 hover:bg-gray-50 cursor-pointer"
                                    style="border: 1px solid #e5e7eb;">
                                    Ditolak ({{ $rejectedApplications }})
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- All Applicants Table --}}
                    @if ($applications->count() > 0)
                    <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-xs bg-white">
                        <table class="w-full divide-y divide-gray-200 text-left" id="table-accepted-sa">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th class="w-10 px-4 py-3 text-center">
                                        <span class="sr-only">Pilih</span>
                                    </th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Pelamar</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Posisi / Kategori</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status Lamaran</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Foto Profil</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-sm">
                                @foreach ($applications as $app)
                                @php
                                    $user = $app->user;
                                    $profile = $user?->profile;
                                    $hasPhoto = !empty($profile?->profile_photo);
                                    $photoUrl = $hasPhoto ? asset('storage/' . ltrim($profile->profile_photo, '/')) : null;
                                    $isAccepted = in_array($app->status, ['approved', 'accepted']);
                                    $isRejected = ($app->status === 'rejected');
                                    $isPending = (!$isAccepted && !$isRejected);

                                    $statusCategory = $isAccepted ? 'accepted' : ($isRejected ? 'rejected' : 'pending');

                                    $appModalData = [
                                        'id' => $app->id,
                                        'name' => $user?->name ?: ($profile?->name ?: ($user?->username ?: '-')),
                                        'username' => $user?->username ?: '-',
                                        'email' => $user?->email ?: '-',
                                        'phone' => $profile?->phone ?: '-',
                                        'nik' => $profile?->nik ?: '-',
                                        'kewarganegaraan' => ($profile && $profile->nationality_type === 'wna') ? 'WNA' : 'WNI',
                                        'dob' => $profile?->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->format('d M Y') : '-',
                                        'age' => $profile?->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->age . ' Tahun' : '-',
                                        'address' => $profile?->address ?: '-',
                                        'city' => $profile?->city_regency ?: '-',
                                        'province' => $profile?->province ?: '-',
                                        'postal_code' => $profile?->postal_code ?: '-',
                                        'rt_rw' => ($profile && ($profile->rt || $profile->rw)) ? 'RT ' . ($profile->rt ?: '-') . ' / RW ' . ($profile->rw ?: '-') : '-',
                                        'education' => $profile?->last_education ?: '-',
                                        'study' => $profile?->field_of_study ?: '-',
                                        'university' => $profile?->university ?: '-',
                                        'skills' => $profile?->skills ?: '-',
                                        'languages' => $profile?->languages ?: '-',
                                        'opening_title' => $app->opening?->title ?: '-',
                                        'job_category' => $app->opening?->jobCategory?->name ?: '-',
                                        'fa_name' => $app->opening?->jobCategory?->fa_name ?: '-',
                                        'status' => ucfirst($app->status),
                                        'status_badge' => $statusCategory,
                                        'created_at' => $app->created_at ? $app->created_at->format('d M Y H:i') : '-',
                                        'motivation' => $app->motivation ?: '-',
                                        'experience' => $app->experience ?: '-',
                                        'review_notes' => $app->review_notes ?: '-',
                                        'reviewed_at' => $app->reviewed_at ? $app->reviewed_at->format('d M Y H:i') : '-',
                                        'reviewer' => $app->reviewer?->name ?: '-',
                                        'has_photo' => $hasPhoto,
                                        'photo_url' => $photoUrl,
                                        'cv_path' => $app->cv_path ?: ($profile?->cv_file ?: null),
                                        'cv_url' => ($app->cv_path ?: $profile?->cv_file) ? asset('storage/' . ltrim(($app->cv_path ?: $profile->cv_file), '/')) : null,
                                    ];
                                @endphp
                                <tr class="hover:bg-blue-50/30 transition-colors row-applicant-sa"
                                    data-status="{{ $statusCategory }}"
                                    data-search="{{ strtolower(($user?->name ?? '') . ' ' . ($user?->username ?? '') . ' ' . ($user?->email ?? '') . ' ' . ($profile?->nik ?? '') . ' ' . ($app->opening?->title ?? '') . ' ' . ($app->opening?->jobCategory?->name ?? '') . ' ' . $app->status) }}">
                                    <td class="px-4 py-3 text-center">
                                        @if($isAccepted && $hasPhoto)
                                            <input type="checkbox" value="{{ $app->id }}"
                                                class="check-item-sa rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer"
                                                onchange="updateSelectionStateSA()">
                                        @else
                                            <input type="checkbox" disabled
                                                class="rounded border-gray-200 text-gray-300 w-4 h-4 cursor-not-allowed opacity-40"
                                                title="Hanya pelamar berstatus diterima dengan foto profil yang dapat diunduh fotonya">
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            @if($hasPhoto)
                                                <div class="w-10 h-10 rounded-full flex-shrink-0 overflow-hidden border border-gray-200 shadow-2xs">
                                                    <img src="{{ $photoUrl }}" alt="" class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0 text-slate-600 font-bold text-xs">
                                                    {{ strtoupper(substr($user?->name ?: ($user?->username ?: 'P'), 0, 2)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-bold text-gray-900 text-sm leading-snug">{{ $user?->name ?: ($user?->username ?: 'Pelamar #' . $app->id) }}</p>
                                                <p class="text-xs text-gray-500 leading-snug">{{ $user?->email }}</p>
                                                @if(!empty($profile?->nik))
                                                    <span class="inline-flex items-center gap-1 text-[11px] font-mono text-emerald-800 bg-emerald-50 border border-emerald-200/80 rounded px-1.5 py-0.2 mt-0.5">
                                                        <i class="fas fa-id-card text-[10px]"></i> NIK: {{ $profile->nik }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-800 leading-snug">{{ $app->opening?->title ?? 'N/A' }}</p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-xs text-gray-500">{{ $app->opening?->jobCategory?->name ?? 'General' }}</span>
                                            @if(!empty($app->opening?->jobCategory?->fa_name))
                                                <span class="text-gray-300">•</span>
                                                <span class="text-[11px] font-medium text-blue-600">{{ $app->opening->jobCategory->fa_name }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($isAccepted)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <i class="fas fa-check-circle text-[10px]"></i> Diterima
                                            </span>
                                        @elseif($isRejected)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                                <i class="fas fa-times-circle text-[10px]"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                <i class="fas fa-clock text-[10px]"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($hasPhoto)
                                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                                                <i class="fas fa-check text-[10px]"></i> Tersedia
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-400 bg-gray-100 px-2 py-0.5 rounded">
                                                <i class="fas fa-minus text-[10px]"></i> Belum ada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                                        <p class="font-medium text-gray-700">{{ $app->created_at ? $app->created_at->format('d M Y') : '-' }}</p>
                                        <p class="text-[11px] text-gray-400">{{ $app->created_at ? $app->created_at->format('H:i') . ' WIB' : '' }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            @if($hasPhoto)
                                                <a href="{{ route('super-admin.applications.photo.download', $app) }}"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200 transition"
                                                    title="Unduh foto profil pelamar ini">
                                                    <i class="fas fa-download text-xs"></i>
                                                    <span class="hidden sm:inline">Foto</span>
                                                </a>
                                            @endif

                                            <button type="button"
                                                data-applicant="{{ json_encode($appModalData) }}"
                                                onclick="openApplicantModalFromBtn(this)"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg border border-gray-200 transition cursor-pointer"
                                                title="Lihat detail lengkap pelamar">
                                                <i class="fas fa-eye text-xs"></i>
                                                <span>Detail</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-12 bg-gray-50 border border-gray-200 rounded-xl text-center">
                        <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-500 mx-auto flex items-center justify-center text-2xl mb-3">
                            <i class="fas fa-users-slash"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 text-base">Belum ada pelamar yang mendaftar pada event ini</h4>
                        <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">
                            Ketika calon relawan/pekerja mendaftar pada lowongan event ini, daftar pelamar dan fitur ekspor akan otomatis tersedia di sini.
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Applicant Detail Modal --}}
            <div id="applicant-detail-modal-sa" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeApplicantDetailModalSA()"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-200">
                        {{-- Modal Header --}}
                        <div class="bg-gradient-to-r from-slate-900 to-blue-900 px-6 py-4 text-white flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-lg">
                                    <i class="fas fa-id-card text-blue-300"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-white" id="modal-app-name">Detail Pelamar</h3>
                                    <p class="text-xs text-blue-200" id="modal-app-sub">Informasi lengkap pendaftaran</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeApplicantDetailModalSA()" class="text-gray-300 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <div class="px-6 py-5 max-h-[75vh] overflow-y-auto space-y-5 text-sm">
                            {{-- Profile Snapshot --}}
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div id="modal-app-photo-container" class="w-14 h-14 rounded-full overflow-hidden border border-gray-300 flex-shrink-0 bg-white flex items-center justify-center text-gray-500 font-bold">
                                    <i class="fas fa-user text-xl text-gray-400"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-gray-900 text-base truncate" id="modal-app-fullname">-</h4>
                                        <span id="modal-app-status-badge" class="px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">Status</span>
                                    </div>
                                    <p class="text-xs text-gray-500" id="modal-app-email">-</p>
                                    <p class="text-xs text-emerald-700 font-mono font-medium mt-0.5" id="modal-app-nik">-</p>
                                </div>
                            </div>

                            {{-- Grid Info --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-3.5 bg-white border border-gray-200 rounded-xl space-y-2">
                                    <h5 class="text-xs font-bold uppercase tracking-wider text-gray-400"><i class="fas fa-user mr-1 text-blue-600"></i> Data Pribadi</h5>
                                    <div><span class="text-xs text-gray-500">Telepon / WhatsApp:</span> <p class="font-semibold text-gray-900 text-xs" id="modal-app-phone">-</p></div>
                                    <div><span class="text-xs text-gray-500">Kewarganegaraan:</span> <p class="font-semibold text-gray-900 text-xs" id="modal-app-nationality">-</p></div>
                                    <div><span class="text-xs text-gray-500">Tanggal Lahir & Usia:</span> <p class="font-semibold text-gray-900 text-xs" id="modal-app-dob-age">-</p></div>
                                    <div><span class="text-xs text-gray-500">Alamat:</span> <p class="font-medium text-gray-900 text-xs" id="modal-app-address">-</p></div>
                                </div>

                                <div class="p-3.5 bg-white border border-gray-200 rounded-xl space-y-2">
                                    <h5 class="text-xs font-bold uppercase tracking-wider text-gray-400"><i class="fas fa-graduation-cap mr-1 text-indigo-600"></i> Pendidikan & Keahlian</h5>
                                    <div><span class="text-xs text-gray-500">Pendidikan Terakhir:</span> <p class="font-semibold text-gray-900 text-xs" id="modal-app-education">-</p></div>
                                    <div><span class="text-xs text-gray-500">Jurusan & Institusi:</span> <p class="font-semibold text-gray-900 text-xs" id="modal-app-study-inst">-</p></div>
                                    <div><span class="text-xs text-gray-500">Keahlian (Skills):</span> <p class="font-medium text-gray-900 text-xs" id="modal-app-skills">-</p></div>
                                    <div><span class="text-xs text-gray-500">Bahasa:</span> <p class="font-medium text-gray-900 text-xs" id="modal-app-languages">-</p></div>
                                </div>
                            </div>

                            {{-- Posisi Dilamar --}}
                            <div class="p-3.5 bg-blue-50/60 border border-blue-200 rounded-xl">
                                <h5 class="text-xs font-bold uppercase tracking-wider text-blue-800 mb-2"><i class="fas fa-briefcase mr-1"></i> Posisi Lamaran</h5>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                                    <div><span class="text-gray-500">Posisi:</span> <p class="font-bold text-gray-900" id="modal-app-opening">-</p></div>
                                    <div><span class="text-gray-500">Kategori:</span> <p class="font-bold text-gray-900" id="modal-app-cat">-</p></div>
                                    <div><span class="text-gray-500">Functional Area (FA):</span> <p class="font-bold text-gray-900" id="modal-app-fa">-</p></div>
                                </div>
                            </div>

                            {{-- Motivasi & Pengalaman --}}
                            <div class="space-y-3">
                                <div>
                                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Motivasi:</span>
                                    <p class="text-xs text-gray-800 bg-gray-50 p-2.5 rounded-lg border border-gray-200 mt-1 whitespace-pre-line" id="modal-app-motivation">-</p>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Pengalaman Terkait:</span>
                                    <p class="text-xs text-gray-800 bg-gray-50 p-2.5 rounded-lg border border-gray-200 mt-1 whitespace-pre-line" id="modal-app-experience">-</p>
                                </div>
                            </div>

                            {{-- Review Notes & CV File --}}
                            <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-xl space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-gray-700">Berkas CV:</span>
                                    <div id="modal-app-cv-link-container">
                                        <span class="text-gray-400 italic">Tidak ada CV</span>
                                    </div>
                                </div>
                                <div class="pt-2 border-t border-gray-200">
                                    <span class="font-bold text-gray-700">Catatan Reviewer:</span>
                                    <p class="text-gray-600 italic mt-0.5" id="modal-app-notes">-</p>
                                    <p class="text-[11px] text-gray-400 mt-1" id="modal-app-review-meta"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 flex justify-end">
                            <button type="button" onclick="closeApplicantDetailModalSA()"
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs font-bold rounded-xl transition">
                                Tutup
                            </button>
                        </div>
                    </div>
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

            {{-- Attendance Tab --}}
            <div class="tab-content hidden" id="attendance-tab">
                {{-- Attendance Summary Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white border border-gray-200/90 rounded-2xl p-4 sm:p-5 flex items-center justify-between shadow-2xs hover:shadow-xs transition">
                        <div>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Volunteer Approved</p>
                            <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">{{ $totalApprovedVolunteers ?? 0 }}</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">Berhak melakukan absensi</p>
                        </div>
                        <div class="w-11 h-11 rounded-2xl bg-gray-100 text-gray-600 flex items-center justify-center text-lg shrink-0">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>

                    <div class="bg-white border border-blue-100 rounded-2xl p-4 sm:p-5 flex items-center justify-between shadow-2xs hover:shadow-xs transition bg-gradient-to-br from-white to-blue-50/40">
                        <div>
                            <p class="text-[11px] font-bold text-blue-600 uppercase tracking-wider">Total Record Absensi</p>
                            <p class="text-2xl sm:text-3xl font-extrabold text-blue-800 mt-1">{{ $totalAttendanceRecords ?? 0 }}</p>
                            <p class="text-[11px] text-blue-600/70 mt-0.5">Akumulasi seluruh hari</p>
                        </div>
                        <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-lg shrink-0">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                    </div>

                    <div class="bg-white border border-emerald-100 rounded-2xl p-4 sm:p-5 flex items-center justify-between shadow-2xs hover:shadow-xs transition bg-gradient-to-br from-white to-emerald-50/40">
                        <div>
                            <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Selesai (In & Out)</p>
                            <p class="text-2xl sm:text-3xl font-extrabold text-emerald-800 mt-1">{{ $completedAttendanceCount ?? 0 }}</p>
                            <p class="text-[11px] text-emerald-600/70 mt-0.5">Check-out tercatat</p>
                        </div>
                        <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg shrink-0">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>

                    <div class="bg-white border border-amber-100 rounded-2xl p-4 sm:p-5 flex items-center justify-between shadow-2xs hover:shadow-xs transition bg-gradient-to-br from-white to-amber-50/40">
                        <div>
                            <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Check-in Saja</p>
                            <p class="text-2xl sm:text-3xl font-extrabold text-amber-800 mt-1">{{ $checkInOnlyAttendanceCount ?? 0 }}</p>
                            <p class="text-[11px] text-amber-600/70 mt-0.5">Belum / tidak check-out</p>
                        </div>
                        <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg shrink-0">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                {{-- Filter & Search Toolbar (Single Clean Row) --}}
                <div class="bg-white rounded-xl border border-gray-200 p-3 mb-5 shadow-2xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        {{-- Dropdown Filters (Left) --}}
                        <div class="flex items-center gap-3 shrink-0">
                            {{-- Date Filter --}}
                            <div class="flex items-center gap-2">
                                <label for="filter-attendance-date-sa" class="text-xs font-semibold text-gray-600 whitespace-nowrap">
                                    Tanggal:
                                </label>
                                <select id="filter-attendance-date-sa" onchange="filterAttendanceRowsSA()"
                                        class="border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer">
                                    <option value="all">Semua Tanggal</option>
                                    @php
                                        $uniqueAttendanceDates = ($attendances ?? collect())->map(function($att) use ($event) {
                                            return $att->attendance_date ? $att->attendance_date->format('Y-m-d') : ($att->check_in_at ? $att->check_in_at->timezone($event->timezone)->format('Y-m-d') : null);
                                        })->filter()->unique()->sortDesc();
                                    @endphp
                                    @foreach($uniqueAttendanceDates as $ad)
                                        <option value="{{ $ad }}">{{ \Carbon\Carbon::parse($ad)->translatedFormat('d M Y') }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status Filter --}}
                            <div class="flex items-center gap-2">
                                <label for="filter-attendance-status-sa" class="text-xs font-semibold text-gray-600 whitespace-nowrap">
                                    Status:
                                </label>
                                <select id="filter-attendance-status-sa" onchange="filterAttendanceRowsSA()"
                                        class="border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer">
                                    <option value="all">Semua Status</option>
                                    <option value="completed">Completed (In & Out)</option>
                                    <option value="checkin">Check-in Saja</option>
                                </select>
                            </div>
                        </div>

                        {{-- Search Input (Right) --}}
                        <div class="relative w-full sm:w-64" style="position: relative;">
                            <i class="fas fa-search pointer-events-none" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 11px; color: #9ca3af; z-index: 2;"></i>
                            <input type="text" id="filter-attendance-search-sa" oninput="filterAttendanceRowsSA()"
                                   placeholder="Cari nama atau email..."
                                   class="w-full pr-3 py-1.5 text-xs bg-white rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500 text-gray-800 placeholder:text-gray-400 transition"
                                   style="padding-left: 36px;">
                        </div>
                    </div>
                </div>

                {{-- Attendance Records Table --}}
                <div class="bg-white rounded-2xl border border-gray-200/90 shadow-2xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50/80 text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                                <tr>
                                    <th class="px-5 py-3.5 text-left whitespace-nowrap">
                                        <i class="fas fa-calendar-day text-blue-500 mr-1.5"></i> Tanggal
                                    </th>
                                    <th class="px-5 py-3.5 text-left">
                                        <i class="fas fa-user text-indigo-500 mr-1.5"></i> Volunteer
                                    </th>
                                    <th class="px-5 py-3.5 text-left whitespace-nowrap">
                                        <i class="fas fa-check-circle text-emerald-500 mr-1.5"></i> Status
                                    </th>
                                    <th class="px-5 py-3.5 text-left whitespace-nowrap">
                                        <i class="fas fa-sign-in-alt text-emerald-600 mr-1.5"></i> Check-in
                                    </th>
                                    <th class="px-5 py-3.5 text-left whitespace-nowrap">
                                        <i class="fas fa-sign-out-alt text-blue-600 mr-1.5"></i> Check-out
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white" id="attendance-tbody-sa">
                                @forelse($attendances ?? [] as $att)
                                    @php
                                        $attDate = $att->attendance_date ? $att->attendance_date->format('Y-m-d') : ($att->check_in_at ? $att->check_in_at->timezone($event->timezone)->format('Y-m-d') : '');
                                        $attDateFormatted = $att->attendance_date ? $att->attendance_date->translatedFormat('d M Y') : ($att->check_in_at ? $att->check_in_at->timezone($event->timezone)->translatedFormat('d M Y') : '-');
                                        $isComplete = ($att->check_out_at !== null);
                                        $statusKey = $isComplete ? 'completed' : 'checkin';
                                        $volName = $att->user ? $att->user->name : 'N/A';
                                        $volEmail = $att->user ? $att->user->email : '';
                                        $volInitial = strtoupper(substr($volName, 0, 1));
                                    @endphp
                                    <tr class="row-attendance-sa hover:bg-blue-50/30 transition"
                                        data-date="{{ $attDate }}"
                                        data-status="{{ $statusKey }}"
                                        data-name="{{ strtolower($volName . ' ' . $volEmail) }}">
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="font-bold text-gray-900 text-xs">{{ $attDateFormatted }}</span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                                    {{ $volInitial }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-900 text-xs leading-snug">{{ $volName }}</div>
                                                    @if($volEmail)
                                                        <div class="text-[11px] text-gray-400 leading-snug">{{ $volEmail }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            @if($isComplete)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                                    <i class="fas fa-check-double text-[10px]"></i> Selesai
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                    <i class="fas fa-clock text-[10px]"></i> Check-in Saja
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            @if($att->check_in_at)
                                                <div class="flex items-center gap-2.5">
                                                    <div>
                                                        <div class="font-mono font-bold text-gray-900 text-xs leading-tight">
                                                            {{ $att->check_in_at->timezone($event->timezone)->format('H:i:s') }}
                                                            <span class="text-[10px] text-gray-400 font-semibold">{{ $event->timezone_label }}</span>
                                                        </div>
                                                        <div class="text-[10px] text-gray-400">{{ $att->check_in_at->timezone($event->timezone)->format('d M Y') }}</div>
                                                    </div>
                                                    @if($att->check_in_photo_path)
                                                        <button type="button"
                                                                onclick="previewAttendancePhotoSA('{{ route('super-admin.events.attendance.photo', ['event' => $event->id, 'attendance' => $att->id, 'type' => 'check-in']) }}', 'Check-in: {{ addslashes($volName) }}', '{{ $attDateFormatted }} {{ $att->check_in_at->timezone($event->timezone)->format('H:i:s') }} {{ $event->timezone_label }}')"
                                                                class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200 transition flex items-center gap-1 shrink-0">
                                                            <i class="fas fa-camera"></i> Foto
                                                        </button>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-300 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            @if($att->check_out_at)
                                                <div class="flex items-center gap-2.5">
                                                    <div>
                                                        <div class="font-mono font-bold text-gray-900 text-xs leading-tight">
                                                            {{ $att->check_out_at->timezone($event->timezone)->format('H:i:s') }}
                                                            <span class="text-[10px] text-gray-400 font-semibold">{{ $event->timezone_label }}</span>
                                                        </div>
                                                        <div class="text-[10px] text-gray-400">{{ $att->check_out_at->timezone($event->timezone)->format('d M Y') }}</div>
                                                    </div>
                                                    @if($att->check_out_photo_path)
                                                        <button type="button"
                                                                onclick="previewAttendancePhotoSA('{{ route('super-admin.events.attendance.photo', ['event' => $event->id, 'attendance' => $att->id, 'type' => 'check-out']) }}', 'Check-out: {{ addslashes($volName) }}', '{{ $attDateFormatted }} {{ $att->check_out_at->timezone($event->timezone)->format('H:i:s') }} {{ $event->timezone_label }}')"
                                                                class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold border border-blue-200 transition flex items-center gap-1 shrink-0">
                                                            <i class="fas fa-camera"></i> Foto
                                                        </button>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-300 text-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="empty-attendance-row-sa">
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="max-w-xs mx-auto flex flex-col items-center">
                                                <div class="w-14 h-14 rounded-2xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 mb-3 shadow-2xs">
                                                    <i class="fas fa-calendar-times text-2xl text-gray-400"></i>
                                                </div>
                                                <h4 class="text-sm font-bold text-gray-800">Belum Ada Data Absensi</h4>
                                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                                    Belum ada volunteer yang melakukan absensi atau check-in untuk event ini.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                <tr id="no-filtered-attendance-sa" class="hidden">
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="max-w-xs mx-auto flex flex-col items-center">
                                            <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-500 mb-3 shadow-2xs">
                                                <i class="fas fa-search text-xl"></i>
                                            </div>
                                            <h4 class="text-sm font-bold text-gray-800">Tidak Ada Hasil Ditemukan</h4>
                                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                                Tidak ada catatan absensi yang sesuai dengan filter tanggal, status, atau pencarian Anda.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStatusFilterSA = 'all';

    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        function switchTab(tabName) {
            tabButtons.forEach(btn => {
                btn.classList.remove('border-b-2', 'border-blue-500', 'text-gray-800');
                btn.classList.add('text-gray-600', 'hover:text-gray-800');
            });

            tabContents.forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('active');
            });

            const targetBtn = document.querySelector(`.tab-button[data-tab="${tabName}"]`);
            const targetContent = document.getElementById(tabName + '-tab');
            if (targetBtn && targetContent) {
                targetBtn.classList.add('border-b-2', 'border-blue-500', 'text-gray-800');
                targetBtn.classList.remove('text-gray-600', 'hover:text-gray-800');
                targetContent.classList.remove('hidden');
                targetContent.classList.add('active');
            }
        }

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');
                switchTab(tabName);
                if (history.replaceState) {
                    history.replaceState(null, null, '#' + tabName);
                }
            });
        });

        // Check hash in URL (e.g. #applications)
        const hash = window.location.hash.replace('#', '');
        if (hash && document.getElementById(hash + '-tab')) {
            switchTab(hash);
        }

        // Live search filter for applicants table
        const filterInput = document.getElementById('filter-accepted-sa');
        if (filterInput) {
            filterInput.addEventListener('input', function() {
                applyFiltersSA();
            });
        }

        // Select all checkbox
        const checkAll = document.getElementById('check-all-sa');
        if (checkAll) {
            checkAll.addEventListener('change', function() {
                const isChecked = this.checked;
                const rows = document.querySelectorAll('.row-applicant-sa');
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cb = row.querySelector('.check-item-sa');
                        if (cb && !cb.disabled) {
                            cb.checked = isChecked;
                        }
                    }
                });
                updateSelectionStateSA();
            });
        }
    });

    function filterByStatusSA(status) {
        currentStatusFilterSA = status;

        // Update filter button styling
        const filterBtns = document.querySelectorAll('.status-filter-btn');
        filterBtns.forEach(btn => {
            btn.className = 'status-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition bg-white text-gray-700 hover:bg-gray-50 cursor-pointer';
            btn.style.border = '1px solid #e5e7eb';
        });

        const activeBtn = document.getElementById('btn-filter-' + status);
        if (activeBtn) {
            activeBtn.className = 'status-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition bg-blue-600 text-white shadow-xs cursor-pointer';
            activeBtn.style.border = 'none';
        }

        applyFiltersSA();
    }

    function applyFiltersSA() {
        const filterInput = document.getElementById('filter-accepted-sa');
        const term = filterInput ? filterInput.value.toLowerCase().trim() : '';
        const rows = document.querySelectorAll('.row-applicant-sa');
        let visible = 0;

        rows.forEach(row => {
            const rowSearch = row.getAttribute('data-search') || '';
            const rowStatus = row.getAttribute('data-status') || '';

            const matchSearch = !term || rowSearch.includes(term);
            const matchStatus = (currentStatusFilterSA === 'all') || (rowStatus === currentStatusFilterSA);

            if (matchSearch && matchStatus) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
                // Uncheck if hidden
                const cb = row.querySelector('.check-item-sa');
                if (cb) cb.checked = false;
            }
        });

        const countEl = document.getElementById('visible-count-sa');
        if (countEl) countEl.textContent = visible;

        updateSelectionStateSA();
    }

    function updateSelectionStateSA() {
        const checkedItems = document.querySelectorAll('.check-item-sa:checked');
        const count = checkedItems.length;
        const countEl = document.getElementById('count-selected-sa');
        const btnDownload = document.getElementById('btn-download-selected-sa');
        if (countEl) countEl.textContent = count;
        if (btnDownload) {
            btnDownload.disabled = (count === 0);
            if (count === 0) {
                btnDownload.className = 'inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-medium cursor-not-allowed whitespace-nowrap transition';
                btnDownload.style.border = '1px solid #e5e7eb';
            } else {
                btnDownload.className = 'inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold cursor-pointer shadow-xs transition whitespace-nowrap';
                btnDownload.style.border = 'none';
            }
        }
    }

    function submitBatchDownloadSA(all) {
        const form = document.getElementById('form-download-photos-sa');
        const container = document.getElementById('selected-ids-container-sa');
        const inputAll = document.getElementById('input-download-all-sa');
        container.innerHTML = '';

        if (all) {
            inputAll.value = '1';
            form.submit();
            return;
        }

        inputAll.value = '0';
        const checked = document.querySelectorAll('.check-item-sa:checked');
        if (checked.length === 0) {
            alert('Silakan pilih minimal 1 pelamar untuk mengunduh foto.');
            return;
        }

        checked.forEach(cb => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'application_ids[]';
            hidden.value = cb.value;
            container.appendChild(hidden);
        });

        form.submit();
    }

    function openApplicantModalFromBtn(btn) {
        try {
            const raw = btn.getAttribute('data-applicant');
            const data = JSON.parse(raw);
            showApplicantDetailModalSA(data);
        } catch(e) {
            console.error("Error parsing applicant data", e);
        }
    }

    function showApplicantDetailModalSA(data) {
        document.getElementById('modal-app-name').textContent = 'Detail: ' + data.name;
        document.getElementById('modal-app-sub').textContent = 'Pendaftaran #' + data.id + ' • ' + data.created_at;
        document.getElementById('modal-app-fullname').textContent = data.name;
        document.getElementById('modal-app-email').textContent = data.email;
        document.getElementById('modal-app-nik').textContent = data.nik !== '-' ? 'NIK: ' + data.nik : 'NIK Belum Diisi';
        document.getElementById('modal-app-phone').textContent = data.phone;
        document.getElementById('modal-app-nationality').textContent = data.kewarganegaraan;
        document.getElementById('modal-app-dob-age').textContent = data.dob + ' (' + data.age + ')';
        document.getElementById('modal-app-address').textContent = data.address + (data.city !== '-' ? ', ' + data.city : '') + (data.province !== '-' ? ', ' + data.province : '');

        document.getElementById('modal-app-education').textContent = data.education;
        document.getElementById('modal-app-study-inst').textContent = data.study + (data.university !== '-' ? ' - ' + data.university : '');
        document.getElementById('modal-app-skills').textContent = data.skills;
        document.getElementById('modal-app-languages').textContent = data.languages;

        document.getElementById('modal-app-opening').textContent = data.opening_title;
        document.getElementById('modal-app-cat').textContent = data.job_category;
        document.getElementById('modal-app-fa').textContent = data.fa_name;

        document.getElementById('modal-app-motivation').textContent = data.motivation;
        document.getElementById('modal-app-experience').textContent = data.experience;
        document.getElementById('modal-app-notes').textContent = data.review_notes !== '-' ? data.review_notes : 'Tidak ada catatan review.';

        const reviewMeta = document.getElementById('modal-app-review-meta');
        if (data.reviewer !== '-') {
            reviewMeta.textContent = 'Direview oleh: ' + data.reviewer + (data.reviewed_at !== '-' ? ' pada ' + data.reviewed_at : '');
        } else {
            reviewMeta.textContent = '';
        }

        // Status badge
        const badge = document.getElementById('modal-app-status-badge');
        badge.textContent = data.status;
        if (data.status_badge === 'accepted') {
            badge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200';
        } else if (data.status_badge === 'rejected') {
            badge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200';
        } else {
            badge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200';
        }

        // Photo
        const photoContainer = document.getElementById('modal-app-photo-container');
        if (data.has_photo && data.photo_url) {
            photoContainer.innerHTML = `<img src="${data.photo_url}" alt="${data.name}" class="w-full h-full object-cover">`;
        } else {
            photoContainer.innerHTML = `<i class="fas fa-user text-xl text-gray-400"></i>`;
        }

        // CV Link
        const cvContainer = document.getElementById('modal-app-cv-link-container');
        if (data.cv_url) {
            cvContainer.innerHTML = `<a href="${data.cv_url}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-2xs transition"><i class="fas fa-file-pdf"></i><span>Buka / Unduh Berkas CV</span></a>`;
        } else {
            cvContainer.innerHTML = `<span class="text-gray-400 italic">Belum melampirkan berkas CV</span>`;
        }

        const modal = document.getElementById('applicant-detail-modal-sa');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeApplicantDetailModalSA() {
        const modal = document.getElementById('applicant-detail-modal-sa');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    // Attendance Filter & Modal (Super Admin)
    function filterAttendanceRowsSA() {
        const dateFilter = document.getElementById('filter-attendance-date-sa')?.value || 'all';
        const statusFilter = document.getElementById('filter-attendance-status-sa')?.value || 'all';
        const searchFilter = (document.getElementById('filter-attendance-search-sa')?.value || '').toLowerCase().trim();

        const rows = document.querySelectorAll('.row-attendance-sa');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowDate = row.getAttribute('data-date');
            const rowStatus = row.getAttribute('data-status');
            const rowName = row.getAttribute('data-name');

            const matchDate = (dateFilter === 'all' || rowDate === dateFilter);
            const matchStatus = (statusFilter === 'all' || rowStatus === statusFilter);
            const matchSearch = (!searchFilter || rowName.includes(searchFilter));

            if (matchDate && matchStatus && matchSearch) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        const noDataRow = document.getElementById('no-filtered-attendance-sa');
        if (noDataRow) {
            if (visibleCount === 0 && rows.length > 0) {
                noDataRow.classList.remove('hidden');
            } else {
                noDataRow.classList.add('hidden');
            }
        }
    }

    function previewAttendancePhotoSA(url, title, subtitle) {
        const modal = document.getElementById('attendance-photo-modal-sa');
        const img = document.getElementById('photo-modal-img-sa');
        const titleEl = document.getElementById('photo-modal-title-sa');
        const subEl = document.getElementById('photo-modal-subtitle-sa');
        const linkEl = document.getElementById('photo-modal-link-sa');

        if (!modal || !img) return;

        titleEl.textContent = title || 'Bukti Foto Absensi';
        subEl.textContent = subtitle || '';
        img.src = url;
        linkEl.href = url;

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAttendancePhotoModalSA() {
        const modal = document.getElementById('attendance-photo-modal-sa');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }
</script>

{{-- Attendance Photo Modal for Super Admin --}}
<div id="attendance-photo-modal-sa" class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full overflow-hidden shadow-2xl">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 id="photo-modal-title-sa" class="text-base font-bold text-gray-900">Bukti Foto Absensi</h3>
                <p id="photo-modal-subtitle-sa" class="text-xs text-gray-500 mt-0.5"></p>
            </div>
            <button type="button" onclick="closeAttendancePhotoModalSA()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4 bg-gray-950 flex items-center justify-center min-h-[300px] max-h-[70vh] overflow-hidden">
            <img id="photo-modal-img-sa" src="" alt="Bukti Foto" class="max-h-[65vh] w-auto object-contain rounded-lg shadow-lg">
        </div>
        <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between bg-gray-50">
            <span class="text-xs text-gray-500 flex items-center gap-1.5">
                <i class="fas fa-shield-alt text-emerald-600"></i> Watermark Resmi Terverifikasi
            </span>
            <a id="photo-modal-link-sa" href="#" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                <i class="fas fa-external-link-alt"></i> Buka Ukuran Penuh
            </a>
        </div>
    </div>
</div>
@endsection