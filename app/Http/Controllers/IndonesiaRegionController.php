<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

/**
 * Provides Indonesia region data (province → city → district → village)
 * using the public emsifa/kel-data API (no API key required).
 * API docs: https://ibnux.github.io/data-indonesia/
 */
class IndonesiaRegionController extends Controller
{
    private const BASE_URL = 'https://ibnux.github.io/data-indonesia';
    private const CACHE_TTL = 60 * 24 * 7; // 7 days in minutes

    public function provinces()
    {
        return response()->json($this->fetchAndCache('id_provinces', '/provinsi.json'));
    }

    public function cities(string $provinceId)
    {
        return response()->json($this->fetchAndCache("id_cities_{$provinceId}", "/kabupaten/{$provinceId}.json"));
    }

    public function districts(string $cityId)
    {
        return response()->json($this->fetchAndCache("id_districts_{$cityId}", "/kecamatan/{$cityId}.json"));
    }

    public function villages(string $districtId)
    {
        return response()->json($this->fetchAndCache("id_villages_{$districtId}", "/kelurahan/{$districtId}.json"));
    }

    private function fetchAndCache(string $cacheKey, string $endpoint)
    {
        if (Cache::has($cacheKey)) {
            $data = Cache::get($cacheKey);
            if (!empty($data)) return $data;
        }

        try {
            $response = Http::timeout(10)->get(self::BASE_URL . $endpoint);
            if ($response->successful() && !empty($response->json())) {
                $data = $response->json();
                Cache::put($cacheKey, $data, self::CACHE_TTL * 60);
                return $data;
            }
        } catch (\Exception $e) {
            // Log error if needed, fallback to empty array
        }

        return [];
    }
}
