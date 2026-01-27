<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_opening_id',
        'user_id',
        'motivation',
        'experience',
        'cv_path',
        'status',
        'reviewed_by',
        'review_notes',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function opening()
    {
        return $this->belongsTo(
            \App\Models\WorkerOpening::class,
            'worker_opening_id'
        );
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
    public function accessCard()
    {
        return $this->hasOne(\App\Models\AccessCard::class);
    }

    public function workerOpening()
    {
        return $this->belongsTo(\App\Models\WorkerOpening::class, 'worker_opening_id');
    }

}
