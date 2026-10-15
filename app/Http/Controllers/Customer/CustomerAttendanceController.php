<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class CustomerAttendanceController extends Controller
{
    /**
     * Tampilkan daftar event yang eligible untuk check-in/out.
     */
    public function index()
    {
        $userId = session('customer_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jalankan auto-checkout di 23:59 bagi volunteer yang lupa check-out di hari check-in
        EventAttendance::autoCheckOutExpired();

        $now = Carbon::now();

        // Cari event yang:
        // 1. Status active
        // 2. start_at <= now, dan jika end_at diatur maka end_at >= now
        // 3. User punya application approved di event ini
        $events = Event::where('status', 'active')
            ->where('start_at', '<=', $now)
            ->where(function($q) use ($now) {
                $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
            })
            ->whereHas('workerOpenings.applications', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('status', 'approved');
            })
            ->orderBy('start_at')
            ->get();

        return view('menu.customer.attendance.index', compact('events'));
    }

    /**
     * Tampilkan antarmuka kamera / status attendance untuk satu event.
     */
    public function show(Event $event)
    {
        $this->verifyEligibility($event);

        // Jalankan auto-checkout di 23:59 jika ada yang expired
        EventAttendance::autoCheckOutExpired();

        $userId = session('customer_id');
        $todayDate = now($event->timezone)->toDateString();

        // Cari absensi khusus hari ini
        $attendance = EventAttendance::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->where(function($q) use ($todayDate) {
                $q->whereDate('attendance_date', $todayDate)
                  ->orWhere(function($sub) use ($todayDate) {
                      $sub->whereNull('attendance_date')->whereDate('check_in_at', $todayDate);
                  });
            })
            ->latest()
            ->first();

        // Ambil seluruh riwayat absensi volunteer untuk event ini (semua hari yang pernah dihadiri)
        $attendanceHistory = EventAttendance::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->orderByDesc('attendance_date')
            ->orderByDesc('check_in_at')
            ->get();

        return view('menu.customer.attendance.show', compact('event', 'attendance', 'attendanceHistory', 'todayDate'));
    }

    /**
     * Handle proses Check-in / Retake Foto Check-in.
     */
    public function checkIn(Request $request, Event $event)
    {
        $this->verifyEligibility($event);
        $userId = session('customer_id');
        $isRetake = $request->boolean('is_retake');
        $todayDate = now($event->timezone)->toDateString();

        // Cari absensi hari ini
        $existing = EventAttendance::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->where(function($q) use ($todayDate) {
                $q->whereDate('attendance_date', $todayDate)
                  ->orWhere(function($sub) use ($todayDate) {
                      $sub->whereNull('attendance_date')->whereDate('check_in_at', $todayDate);
                  });
            })
            ->first();

        if ($existing && !$isRetake) {
            return back()->with('error', 'Anda sudah melakukan check-in untuk hari ini.');
        }

        $path = null;

        // Handle direct camera capture (base64 with official watermark badge)
        if ($request->filled('photo_base64')) {
            $base64Data = $request->input('photo_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]);
                $imageContent = base64_decode($base64Data);

                $filename = $userId . '_checkin_' . Str::uuid() . '.' . ($type === 'png' ? 'png' : 'jpg');
                $path = 'event-attendance/' . $event->id . '/' . $filename;

                Storage::disk('local')->put($path, $imageContent);
            }
        } elseif ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ], [
                'photo.required' => 'Foto bukti kehadiran wajib diambil atau diunggah.',
                'photo.image' => 'File harus berupa gambar.',
                'photo.max' => 'Ukuran foto maksimal adalah 5MB.',
            ]);

            $file = $request->file('photo');
            $filename = $userId . '_checkin_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('event-attendance/' . $event->id, $filename, 'local');
        }

        if (!$path) {
            return back()->with('error', 'Gagal memproses foto check-in. Pastikan kamera menyala atau unggah foto.');
        }

        if ($existing && $isRetake) {
            // Delete old photo if exists
            if ($existing->check_in_photo_path && Storage::disk('local')->exists($existing->check_in_photo_path)) {
                Storage::disk('local')->delete($existing->check_in_photo_path);
            }
            $existing->update([
                'attendance_date' => $todayDate,
                'check_in_at' => Carbon::now($event->timezone),
                'check_in_photo_path' => $path,
            ]);

            return redirect()->route('customer.attendance.show', $event->id)
                ->with('success', 'Foto Check-in hari ini berhasil diperbarui!');
        }

        try {
            EventAttendance::create([
                'event_id' => $event->id,
                'user_id' => $userId,
                'attendance_date' => $todayDate,
                'check_in_at' => Carbon::now($event->timezone),
                'check_in_photo_path' => $path,
            ]);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062 || $e->getCode() == '23000') {
                Storage::disk('local')->delete($path);
                return back()->with('error', 'Anda sudah melakukan check-in hari ini.');
            }
            throw $e;
        }

        return redirect()->route('customer.attendance.show', $event->id)
            ->with('success', 'Check-in hari ini berhasil dicatat.');
    }

    /**
     * Handle proses Check-out / Retake Foto Check-out.
     */
    public function checkOut(Request $request, Event $event)
    {
        $this->verifyEligibility($event);
        $userId = session('customer_id');
        $isRetake = $request->boolean('is_retake');
        $todayDate = now($event->timezone)->toDateString();
        
        $attendance = EventAttendance::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->where(function($q) use ($todayDate) {
                $q->whereDate('attendance_date', $todayDate)
                  ->orWhere(function($sub) use ($todayDate) {
                      $sub->whereNull('attendance_date')->whereDate('check_in_at', $todayDate);
                  });
            })
            ->first();
        
        if (!$attendance) {
            return back()->with('error', 'Anda belum melakukan check-in untuk hari ini.');
        }
        if ($attendance->check_out_at && !$isRetake) {
            return back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        $path = null;

        // Handle direct camera capture (base64 with official watermark badge)
        if ($request->filled('photo_base64')) {
            $base64Data = $request->input('photo_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]);
                $imageContent = base64_decode($base64Data);

                $filename = $userId . '_checkout_' . Str::uuid() . '.' . ($type === 'png' ? 'png' : 'jpg');
                $path = 'event-attendance/' . $event->id . '/' . $filename;

                Storage::disk('local')->put($path, $imageContent);
            }
        } elseif ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ], [
                'photo.required' => 'Foto bukti kepulangan wajib diambil atau diunggah.',
                'photo.image' => 'File harus berupa gambar.',
                'photo.max' => 'Ukuran foto maksimal adalah 5MB.',
            ]);

            $file = $request->file('photo');
            $filename = $userId . '_checkout_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('event-attendance/' . $event->id, $filename, 'local');
        }

        if (!$path) {
            return back()->with('error', 'Gagal memproses foto check-out. Pastikan kamera menyala atau unggah foto.');
        }

        if ($attendance->check_out_photo_path && Storage::disk('local')->exists($attendance->check_out_photo_path)) {
            Storage::disk('local')->delete($attendance->check_out_photo_path);
        }

        $attendance->update([
            'check_out_at' => Carbon::now($event->timezone),
            'check_out_photo_path' => $path,
        ]);

        $msg = $isRetake ? 'Foto Check-out hari ini berhasil diperbarui!' : 'Check-out hari ini berhasil dicatat.';
        return redirect()->route('customer.attendance.show', $event->id)->with('success', $msg);
    }

    /**
     * Reset absensi event agar bisa mengambil foto check-in dari awal lagi (hanya mereset hari ini).
     */
    public function reset(Event $event)
    {
        $this->verifyEligibility($event);
        $userId = session('customer_id');
        $todayDate = now($event->timezone)->toDateString();

        $attendance = EventAttendance::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->where(function($q) use ($todayDate) {
                $q->whereDate('attendance_date', $todayDate)
                  ->orWhere(function($sub) use ($todayDate) {
                      $sub->whereNull('attendance_date')->whereDate('check_in_at', $todayDate);
                  });
            })
            ->first();

        if ($attendance) {
            if ($attendance->check_in_photo_path && Storage::disk('local')->exists($attendance->check_in_photo_path)) {
                Storage::disk('local')->delete($attendance->check_in_photo_path);
            }
            if ($attendance->check_out_photo_path && Storage::disk('local')->exists($attendance->check_out_photo_path)) {
                Storage::disk('local')->delete($attendance->check_out_photo_path);
            }
            $attendance->delete();
        }

        return redirect()->route('customer.attendance.show', $event->id)
            ->with('success', 'Absensi hari ini berhasil di-reset. Anda dapat mengambil foto check-in baru sekarang!');
    }

    /**
     * Melihat file foto absensi milik customer yang sedang login.
     */
    public function photo(Request $request, Event $event, $type)
    {
        $this->verifyEligibility($event);
        $userId = session('customer_id');

        $query = EventAttendance::where('event_id', $event->id)
            ->where('user_id', $userId);

        if ($request->has('attendance_id')) {
            $query->where('id', $request->query('attendance_id'));
        }

        $attendance = $query->latest()->firstOrFail();

        $path = ($type === 'check-out') ? $attendance->check_out_photo_path : $attendance->check_in_photo_path;

        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404, 'Foto absensi tidak ditemukan.');
        }

        return Storage::disk('local')->response($path);
    }

    /**
     * Sentralisasi logika pengecekan otorisasi dan validitas waktu event.
     */
    private function verifyEligibility(Event $event)
    {
        $userId = session('customer_id');
        if (!$userId) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        $now = Carbon::now();

        // 1. Status event harus active
        if ($event->status !== 'active') {
            abort(403, 'Event tidak dalam status aktif.');
        }

        // 2. Waktu harus di dalam rentang start_at dan end_at (jika end_at diatur)
        if ($now < $event->start_at) {
            abort(403, 'Waktu pelaksanaan event belum dimulai.');
        }
        if ($event->end_at && $now > $event->end_at) {
            abort(403, 'Waktu pelaksanaan event ini sudah berakhir.');
        }

        // 4. User harus punya aplikasi berstatus approved di event ini
        $isApproved = \App\Models\Application::where('user_id', $userId)
            ->where('status', 'approved')
            ->whereHas('workerOpening', function ($q) use ($event) {
                $q->where('event_id', $event->id);
            })->exists();

        if (!$isApproved) {
            abort(403, 'Anda tidak memiliki otorisasi absensi untuk event ini.');
        }
    }
}
