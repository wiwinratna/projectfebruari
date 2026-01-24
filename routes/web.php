<?php

use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\AnalyticsDashboardController;
use App\Http\Controllers\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Landing page route
Route::get('/', function () {
    $jobController = new \App\Http\Controllers\JobController();
    $recentJobs = $jobController->getRecentJobs();

    return view('landing', compact('recentJobs'));
})->name('landing');

// Public Job Routes (accessible without login)
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

// Customer Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Customer Login processing - redirect back to jobs after login
Route::post('/login', function () {
    $credentials = request()->only('email', 'password');

    // Database-backed authentication
    $user = \App\Models\User::where('email', $credentials['email'])->first();

    if ($user && \Hash::check($credentials['password'], $user->password)) {
        // Only allow non-admin users to login via customer login
        if ($user->role === 'admin') {
            return back()->withErrors(['email' => 'Admin users must login via admin portal'])->withInput();
        }

        session([
            'customer_authenticated' => true,
            'customer_id' => $user->id,
            'customer_username' => $user->username,
            'customer_role' => $user->role,
            'customer_login_time' => now(),
            'customer_profile_photo' => $user->profile?->profile_photo
        ]);

        // Redirect back to jobs page or to intended URL
        $redirectTo = session('intended_url', '/jobs');
        session()->forget('intended_url');

        return redirect($redirectTo);
    }

    return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
})->middleware('web')->name('login.submit');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function () {
    $validated = request()->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'username' => 'required|string|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'terms' => 'accepted',
    ]);

    // Create User
    $user = \App\Models\User::create([
        'name' => $validated['first_name'] . ' ' . $validated['last_name'],
        'username' => $validated['username'],
        'email' => $validated['email'],
        'password' => \Hash::make($validated['password']),
        'role' => 'customer',
    ]);

    // Create Empty Profile
    \App\Models\UserProfile::create([
        'user_id' => $user->id,
    ]);

    // Login User
    session([
        'customer_authenticated' => true,
        'customer_id' => $user->id,
        'customer_username' => $user->username,
        'customer_role' => $user->role,
        'customer_login_time' => now(),
        'customer_profile_photo' => null
    ]);

    return redirect('/jobs')->with('success', 'Registration successful! Welcome to NOCIS.');
});

