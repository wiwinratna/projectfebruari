@extends('layouts.app')

@section('title', 'Event Attendance - ' . $event->title)

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200">
                    <i class="fas fa-calendar-check mr-1"></i> Multi-Day Attendance
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                    Zona Waktu: {{ $event->timezone_label }} ({{ $event->timezone }})
                </span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Event Attendance</h1>
            <p class="text-gray-500 text-sm mt-0.5">Laporan kehadiran partisipan untuk <strong class="text-gray-800">{{ $event->title }}</strong></p>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm text-sm font-medium flex items-center gap-2">
            <i class="fas fa-exclamation-circle text-red-500 text-base"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Filter & Search Bar --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.attendance.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-3">
                {{-- Date Filter --}}
                <div class="flex items-center gap-2">
                    <label for="date-select" class="text-xs font-bold text-gray-600 uppercase tracking-wider flex items-center gap-1.5 whitespace-nowrap">
                        <i class="fas fa-calendar-alt text-blue-600"></i> Tanggal:
                    </label>
                    <select id="date-select" name="date" onchange="this.form.submit()"
                            class="border border-gray-300 rounded-xl px-3 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50/50 hover:bg-white transition cursor-pointer">
                        <option value="all" {{ $selectedDate === 'all' ? 'selected' : '' }}>Semua Hari (Overview Partisipan)</option>
                        @foreach($availableDates as $d)
                            @php
                                $dCarbon = \Carbon\Carbon::parse($d);
                                $isTodayOption = ($d === $todayDate);
                            @endphp
                            <option value="{{ $d }}" {{ $selectedDate === $d ? 'selected' : '' }}>
                                {{ $dCarbon->translatedFormat('d M Y') }} {{ $isTodayOption ? '(Hari Ini)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($selectedDate !== 'all')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                        <i class="fas fa-filter"></i> Menampilkan data: {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d M Y') }}
                    </span>
                @endif
            </div>

            {{-- Search Box --}}
            <div class="flex items-center gap-2">
                <div class="relative flex-1 md:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, username, email..."
                           class="w-full border border-gray-300 rounded-xl pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-sm font-semibold hover:bg-black transition shadow-xs">
                    Cari
                </button>
                @if(request('search') || request('date'))
                    <a href="{{ route('admin.attendance.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Approved --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Total Volunteer Approved</div>
                    <div class="text-3xl font-extrabold text-gray-900">{{ $totalApproved }}</div>
                    <div class="text-xs text-gray-400 mt-1">Partisipan yang berhak absen</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-600 flex items-center justify-center text-xl">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        {{-- Checked In --}}
        <div class="bg-white rounded-2xl border border-emerald-100 p-5 shadow-sm relative overflow-hidden bg-gradient-to-br from-white to-emerald-50/40">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">
                        Checked In {{ $selectedDate !== 'all' ? '(Hari Ini)' : '(Total Absen)' }}
                    </div>
                    <div class="text-3xl font-extrabold text-emerald-700">{{ $checkedInCount }}</div>
                    <div class="text-xs text-emerald-600/70 mt-1">Sudah melakukan check-in</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
            </div>
        </div>

        {{-- Checked Out --}}
        <div class="bg-white rounded-2xl border border-blue-100 p-5 shadow-sm relative overflow-hidden bg-gradient-to-br from-white to-blue-50/40">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">
                        Checked Out {{ $selectedDate !== 'all' ? '(Lengkap)' : '(Total Selesai)' }}
                    </div>
                    <div class="text-3xl font-extrabold text-blue-700">{{ $checkedOutCount }}</div>
                    <div class="text-xs text-blue-600/70 mt-1">Absensi check-in & out lengkap</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        {{-- Belum Absen --}}
        <div class="bg-white rounded-2xl border border-rose-100 p-5 shadow-sm relative overflow-hidden bg-gradient-to-br from-white to-rose-50/40">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-rose-600 uppercase tracking-wider mb-1">
                        {{ $selectedDate !== 'all' ? 'Belum Absen Tanggal Ini' : 'Belum Pernah Absen' }}
                    </div>
                    <div class="text-3xl font-extrabold text-rose-700">{{ $notCheckedInCount }}</div>
                    <div class="text-xs text-rose-600/70 mt-1">Volunteer yang belum absen</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Participants & Attendance Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/60">
            <div>
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-clipboard-list text-blue-600"></i>
                    Daftar Kehadiran Partisipan
                    <span class="text-xs font-normal text-gray-500">({{ $participants->total() }} volunteer terdaftar)</span>
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    Mode tampilan: <strong class="text-gray-700">{{ $selectedDate === 'all' ? 'Semua Hari (Overview Lengkap)' : 'Spesifik Tanggal ' . \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</strong>
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs font-bold uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Partisipan</th>
                        @if($selectedDate !== 'all')
                            <th class="px-4 py-4">Status Hari Ini</th>
                            <th class="px-4 py-4">Check-in</th>
                            <th class="px-4 py-4">Check-out</th>
                        @else
                            <th class="px-4 py-4">Kehadiran</th>
                            <th class="px-4 py-4">Riwayat Hari Hadir</th>
                            <th class="px-4 py-4 text-center">Rincian</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($participants as $p)
                        @php
                            $userAttendances = $p->eventAttendances;
                            $totalDays = $userAttendances->count();

                            if ($selectedDate !== 'all') {
                                $currentAttendance = $userAttendances->first(function($att) use ($selectedDate, $event) {
                                    $d = $att->attendance_date ? $att->attendance_date->format('Y-m-d') : ($att->check_in_at ? $att->check_in_at->timezone($event->timezone)->format('Y-m-d') : null);
                                    return $d === $selectedDate;
                                });
                            }
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition">
                            {{-- Partisipan Info --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-xs">
                                        {{ strtoupper(substr($p->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $p->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $p->email }}</div>
                                        @if($p->username)
                                            <div class="text-[11px] text-gray-400 font-mono">&#64;{{ $p->username }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            @if($selectedDate !== 'all')
                                {{-- Single Date Mode --}}
                                <td class="px-4 py-4">
                                    @if($currentAttendance && $currentAttendance->check_out_at)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                            <i class="fas fa-check-double text-[10px]"></i> Completed
                                        </span>
                                    @elseif($currentAttendance && $currentAttendance->check_in_at)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <i class="fas fa-sign-in-alt text-[10px]"></i> Checked In
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">
                                            <i class="fas fa-minus text-[10px]"></i> Belum Absen
                                        </span>
                                    @endif
                                </td>

                                {{-- Check-in Column --}}
                                <td class="px-4 py-4">
                                    @if($currentAttendance && $currentAttendance->check_in_at)
                                        <div class="flex items-center gap-2">
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ $currentAttendance->check_in_at->timezone($event->timezone)->format('H:i:s') }}
                                                    <span class="text-[10px] font-semibold text-gray-500">{{ $event->timezone_label }}</span>
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $currentAttendance->check_in_at->timezone($event->timezone)->format('d M Y') }}
                                                </div>
                                            </div>
                                            @if($currentAttendance->check_in_photo_path)
                                                <button type="button" 
                                                        onclick="previewAttendancePhoto('{{ route('admin.attendance.photo', ['attendance' => $currentAttendance->id, 'type' => 'check-in']) }}', 'Foto Check-in: {{ addslashes($p->name) }}', '{{ $currentAttendance->check_in_at->timezone($event->timezone)->format('d M Y, H:i:s') }} {{ $event->timezone_label }}')"
                                                        class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200 transition shrink-0 flex items-center gap-1">
                                                    <i class="fas fa-camera"></i> Foto
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs italic">-</span>
                                    @endif
                                </td>

                                {{-- Check-out Column --}}
                                <td class="px-4 py-4">
                                    @if($currentAttendance && $currentAttendance->check_out_at)
                                        <div class="flex items-center gap-2">
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ $currentAttendance->check_out_at->timezone($event->timezone)->format('H:i:s') }}
                                                    <span class="text-[10px] font-semibold text-gray-500">{{ $event->timezone_label }}</span>
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $currentAttendance->check_out_at->timezone($event->timezone)->format('d M Y') }}
                                                </div>
                                            </div>
                                            @if($currentAttendance->check_out_photo_path)
                                                <button type="button"
                                                        onclick="previewAttendancePhoto('{{ route('admin.attendance.photo', ['attendance' => $currentAttendance->id, 'type' => 'check-out']) }}', 'Foto Check-out: {{ addslashes($p->name) }}', '{{ $currentAttendance->check_out_at->timezone($event->timezone)->format('d M Y, H:i:s') }} {{ $event->timezone_label }}')"
                                                        class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold border border-blue-200 transition shrink-0 flex items-center gap-1">
                                                    <i class="fas fa-camera"></i> Foto
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs italic">-</span>
                                    @endif
                                </td>
                            @else
                                {{-- All Dates Overview Mode --}}
                                <td class="px-4 py-4">
                                    @if($totalDays > 0)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                            <i class="fas fa-calendar-check text-[10px]"></i> {{ $totalDays }} Hari Hadir
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">
                                            <i class="fas fa-times-circle text-[10px]"></i> Belum Ada Absen
                                        </span>
                                    @endif
                                </td>

                                {{-- Badges of Attended Days --}}
                                <td class="px-4 py-4">
                                    @if($totalDays > 0)
                                        <div class="flex flex-wrap gap-1.5 max-w-md">
                                            @foreach($userAttendances as $att)
                                                @php
                                                    $attDateStr = $att->attendance_date ? $att->attendance_date->format('d M') : ($att->check_in_at ? $att->check_in_at->timezone($event->timezone)->format('d M') : '-');
                                                    $isCompleted = ($att->check_out_at !== null);
                                                @endphp
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold {{ $isCompleted ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                                    <i class="fas {{ $isCompleted ? 'fa-check-double text-blue-500' : 'fa-sign-in-alt text-emerald-500' }} text-[9px]"></i>
                                                    {{ $attDateStr }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Tidak ada catatan</span>
                                    @endif
                                </td>

                                {{-- Toggle Detail Row Button --}}
                                <td class="px-4 py-4 text-center">
                                    @if($totalDays > 0)
                                        <button type="button" onclick="toggleDetailsRow('detail-row-{{ $p->id }}', this)"
                                                class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition flex items-center justify-center gap-1 mx-auto">
                                            <span>Rincian</span>
                                            <i class="fas fa-chevron-down text-[10px] transition-transform duration-200"></i>
                                        </button>
                                    @else
                                        <span class="text-gray-300 text-xs">-</span>
                                    @endif
                                </td>
                            @endif
                        </tr>

                        {{-- Collapsible Multi-day Details Sub-table (All Mode) --}}
                        @if($selectedDate === 'all' && $totalDays > 0)
                            <tr id="detail-row-{{ $p->id }}" class="hidden bg-gray-50/70 border-b border-gray-100">
                                <td colspan="4" class="px-6 py-4">
                                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-2xs">
                                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                                            <i class="fas fa-history text-indigo-600"></i> Riwayat Absensi Lengkap: {{ $p->name }}
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                            @foreach($userAttendances as $att)
                                                @php
                                                    $dFormatted = $att->attendance_date ? $att->attendance_date->translatedFormat('l, d M Y') : ($att->check_in_at ? $att->check_in_at->timezone($event->timezone)->translatedFormat('l, d M Y') : '-');
                                                    $isComplete = ($att->check_out_at !== null);
                                                @endphp
                                                <div class="rounded-xl border {{ $isComplete ? 'border-blue-200 bg-blue-50/20' : 'border-emerald-200 bg-emerald-50/20' }} p-3">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="text-xs font-bold text-gray-900">{{ $dFormatted }}</span>
                                                        @if($isComplete)
                                                            <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full">Lengkap</span>
                                                        @else
                                                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Check-in saja</span>
                                                        @endif
                                                    </div>
                                                    <div class="space-y-1.5 text-xs">
                                                        {{-- Check-in --}}
                                                        <div class="flex items-center justify-between text-gray-700">
                                                            <span class="text-[11px] font-medium text-emerald-600">In:</span>
                                                            <div class="flex items-center gap-1.5">
                                                                <span class="font-bold font-mono">{{ $att->check_in_at ? $att->check_in_at->timezone($event->timezone)->format('H:i:s') : '-' }}</span>
                                                                @if($att->check_in_photo_path)
                                                                    <button type="button"
                                                                            onclick="previewAttendancePhoto('{{ route('admin.attendance.photo', ['attendance' => $att->id, 'type' => 'check-in']) }}', 'Check-in: {{ addslashes($p->name) }}', '{{ $dFormatted }} - {{ $att->check_in_at->timezone($event->timezone)->format('H:i:s') }}')"
                                                                            class="text-emerald-600 hover:text-emerald-800 text-[11px]">
                                                                        <i class="fas fa-image"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        {{-- Check-out --}}
                                                        <div class="flex items-center justify-between text-gray-700">
                                                            <span class="text-[11px] font-medium text-blue-600">Out:</span>
                                                            <div class="flex items-center gap-1.5">
                                                                <span class="font-bold font-mono">{{ $att->check_out_at ? $att->check_out_at->timezone($event->timezone)->format('H:i:s') : '-' }}</span>
                                                                @if($att->check_out_photo_path)
                                                                    <button type="button"
                                                                            onclick="previewAttendancePhoto('{{ route('admin.attendance.photo', ['attendance' => $att->id, 'type' => 'check-out']) }}', 'Check-out: {{ addslashes($p->name) }}', '{{ $dFormatted }} - {{ $att->check_out_at->timezone($event->timezone)->format('H:i:s') }}')"
                                                                            class="text-blue-600 hover:text-blue-800 text-[11px]">
                                                                        <i class="fas fa-image"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="{{ $selectedDate !== 'all' ? 4 : 4 }}" class="px-6 py-12 text-center text-sm text-gray-500">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center text-2xl mx-auto mb-3">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <p class="font-bold text-gray-700">Tidak ada data partisipan yang ditemukan</p>
                                <p class="text-xs text-gray-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter tanggal yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($participants->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80">
                {{ $participants->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Photo Preview Modal --}}
<div id="attendance-photo-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full overflow-hidden shadow-2xl animate-scale-in">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 id="photo-modal-title" class="text-base font-bold text-gray-900">Bukti Foto Absensi</h3>
                <p id="photo-modal-subtitle" class="text-xs text-gray-500 mt-0.5"></p>
            </div>
            <button type="button" onclick="closeAttendancePhotoModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4 bg-gray-950 flex items-center justify-center min-h-[300px] max-h-[70vh] overflow-hidden">
            <img id="photo-modal-img" src="" alt="Bukti Foto" class="max-h-[65vh] w-auto object-contain rounded-lg shadow-lg">
        </div>
        <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between bg-gray-50">
            <span class="text-xs text-gray-500 flex items-center gap-1.5">
                <i class="fas fa-shield-alt text-emerald-600"></i> Watermark Resmi Terverifikasi
            </span>
            <a id="photo-modal-link" href="#" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                <i class="fas fa-external-link-alt"></i> Buka Ukuran Penuh
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleDetailsRow(rowId, btn) {
        const row = document.getElementById(rowId);
        if (!row) return;
        const icon = btn.querySelector('i');
        if (row.classList.contains('hidden')) {
            row.classList.remove('hidden');
            if (icon) icon.classList.add('rotate-180');
        } else {
            row.classList.add('hidden');
            if (icon) icon.classList.remove('rotate-180');
        }
    }

    function previewAttendancePhoto(url, title, subtitle) {
        const modal = document.getElementById('attendance-photo-modal');
        const img = document.getElementById('photo-modal-img');
        const titleEl = document.getElementById('photo-modal-title');
        const subEl = document.getElementById('photo-modal-subtitle');
        const linkEl = document.getElementById('photo-modal-link');

        if (!modal || !img) return;

        titleEl.textContent = title || 'Bukti Foto Absensi';
        subEl.textContent = subtitle || '';
        img.src = url;
        linkEl.href = url;

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAttendancePhotoModal() {
        const modal = document.getElementById('attendance-photo-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    // Close on click outside modal content
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('attendance-photo-modal');
        if (modal && !modal.classList.contains('hidden') && e.target === modal) {
            closeAttendancePhotoModal();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAttendancePhotoModal();
        }
    });
</script>
@endpush
