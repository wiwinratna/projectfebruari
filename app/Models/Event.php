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
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
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
}
