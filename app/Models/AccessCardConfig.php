<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AccreditationMapping; // ✅ tambahin ini
use App\Models\VenueAccess;
use App\Models\ZoneAccessCode;
use App\Models\TransportationCode;

class AccessCardConfig extends Model
{
    protected $fillable = [
        'event_id',
        'accreditation_mapping_id',
        'transportation_code_id',
        'accommodation_code_id',
        'keterangan',
    ];

    protected $casts = [
        'accommodation_code_id' => 'array',
    ];

    public function mapping()
    {
        return $this->belongsTo(AccreditationMapping::class, 'accreditation_mapping_id');
    }

    public function venueAccesses()
    {
        return $this->belongsToMany(
            VenueAccess::class,
            'access_card_config_venues',
            'access_card_config_id',
            'venue_access_id'
        )->withTimestamps();
    }

    public function zoneAccessCodes()
    {
        return $this->belongsToMany(
            ZoneAccessCode::class,
            'access_card_config_zones',
            'access_card_config_id',
            'zone_access_code_id'
        )->withTimestamps();
    }

    public function transportationCode()
    {
        return $this->belongsTo(TransportationCode::class, 'transportation_code_id');
    }

    public function venues()
    {
        return $this->belongsToMany(VenueAccess::class, 'access_card_config_venues', 'access_card_config_id', 'venue_access_id');
    }

    public function zones()
    {
        return $this->belongsToMany(ZoneAccessCode::class, 'access_card_config_zones', 'access_card_config_id', 'zone_access_code_id');
    }
}
