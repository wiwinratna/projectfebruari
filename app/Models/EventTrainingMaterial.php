<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTrainingMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'description',
        'file_path',
        'original_filename',
        'link_url',
        'available_from',
        'available_until',
    ];

    protected function casts(): array
    {
        return [
            'available_from' => 'datetime',
            'available_until' => 'datetime',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
