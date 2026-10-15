<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'attendance_date',
        'check_in_at',
        'check_in_photo_path',
        'check_out_at',
        'check_out_photo_path',
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'check_in_at' => 'datetime',
            'check_out_at' => 'datetime',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Otomatis checkout di 23:59:59 pada hari check-in jika volunteer lupa check-out.
     */
    public static function autoCheckOutExpired(): int
    {
        $now = \Carbon\Carbon::now();
        $attendances = self::whereNotNull('check_in_at')
            ->whereNull('check_out_at')
            ->get();

        $count = 0;
        foreach ($attendances as $attendance) {
            $endOfDay = $attendance->check_in_at->copy()->endOfDay(); // 23:59:59 pada hari check-in
            if ($now->greaterThan($endOfDay)) {
                $attendance->update([
                    'check_out_at' => $endOfDay,
                ]);
                $count++;
            }
        }

        return $count;
    }
}
