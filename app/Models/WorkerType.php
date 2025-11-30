<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function workerOpenings()
    {
        return $this->hasMany(WorkerOpening::class);
    }

    public function jobCategories()
    {
        return $this->hasMany(JobCategory::class);
    }
}