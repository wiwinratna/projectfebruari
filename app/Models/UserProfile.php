<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'professional_headline',
        'phone',
        'date_of_birth',
        'address',

        // Nationality & structured address
        'nationality_type',   // 'wni' | 'wna'

        // WNI - Indonesian cascading address
        'province',
        'city_regency',
        'district',
        'village',
        'postal_code',
        'rt',
        'rw',

        // WNA - international manual address
        'country',
        'state_region',

        // Social media
        'linkedin',
        'instagram',
        'twitter',
        'tiktok',
        'website',

        // CV
        'cv_file',
        'cv_updated_at',

        // Profile photo & summary
        'profile_photo',
        'summary',

        // Legacy single-entry education (kept for backward compat)
        'last_education',
        'field_of_study',
        'university',
        'graduation_year',

        // Skills & Languages
        'skills',
        'languages',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'cv_updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}