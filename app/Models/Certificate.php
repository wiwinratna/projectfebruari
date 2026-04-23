<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'event_id',
        'application_id',
        'layout_id',
        'layout_snapshot',
        'status',
        'cert_code',
        'qr_token',
        'verify_url',
        'signature',
        'payload',
        'snapshot',
        'published_at',
        'downloaded_at',
        'issued_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'layout_snapshot' => 'array',
        'payload'         => 'array',
        'snapshot'        => 'array',
        'published_at'    => 'datetime',
        'downloaded_at'   => 'datetime',
        'issued_at'       => 'datetime',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function layout(): BelongsTo
    {
        return $this->belongsTo(CertificateLayout::class, 'layout_id');
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
     * Get the effective layout JSON.
     * ALWAYS uses layout_snapshot (frozen at publish) — never the live layout row.
     */
    public function getEffectiveLayout(): array
    {
        if (!empty($this->layout_snapshot)) {
            return $this->layout_snapshot;
        }

        return CertificateLayout::getDefaultLayout();
    }

    /**
     * Resolve the volunteer name from payload snapshot.
     */
    public function getVolunteerNameAttribute(): string
    {
        return $this->payload['volunteer_name'] ?? $this->snapshot['volunteer_name'] ?? '—';
    }

    /**
     * Resolve the role label from payload snapshot.
     */
    public function getRoleLabelAttribute(): string
    {
        return $this->payload['role_label'] ?? '—';
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByEvent($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }
}
