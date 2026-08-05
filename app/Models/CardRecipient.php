<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'event_id',
        'name',
        'population',
        'category',
        'venue_access',
        'zone_access',
        'seating_access',
        'transport',
        'identity_number',
        'photo_path',
        'job_category_id',
        'accreditation_mapping_id',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class);
    }

    public function accreditationMapping()
    {
        return $this->belongsTo(AccreditationMapping::class);
    }

    /**
     * A recipient maps to exactly one Card (one-to-one, import-only source).
     */
    public function card()
    {
        return $this->hasOne(Card::class, 'card_recipient_id');
    }
}
