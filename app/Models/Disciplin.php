<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplin extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'sport_id',
        'venue_location_id',
        'nama_disiplin',
        'keterangan',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function venueLocation()
    {
        return $this->belongsTo(VenueLocation::class);
    }
}
