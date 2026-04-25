<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingFooterConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'brand_description',
        'quick_links_title',
        'connect_title',
        'quick_links',
        'legal_links',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'address_text',
        'address_url',
        'phone_text',
        'phone_url',
        'email_text',
        'email_url',
        'copyright_text',
    ];

    protected $casts = [
        'quick_links' => 'array',
        'legal_links' => 'array',
    ];
}
