<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventAttendanceController extends Controller
{
    /**
     * Tampilkan overview dan tabel absensi event yang dikelola Admin.
     */
    public function index(Request $request)
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin event session tidak ditemukan');
        }

        $event = Event::findOrFail($eventId);

        // Cari user yang eligible (punya aplikasi approved)
        $approvedUserIds = Application::where('status', 'approved')
            ->whereHas('workerOpening', function($q) use ($eventId) {
                $q->where('event_id', $eventId);
            })
            ->pluck('user_id')
            ->unique();

        // Cari user yang sudah terlanjur absen (meski mungkin approvalnya dicabut kemudian)
        $attendedUserIds = EventAttendance::where('event_id', $eventId)->pluck('user_id');

        $allRelevantUserIds = $approvedUserIds->merge($attendedUserIds)->unique();

        // Statistik
        $totalApproved = $approvedUserIds->count();
        $allAttendances = EventAttendance::where('event_id', $eventId)->get();
        $checkedInCount = $allAttendances->whereNotNull('check_in_at')->count();
        $checkedOutCount = $allAttendances->whereNotNull('check_out_at')->count();
        $notCheckedInCount = max(0, $totalApproved - $checkedInCount);

        // Ambil data partisipan + eager load absensinya
        $query = User::whereIn('id', $allRelevantUserIds)
            ->with(['eventAttendances' => function($q) use ($eventId) {
                $q->where('event_id', $eventId);
            }]);

        // Filter berdasarkan pencarian nama jika ada
        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $participants = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.events.attendance.index', compact(
            'event',
            'participants',
            'totalApproved',
            'checkedInCount',
            'checkedOutCount',
            'notCheckedInCount'
        ));
    }

    /**
     * Melayani file foto absensi secara privat.
     */
    public function photo(EventAttendance $attendance, $type)
    {
        $eventId = session('admin_event_id');
        
        if (!$eventId || $attendance->event_id !== $eventId) {
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
