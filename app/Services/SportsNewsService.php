<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class SportsNewsService
{
    public function latest(int $limit = 6): array
    {
        $key = config('services.newsapi.key');
        if (!$key) return [];

        $res = Http::timeout(8)->get('https://newsapi.org/v2/top-headlines', [
            'category' => 'sports',
            'country' => config('services.newsapi.country', 'id'),
            'pageSize' => $limit,
            'apiKey' => $key,
        ]);

        if (!$res->ok()) return [];

        $articles = $res->json('articles', []);

        // normalisasi biar enak dipakai di blade
        return collect($articles)->map(function ($a) {
            return [
                'title' => $a['title'] ?? '',
                'excerpt' => $a['description'] ?? '',
                'url' => $a['url'] ?? '#',
                'image' => $a['urlToImage'] ?? null,
                'source' => $a['source']['name'] ?? 'News',
                'published_at' => $a['publishedAt'] ?? null,
            ];
        })->toArray();
    }
}
