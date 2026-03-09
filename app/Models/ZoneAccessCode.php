<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneAccessCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'kode_zona',
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
            'access_card_config_zones',
            'zone_access_code_id',
            'access_card_config_id'
        );
    }
}
