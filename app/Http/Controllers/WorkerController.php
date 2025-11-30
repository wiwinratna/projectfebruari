<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Event;
use App\Models\JobCategory;
use App\Models\WorkerOpening;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index()
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        $openings = WorkerOpening::with(['event', 'jobCategory'])
            ->withCount('applications')
            ->orderByDesc('status')
            ->orderBy('shift_start')
            ->get();

        $stats = [
            'total_openings' => $openings->count(),
            'active_openings' => $openings->where('status', 'open')->count(),
            'total_applications' => Application::count(),
            'positions_filled' => $openings->sum('slots_filled'),
        ];

        $categories = JobCategory::orderBy('name')->get();
        $events = Event::orderBy('start_at')->get(['id', 'title', 'venue']);

        return view('menu.workers.index', [
            'openings' => $openings,
            'stats' => $stats,
            'categories' => $categories,
            'events' => $events,
        ]);
    }

    public function create()
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        $categories = JobCategory::orderBy('name')->get();
        $events = Event::orderBy('start_at')->get(['id', 'title', 'venue']);
        $opening = new WorkerOpening(['status' => 'planned', 'slots_filled' => 0]);

        return view('menu.workers.create', [
            'opening' => $opening,
            'categories' => $categories,
            'events' => $events,
        ]);
    }

    public function store(Request $request)
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'job_category_id' => 'required|exists:job_categories,id',
            'event_id' => 'required|exists:events,id',
            'description' => 'nullable|string',
            'shift_start' => 'required|date',
            'shift_end' => 'nullable|date|after_or_equal:shift_start',
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
            'shift_start' => $validated['shift_start'],
            'shift_end' => $validated['shift_end'],
            'slots_total' => $validated['slots_total'],
            'slots_filled' => $validated['slots_filled'] ?? 0,
            'status' => $validated['status'],
            'requirements' => $requirements,
            'benefits' => $validated['benefits'],
        ]);

        return redirect()->route('workers.index', ['flash' => 'created', 'name' => $opening->title]);
    }

    public function edit(WorkerOpening $opening)
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        $categories = JobCategory::orderBy('name')->get();

        return view('menu.workers.edit', [
            'opening' => $opening,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, WorkerOpening $opening)
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'job_category_id' => 'required|exists:job_categories,id',
            'description' => 'nullable|string',
            'shift_start' => 'required|date',
            'shift_end' => 'nullable|date|after_or_equal:shift_start',
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

        $opening->update([
            'title' => $validated['title'],
            'job_category_id' => $validated['job_category_id'],
            'description' => $validated['description'],
            'shift_start' => $validated['shift_start'],
            'shift_end' => $validated['shift_end'],
            'slots_total' => $validated['slots_total'],
            'slots_filled' => $validated['slots_filled'] ?? 0,
            'status' => $validated['status'],
            'requirements' => $requirements,
            'benefits' => $validated['benefits'],
        ]);

        return redirect()->route('workers.index', ['flash' => 'updated', 'name' => $opening->title]);
    }
}