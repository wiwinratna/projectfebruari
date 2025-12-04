<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\AnalyticsDashboardController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Login processing
Route::post('/login', function () {
    $credentials = request()->only('username', 'password');
    
    if ($credentials['username'] === 'admin' && $credentials['password'] === 'admin123') {
        session(['authenticated' => true, 'user' => 'admin', 'login_time' => now()]);
        return redirect('/dashboard');
    }
    
    return back()->withErrors(['username' => 'Invalid credentials'])->withInput();
})->middleware('web')->name('login.submit');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/password/reset', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/logout', function () {
    session()->forget(['authenticated', 'user']);
    return redirect('/login');
})->name('logout');

// Flash message handler
Route::post('/flash-message', function () {
    $data = request()->only(['message', 'type']);
    $type = in_array($data['type'], ['status', 'error', 'warning']) ? $data['type'] : 'status';
    
    return back()->with($type, $data['message']);
})->name('flash.message');

// Protected Routes (require authentication)
Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', function () {
        \Log::info('Dashboard access attempt', [
            'authenticated' => session('authenticated'),
            'user' => session('user'),
            'session_data' => session()->all(),
            'session_id' => session()->getId(),
            'request_method' => request()->method(),
            'user_agent' => request()->userAgent(),
        ]);
        
        if (!session('authenticated')) {
            \Log::warning('Dashboard access denied - not authenticated');
            return redirect('/login')->with('error', 'Please login to access dashboard');
        }
        
        \Log::info('Dashboard access granted');
        return view('menu.dashboard.dashboard');
    })->name('dashboard');

    Route::get('/analytics', [AnalyticsDashboardController::class, 'index'])->name('analytics');

    Route::resource('events', EventController::class);

    Route::get('/workers', [WorkerController::class, 'index'])->name('workers.index');
    Route::get('/workers/create', [WorkerController::class, 'create'])->name('workers.create');
    Route::post('/workers', [WorkerController::class, 'store'])->name('workers.store');
    Route::get('/workers/{opening}/edit', [WorkerController::class, 'edit'])->name('workers.edit');
    Route::put('/workers/{opening}', [WorkerController::class, 'update'])->name('workers.update');

    // Job Categories CRUD
    Route::get('/categories', [JobCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [JobCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [JobCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [JobCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [JobCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [JobCategoryController::class, 'destroy'])->name('categories.destroy');

    // Sports CRUD
    Route::get('/sports', [SportController::class, 'index'])->name('sports.index');
    Route::get('/sports/create', [SportController::class, 'create'])->name('sports.create');
    Route::post('/sports', [SportController::class, 'store'])->name('sports.store');
    Route::get('/sports/{sport}/edit', [SportController::class, 'edit'])->name('sports.edit');
    Route::put('/sports/{sport}', [SportController::class, 'update'])->name('sports.update');
    Route::delete('/sports/{sport}', [SportController::class, 'destroy'])->name('sports.destroy');

    Route::get('/reviews', function () {
        if (!session('authenticated')) {
            return redirect('/login');
        }
        return view('menu.reviews.index');
    });
});

