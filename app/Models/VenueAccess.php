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

    public function accessCardConfigs()
    {
        return $this->belongsToMany(
            AccessCardConfig::class,
            'access_card_config_venues',
            'venue_access_id',
            'access_card_config_id'
        );
    }
}
