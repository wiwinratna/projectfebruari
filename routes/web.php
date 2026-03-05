<?php

use Illuminate\Support\Facades\File;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\AnalyticsDashboardController;
use App\Http\Controllers\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Event;
use App\Http\Controllers\NewsPostController;
use App\Models\NewsPost;
use App\Services\SportsNewsService;
use Illuminate\Support\Facades\Cache;
use App\Services\SportsNewsRssService;
use App\Http\Controllers\Admin\AccessCardConfigController;
use App\Http\Controllers\Admin\Card\CardController;
use App\Http\Controllers\Admin\Card\CardIssueController;
use App\Http\Controllers\Admin\Card\CardAccessController;
use App\Http\Controllers\PublicCardVerifyController;
use App\Http\Controllers\CardVerifyController;
use App\Http\Controllers\admin\card\CardPrintController;
use App\Http\Controllers\Customer\CustomerCardController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;

// Landing page route serving the React app
Route::get('/', function () {
    return view('app');
})->name('landing');


// Public Job Routes (accessible without login)
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

//  Public News Routes (accessible without login)
Route::get('/news', [NewsPostController::class, 'publicIndex'])->name('news.index');
Route::get('/news/{news}', [NewsPostController::class, 'publicShow'])->name('news.show');

//absah

Route::get('/cards/verify/{token}', [CardVerifyController::class, 'show'])
  ->name('cards.verify.public');
Route::post('/cards/verify/{token}', [CardVerifyController::class, 'store'])
  ->name('cards.verify.store');

// Customer Authentication Routes
Route::get('/login', function () {
    $redirect = request()->get('redirect');
    if (
        is_string($redirect)
        && $redirect !== ''
        && str_starts_with($redirect, '/')
        && !str_starts_with($redirect, '//')
        && !str_contains($redirect, '\\')
    ) {
        session(['intended_url' => $redirect]);
    }

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
})->middleware(['web', 'throttle:login'])->name('login.submit');

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
    session()->forget(['customer_authenticated', 'customer_username', 'customer_id', 'customer_login_time']);

    // Handle AJAX requests
    if (request()->wantsJson()) {
        return response()->json(['success' => true, 'message' => 'Logged out successfully']);
    }

    return redirect('/jobs');
})->name('logout');

