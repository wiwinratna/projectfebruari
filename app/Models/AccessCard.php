<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class AccessCard extends Model
{
    protected $fillable = [
        'application_id',
        'user_id',
        'event_id',
        'worker_opening_id',
        'registration_code',
        'issued_at',
        'qr_token',
        'status',
    ];

    protected static function booted()
    {
        static::saving(function ($card) {
            if (empty($card->qr_token)) {
                $card->qr_token = (string) Str::uuid();
            }
        });
    }


    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function workerOpening()
    {
        return $this->belongsTo(WorkerOpening::class, 'worker_opening_id');
    }

public function accessCodes()
{
    return $this->belongsToMany(
        \App\Models\EventAccessCode::class,
        'access_card_access_codes',
        'access_card_id',
        'event_access_code_id'
    )->withTimestamps();
}
}
