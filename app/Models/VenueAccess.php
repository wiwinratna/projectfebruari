<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'nama_vanue',
        'keterangan',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
