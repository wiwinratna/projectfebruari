<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\EventController;

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
});
