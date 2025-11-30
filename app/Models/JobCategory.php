<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'requires_certification',
        'default_shift_hours',
        'is_active',
        'worker_type_id',
    ];

    protected $casts = [
        'requires_certification' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function workerOpenings()
    {
        return $this->hasMany(WorkerOpening::class);
    }

    public function workerType()
    {
        return $this->belongsTo(WorkerType::class);
    }
}
