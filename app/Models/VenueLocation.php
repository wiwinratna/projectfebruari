<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'gugus',
        'nama',
        'alamat',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function disciplins()
    {
        return $this->hasMany(Disciplin::class);
    }
}
