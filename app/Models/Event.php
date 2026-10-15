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
     * Dapatkan nama zona waktu IANA untuk event ini (Mendukung Seluruh Indonesia & Luar Negeri).
     */
    public function getTimezoneAttribute(): string
    {
        $country = trim($this->country ?? '');
        $isAbroad = $country && !in_array(strtoupper($country), ['INDONESIA', 'ID', 'INA']);

        // Jika Event Diselenggarakan di Luar Negeri
        if ($isAbroad) {
            $countryUpper = strtoupper($country);

            $countryTimezones = [
                // ASEAN / Asia Tenggara
                'SINGAPORE' => 'Asia/Singapore',
                'SINGAPURA' => 'Asia/Singapore',
                'MALAYSIA' => 'Asia/Kuala_Lumpur',
                'THAILAND' => 'Asia/Bangkok',
                'VIETNAM' => 'Asia/Ho_Chi_Minh',
                'PHILIPPINES' => 'Asia/Manila',
                'FILIPINA' => 'Asia/Manila',
                'CAMBODIA' => 'Asia/Phnom_Penh',
                'KAMBOJA' => 'Asia/Phnom_Penh',
                'LAOS' => 'Asia/Vientiane',
                'MYANMAR' => 'Asia/Yangon',
                'BRUNEI' => 'Asia/Brunei',
                'TIMOR LESTE' => 'Asia/Dili',
                'TIMOR-LESTE' => 'Asia/Dili',

                // Asia Timur & Selatan
                'JAPAN' => 'Asia/Tokyo',
                'JEPANG' => 'Asia/Tokyo',
                'SOUTH KOREA' => 'Asia/Seoul',
                'KOREA SELATAN' => 'Asia/Seoul',
                'KOREA' => 'Asia/Seoul',
                'CHINA' => 'Asia/Shanghai',
                'TIONGKOK' => 'Asia/Shanghai',
                'HONG KONG' => 'Asia/Hong_Kong',
                'HONGKONG' => 'Asia/Hong_Kong',
                'TAIWAN' => 'Asia/Taipei',
                'INDIA' => 'Asia/Kolkata',

                // Timur Tengah
                'QATAR' => 'Asia/Qatar',
                'UNITED ARAB EMIRATES' => 'Asia/Dubai',
                'UAE' => 'Asia/Dubai',
                'EMIRAT ARAB' => 'Asia/Dubai',
                'SAUDI ARABIA' => 'Asia/Riyadh',
                'ARAB SAUDI' => 'Asia/Riyadh',

                // Eropa
                'FRANCE' => 'Europe/Paris',
                'PRANCIS' => 'Europe/Paris',
                'UNITED KINGDOM' => 'Europe/London',
                'UK' => 'Europe/London',
                'INGGRIS' => 'Europe/London',
                'GERMANY' => 'Europe/Berlin',
                'JERMAN' => 'Europe/Berlin',
                'ITALY' => 'Europe/Rome',
                'ITALIA' => 'Europe/Rome',
                'SPAIN' => 'Europe/Madrid',
                'SPANYOL' => 'Europe/Madrid',
                'NETHERLANDS' => 'Europe/Amsterdam',
                'BELANDA' => 'Europe/Amsterdam',
                'SWITZERLAND' => 'Europe/Zurich',
                'SWISS' => 'Europe/Zurich',

                // Australia & Oseania
                'AUSTRALIA' => 'Australia/Sydney',
                'NEW ZEALAND' => 'Pacific/Auckland',
                'SELANDIA BARU' => 'Pacific/Auckland',

                // Amerika
                'UNITED STATES' => 'America/New_York',
                'USA' => 'America/New_York',
                'AMERIKA SERIKAT' => 'America/New_York',
                'CANADA' => 'America/Toronto',
                'KANADA' => 'America/Toronto',
                'BRAZIL' => 'America/Sao_Paulo',
                'BRASIL' => 'America/Sao_Paulo',
            ];

            foreach ($countryTimezones as $key => $tz) {
                if (str_contains($countryUpper, $key) || $countryUpper === $key) {
                    return $tz;
                }
            }

            // Estimasi dari koordinat longitude jika tersedia
            if ($this->longitude !== null) {
                $offsetHours = (int) round($this->longitude / 15);
                return "Etc/GMT" . ($offsetHours >= 0 ? "-{$offsetHours}" : "+{$offsetHours}");
            }

            return 'UTC';
        }

        // Jika Event di Indonesia: Deteksi berdasarkan Provinsi
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
     * Dapatkan singkatan zona waktu (WIB, WITA, WIT untuk Indonesia, atau singkatan resmi negara luar).
     */
    public function getTimezoneLabelAttribute(): string
    {
        $tz = $this->timezone;

        return match ($tz) {
            'Asia/Jakarta' => 'WIB',
            'Asia/Makassar' => 'WITA',
            'Asia/Jayapura' => 'WIT',
            'Asia/Singapore', 'Asia/Kuala_Lumpur', 'Asia/Brunei' => 'SGT',
            'Asia/Bangkok', 'Asia/Ho_Chi_Minh', 'Asia/Phnom_Penh', 'Asia/Vientiane' => 'ICT',
            'Asia/Tokyo' => 'JST',
            'Asia/Seoul' => 'KST',
            'Asia/Manila' => 'PST',
            'Asia/Shanghai', 'Asia/Hong_Kong', 'Asia/Taipei' => 'CST',
            'Europe/London' => 'GMT',
            'Europe/Paris', 'Europe/Berlin', 'Europe/Rome', 'Europe/Madrid', 'Europe/Amsterdam' => 'CET',
            'Asia/Dubai' => 'GST',
            'Asia/Qatar', 'Asia/Riyadh' => 'AST',
            default => (function() use ($tz) {
                try {
                    $abbr = now($tz)->format('T');
                    if (preg_match('/^[+-]\d+$/', $abbr)) {
                        $hours = (int) $abbr;
                        return 'UTC' . ($hours >= 0 ? "+{$hours}" : "{$hours}");
                    }
                    return $abbr ?: 'UTC';
                } catch (\Throwable $e) {
                    return 'UTC';
                }
            })(),
        };
    }

    /**
     * Dapatkan representasi teks lokasi event yang rapi (Mendukung Event Dalam & Luar Negeri).
     */
    public function getLocationDisplayAttribute(): string
    {
        $venue = trim($this->venue ?? '');
        $cityName = trim($this->city_name ?: ($this->city ? $this->city->name : ''));
        $provinceName = trim($this->province_name ?: ($this->city ? $this->city->province : ''));
        $country = trim($this->country ?? '');
        $isAbroad = $country && !in_array(strtoupper($country), ['INDONESIA', 'ID', 'INA']);

        // Jika Event di Luar Negeri
        if ($isAbroad) {
            if ($venue && $cityName) {
                return "{$venue}, {$cityName}, {$country}";
            }
            if ($venue) {
                return "{$venue}, {$country}";
            }
            if ($cityName) {
                return "{$cityName}, {$country}";
            }
            return $country;
        }

        // Jika Event di Dalam Negeri (Indonesia)
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
