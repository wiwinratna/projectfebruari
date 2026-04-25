<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingSectionConfig extends Model
{
    use HasFactory;

    public const SECTIONS = ['about'];

    protected $fillable = [
        'section',
        'badge_text',
        'title_text',
        'subtitle_text',
        'extra_text',
        'extra_text_2',
        'extra_text_3',
        'chip_text_1',
        'chip_text_2',
        'chip_text_3',
        'cta_text',
        'mission_title',
        'vision_title',
    ];
}
