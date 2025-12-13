<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WorkerOpening;
use App\Models\Application;
use App\Models\Worker;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Fetch real statistics from database
        $totalEvents = \App\Models\Event::count();
        $activeEvents = \App\Models\Event::where('status', 'upcoming')->orWhere('status', 'ongoing')->count();
        $totalCandidates = User::where('role', 'customer')->count();
        $openJobs = WorkerOpening::where('status', 'open')->count(); // Replacing Total Budget

        // Fetch Recent Applications (Review Panitia replacement)
        $recentApplications = Application::with(['user', 'opening.event.city'])
            ->latest()
            ->take(5)
            ->get();

        // Fetch Upcoming Events
        $upcomingEvents = \App\Models\Event::with('city')
            ->where('status', 'upcoming')
            ->orderBy('start_at', 'asc')
            ->take(5)
            ->get();

        // Pass data to view
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