Route::get('/password/reset', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Customer logout
Route::post('/logout', function () {
    session()->forget(['customer_authenticated', 'customer_username']);
    return redirect('/jobs');
})->name('logout');

// Job application routes (require customer login)
Route::middleware(['web', 'customer'])->group(function () {
    Route::post('/jobs/{job}/apply', [JobController::class, 'apply'])->name('jobs.apply');
});

// Customer Dashboard Routes (require customer login)
Route::prefix('dashboard')->name('customer.')->middleware(['web', 'customer'])->group(function () {
    Route::get('/', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/upload-cv', [CustomerDashboardController::class, 'uploadCv'])->name('profile.upload-cv');
    Route::post('/profile/update-social', [CustomerDashboardController::class, 'updateSocialMedia'])->name('profile.update-social');
    Route::delete('/profile/remove-cv', [CustomerDashboardController::class, 'removeCV'])->name('profile.remove-cv');
    Route::get('/settings', [CustomerDashboardController::class, 'settings'])->name('settings');
    Route::post('/settings', [CustomerDashboardController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/photo', [CustomerDashboardController::class, 'updateProfilePhoto'])->name('settings.photo');
    Route::delete('/settings/photo', [CustomerDashboardController::class, 'removeProfilePhoto'])->name('settings.photo.remove');
    Route::get('/applications', [CustomerDashboardController::class, 'applications'])->name('applications');

    // Saved jobs routes
    Route::post('/jobs/{job}/save', [CustomerDashboardController::class, 'saveJob'])->name('jobs.save');
    Route::delete('/jobs/{job}/unsave', [CustomerDashboardController::class, 'unsaveJob'])->name('jobs.unsave');
    Route::get('/saved-jobs', [CustomerDashboardController::class, 'savedJobs'])->name('saved-jobs');
});

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin login page
    Route::get('/login', function () {
        return view('auth.admin-login');
    })->name('login');

    // Admin login processing
    Route::post('/login', function () {
        $credentials = request()->only('username', 'password');

        // Database-backed authentication
        $user = \App\Models\User::where('username', $credentials['username'])->first();

        if ($user && \Hash::check($credentials['password'], $user->password)) {
            // Only allow admin users to login via admin portal
            if ($user->role !== 'admin') {
                return back()->withErrors(['username' => 'Invalid admin credentials'])->withInput();
            }

            session([
                'admin_authenticated' => true,
                'admin_id' => $user->id,
                'admin_username' => $user->username,
                'admin_role' => $user->role,
                'admin_login_time' => now()
            ]);

            return redirect('/admin/dashboard');
        }

        return back()->withErrors(['username' => 'Invalid admin credentials'])->withInput();
    })->name('login.submit');

    // Admin logout
    Route::post('/logout', function () {
        session()->forget(['admin_authenticated', 'admin_username']);
        return redirect('/admin/login');
    })->name('logout');
});

// Flash message handler
Route::post('/flash-message', function () {
    $data = request()->only(['message', 'type']);
    $type = in_array($data['type'], ['status', 'error', 'warning']) ? $data['type'] : 'status';

    return back()->with($type, $data['message']);
})->name('flash.message');

// Protected Admin Routes (require admin authentication)
Route::prefix('admin')->name('admin.')->middleware(['web', 'admin'])->group(function () {
    // Admin Dashboard - Protected
    Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');

    // Analytics Dashboard - Protected
    Route::get('/analytics', [AnalyticsDashboardController::class, 'index'])->name('analytics');

    // Events Management - Protected
    Route::resource('events', EventController::class);

    // Workers Management - Protected
    Route::resource('workers', WorkerController::class);

    // Job Categories CRUD - Protected
    Route::resource('categories', JobCategoryController::class);

    // Sports CRUD - Protected
    Route::resource('sports', SportController::class);

    // Reviews Management - Protected
    Route::get('/reviews', [\App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/export', [\App\Http\Controllers\ReviewController::class, 'export'])->name('reviews.export');
    Route::post('/reviews/{application}', [\App\Http\Controllers\ReviewController::class, 'updateStatus'])->name('reviews.update');

    // Dedicated Application Review (Full Page)
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/{application}', [\App\Http\Controllers\ApplicationController::class, 'show'])->name('show');
        Route::post('/{application}', [\App\Http\Controllers\ApplicationController::class, 'update'])->name('update');
    });

    // Admin Profile & Settings
    Route::get('/profile', [\App\Http\Controllers\AdminProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\AdminProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\AdminProfileController::class, 'updatePassword'])->name('profile.password');
});

// Prevent customer users from accessing admin routes directly
Route::middleware(['web'])->group(function () {
    Route::get('/admin/{any}', function () {
        if (session('customer_authenticated')) {
            return redirect('/jobs')->with('error', 'Access denied. Admin area restricted.');
        }
        return redirect('/admin/login')->with('error', 'Please login to access admin area.');
    })->where('any', '.*');
});

// Storage file serving route (workaround for symlink permission issues)
Route::get('/storage/{path}', function ($path) {
    $file = storage_path('app/public/storage/profile_photos' . $path);
    
    if (!file_exists($file)) {
        abort(404);
    }
    
    // Get file mime type
    $mimeType = mime_content_type($file);
    
    // Return file with proper headers
    return response()->file($file, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('storage.serve');

// Store intended URL before redirecting to login (for apply functionality)
Route::middleware(['web'])->group(function () {
    Route::get('/store-intended-url', function () {
        session(['intended_url' => url()->previous()]);
        return response()->json(['success' => true]);
    })->name('store.intended.url');
});
