<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_at',
        'end_at',
        'venue',
        'city_id',
        'status',
        'stage',
        'penyelenggara',
        'instagram',
        'email',
        'logo_path',
        'card_template_path',
        'card_template_updated_at',
        'country',
        'province_code',
        'province_name',
        'city_code',
        'city_name',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'card_template_updated_at' => 'datetime',
        ];
    }



    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the city name with fallback
     */
    public function getCityNameAttribute()
    {
        if ($this->city && is_object($this->city) && isset($this->city->name)) {
            return $this->city->name;
        }

        return 'Lokasi belum ditentukan';
    }

    /**
     * Get the city province with fallback
     */
    public function getCityProvinceAttribute()
    {
        if ($this->city && is_object($this->city) && isset($this->city->province)) {
            return $this->city->province;
        }

        return null;
    }

    /**
     * Dapatkan nama zona waktu IANA untuk event ini (Asia/Jakarta, Asia/Makassar, Asia/Jayapura).
     */
    public function getTimezoneAttribute(): string
    {
        $province = $this->province_name ?: ($this->city ? $this->city->province : null);

        if (!$province) {
            return 'Asia/Jakarta';
        }

        $provinceUpper = strtoupper(trim($province));

        // WITA (Waktu Indonesia Tengah - UTC+8)
        $witaProvinces = [
            'BALI',
            'NUSA TENGGARA BARAT',
            'NUSA TENGGARA TIMUR',
            'KALIMANTAN SELATAN',
            'KALIMANTAN TIMUR',
            'KALIMANTAN UTARA',
            'SULAWESI UTARA',
            'GORONTALO',
            'SULAWESI TENGAH',
            'SULAWESI BARAT',
            'SULAWESI SELATAN',
            'SULAWESI TENGGARA',
        ];

        foreach ($witaProvinces as $wita) {
            if (str_contains($provinceUpper, $wita)) {
                return 'Asia/Makassar';
            }
        }

        // WIT (Waktu Indonesia Timur - UTC+9)
        $witProvinces = [
            'MALUKU',
            'MALUKU UTARA',
            'PAPUA',
        ];

        foreach ($witProvinces as $wit) {
            if (str_contains($provinceUpper, $wit)) {
                return 'Asia/Jayapura';
            }
        }

        // Default: WIB (Waktu Indonesia Barat - UTC+7)
        return 'Asia/Jakarta';
    }

    /**
     * Dapatkan singkatan zona waktu Indonesia (WIB, WITA, WIT).
     */
    public function getTimezoneLabelAttribute(): string
    {
        return match ($this->timezone) {
            'Asia/Makassar' => 'WITA',
            'Asia/Jayapura' => 'WIT',
            default => 'WIB',
        };
    }

    /**
     * Dapatkan representasi teks lokasi event yang rapi (Venue, Kota, Provinsi).
     */
    public function getLocationDisplayAttribute(): string
    {
        $venue = trim($this->venue ?? '');
        $cityName = trim($this->city_name ?: ($this->city ? $this->city->name : ''));
        $provinceName = trim($this->province_name ?: ($this->city ? $this->city->province : ''));

        if ($venue && $cityName) {
            return "{$venue}, {$cityName}";
        }

        if ($venue) {
            return $venue;
        }

        if ($cityName && $provinceName) {
            return "{$cityName}, {$provinceName}";
        }

        if ($cityName) {
            return $cityName;
        }

        if ($provinceName) {
            return $provinceName;
        }

        return 'Lokasi Resmi Event';
    }

    public function sports()
    {
        return $this->belongsToMany(Sport::class)
            ->withPivot(['quota', 'notes'])
            ->withTimestamps();
    }

    public function workerOpenings()
    {
        return $this->hasMany(WorkerOpening::class);
    }

    public function applications()
    {
        return $this->hasManyThrough(Application::class, WorkerOpening::class);
    }

    // Scopes for different visibility levels
    /**
     * Scope for events visible to customers (active and upcoming only)
     */
    public function scopeCustomerVisible($query)
    {
        return $query->whereIn('status', ['active', 'upcoming'])
            ->orderBy('start_at');
    }

    /**
     * Scope for admin view (includes all events including completed)
     */
    public function scopeForAdmin($query)
    {
        return $query->orderBy('start_at');
    }

    /**
     * Scope for active events only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->orderBy('start_at');
    }

    /**
     * Scope for upcoming events only
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')->orderBy('start_at');
    }

    /**
     * Scope for completed events only
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')->orderBy('start_at', 'desc');
    }

    /**
     * Scope for planning events (internal/admin only)
     */
    public function scopePlanning($query)
    {
        return $query->where('status', 'planning')->orderBy('start_at');
    }

    /**
     * Scope to exclude completed events (for customer-facing displays)
     */
    public function scopeNotCompleted($query)
    {
        return $query->where('status', '!=', 'completed')->orderBy('start_at');
    }
    public function accessCodes()
    {
        return $this->hasMany(\App\Models\EventAccessCode::class);
    }

    public function venueLocations()
    {
        return $this->hasMany(VenueLocation::class);
    }

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class);
    }

    public function disciplins()
    {
        return $this->hasMany(Disciplin::class);
    }

    public function accreditations()
    {
        return $this->hasMany(Accreditation::class);
    }

    public function accommodationCodes()
    {
        return $this->hasMany(AccommodationCode::class);
    }

    public function transportationCodes()
    {
        return $this->hasMany(TransportationCode::class);
    }

    public function zoneAccessCodes()
    {
        return $this->hasMany(ZoneAccessCode::class);
    }

    public function venueAccesses()
    {
        return $this->hasMany(VenueAccess::class);
    }

    public function admins()
    {
        return $this->hasMany(User::class, 'event_id')
            ->where('role', 'admin');
    }

    public function cardLayouts()
    {
        return $this->hasMany(CardLayout::class);
    }

    public function activeCardLayout()
    {
        return $this->hasOne(CardLayout::class)
            ->where('is_active', true);
    }

    // ── Certificate relationships ──────────────────

    public function certificateLayouts()
    {
        return $this->hasMany(CertificateLayout::class);
    }

    /**
     * The single published (active) certificate layout used for generation.
     * A published layout is also is_active=true; if multiple exist, take the latest.
     */
    public function activeCertificateLayout()
    {
        return $this->hasOne(CertificateLayout::class)
            ->where('is_active', true)
            ->orderByDesc('id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function attendances()
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function trainingMaterials()
    {
        return $this->hasMany(EventTrainingMaterial::class);
    }
}
