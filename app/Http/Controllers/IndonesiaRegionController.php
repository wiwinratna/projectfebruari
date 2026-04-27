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
        $data = Cache::remember('id_provinces', self::CACHE_TTL * 60, function () {
            $response = Http::timeout(10)->get(self::BASE_URL . '/provinsi.json');
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($data);
    }

    public function cities(string $provinceId)
    {
        $data = Cache::remember("id_cities_{$provinceId}", self::CACHE_TTL * 60, function () use ($provinceId) {
            $response = Http::timeout(10)->get(self::BASE_URL . "/kabupaten/{$provinceId}.json");
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($data);
    }

    public function districts(string $cityId)
    {
        $data = Cache::remember("id_districts_{$cityId}", self::CACHE_TTL * 60, function () use ($cityId) {
            $response = Http::timeout(10)->get(self::BASE_URL . "/kecamatan/{$cityId}.json");
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($data);
    }

    public function villages(string $districtId)
    {
        $data = Cache::remember("id_villages_{$districtId}", self::CACHE_TTL * 60, function () use ($districtId) {
            $response = Http::timeout(10)->get(self::BASE_URL . "/kelurahan/{$districtId}.json");
            return $response->successful() ? $response->json() : [];
        });

        return response()->json($data);
    }
}
