<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardLayout extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'is_active',
        'version',
        'layout_json',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'layout_json' => 'array',
        'is_active' => 'boolean',
        'version' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get default layout structure (Mode 1 preset)
     */
    public static function getDefaultLayout(): array
    {
        return [
            'schemaVersion' => 1,
            'contentArea' => [
                'xMm' => 0,
                'yMm' => 0,
                'wMm' => 148,
                'hMm' => 210,
            ],
            'elements' => [
                [
                    'id' => 'photo',
                    'type' => 'photo',
                    'label' => 'Photo',
                    'visible' => true,
                    'rect' => [
                        'xMm' => 20,
                        'yMm' => 15,
                        'wMm' => 40,
                        'hMm' => 50,
                    ],
                    'constraints' => [
                        'allowResize' => true,
                        'aspectRatio' => 1,
                    ],
                ],
                [
                    'id' => 'qr',
                    'type' => 'qr',
                    'label' => 'QR Code',
                    'visible' => true,
                    'rect' => [
                        'xMm' => 75,
                        'yMm' => 15,
                        'wMm' => 40,
                        'hMm' => 40,
                    ],
                ],
                [
                    'id' => 'name',
                    'type' => 'text-name',
                    'label' => 'Nama',
                    'visible' => true,
                    'rect' => [
                        'xMm' => 20,
                        'yMm' => 70,
                        'wMm' => 95,
                        'hMm' => 10,
                    ],
                    'style' => [
                        'fontSize' => 14,
                        'fontWeight' => 'bold',
                        'maxLines' => 2,
                    ],
                ],
                [
                    'id' => 'job_category',
                    'type' => 'text-job',
                    'label' => 'Job/Role',
                    'visible' => true,
                    'rect' => [
                        'xMm' => 20,
                        'yMm' => 82,
                        'wMm' => 95,
                        'hMm' => 8,
                    ],
                    'style' => [
                        'fontSize' => 10,
                    ],
                ],
                [
                    'id' => 'accreditation_badge',
                    'type' => 'text-accreditation',
                    'label' => 'Accreditation',
                    'visible' => true,
                    'rect' => [
                        'xMm' => 20,
                        'yMm' => 92,
                        'wMm' => 95,
                        'hMm' => 8,
                    ],
                    'style' => [
                        'fontSize' => 9,
                    ],
                ],
                [
                    'id' => 'transport_accommodation_group',
                    'type' => 'group-badges',
                    'label' => 'Transport & Accommodation',
                    'visible' => true,
                    'rect' => [
                        'xMm' => 20,
                        'yMm' => 102,
                        'wMm' => 95,
                        'hMm' => 20,
                    ],
                    'style' => [
                        'badgeSize' => 'sm',
                    ],
                ],
                [
                    'id' => 'venue_zone_group',
                    'type' => 'group-chips',
                    'label' => 'Venue & Zone Chips',
                    'visible' => true,
                    'rect' => [
                        'xMm' => 20,
                        'yMm' => 124,
                        'wMm' => 95,
                        'hMm' => 50,
                    ],
                    'style' => [
                        'maxVenueChips' => 4,
                        'maxZoneChips' => 4,
                    ],
                ],
            ],
        ];
    }

    /**
     * Scope: Get active layout for event
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get layouts by event
     */
    public function scopeByEvent($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }
}
