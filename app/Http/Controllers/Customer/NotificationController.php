<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $customerId = session('customer_id');
        if (!$customerId) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($customerId);
        if (!Schema::hasTable('notifications')) {
            $notifications = new LengthAwarePaginator([], 0, 20, 1, [
                'path' => url()->current(),
                'query' => request()->query(),
            ]);
            return view('menu.customer.notifications', compact('notifications'))
                ->with('status', 'Notifications are not ready yet. Run migrations first.');
        }

        $notifications = $user->notifications()
            ->orderByRaw('read_at IS NULL DESC')
            ->latest()
            ->paginate(20);

        return view('menu.customer.notifications', compact('notifications'));
    }

    public function markRead(Request $request, string $notificationId): RedirectResponse
    {
        $customerId = session('customer_id');
        if (!$customerId) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($customerId);
        if (!Schema::hasTable('notifications')) {
            return back()->with('status', 'Notifications table is not available yet.');
        }
        $notification = $user->notifications()->where('id', $notificationId)->firstOrFail();

        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return back()->with('status', 'Notification marked as read.');
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $customerId = session('customer_id');
        if (!$customerId) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($customerId);
        if (!Schema::hasTable('notifications')) {
            return back()->with('status', 'Notifications table is not available yet.');
        }
        $user->unreadNotifications->markAsRead();

        return back()->with('status', 'All notifications marked as read.');
    }
}
