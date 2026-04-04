<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'website',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope to only return active clients.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the logo URL (storage path or external URL).
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        $logo = trim((string) $this->logo);

        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://')) {
            $parsedHost = parse_url($logo, PHP_URL_HOST);
            $parsedPath = parse_url($logo, PHP_URL_PATH);

            if (
                is_string($parsedPath)
                && $parsedPath !== ''
                && (
                    in_array($parsedHost, ['localhost', '127.0.0.1'], true)
                    || str_contains($parsedPath, '/storage/')
                    || str_starts_with($parsedPath, 'storage/')
                )
            ) {
                $logo = $parsedPath;
            } else {
                return $logo;
            }
        }

        $path = ltrim(str_replace('\\', '/', $logo), '/');

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        $encodedPath = implode('/', array_map('rawurlencode', explode('/', $path)));

        return '/media/' . $encodedPath;
    }
}
