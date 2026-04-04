<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        $image = trim((string) $this->image);

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            $parsedHost = parse_url($image, PHP_URL_HOST);
            $parsedPath = parse_url($image, PHP_URL_PATH);

            if (
                is_string($parsedPath)
                && $parsedPath !== ''
                && (
                    in_array($parsedHost, ['localhost', '127.0.0.1'], true)
                    || str_contains($parsedPath, '/storage/')
                    || str_starts_with($parsedPath, 'storage/')
                )
            ) {
                $image = $parsedPath;
            } else {
                return $image;
            }
        }

        $path = ltrim(str_replace('\\', '/', $image), '/');

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
