<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Calculate profile completion percentage
     */
    public function getProfileCompletionAttribute()
    {
        if ($this->role === 'admin') return 100;

        $fields = [
            'profile_photo' => 10,
            'summary' => 15,
            'phone' => 10,
            'address' => 10,
            'date_of_birth' => 5,
            'cv_file' => 20,
            'last_education' => 10,
            'skills' => 10,
            'languages' => 10,
        ];

        $completed = 0;
        $profile = $this->profile;

        if (!$profile) return 0;

        foreach ($fields as $field => $percentage) {
            if (!empty($profile->$field)) {
                $completed += $percentage;
            }
        }

        return min($completed, 100);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
    public function reviewedApplications()
    {
        return $this->hasMany(Application::class, 'reviewed_by');
    }

    public function savedJobs()
    {
        return $this->belongsToMany(WorkerOpening::class, 'saved_jobs')->withTimestamps();
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
    public function certificates()
    {
        return $this->hasMany(\App\Models\UserCertificate::class);
    }
}
