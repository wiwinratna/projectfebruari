<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'province',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByProvince($query, $province)
    {
        return $query->where('province', $province);
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'city_id');
    }

    /**
     * Get all unique provinces
     */
    public static function getProvinces()
    {
        return self::active()
            ->distinct()
            ->orderBy('province')
            ->pluck('province')
            ->toArray();
    }

    /**
     * Get cities by province
     */
    public static function getCitiesByProvince($province = null)
    {
        $query = self::active();
        
        if ($province) {
            $query->where('province', $province);
        }
        
        return $query->orderBy('province')
                    ->orderBy('name')
                    ->get();
    }
}