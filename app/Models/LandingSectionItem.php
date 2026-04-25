<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingSectionItem extends Model
{
    use HasFactory;

    public const SECTIONS = ['about', 'flow', 'features'];

    protected $fillable = [
        'section',
        'title',
        'description',
        'emoji',
        'highlight',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
