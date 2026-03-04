<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\EventController;
use App\Models\Client;
use App\Models\Partner;

Route::middleware('api')->group(function () {
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
});