// Job application routes (require customer login)
Route::middleware(['web', 'customer'])->group(function () {
    Route::post('/jobs/{job}/apply', [JobController::class, 'apply'])->name('jobs.apply');
    Route::get('/notifications', [CustomerNotificationController::class, 'index'])->name('customer.notifications.index');
    Route::post('/notifications/{notification}/read', [CustomerNotificationController::class, 'markRead'])->name('customer.notifications.read');
    Route::post('/notifications/read-all', [CustomerNotificationController::class, 'markAllRead'])->name('customer.notifications.read-all');
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


    Route::post('/profile/upload-certificates', [CustomerDashboardController::class, 'uploadCertificates'])
        ->name('profile.upload-certificates');

    Route::get('/profile/certificates/{certificate}', [CustomerDashboardController::class, 'certificateDetail'])
        ->name('profile.certificate.detail');

    Route::delete('/profile/certificates/{certificate}', [CustomerDashboardController::class, 'certificateDelete'])
        ->name('profile.certificate.delete');

    Route::get('/applications/{application}/card', [CustomerCardController::class, 'show'])
    ->name('applications.card');
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

            // Validasi admin harus punya event yang ditugaskan
            if (!$user->event_id) {
                return back()->withErrors(['username' => 'Akun admin belum ditugaskan ke event manapun. Hubungi super admin.'])->withInput();
            }

            session([
                'admin_authenticated' => true,
                'admin_id' => $user->id,
                'admin_username' => $user->username,
                'admin_role' => $user->role,
                'admin_login_time' => now(),
                'admin_event_id' => $user->event_id,
                'admin_event_name' => $user->event->title ?? 'Unknown Event',
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

// Super Admin Authentication Routes
Route::prefix('super-admin')->name('super-admin.')->group(function () {
    // Super Admin login page
    Route::get('/login', function () {
        return view('auth.super-admin-login');
    })->name('login');

    // Super Admin login processing
    Route::post('/login', function () {
        $credentials = request()->only('username', 'password');

        // Database-backed authentication
        $user = \App\Models\User::where('username', $credentials['username'])->first();

        if ($user && \Hash::check($credentials['password'], $user->password)) {
            // Only allow super admin users to login via super admin portal
            if ($user->role !== 'super_admin') {
                return back()->withErrors(['username' => 'Invalid super admin credentials'])->withInput();
            }

            session([
                'super_admin_authenticated' => true,
                'super_admin_id' => $user->id,
                'super_admin_username' => $user->username,
                'super_admin_role' => $user->role,
                'super_admin_login_time' => now(),
            ]);

            return redirect('/super-admin/dashboard');
        }

        return back()->withErrors(['username' => 'Invalid super admin credentials'])->withInput();
    })->name('login.submit');

    // Super Admin logout
    Route::post('/logout', function () {
        session()->forget(['super_admin_authenticated', 'super_admin_username', 'super_admin_id']);
        return redirect('/super-admin/login');
    })->name('logout');
});

// Flash message handler
Route::post('/flash-message', function () {
    $data = request()->only(['message', 'type']);
    $type = in_array($data['type'], ['status', 'error', 'warning']) ? $data['type'] : 'status';

    return back()->with($type, $data['message']);
})->name('flash.message');

// Protected Super Admin Routes (require super admin authentication)
Route::prefix('super-admin')->name('super-admin.')->middleware(['web', 'super_admin'])->group(function () {
    // Super Admin Dashboard - Protected
    Route::get('/dashboard', [\App\Http\Controllers\SuperAdminDashboardController::class, 'index'])->name('dashboard');

    // Admins Management (CRUD)
    Route::get('/admins', [\App\Http\Controllers\SuperAdminDashboardController::class, 'admins'])->name('admins.index');
    Route::get('/admins/create', [\App\Http\Controllers\SuperAdminDashboardController::class, 'adminCreate'])->name('admins.create');
    Route::post('/admins', [\App\Http\Controllers\SuperAdminDashboardController::class, 'adminStore'])->name('admins.store');
    Route::get('/admins/{user}/edit', [\App\Http\Controllers\SuperAdminDashboardController::class, 'adminEdit'])->name('admins.edit');
    Route::put('/admins/{user}', [\App\Http\Controllers\SuperAdminDashboardController::class, 'adminUpdate'])->name('admins.update');
    Route::delete('/admins/{user}', [\App\Http\Controllers\SuperAdminDashboardController::class, 'adminDelete'])->name('admins.delete');

    // Events Management
    Route::get('/events', [\App\Http\Controllers\SuperAdminDashboardController::class, 'events'])->name('events.index');
    Route::get('/events/create', [\App\Http\Controllers\SuperAdminDashboardController::class, 'eventCreate'])->name('events.create');
    Route::post('/events', [\App\Http\Controllers\SuperAdminDashboardController::class, 'eventStore'])->name('events.store');
    Route::get('/events/{event}', [\App\Http\Controllers\SuperAdminDashboardController::class, 'eventView'])->name('events.show');
    Route::get('/events/{event}/edit', [\App\Http\Controllers\SuperAdminDashboardController::class, 'eventEdit'])->name('events.edit');
    Route::put('/events/{event}', [\App\Http\Controllers\SuperAdminDashboardController::class, 'eventUpdate'])->name('events.update');
    Route::delete('/events/{event}', [\App\Http\Controllers\SuperAdminDashboardController::class, 'eventDelete'])->name('events.delete');

    // News Management
    Route::resource('news', NewsPostController::class)->names('news');

    // Super Admin Profile & Settings
    Route::get('/profile', [\App\Http\Controllers\SuperAdminDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\SuperAdminDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\SuperAdminDashboardController::class, 'updatePassword'])->name('profile.password');
    Route::resource('job-categories', \App\Http\Controllers\JobCategoryController::class)
        ->names('job-categories');

    // Our Clients Management (Landing Page)
    Route::resource('clients', ClientController::class)->names('clients');

    // Our Partners Management (Landing Page)
    Route::resource('partners', PartnerController::class)->names('partners');
});

// Prevent customer users from accessing super admin routes directly
Route::middleware(['web'])->group(function () {
    Route::get('/super-admin/{any}', function () {
        if (session('customer_authenticated')) {
            return redirect('/jobs')->with('error', 'Access denied. Super admin area restricted.');
        }
        if (session('admin_authenticated')) {
            return redirect('/admin/dashboard')->with('error', 'Access denied. Super admin area restricted.');
        }
        return redirect('/super-admin/login')->with('error', 'Please login to access super admin area.');
    })->where('any', '.*');
});

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
    //Route::resource('categories', JobCategoryController::class);

    // Sports CRUD - Protected
    Route::resource('sports', SportController::class);

    // Reviews Management - Protected
    Route::get('/reviews', [\App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/export', [\App\Http\Controllers\ReviewController::class, 'export'])->name('reviews.export');
    Route::post('/reviews/{application}', [\App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');


    // Dedicated Application Review (Full Page)
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/{application}', [\App\Http\Controllers\ApplicationController::class, 'show'])->name('show');
        Route::post('/{application}', [\App\Http\Controllers\ApplicationController::class, 'update'])->name('update');
    });

    // Admin Profile & Settings
    Route::get('/profile', [\App\Http\Controllers\AdminProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\AdminProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\AdminProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/events/{event}/access-codes', function (Event $event) {
        return response()->json(
            $event->accessCodes()
                ->select('id', 'code', 'label', 'color_hex')
                ->orderBy('code')
                ->get()
        );
    })->name('events.access-codes');

    Route::get('/cards/{card}/pdf-a5', [CardPrintController::class, 'pdfA5'])
    ->name('cards.pdfA5');


    // Master Data Routes (otomatis pakai event_id dari session admin)
    Route::prefix('master-data')->name('master-data.')->group(function () {
        Route::resource('venue-locations', \App\Http\Controllers\VenueLocationController::class)->except(['show']);
        Route::resource('jabatan', \App\Http\Controllers\JabatanController::class)->except(['show']);
        Route::resource('disciplins', \App\Http\Controllers\DisciplinController::class)->except(['show']);
        Route::resource('accreditations', \App\Http\Controllers\AccreditationController::class)->except(['show']);
        Route::resource('accommodation-codes', \App\Http\Controllers\AccommodationCodeController::class)->except(['show']);
        Route::resource('transportation-codes', \App\Http\Controllers\TransportationCodeController::class)->except(['show']);
        Route::resource('zone-access-codes', \App\Http\Controllers\ZoneAccessCodeController::class)->except(['show']);
        Route::resource('venue-accesses', \App\Http\Controllers\VenueAccessController::class)->except(['show']);
        // Access Card Configs (based on Accreditation Mapping)
        Route::get('/access-card-configs', [\App\Http\Controllers\Admin\AccessCardConfigController::class, 'index'])
            ->name('access-card-configs.index');

        Route::get('/access-card-configs/create', [\App\Http\Controllers\Admin\AccessCardConfigController::class, 'create'])
            ->name('access-card-configs.create');

        Route::post('/access-card-configs', [\App\Http\Controllers\Admin\AccessCardConfigController::class, 'store'])
            ->name('access-card-configs.store');

        Route::get('/access-card-configs/{config}/edit', [\App\Http\Controllers\Admin\AccessCardConfigController::class, 'edit'])
            ->name('access-card-configs.edit');

        Route::put('/access-card-configs/{config}', [\App\Http\Controllers\Admin\AccessCardConfigController::class, 'update'])
            ->name('access-card-configs.update');

        Route::delete('/access-card-configs/{config}', [\App\Http\Controllers\Admin\AccessCardConfigController::class, 'destroy'])
            ->name('access-card-configs.destroy');
    });

    Route::get('/accreditation-mapping', [\App\Http\Controllers\Admin\AccreditationMappingController::class, 'index'])
        ->name('accreditation-mapping.index');

    Route::get('/accreditation-mapping/create', [\App\Http\Controllers\Admin\AccreditationMappingController::class, 'create'])
        ->name('accreditation-mapping.create');

    Route::post('/accreditation-mapping', [\App\Http\Controllers\Admin\AccreditationMappingController::class, 'store'])
        ->name('accreditation-mapping.store');

    // lanjut step berikutnya (edit mapping job categories)
    Route::get('/accreditation-mapping/{mapping}/edit', [\App\Http\Controllers\Admin\AccreditationMappingController::class, 'edit'])
        ->name('accreditation-mapping.edit');

    Route::put('/accreditation-mapping/{mapping}', [\App\Http\Controllers\Admin\AccreditationMappingController::class, 'update'])
        ->name('accreditation-mapping.update');

    Route::resource('card-configs', AccessCardConfigController::class)
    ->parameters(['card-configs' => 'config'])
    ->except(['show']);

    //Ini yang berhubungan sama card yeah
    Route::get('/cards/{card}/access', [CardAccessController::class, 'edit'])->name('cards.access.edit');
    Route::post('/cards/{card}/access', [CardAccessController::class, 'update'])->name('cards.access.update');
    Route::get('/cards', [CardController::class, 'index'])->name('cards.index');
    Route::post('/cards/issue-batch', [CardIssueController::class, 'issueBatch'])->name('cards.issueBatch');
    Route::post('/cards/{card}/issue', [CardIssueController::class, 'issue'])->name('cards.issue');

    //PrintCard Disini Yeahhhh
    Route::get('/cards/{card}/preview', [CardPrintController::class, 'preview'])->name('cards.preview');
    Route::get('/cards/preview-all', [CardPrintController::class, 'previewAll'])->name('cards.previewAll');

    //print html card cenah
        Route::post('/cards/print-html/batch', [CardPrintController::class, 'printHtmlBatch'])
        ->name('cards.print.html.batch');

        Route::get('/cards/{card}/print-html', [CardPrintController::class, 'printHtmlSingle'])
        ->name('cards.print.html.single');

    Route::get('/icon-svg-inline/{key}', function (string $key) {
        $svg = icon_svg_inline($key);
        abort_if(!$svg, 404);

        // Pastikan ada xmlns biar browser ga rewel
        if (!str_contains($svg, 'xmlns=')) {
            $svg = preg_replace('/<svg\b/', '<svg xmlns="http://www.w3.org/2000/svg"', $svg, 1);
        }

        // Paksa ukuran biar rapi di preview
        $svg = preg_replace('/<svg\b/', '<svg width="16" height="16"', $svg, 1);

        // balikin HTML (inline svg)
        return response($svg)->header('Content-Type', 'text/html; charset=UTF-8');
    })->name('icon-svg-inline');

    // Event Settings (Form untuk edit event milik admin via session)
    Route::get('/event/settings', [\App\Http\Controllers\Admin\EventSettingsController::class, 'edit'])
        ->name('event.settings.edit');
    
    Route::post('/event/settings', [\App\Http\Controllers\Admin\EventSettingsController::class, 'update'])
        ->name('event.settings.update');
    
    Route::post('/event/settings/logo/remove', [\App\Http\Controllers\Admin\EventSettingsController::class, 'removeLogo'])
        ->name('event.settings.logo.remove');
    
    Route::post('/event/settings/template/remove', [\App\Http\Controllers\Admin\EventSettingsController::class, 'removeTemplate'])
        ->name('event.settings.template.remove');

    // Card Layout Builder (Canvas + Drag & Drop)
    Route::get('/card-layouts/builder', [\App\Http\Controllers\Admin\CardLayoutController::class, 'builder'])
        ->name('card-layouts.builder');
    
    Route::get('/card-layouts/active', [\App\Http\Controllers\Admin\CardLayoutController::class, 'getActive'])
        ->name('card-layouts.active');
    
    Route::post('/card-layouts/save', [\App\Http\Controllers\Admin\CardLayoutController::class, 'save'])
        ->name('card-layouts.save');
    
    Route::post('/card-layouts/reset-default', [\App\Http\Controllers\Admin\CardLayoutController::class, 'resetDefault'])
        ->name('card-layouts.reset-default');
    
    Route::get('/card-layouts/preview-sample', [\App\Http\Controllers\Admin\CardLayoutController::class, 'previewSample'])
        ->name('card-layouts.preview-sample');
});

// Prevent customer users from accessing admin routes directly
Route::middleware(['web'])->group(function () {
    Route::get('/admin/{any}', function () {
        if (session('customer_authenticated')) {
            return redirect('/jobs')->with('error', 'Access denied. Admin area restricted.');
        }
        if (session('super_admin_authenticated')) {
            return redirect('/super-admin/dashboard')->with('error', 'Please use super admin portal.');
        }
        return redirect('/admin/login')->with('error', 'Please login to access admin area.');
    })->where('any', '.*');
});

// Storage file serving route (workaround for symlink permission issues)
Route::get('/storage/{path}', function ($path) {
    $file = storage_path('app/public/' . $path);

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
