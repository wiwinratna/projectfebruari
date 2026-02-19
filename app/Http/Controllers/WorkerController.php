<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Event;
use App\Models\JobCategory;
use App\Models\WorkerOpening;
use Illuminate\Http\Request;
use App\Models\AccessCard;

class WorkerController extends Controller
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

        $statusFilter = $request->input('status', 'active');
        $now = now();

        // Get openings ONLY for admin's assigned event
        $allOpenings = WorkerOpening::where('event_id', $adminEventId)
            ->with(['event', 'jobCategory'])
            ->withCount('applications')
            ->get();

        // Calculate statistics for admin's event only
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
            'total_applications' => Application::whereHas('opening', function($q) use ($adminEventId) {
                $q->where('event_id', $adminEventId);
            })->count(),
            'positions_filled' => $allOpenings->sum('slots_filled'),
        ];

        // Apply filtering for display
        $query = WorkerOpening::where('event_id', $adminEventId)
            ->with(['event', 'jobCategory'])
            ->withCount('applications');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                  ->orWhereHas('event', function($sq) use ($search) {
                      $sq->where('title', 'like', '%'.$search.'%');
                  });
            });

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

        $openings = $query->orderByDesc('status')
            ->orderBy('application_deadline')
            ->get();

        $categories = JobCategory::orderBy('name')->get();
        // Only show admin's assigned event
        $event = Event::findOrFail($adminEventId);
        $events = collect([$event]);

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

        // Get admin's assigned event
        $adminEventId = session('admin_event_id');
        if (!$adminEventId) {
            return back()->withErrors(['message' => 'Akun admin belum ditugaskan ke event manapun. Hubungi super admin.']);
        }

        $categories = JobCategory::orderBy('name')->get();
        // Only show admin's assigned event
        $adminEvent = Event::findOrFail($adminEventId);
        $events = collect([$adminEvent]);
        $opening = new WorkerOpening(['status' => 'planned', 'slots_filled' => 0, 'event_id' => $adminEventId]);

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

        // Get admin's assigned event
        $adminEventId = session('admin_event_id');
        if (!$adminEventId) {
            return back()->withErrors(['message' => 'Akun admin belum ditugaskan ke event manapun.']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'job_category_id' => 'required|exists:job_categories,id',
            'description' => 'nullable|string',
            'application_deadline' => 'required|date',
            'slots_total' => 'required|integer|min:1',
            'slots_filled' => 'nullable|integer|min:0',
            'status' => 'required|in:planned,open,closed',
            'requirements_text' => 'nullable|string',
            'benefits' => 'nullable|string',
            'access_code_ids' => ['nullable','array'],
            'access_code_ids.*' => ['exists:event_access_codes,id'],
        ]);

        $requirements = [];
        if (!empty($validated['requirements_text'])) {
            $requirements = array_filter(array_map('trim', explode("\n", $validated['requirements_text'])));
        }

        // Force event_id to admin's assigned event
        $opening = WorkerOpening::create([
            'title' => $validated['title'],
            'job_category_id' => $validated['job_category_id'],
            'event_id' => $adminEventId,
            'description' => $validated['description'],
            'application_deadline' => $validated['application_deadline'],
            'slots_total' => $validated['slots_total'],
            'slots_filled' => $validated['slots_filled'] ?? 0,
            'status' => $validated['status'],
            'requirements' => $requirements,
            'benefits' => $validated['benefits'],
        ]);

        $opening->accessCodes()->sync($request->input('access_code_ids', []));

        return redirect()->route('admin.workers.index', ['flash' => 'created', 'name' => $opening->title]);
    }

    public function edit(WorkerOpening $worker)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Check if opening belongs to admin's assigned event
        $adminEventId = session('admin_event_id');
        if ($worker->event_id !== $adminEventId) {
            return back()->withErrors(['message' => 'You are not authorized to edit this opening.']);
        }

        $worker->load(['accessCodes', 'event.accessCodes']);

        $categories = JobCategory::orderBy('name')->get();
        // Only show admin's assigned event
        $adminEvent = Event::findOrFail($adminEventId);
        $events = collect([$adminEvent]);

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

        // Check if opening belongs to admin's assigned event
        $adminEventId = session('admin_event_id');
        if ($worker->event_id !== $adminEventId) {
            return back()->withErrors(['message' => 'You are not authorized to update this opening.']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'job_category_id' => 'required|exists:job_categories,id',
            'description' => 'nullable|string',
            'application_deadline' => 'required|date',
            'slots_total' => 'required|integer|min:1',
            'slots_filled' => 'nullable|integer|min:0',
            'status' => 'required|in:planned,open,closed',
            'requirements_text' => 'nullable|string',
            'benefits' => 'nullable|string',
            'access_code_ids' => ['nullable','array'],
            'access_code_ids.*' => ['exists:event_access_codes,id'],
        ]);

        $requirements = [];
        if (!empty($validated['requirements_text'])) {
            $requirements = array_filter(array_map('trim', explode("\n", $validated['requirements_text'])));
        }

        $worker->update([
            'title' => $validated['title'],
            'job_category_id' => $validated['job_category_id'],
            'event_id' => $adminEventId,
            'description' => $validated['description'],
            'application_deadline' => $validated['application_deadline'],
            'slots_total' => $validated['slots_total'],
            'slots_filled' => $validated['slots_filled'] ?? 0,
            'status' => $validated['status'],
            'requirements' => $requirements,
            'benefits' => $validated['benefits'],
        ]);
        $worker->accessCodes()->sync($request->input('access_code_ids', []));
        // 🔥 AUTO-SYNC AKSES KARTU (kartu yang sudah dibuat dari opening ini)
        $newAccessIds = $worker->accessCodes()->pluck('event_access_codes.id')->all();

        AccessCard::where('worker_opening_id', $worker->id)
            ->get()
            ->each(function ($card) use ($newAccessIds) {
                $card->accessCodes()->sync($newAccessIds);
            });


        // Auto-update status based on capacity (Enforce rule: Full = Closed)
        if ($worker->slots_filled >= $worker->slots_total && $worker->status === 'open') {
            $worker->update(['status' => 'closed']);
        } elseif ($worker->slots_filled < $worker->slots_total && $worker->status === 'closed') {
            // Only re-open if the deadline hasn't passed
            if ($worker->application_deadline > now()) {
                $worker->update(['status' => 'open']);
            }
        }

        return redirect()->route('admin.workers.index', ['flash' => 'updated', 'name' => $worker->title]);
    }

    public function show(WorkerOpening $worker)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Check if opening belongs to admin's assigned event
        $adminEventId = session('admin_event_id');
        if ($worker->event_id !== $adminEventId) {
            return back()->withErrors(['message' => 'You are not authorized to view this opening.']);
        }

        $worker->load(['event', 'jobCategory', 'applications.user.profile']);

        return view('menu.workers.show', [
            'opening' => $worker,
            'applications' => $worker->applications()->latest()->get()
        ]);
    }
}
