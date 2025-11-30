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
    public function index()
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        // Get all events with their applications and worker openings
        $events = Event::with([
            'workerOpenings' => function ($query) {
                $query->with(['jobCategory', 'applications']);
            },
            'applications' => function ($query) {
                $query->with('user');
            }
        ])->get();

        // Get application statistics by category for each event
        $eventAnalytics = [];
        foreach ($events as $event) {
            $categoryStats = [];
            $totalApplications = $event->applications->count();
            
            // Get applications by category for this event
            $applicationsByCategory = DB::table('applications')
                ->join('worker_openings', 'applications.worker_opening_id', '=', 'worker_openings.id')
                ->join('job_categories', 'worker_openings.job_category_id', '=', 'job_categories.id')
                ->where('worker_openings.event_id', $event->id)
                ->select('job_categories.name as category_name', DB::raw('COUNT(*) as application_count'))
                ->groupBy('job_categories.name')
                ->orderByDesc('application_count')
                ->get();

            // Get worker opening slots by category
            $slotsByCategory = DB::table('worker_openings')
                ->join('job_categories', 'worker_openings.job_category_id', '=', 'job_categories.id')
                ->where('worker_openings.event_id', $event->id)
                ->select('job_categories.name as category_name', 'worker_openings.slots_total', 'worker_openings.slots_filled')
                ->get();

            // Merge the data
            foreach ($applicationsByCategory as $appStat) {
                $slotsData = $slotsByCategory->firstWhere('category_name', $appStat->category_name);
                $categoryStats[] = [
                    'category_name' => $appStat->category_name,
                    'applications' => $appStat->application_count,
                    'slots_total' => $slotsData->slots_total ?? 0,
                    'slots_filled' => $slotsData->slots_filled ?? 0,
                    'utilization_rate' => $slotsData->slots_total > 0 ? round(($slotsData->slots_filled / $slotsData->slots_total) * 100, 1) : 0
                ];
            }

            // Sort by application count descending
            usort($categoryStats, function($a, $b) {
                return $b['applications'] - $a['applications'];
            });

            $eventAnalytics[] = [
                'event' => $event,
                'category_stats' => $categoryStats,
                'total_applications' => $totalApplications,
                'total_slots' => collect($categoryStats)->sum('slots_total'),
                'total_filled' => collect($categoryStats)->sum('slots_filled')
            ];
        }

        // Get overall statistics
        $overallStats = [
            'total_events' => $events->count(),
            'total_applications' => Application::join('worker_openings', 'applications.worker_opening_id', '=', 'worker_openings.id')->count(),
            'total_slots' => WorkerOpening::sum('slots_total'),
            'total_filled' => WorkerOpening::sum('slots_filled'),
            'average_utilization' => 0
        ];

        if ($overallStats['total_slots'] > 0) {
            $overallStats['average_utilization'] = round(($overallStats['total_filled'] / $overallStats['total_slots']) * 100, 1);
        }

        // Get top performing categories across all events
        $topCategories = DB::table('applications')
            ->join('worker_openings', 'applications.worker_opening_id', '=', 'worker_openings.id')
            ->join('job_categories', 'worker_openings.job_category_id', '=', 'job_categories.id')
            ->select('job_categories.name as category_name', DB::raw('COUNT(*) as total_applications'))
            ->groupBy('job_categories.name')
            ->orderByDesc('total_applications')
            ->limit(10)
            ->get();

        // Get application trends by month
        $monthlyTrends = DB::table('applications')
            ->join('worker_openings', 'applications.worker_opening_id', '=', 'worker_openings.id')
            ->select(DB::raw('DATE_FORMAT(applications.created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as applications'))
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        return view('menu.dashboard.analytics', [
            'eventAnalytics' => $eventAnalytics,
            'overallStats' => $overallStats,
            'topCategories' => $topCategories,
            'monthlyTrends' => $monthlyTrends
        ]);
    }
}