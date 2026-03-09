<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'kode',
        'keterangan',
        'show_icon',
        'show_code',
        'icon_key',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function accessCardConfigs()
    {
        return $this->hasMany(AccessCardConfig::class, 'transportation_code_id');
    }
}
