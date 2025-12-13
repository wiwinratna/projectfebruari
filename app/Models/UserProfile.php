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
        'linkedin',
        'instagram',
        'twitter',
        'tiktok',
        'website',
        'cv_file',
        'cv_updated_at',
        'profile_photo',
        'summary',
        // Education & Preferences
        'last_education',
        'field_of_study',
        'university',
        'graduation_year',
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