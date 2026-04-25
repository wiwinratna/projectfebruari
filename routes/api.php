<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\EventController;
use App\Models\Client;
use App\Models\HeroSlide;
use App\Models\LandingFooterConfig;
use App\Models\LandingSectionConfig;
use App\Models\LandingSectionItem;
use App\Models\Partner;

Route::middleware('api')->group(function () {
    // Public media endpoint for files stored without relying on storage symlink.
    Route::get('/media/{path}', function ($path) {
        $normalizedPath = ltrim(str_replace('\\', '/', (string) $path), '/');

        if ($normalizedPath === '' || str_contains($normalizedPath, '../')) {
            abort(404);
        }

        $candidates = [
            storage_path('app/public/' . $normalizedPath),
            public_path('storage/' . $normalizedPath),
        ];

        $file = null;
        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                $file = $candidate;
                break;
            }
        }

        if (!$file) {
            abort(404);
        }

        $mimeType = mime_content_type($file) ?: 'application/octet-stream';

        return response()->file($file, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    })->where('path', '.*')->name('api.media.serve');

    // Public API endpoint for news - accessible to everyone including React frontend
    Route::get('/news', [NewsPostController::class, 'apiIndex']);
    Route::get('/news/{news}', [NewsPostController::class, 'apiShow']);

    // Public API endpoint for jobs - accessible to everyone including React frontend
    Route::get('/jobs', [JobController::class, 'apiIndex']);
    Route::get('/jobs/{job}', [JobController::class, 'apiShow']);

    // Public API endpoint for job categories - accessible to everyone including React frontend
    Route::get('/categories', [JobCategoryController::class, 'apiIndex']);

    // Public API endpoint for events - active events only
    Route::get('/events', [EventController::class, 'apiIndex']);

    // Public API endpoints for landing page clients & partners
    Route::get('/clients', function () {
        $clients = Client::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'logo', 'website', 'description'])
            ->map(function ($c) {
                return [
                    'id'          => $c->id,
                    'name'        => $c->name,
                    'logo_url'    => $c->logo_url,
                    'website'     => $c->website,
                    'description' => $c->description,
                    'initial'     => strtoupper(substr($c->name, 0, 2)),
                ];
            });
        return response()->json($clients);
    });

    Route::get('/partners', function () {
        $partners = Partner::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'logo', 'website', 'description'])
            ->map(function ($p) {
                return [
                    'id'          => $p->id,
                    'name'        => $p->name,
                    'logo_url'    => $p->logo_url,
                    'website'     => $p->website,
                    'description' => $p->description,
                    'initial'     => strtoupper(substr($p->name, 0, 2)),
                ];
            });
        return response()->json($partners);
    });

    Route::get('/hero-slides', function () {
        $slides = HeroSlide::active()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get(['id', 'title', 'subtitle', 'description', 'image'])
            ->map(function ($slide) {
                return [
                    'id'          => $slide->id,
                    'title'       => $slide->title,
                    'subtitle'    => $slide->subtitle,
                    'description' => $slide->description,
                    'image_url'   => $slide->image_url,
                ];
            });

        return response()->json($slides);
    });

    Route::get('/landing-sections/{section}', function (string $section) {
        if (!in_array($section, LandingSectionItem::SECTIONS, true)) {
            abort(404);
        }

        $items = LandingSectionItem::active()
            ->where('section', $section)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'section', 'title', 'description', 'emoji', 'highlight', 'sort_order'])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'section' => $item->section,
                    'title' => $item->title,
                    'description' => $item->description,
                    'emoji' => $item->emoji,
                    'highlight' => $item->highlight,
                    'sort_order' => $item->sort_order,
                ];
            });

        return response()->json($items);
    });

    Route::get('/landing-section-configs/{section}', function (string $section) {
        if (!in_array($section, LandingSectionConfig::SECTIONS, true)) {
            abort(404);
        }

        $config = LandingSectionConfig::query()->where('section', $section)->first();

        return response()->json([
            'section' => $section,
            'badge_text' => $config?->badge_text,
            'title_text' => $config?->title_text,
            'subtitle_text' => $config?->subtitle_text,
            'extra_text' => $config?->extra_text,
            'extra_text_2' => $config?->extra_text_2,
            'extra_text_3' => $config?->extra_text_3,
            'chip_text_1' => $config?->chip_text_1,
            'chip_text_2' => $config?->chip_text_2,
            'chip_text_3' => $config?->chip_text_3,
            'cta_text' => $config?->cta_text,
            'mission_title' => $config?->mission_title,
            'vision_title' => $config?->vision_title,
        ]);
    });

    Route::get('/landing-footer', function () {
        $config = LandingFooterConfig::query()->where('key', 'default')->first();

        return response()->json([
            'brand_description' => $config?->brand_description,
            'quick_links_title' => $config?->quick_links_title,
            'connect_title' => $config?->connect_title,
            'quick_links' => $config?->quick_links,
            'legal_links' => $config?->legal_links,
            'facebook_url' => $config?->facebook_url,
            'twitter_url' => $config?->twitter_url,
            'instagram_url' => $config?->instagram_url,
            'linkedin_url' => $config?->linkedin_url,
            'address_text' => $config?->address_text,
            'address_url' => $config?->address_url,
            'phone_text' => $config?->phone_text,
            'phone_url' => $config?->phone_url,
            'email_text' => $config?->email_text,
            'email_url' => $config?->email_url,
            'copyright_text' => $config?->copyright_text,
        ]);
    });
});
