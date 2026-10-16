<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventAttendanceController extends Controller
{
    /**
     * Tampilkan overview dan tabel absensi event yang dikelola Admin (Mendukung Multi-day).
     */
    public function index(Request $request)
    {
        $eventId = (int) session('admin_event_id');
        
        if (!$eventId) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin event session tidak ditemukan');
        }

        $event = Event::findOrFail($eventId);

        // Cari user yang eligible (punya aplikasi approved di event ini)
        $approvedUserIds = Application::where('status', 'approved')
            ->whereHas('workerOpening', function($q) use ($eventId) {
                $q->where('event_id', $eventId);
            })
            ->pluck('user_id')
            ->unique();

        // Cari user yang sudah pernah absen
        $attendedUserIds = EventAttendance::where('event_id', $eventId)->pluck('user_id');

        $allRelevantUserIds = $approvedUserIds->merge($attendedUserIds)->unique();

        // Ambil semua absensi untuk event ini
        $allEventAttendances = EventAttendance::where('event_id', $eventId)->get();

        // Ambil daftar tanggal absensi unik (format Y-m-d)
        $existingDates = $allEventAttendances->map(function($att) use ($event) {
            if ($att->attendance_date) {
                return $att->attendance_date->format('Y-m-d');
            }
            if ($att->check_in_at) {
                return $att->check_in_at->timezone($event->timezone)->format('Y-m-d');
            }
            return null;
        })->filter()->unique()->sortDesc()->values();

        $todayDate = now($event->timezone)->format('Y-m-d');

        // Available dates dropdown
        $availableDates = $existingDates;
        if (!$availableDates->contains($todayDate)) {
            $availableDates->prepend($todayDate);
        }

        // Filter tanggal dari input request (default: 'all' atau bisa pilih tanggal tertentu)
        $selectedDate = $request->get('date', 'all');

        // Statistik
        $totalApproved = $approvedUserIds->count();

        if ($selectedDate !== 'all') {
            // Stats spesifik untuk tanggal yang dipilih
            $dateAttendances = $allEventAttendances->filter(function($att) use ($selectedDate, $event) {
                $d = $att->attendance_date ? $att->attendance_date->format('Y-m-d') : ($att->check_in_at ? $att->check_in_at->timezone($event->timezone)->format('Y-m-d') : null);
                return $d === $selectedDate;
            });

            $checkedInCount = $dateAttendances->whereNotNull('check_in_at')->count();
            $checkedOutCount = $dateAttendances->whereNotNull('check_out_at')->count();
            $notCheckedInCount = max(0, $totalApproved - $checkedInCount);
        } else {
            // Stats overall
            $checkedInCount = $allEventAttendances->whereNotNull('check_in_at')->count();
            $checkedOutCount = $allEventAttendances->whereNotNull('check_out_at')->count();
            $uniqueAttendedCount = $allEventAttendances->pluck('user_id')->unique()->count();
            $notCheckedInCount = max(0, $totalApproved - $uniqueAttendedCount);
        }

        // Ambil data partisipan + eager load absensi & profile
        $query = User::whereIn('id', $allRelevantUserIds)
            ->with([
                'profile',
                'eventAttendances' => function($q) use ($eventId) {
                    $q->where('event_id', $eventId)
                      ->orderBy('attendance_date', 'desc')
                      ->orderBy('check_in_at', 'desc');
                }
            ]);

        // Filter pencarian nama / username / email
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $participants = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.events.attendance.index', compact(
            'event',
            'participants',
            'totalApproved',
            'checkedInCount',
            'checkedOutCount',
            'notCheckedInCount',
            'availableDates',
            'selectedDate',
            'todayDate'
        ));
    }

    /**
     * Melayani file foto absensi secara privat.
     */
    public function photo(EventAttendance $attendance, $type)
    {
        $eventId = (int) session('admin_event_id');
        
        if (!$eventId || (int)$attendance->event_id !== $eventId) {
            abort(403, 'Anda tidak memiliki akses untuk melihat foto absensi event ini.');
        }

        $path = null;
        if ($type === 'check-in') {
            $path = $attendance->check_in_photo_path;
        } elseif ($type === 'check-out') {
            $path = $attendance->check_out_photo_path;
        }

        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404, 'Foto tidak ditemukan.');
        }

        return Storage::disk('local')->response($path);
    }
}
