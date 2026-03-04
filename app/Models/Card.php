<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    protected $fillable = [
        'event_id',
        'application_id',
        'accreditation_mapping_id',
        'access_card_config_id',
        'status',
        'card_number',
        'qr_token',
        'qr_payload',
        'signature',
        'issued_at',
        'issued_by',
        'printed_at',
        'printed_by',
        'snapshot',
    ];

    protected $casts = [
        'snapshot'   => 'array',
        'issued_at'  => 'datetime',
        'printed_at' => 'datetime',
    ];

    public function overrides(): HasMany
    {
        return $this->hasMany(CardAccessOverride::class, 'card_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function mapping(): BelongsTo
    {
        return $this->belongsTo(AccreditationMapping::class, 'accreditation_mapping_id');
    }

    public function accessConfig(): BelongsTo
    {
        return $this->belongsTo(AccessCardConfig::class, 'access_card_config_id');
    }
}