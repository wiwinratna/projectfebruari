<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsPost extends Model
{
    protected $fillable = [
        'title','slug','excerpt','content','cover_image',
        'source_name','source_url','published_at','is_published'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public static function booted()
    {
        static::saving(function ($m) {
            if (!$m->slug) $m->slug = Str::slug($m->title).'-'.Str::random(6);
            if ($m->is_published && !$m->published_at) $m->published_at = now();
        });
    }
}

