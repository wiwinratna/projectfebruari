<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\EventController;
use App\Models\Client;
use App\Models\HeroSlide;
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
});
