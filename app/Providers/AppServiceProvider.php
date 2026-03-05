<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');
            $key = sha1($email . '|' . $request->ip());

            return Limit::perMinute(10)->by($key);
        });

        View::composer(['components.sidebar', 'components.header'], function ($view) {
            $adminPendingApplicationsCount = 0;
            $customerUnreadNotificationsCount = 0;

            if (session('admin_authenticated') && session('admin_event_id')) {
                $adminEventId = (int) session('admin_event_id');
                $adminPendingApplicationsCount = Application::query()
                    ->where('status', 'pending')
                    ->whereHas('opening', function ($query) use ($adminEventId) {
                        $query->where('event_id', $adminEventId);
                    })
                    ->count();
            }

            if (session('customer_authenticated') && session('customer_id') && Schema::hasTable('notifications')) {
                $customer = User::query()->find(session('customer_id'));
                if ($customer) {
                    $customerUnreadNotificationsCount = $customer->unreadNotifications()->count();
                }
            }

            $view->with('adminPendingApplicationsCount', $adminPendingApplicationsCount);
            $view->with('customerUnreadNotificationsCount', $customerUnreadNotificationsCount);
        });
    }
}
