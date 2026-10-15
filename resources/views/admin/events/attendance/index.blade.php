@extends('layouts.app')

@section('title', 'Event Attendance - ' . $event->title)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Event Attendance</h1>
                <p class="text-gray-500 text-sm mt-1">Laporan kehadiran partisipan untuk event {{ $event->title }}</p>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Approved</div>
                <div class="text-3xl font-bold text-gray-900">{{ $totalApproved }}</div>
            </div>
            <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-5 shadow-sm">
                <div class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-1">Checked In</div>
                <div class="text-3xl font-bold text-emerald-700">{{ $checkedInCount }}</div>
            </div>
            <div class="bg-blue-50 rounded-xl border border-blue-200 p-5 shadow-sm">
                <div class="text-sm font-semibold text-blue-600 uppercase tracking-wider mb-1">Checked Out</div>
                <div class="text-3xl font-bold text-blue-700">{{ $checkedOutCount }}</div>
            </div>
            <div class="bg-red-50 rounded-xl border border-red-200 p-5 shadow-sm">
                <div class="text-sm font-semibold text-red-600 uppercase tracking-wider mb-1">Not Checked In</div>
                <div class="text-3xl font-bold text-red-700">{{ $notCheckedInCount }}</div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h2 class="text-base font-bold text-gray-900">Daftar Partisipan</h2>
                <form method="GET" action="{{ route('admin.attendance.index') }}" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..." class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">
                    <button type="submit" class="px-4 py-1.5 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-black transition">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('admin.attendance.index') }}" class="px-4 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">Reset</a>
                    @endif
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500">
                        <tr>
                            <th class="text-left px-6 py-3.5">Partisipan</th>
                            <th class="text-left px-4 py-3.5">Status</th>
                            <th class="text-left px-4 py-3.5">Check-in</th>
                            <th class="text-left px-4 py-3.5">Check-out</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($participants as $p)
                            @php
                                $attendance = $p->eventAttendances->first();
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $p->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $p->username ?? $p->email }}</div>
                                </td>
                                <td class="px-4 py-4 text-xs font-medium">
                                    @if($attendance && $attendance->check_out_at)
                                        <span class="text-blue-600 bg-blue-50 px-2 py-1 rounded-md border border-blue-100">Completed</span>
                                    @elseif($attendance && $attendance->check_in_at)
                                        <span class="text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100">Checked In</span>
                                    @else
                                        <span class="text-gray-500 bg-gray-100 px-2 py-1 rounded-md border border-gray-200">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($attendance && $attendance->check_in_at)
                                        <div class="text-sm text-gray-900 font-semibold">{{ $attendance->check_in_at->timezone($event->timezone)->format('H:i') }} <span class="text-[10px] text-gray-500 font-normal">{{ $event->timezone_label }}</span></div>
                                        <div class="text-xs text-gray-500 mb-1">{{ $attendance->check_in_at->timezone($event->timezone)->format('d M Y') }}</div>
                                        @if($attendance->check_in_photo_path)
                                            <a href="{{ route('admin.attendance.photo', ['attendance' => $attendance->id, 'type' => 'check-in']) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-image mr-1"></i> Lihat Foto
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($attendance && $attendance->check_out_at)
                                        <div class="text-sm text-gray-900 font-semibold">{{ $attendance->check_out_at->timezone($event->timezone)->format('H:i') }} <span class="text-[10px] text-gray-500 font-normal">{{ $event->timezone_label }}</span></div>
                                        <div class="text-xs text-gray-500 mb-1">{{ $attendance->check_out_at->timezone($event->timezone)->format('d M Y') }}</div>
                                        @if($attendance->check_out_photo_path)
                                            <a href="{{ route('admin.attendance.photo', ['attendance' => $attendance->id, 'type' => 'check-out']) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-image mr-1"></i> Lihat Foto
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                Belum ada data partisipan yang eligible untuk event ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($participants->hasPages())
                <div class="px-6 py-3 border-t border-gray-100 bg-gray-50">
                    {{ $participants->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
