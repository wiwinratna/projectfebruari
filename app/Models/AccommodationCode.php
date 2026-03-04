<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'kode',
        'keterangan',
        'icon_key',
        'show_icon',
        'show_code',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
