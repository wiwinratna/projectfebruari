<?php

use App\Models\TransportationCode;
use App\Models\AccommodationCode;

if (!function_exists('transportBadge')) {
    function transportBadge(?TransportationCode $t): array
    {
        if (!$t) return ['type' => 'none', 'icon' => null, 'code' => null, 'show_code' => true];

        $hasIcon  = !empty($t->icon_key) && (bool) $t->show_icon;
        $showCode = $t->show_code === null ? true : (bool) $t->show_code;

        return [
            'type' => $hasIcon ? 'icon' : 'code',
            'icon' => $hasIcon ? $t->icon_key : null,
            'code' => $t->kode,         // fallback selalu ada
            'show_code' => $showCode,
        ];
    }
}

if (!function_exists('accommodationBadge')) {
    function accommodationBadge(?AccommodationCode $a): array
    {
        if (!$a) return ['type' => 'none', 'icon' => null, 'code' => null, 'show_code' => true];

        $hasIcon  = !empty($a->icon_key) && (bool) $a->show_icon;
        $showCode = $a->show_code === null ? true : (bool) $a->show_code;

        return [
            'type' => $hasIcon ? 'icon' : 'code',
            'icon' => $hasIcon ? $a->icon_key : null,
            'code' => $a->kode,
            'show_code' => $showCode,
        ];
    }
}

/**
 * ICON CATALOG HELPERS (from config/icon_catalog.php)
 */

if (!function_exists('icon_catalog_get')) {
    function icon_catalog_get(?string $key): mixed
    {
        if (!$key) return null;

        $catalog = config('icon_catalog', []);
        return $catalog[$key] ?? null;
    }
}

if (!function_exists('icon_catalog_svg')) {
    function icon_catalog_svg(?string $key): ?string
    {
        $val = icon_catalog_get($key);
        if (!$val) return null;

        // catalog langsung string SVG
        if (is_string($val) && str_contains(ltrim($val), '<svg')) {
            return $val;
        }

        // catalog array punya 'svg'
        if (is_array($val) && isset($val['svg']) && is_string($val['svg']) && str_contains(ltrim($val['svg']), '<svg')) {
            return $val['svg'];
        }

        return null;
    }
}

if (!function_exists('icon_catalog_data_uri')) {
    function icon_catalog_data_uri(?string $key): ?string
    {
        $val = icon_catalog_get($key);
        if (!$val) return null;

        // sudah data-uri
        if (is_string($val) && str_starts_with($val, 'data:image/')) {
            return $val;
        }

        if (is_array($val)) {
            // data uri di field lain
            if (isset($val['data_uri']) && is_string($val['data_uri']) && str_starts_with($val['data_uri'], 'data:image/')) {
                return $val['data_uri'];
            }
            if (isset($val['data']) && is_string($val['data']) && str_starts_with($val['data'], 'data:image/')) {
                return $val['data'];
            }

            // base64 png mentah
            if (isset($val['png_base64']) && is_string($val['png_base64']) && $val['png_base64'] !== '') {
                return 'data:image/png;base64,' . $val['png_base64'];
            }
        }

        // string base64 mentah (anggap png)
        if (is_string($val) && !str_contains($val, '<') && strlen($val) > 50) {
            return 'data:image/png;base64,' . $val;
        }

        return null;
    }
}

if (!function_exists('icon_svg_inline')) {
    function icon_svg_inline(?string $key): ?string
    {
        if (!$key) return null;

        // ambil dari config/icon_svg.php
        $svg = config('icon_svg.' . $key);

        // pastikan memang svg string
        if (is_string($svg) && str_contains(ltrim($svg), '<svg')) {
            return $svg;
        }

        return null;
    }
}