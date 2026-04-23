<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateLayout extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'status',       // draft | published (published = locked)
        'is_active',
        'version',
        'layout_json',
        'background_path',
        'event_logo_path',
        'org_logo_path',
        'created_by',
        'updated_by',
        'duplicated_from',
    ];

    protected $casts = [
        'layout_json' => 'array',
        'is_active'   => 'boolean',
        'version'     => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

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

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    /**
     * A published layout must never be modified.
     */
    public function isLocked(): bool
    {
        return $this->status === 'published';
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByEvent($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    // ──────────────────────────────────────────────
    // Default Layout (A4 Landscape: 297mm × 210mm)
    // ──────────────────────────────────────────────

    public static function getDefaultLayout(): array
    {
        return [
            'schemaVersion' => '1.0.0',
            'canvasType'    => 'certificate',
            'contentArea'   => [
                'xMm' => 0.0,
                'yMm' => 0.0,
                'wMm' => 297.0,
                'hMm' => 210.0,
            ],
            'elements' => [
                [
                    'id'      => 'event_logo',
                    'type'    => 'event_logo',
                    'label'   => 'Logo Event',
                    'visible' => true,
                    'rect'    => ['xMm' => 20.0, 'yMm' => 20.0, 'wMm' => 40.0, 'hMm' => 40.0],
                    'style'   => ['objectFit' => 'contain'],
                ],
                [
                    'id'      => 'org_logo',
                    'type'    => 'org_logo',
                    'label'   => 'Logo Organisasi',
                    'visible' => true,
                    'rect'    => ['xMm' => 237.0, 'yMm' => 20.0, 'wMm' => 40.0, 'hMm' => 40.0],
                    'style'   => ['objectFit' => 'contain'],
                ],
                [
                    'id'      => 'volunteer_name',
                    'type'    => 'text-volunteer-name',
                    'label'   => 'Nama Relawan',
                    'visible' => true,
                    'rect'    => ['xMm' => 40.0, 'yMm' => 100.0, 'wMm' => 217.0, 'hMm' => 20.0],
                    'style'   => [
                        'fontSize'   => 24,
                        'fontWeight' => 'bold',
                        'align'      => 'center',
                        'color'      => '#1a1a2e',
                    ],
                ],
                [
                    'id'      => 'volunteer_role',
                    'type'    => 'text-volunteer-role',
                    'label'   => 'Peran / Jabatan',
                    'visible' => true,
                    'rect'    => ['xMm' => 40.0, 'yMm' => 124.0, 'wMm' => 217.0, 'hMm' => 12.0],
                    'style'   => [
                        'fontSize' => 14,
                        'align'    => 'center',
                        'color'    => '#4b5563',
                    ],
                ],
                [
                    'id'      => 'event_name',
                    'type'    => 'text-event-name',
                    'label'   => 'Nama Event',
                    'visible' => true,
                    'rect'    => ['xMm' => 40.0, 'yMm' => 70.0, 'wMm' => 217.0, 'hMm' => 14.0],
                    'style'   => [
                        'fontSize' => 16,
                        'align'    => 'center',
                        'color'    => '#374151',
                    ],
                ],
                [
                    'id'      => 'event_period',
                    'type'    => 'text-event-period',
                    'label'   => 'Periode Event',
                    'visible' => true,
                    'rect'    => ['xMm' => 40.0, 'yMm' => 138.0, 'wMm' => 217.0, 'hMm' => 10.0],
                    'style'   => [
                        'fontSize' => 11,
                        'align'    => 'center',
                        'color'    => '#6b7280',
                    ],
                ],
                [
                    'id'      => 'issue_date',
                    'type'    => 'text-issue-date',
                    'label'   => 'Tanggal Terbit',
                    'visible' => true,
                    'rect'    => ['xMm' => 20.0, 'yMm' => 185.0, 'wMm' => 80.0, 'hMm' => 10.0],
                    'style'   => [
                        'fontSize' => 10,
                        'align'    => 'left',
                        'color'    => '#9ca3af',
                    ],
                ],
                [
                    'id'      => 'qr_code',
                    'type'    => 'qr',
                    'label'   => 'QR Verifikasi',
                    'visible' => true,
                    'rect'    => ['xMm' => 247.0, 'yMm' => 160.0, 'wMm' => 30.0, 'hMm' => 30.0],
                ],
            ],
        ];
    }
}
