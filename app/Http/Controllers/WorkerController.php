<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Event;
use App\Models\JobCategory;
use App\Models\WorkerOpening;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        $statusFilter = $request->input('status', 'active');
        $now = now();

        // Get ALL openings for statistics calculation
        $allOpenings = WorkerOpening::with(['event', 'jobCategory'])
            ->withCount('applications')
            ->get();

        // Calculate statistics consistent with new logic
        $openCount = $allOpenings->filter(function ($opening) use ($now) {
            return $opening->status === 'open' && 
                   $opening->application_deadline > $now && 
                   $opening->slots_filled < $opening->slots_total;
        })->count();

        $closedCount = $allOpenings->filter(function ($opening) use ($now) {
            return $opening->status === 'closed' || 
                   ($opening->status === 'open' && $opening->application_deadline <= $now) ||
                   ($opening->status === 'open' && $opening->slots_filled >= $opening->slots_total);
        })->count();

        $stats = [
            'total_openings' => $allOpenings->count(),
            'active_openings' => $openCount,
            'closed_openings' => $closedCount,
            'total_applications' => Application::count(),
            'positions_filled' => $allOpenings->sum('slots_filled'),
        ];

        // Apply filtering for display
        $query = WorkerOpening::with(['event', 'jobCategory'])
            ->withCount('applications');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                  ->orWhereHas('event', function($sq) use ($search) {
                      $sq->where('title', 'like', '%'.$search.'%');
                  });
            });
            
            // When searching, we ignore status filter to show ALL matches
            // But we can conceptually treat it as 'all' for the view
            $statusFilter = 'all'; 
        } else {
            // Only apply status filter if NOT searching
            if ($statusFilter === 'active') {
                $query->where('status', 'open')
                      ->where('application_deadline', '>', $now)
                      ->whereColumn('slots_filled', '<', 'slots_total');
            } elseif ($statusFilter === 'closed') {
                $query->where(function($q) use ($now) {
                    $q->where('status', 'closed')
                      ->orWhere(function($subQ) use ($now) {
                          $subQ->where('status', 'open')
                               ->where('application_deadline', '<=', $now);
                      })
                      ->orWhere(function($subQ) {
                          $subQ->where('status', 'open')
                               ->whereColumn('slots_filled', '>=', 'slots_total');
                      });
                });
            }
        }
        // 'all' shows everything

        $openings = $query->orderByDesc('status')
            ->orderBy('application_deadline')
            ->get();

        $categories = JobCategory::orderBy('name')->get();
        // Show events that are active/upcoming for filter (or all) - keeping simple for now
        $events = Event::orderBy('start_at')->get(['id', 'title', 'venue']);

        return view('menu.workers.index', [
            'openings' => $openings,
            'stats' => $stats,
            'categories' => $categories,
            'events' => $events,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function create()
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        $categories = JobCategory::orderBy('name')->get();
        // Only show events that are not closed for new worker openings
        $events = Event::whereIn('status', ['planning', 'upcoming', 'active'])
                      ->orderBy('start_at')
                      ->get(['id', 'title', 'venue']);
        $opening = new WorkerOpening(['status' => 'planned', 'slots_filled' => 0]);

        return view('menu.workers.create', [
            'opening' => $opening,
            'categories' => $categories,
            'events' => $events,
        ]);
    }

    public function store(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'job_category_id' => 'required|exists:job_categories,id',
            'event_id' => 'required|exists:events,id',
            'description' => 'nullable|string',
            'application_deadline' => 'required|date',
            'slots_total' => 'required|integer|min:1',
            'slots_filled' => 'nullable|integer|min:0',
            'status' => 'required|in:planned,open,closed',
            'requirements_text' => 'nullable|string',
            'benefits' => 'nullable|string',
        ]);

        $requirements = [];
        if (!empty($validated['requirements_text'])) {
            $requirements = array_filter(array_map('trim', explode("\n", $validated['requirements_text'])));
        }

        $opening = WorkerOpening::create([
            'title' => $validated['title'],
            'job_category_id' => $validated['job_category_id'],
            'event_id' => $validated['event_id'],
            'description' => $validated['description'],
            'application_deadline' => $validated['application_deadline'],
            'slots_total' => $validated['slots_total'],
            'slots_filled' => $validated['slots_filled'] ?? 0,
            'status' => $validated['status'],
            'requirements' => $requirements,
            'benefits' => $validated['benefits'],
        ]);

        return redirect()->route('admin.workers.index', ['flash' => 'created', 'name' => $opening->title]);
    }

    public function edit(WorkerOpening $worker)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        $categories = JobCategory::orderBy('name')->get();
        // For editing, show all events (including closed) in case user needs to see current assignment
        $events = Event::orderBy('start_at')->get(['id', 'title', 'venue', 'status']);

        return view('menu.workers.edit', [
            'opening' => $worker,
            'categories' => $categories,
            'events' => $events,
        ]);
    }

    public function update(Request $request, WorkerOpening $worker)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'job_category_id' => 'required|exists:job_categories,id',
            'event_id' => 'required|exists:events,id',
            'description' => 'nullable|string',
            'application_deadline' => 'required|date',
            'slots_total' => 'required|integer|min:1',
            'slots_filled' => 'nullable|integer|min:0',
            'status' => 'required|in:planned,open,closed',
            'requirements_text' => 'nullable|string',
            'benefits' => 'nullable|string',
        ]);

        $requirements = [];
        if (!empty($validated['requirements_text'])) {
            $requirements = array_filter(array_map('trim', explode("\n", $validated['requirements_text'])));
        }

        $worker->update([
            'title' => $validated['title'],
            'job_category_id' => $validated['job_category_id'],
            'event_id' => $validated['event_id'],
            'description' => $validated['description'],
            'application_deadline' => $validated['application_deadline'],
            'slots_total' => $validated['slots_total'],
            'slots_filled' => $validated['slots_filled'] ?? 0,
            'status' => $validated['status'],
            'requirements' => $requirements,
            'benefits' => $validated['benefits'],
        ]);

        return redirect()->route('admin.workers.index', ['flash' => 'updated', 'name' => $worker->title]);
    }

    public function show(WorkerOpening $worker)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        $worker->load(['event', 'jobCategory', 'applications.user.profile']);

        return view('menu.workers.show', [
            'opening' => $worker,
            'applications' => $worker->applications()->latest()->get()
        ]);
    }
}