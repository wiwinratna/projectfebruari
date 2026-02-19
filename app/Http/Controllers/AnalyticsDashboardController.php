<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Event;
use App\Models\WorkerOpening;
use App\Models\JobCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsDashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Get admin's assigned event
        $adminEventId = session('admin_event_id');
        if (!$adminEventId) {
            return back()->withErrors(['message' => 'Akun admin belum ditugaskan ke event manapun. Hubungi super admin.']);
        }

        // Only show admin's assigned event
        $adminEvent = Event::with('workerOpenings.jobCategory')->findOrFail($adminEventId);
        $allEvents = collect([$adminEvent]);

        // Use admin's assigned event
        $selectedEventId = $adminEventId;
        $selectedEvent = $adminEvent;

        $eventAnalytics = [];
        $topPositions = [];

        if ($selectedEventId && $selectedEvent) {
            // 1. Applications by Category (Pie Chart)
            $applicationsByCategory = DB::table('applications')
                ->join('worker_openings', 'applications.worker_opening_id', '=', 'worker_openings.id')
                ->join('job_categories', 'worker_openings.job_category_id', '=', 'job_categories.id')
                ->where('worker_openings.event_id', $selectedEventId)
                ->select('job_categories.name as category_name', DB::raw('COUNT(*) as application_count'))
                ->groupBy('job_categories.name')
                ->orderByDesc('application_count')
                ->get();

            // 2. Top Applied Positions (New Requirement: Specific Jobs)
            $topPositions = DB::table('applications')
                ->join('worker_openings', 'applications.worker_opening_id', '=', 'worker_openings.id')
                ->where('worker_openings.event_id', $selectedEventId)
                ->select('worker_openings.title as job_title', DB::raw('COUNT(*) as application_count'))
                ->groupBy('worker_openings.title')
                ->orderByDesc('application_count')
                ->limit(5)
                ->get();

            // 3. Daily Application Trend
            $dailyTrends = DB::table('applications')
                ->join('worker_openings', 'applications.worker_opening_id', '=', 'worker_openings.id')
                ->where('worker_openings.event_id', $selectedEventId)
                ->select(DB::raw('DATE_FORMAT(applications.created_at, "%Y-%m-%d") as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();

            // Prepare Data Structure
            $eventAnalytics = [
                'total_applications' => $selectedEvent->applications()->count(),
                'total_slots' => $selectedEvent->workerOpenings->sum('slots_total'),
                'total_filled' => $selectedEvent->workerOpenings->sum('slots_filled'),
                'categories' => $applicationsByCategory,
                'top_positions' => $topPositions,
                'daily_trends' => $dailyTrends
            ];
        }

        return view('menu.dashboard.analytics', [
            'allEvents' => $allEvents,
            'selectedEvent' => $selectedEvent,
            'eventAnalytics' => $eventAnalytics
        ]);
    }
}
