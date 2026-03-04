<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccreditationMapping extends Model
{
    protected $fillable = [
        'event_id',
        'nama_akreditasi',
        'warna',
        'keterangan',
    ];

    // ✅ many-to-many ke job categories via pivot
    public function jobCategories()
    {
        return $this->belongsToMany(
            JobCategory::class,
            'accreditation_mapping_job_category',
            'accreditation_mapping_id',
            'job_category_id'
        )->withTimestamps();
    }

    public function accessCardConfig()
    {
        return $this->hasOne(\App\Models\AccessCardConfig::class, 'accreditation_mapping_id');
    }
}