<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accreditation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'jabatan_id',
        'nama_akreditasi',
        'warna',
        'keterangan',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
