<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sport;
use App\Models\City;
use App\Services\EventStatusService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    private const STATUS_OPTIONS = ['planning', 'upcoming', 'active', 'completed'];
    private const STAGE_OPTIONS = ['province', 'national', 'asean/sea', 'asia', 'world'];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Get admin's assigned event
        $adminEventId = session('admin_event_id');

        // If admin nicht has assigned event, redirect to login
        if (!$adminEventId) {
            return back()->withErrors(['message' => 'Akun admin belum ditugaskan ke event manapun. Hubungi super admin.']);
        }

        // Automatically update all event statuses based on current date
        EventStatusService::updateAllStatuses();

        $searchQuery = $request->get('search');
        $statusFilter = $request->get('status');
        $showCompleted = $request->get('show_completed', false);

        // Set default status filter
        // If there's a search query, default to 'all' to search across all statuses
        if (empty($statusFilter) && !$showCompleted) {
            if (!empty($searchQuery)) {
                $statusFilter = 'all';
            } else {
                $statusFilter = 'active';
            }
        }

        // ONLY fetch the admin's assigned event
        $query = Event::where('id', $adminEventId)
            ->with([
                'sports',
                'city',
                'workerOpenings' => function ($query) {
                    $query->select('id', 'event_id', 'status', 'slots_total', 'slots_filled');
                },
            ])
            ->withCount([
                'workerOpenings',
                'applications',
            ])
            ->withSum('workerOpenings as slots_total_sum', 'slots_total');

        // Apply status filter if provided
        if (!empty($statusFilter) && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        } elseif (!$showCompleted && $statusFilter !== 'all') {
            // By default, exclude completed events unless explicitly requested
            $query->where('status', '!=', 'completed');
        }

        // Apply search filter if provided (though admin only has 1 event)
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', '%' . $searchQuery . '%')
                  ->orWhere('penyelenggara', 'like', '%' . $searchQuery . '%')
                  ->orWhere('venue', 'like', '%' . $searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $searchQuery . '%')
                  ->orWhereHas('city', function ($cityQuery) use ($searchQuery) {
                      $cityQuery->where('name', 'like', '%' . $searchQuery . '%')
                               ->orWhere('province', 'like', '%' . $searchQuery . '%');
                  })
                  ->orWhereHas('sports', function ($sportQuery) use ($searchQuery) {
                      $sportQuery->where('name', 'like', '%' . $sportQuery . '%')
                               ->orWhere('code', 'like', '%' . $searchQuery . '%');
                  });
            });
        }

        $events = $query->orderBy('start_at')->get();

        // Get ONLY the admin's event for stats
        $adminEvent = Event::with([
            'sports',
            'city',
            'workerOpenings' => function ($query) {
                $query->select('id', 'event_id', 'status', 'slots_total', 'slots_filled');
            },
        ])
            ->withCount([
                'workerOpenings',
                'applications',
            ])
            ->withSum('workerOpenings as slots_total_sum', 'slots_total')
            ->where('id', $adminEventId)
            ->first();

        $allEvents = collect([$adminEvent]);

        $stats = [
            'total_events' => 1,
            'active_events' => in_array($adminEvent->status, ['active']) ? 1 : 0,
            'upcoming_events' => in_array($adminEvent->status, ['upcoming']) ? 1 : 0,
            'planning_events' => in_array($adminEvent->status, ['planning']) ? 1 : 0,
            'completed_events' => in_array($adminEvent->status, ['completed']) ? 1 : 0,
            'worker_openings' => $adminEvent->worker_openings_count ?? 0,
            'total_applications' => $adminEvent->applications_count ?? 0,
        ];

        $calendarMonth = now()->copy();
        $calendarDays = collect(range(1, $calendarMonth->daysInMonth()));

        // Prepare event data untuk calendar - ONLY the admin's event
        $eventsByDate = [];
        $allEventsForCalendar = Event::where('id', $adminEventId)
            ->with(['sports', 'city'])
            ->get();

        foreach ($allEventsForCalendar as $event) {
            if ($event->start_at) {
                $dateKey = $event->start_at->format('Y-m-d');
                if (!isset($eventsByDate[$dateKey])) {
                    $eventsByDate[$dateKey] = [];
                }
                $eventsByDate[$dateKey][] = [
                    'id' => $event->id,
                    'title' => $event->title,
                    'status' => $event->status,
                    'time' => $event->start_at->format('H:i'),
                ];
            }
        }

        return view('menu.events.index', [
            'events' => $events,
            'stats' => $stats,
            'calendarMonth' => $calendarMonth,
            'calendarDays' => $calendarDays,
            'eventsByDate' => $eventsByDate,
            'searchQuery' => $searchQuery,
            'statusFilter' => $statusFilter,
            'showCompleted' => $showCompleted,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Only super admin can create events, not regular admin
        return redirect('/admin/events')->withErrors(['message' => 'Only super admin can create events. Contact super admin to create a new event.']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Only super admin can create events, not regular admin
        return redirect('/admin/events')->withErrors(['message' => 'Only super admin can create events. Contact super admin to create a new event.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Check if admin is allowed to view this event
        $adminEventId = session('admin_event_id');
        if ($adminEventId && $event->id !== $adminEventId) {
            return back()->withErrors(['message' => 'You are not authorized to view this event.']);
        }
        $event->load([
            'sports',
            'city',
            'workerOpenings' => function ($query) {
                $query->with('jobCategory');
            },
            'applications' => function ($query) {
                $query->with('opening');
            }
        ]);

        return view('menu.events.show', [
            'event' => $event,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $event->load('accessCodes');

        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Check if admin is allowed to edit this event
        $adminEventId = session('admin_event_id');
        if ($adminEventId && $event->id !== $adminEventId) {
            return back()->withErrors(['message' => 'You are not authorized to edit this event.']);
        }

        return view('menu.events.edit', [
            'event' => $event,
            'statuses' => self::STATUS_OPTIONS,
            'stages' => self::STAGE_OPTIONS,
            'cities' => City::active()->orderBy('province')->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Check if admin is allowed to update this event
        $adminEventId = session('admin_event_id');
        if ($adminEventId && $event->id !== $adminEventId) {
            return back()->withErrors(['message' => 'You are not authorized to update this event.']);
        }

        $data = $this->validatedEventData($request, $event);
        $sports = $data['sports'] ?? [];
        unset($data['sports']);

        $event->update($data);
        $codes = collect($request->input('access_codes', []))
            ->filter(fn($r) => !empty(trim($r['code'] ?? '')))
            ->map(fn($r) => [
                'code' => strtoupper(trim($r['code'])),
                'label' => trim($r['label'] ?? ''),
                'color_hex' => $r['color_hex'] ?? '#EF4444',
            ])
            ->values()
            ->all();

        $event->accessCodes()->delete();
        $event->accessCodes()->createMany($codes);


        // Always sync sports (if array is empty, it removes all sports, which is correct behavior for checkboxes)
        $event->sports()->sync($sports);

        // Automatically calculate and update status based on updated dates
        EventStatusService::updateStatus($event);

        return redirect()->route('admin.events.index', ['flash' => 'updated', 'name' => $event->title]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if (!session('admin_authenticated')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Check if admin is allowed to delete this event
        $adminEventId = session('admin_event_id');
        if ($adminEventId && $event->id !== $adminEventId) {
            return response()->json(['success' => false, 'message' => 'You are not authorized to delete this event.'], 403);
        }

        // Check if event has related data that prevents deletion
        $applicationsCount = $event->applications()->count();
        $workerOpeningsCount = $event->workerOpenings()->count();

        if ($applicationsCount > 0 || $workerOpeningsCount > 0) {
            $issues = [];
            if ($applicationsCount > 0) {
                $issues[] = $applicationsCount . ' applications';
            }
            if ($workerOpeningsCount > 0) {
                $issues[] = $workerOpeningsCount . ' worker openings';
            }

            return response()->json([
                'success' => false,
                'message' => 'Cannot delete event that has ' . implode(' and ', $issues)
            ], 422);
        }

        $eventName = $event->title;
        $event->delete();

        return response()->json(['success' => true, 'message' => 'Event "' . $eventName . '" deleted successfully']);
    }

    /**
     * API: Get active events (for React frontend)
     */
    public function apiIndex()
    {
        $events = Event::active()
            ->with('city')
            ->withCount('workerOpenings')
            ->orderBy('start_at')
            ->take(6)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start_at' => $event->start_at?->toIso8601String(),
                    'end_at' => $event->end_at?->toIso8601String(),
                    'venue' => $event->venue,
                    'city' => $event->city?->name,
                    'status' => $event->status,
                    'stage' => $event->stage,
                    'worker_openings_count' => $event->worker_openings_count ?? 0,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $events,
            'total' => count($events),
        ]);
    }

    private function validatedEventData(Request $request, ?Event $event = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'venue' => ['nullable', 'string', 'max:255'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'status' => ['required', Rule::in(self::STATUS_OPTIONS)],
            'stage' => ['required', Rule::in(self::STAGE_OPTIONS)],
            'penyelenggara' => ['required', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'sports' => ['nullable', 'array'],
            'sports.*' => ['exists:sports,id'],
            'access_codes' => ['nullable','array'],
            'access_codes.*.code' => ['nullable','string','max:50'],
            'access_codes.*.label' => ['nullable','string','max:255'],
            'access_codes.*.color_hex' => ['nullable','string','max:20'],
        ]);

        $data = collect($validated)
            ->only([
                'title',
                'description',
                'start_at',
                'end_at',
                'venue',
                'city_id',
                'status',
                'stage',
                'penyelenggara',
                'instagram',
                'email',
                'sports',
            ])->toArray();

        $data['start_at'] = Carbon::parse($data['start_at']);
        $data['end_at'] = isset($data['end_at']) ? Carbon::parse($data['end_at']) : null;

        return $data;
    }
}
