<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    private const STATUS_OPTIONS = ['planning', 'upcoming', 'active', 'completed'];
    private const PRIORITY_OPTIONS = ['high', 'medium', 'low'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        $events = Event::with([
                'sports',
                'workerOpenings' => function ($query) {
                    $query->select('id', 'event_id', 'status', 'slots_total', 'slots_filled');
                },
            ])
            ->withCount([
                'workerOpenings',
                'applications',
            ])
            ->withSum('workerOpenings as slots_total_sum', 'slots_total')
            ->orderBy('start_at')
            ->get();

        $stats = [
            'total_events' => $events->count(),
            'active_events' => $events->where('status', 'active')->count(),
            'upcoming_events' => $events->where('status', 'upcoming')->count(),
            'planning_events' => $events->where('status', 'planning')->count(),
            'worker_openings' => $events->sum('worker_openings_count'),
            'total_applications' => $events->sum('applications_count'),
        ];

        $calendarDays = collect(range(1, now()->daysInMonth()));
        $calendarMonth = now()->translatedFormat('F Y');

        return view('menu.events.index', [
            'events' => $events,
            'stats' => $stats,
            'calendarMonth' => $calendarMonth,
            'calendarDays' => $calendarDays,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        $event = new Event([
            'status' => 'planning',
            'priority' => 'medium',
            'capacity' => 0,
        ]);

        return view('menu.events.create', [
            'event' => $event,
            'statuses' => self::STATUS_OPTIONS,
            'priorities' => self::PRIORITY_OPTIONS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        $data = $this->validatedEventData($request);

        $event = Event::create($data);

        return redirect()->route('events.index', ['flash' => 'created', 'name' => $event->title]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        return view('menu.events.edit', [
            'event' => $event,
            'statuses' => self::STATUS_OPTIONS,
            'priorities' => self::PRIORITY_OPTIONS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        $data = $this->validatedEventData($request, $event);

        $event->update($data);

        return redirect()->route('events.index', ['flash' => 'updated', 'name' => $event->title]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if (!session('authenticated')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
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

    private function validatedEventData(Request $request, ?Event $event = null): array
    {
        $id = $event?->id;

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('events', 'slug')->ignore($id)],
            'description' => ['nullable', 'string'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'venue' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(self::STATUS_OPTIONS)],
            'priority' => ['required', Rule::in(self::PRIORITY_OPTIONS)],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'contact_pic' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
        ]);

        $data = collect($validated)
            ->only([
                'title',
                'slug',
                'description',
                'start_at',
                'end_at',
                'venue',
                'city',
                'status',
                'priority',
                'capacity',
            ])->toArray();

        if (blank($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (blank($data['slug'])) {
            $data['slug'] = Str::random(8);
        }

        $data['start_at'] = Carbon::parse($data['start_at']);
        $data['end_at'] = isset($data['end_at']) ? Carbon::parse($data['end_at']) : null;
        $data['capacity'] = $data['capacity'] ?? 0;

        $contactInfo = array_filter([
            'pic' => $validated['contact_pic'] ?? null,
            'phone' => $validated['contact_phone'] ?? null,
            'email' => $validated['contact_email'] ?? null,
        ], fn ($value) => filled($value));

        $data['contact_info'] = $contactInfo ?: null;

        return $data;
    }
}
