<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\WorkerOpening;
use App\Models\Application;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $eventId = session('admin_event_id');

        if ($eventId) {
            // Admin ditugaskan ke 1 event → tampilkan data event tersebut saja
            $event = Event::with('city')->findOrFail($eventId);

            $totalEvents = 1;
            $activeEvents = in_array($event->status, ['upcoming', 'active']) ? 1 : 0;
            $totalCandidates = Application::whereHas('opening', function ($q) use ($eventId) {
                $q->where('event_id', $eventId);
            })->distinct('user_id')->count('user_id');
            $openJobs = WorkerOpening::where('event_id', $eventId)->where('status', 'open')->count();

            $recentApplications = Application::with(['user', 'opening.event.city'])
                ->whereHas('opening', function ($q) use ($eventId) {
                    $q->where('event_id', $eventId);
                })
                ->latest()
                ->take(5)
                ->get();

            $upcomingEvents = collect([$event]);
        } else {
            // Fallback: tanpa event assignment (tidak seharusnya terjadi)
            $totalEvents = Event::count();
            $activeEvents = Event::where('status', 'upcoming')->orWhere('status', 'active')->count();
            $totalCandidates = User::where('role', 'customer')->count();
            $openJobs = WorkerOpening::where('status', 'open')->count();

            $recentApplications = Application::with(['user', 'opening.event.city'])
                ->latest()
                ->take(5)
                ->get();

            $upcomingEvents = Event::with('city')
                ->where('status', 'upcoming')
                ->orderBy('start_at', 'asc')
                ->take(5)
                ->get();
        }

        return view('menu.dashboard.dashboard', compact(
            'totalEvents',
            'activeEvents',
            'totalCandidates',
            'openJobs',
            'recentApplications',
            'upcomingEvents'
        ));
    }
}
