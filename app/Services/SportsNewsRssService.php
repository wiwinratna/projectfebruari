<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SportsNewsRssService
{
    public function latest(int $limit = 4): array
    {
        $url = config('services.sports_news.rss_url');
        if (!$url) return [];

        try {
            $res = Http::timeout(6) // kecilin biar ga bikin landing lama
                ->retry(2, 200)     // coba 2x kalau gagal (200ms jeda)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (NOCIS; Laravel RSS Fetch)',
                    'Accept' => 'application/rss+xml, application/xml;q=0.9, */*;q=0.8',
                ])
                ->get($url);

            if (!$res->ok()) return [];

            $xml = @simplexml_load_string($res->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
            if (!$xml || !isset($xml->channel->item)) return [];

            $items = [];
            foreach ($xml->channel->item as $item) {
                if (count($items) >= $limit) break;

                $title = (string) $item->title;
                $link  = (string) $item->link;
                $pub   = (string) $item->pubDate;
                $desc  = strip_tags((string) ($item->description ?? ''));

                $items[] = [
                    'title' => $title,
                    'excerpt' => $this->limitText($desc, 120),
                    'image' => $this->extractImage($item) ?: null, // optional
                    'url' => $link ?: '#',
                    'source' => $this->extractSource($item, $xml),
                    'published_at' => $pub ? date(DATE_ATOM, strtotime($pub)) : null,
                    'type' => 'api',
                ];
            }

            return $items;
        } catch (\Throwable $e) {
            // kalau RSS down / timeout, jangan bikin landing error
            return [];
        }
    }

    private function extractSource($item, $xml): string
    {
        // RSS biasa: channel title
        return (string)($xml->channel->title ?? 'Sports News');
    }

    private function extractImage($item): ?string
    {
        // RSS BBC sering punya <media:thumbnail> atau <media:content>
        $ns = $item->getNameSpaces(true);

        if (isset($ns['media'])) {
            $media = $item->children($ns['media']);

            if (isset($media->thumbnail)) {
                $attr = $media->thumbnail->attributes();
                if (!empty($attr['url'])) return (string) $attr['url'];
            }

            if (isset($media->content)) {
                $attr = $media->content->attributes();
                if (!empty($attr['url'])) return (string) $attr['url'];
            }
        }

        return null;
    }

    private function limitText(string $text, int $max): string
    {
        $text = trim(preg_replace('/\s+/', ' ', $text));
        if (mb_strlen($text) <= $max) return $text;
        return mb_substr($text, 0, $max - 3) . '...';
    }
}
