<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_at',
        'end_at',
        'venue',
        'city',
        'status',
        'priority',
        'capacity',
        'owner_id',
        'contact_info',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'contact_info' => 'array',
        ];
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function sports()
    {
        return $this->belongsToMany(Sport::class)
            ->withPivot(['quota', 'notes'])
            ->withTimestamps();
    }

    public function workerOpenings()
    {
        return $this->hasMany(WorkerOpening::class);
    }

    public function applications()
    {
        return $this->hasManyThrough(Application::class, WorkerOpening::class);
    }
}
