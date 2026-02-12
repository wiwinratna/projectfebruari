<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';

    protected $fillable = [
        'event_id',
        'nama_jabatan',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function accreditations()
    {
        return $this->hasMany(Accreditation::class);
    }
}
