<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAccessCode extends Model
{
    protected $fillable = [
        'event_id','code','label','color_hex','notes'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
}
