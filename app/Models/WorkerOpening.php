<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerOpening extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'job_category_id',
        'title',
        'description',
        'requirements',
        'slots_total',
        'slots_filled',
        'application_deadline',
        'benefits',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'requirements' => 'array',
            'application_deadline' => 'datetime',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function getSlotsRemainingAttribute(): int
    {
        return max(0, (int) $this->slots_total - (int) $this->slots_filled);
    }
}