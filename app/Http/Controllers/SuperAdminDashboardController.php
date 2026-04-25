<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\City;
use App\Models\Sport;
use App\Services\EventStatusService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $totalAdmins = User::where('role', 'admin')->count();
        $unassignedAdmins = User::where('role', 'admin')->whereNull('event_id')->count();
        $totalEvents = Event::count();
        $activeEvents = Event::whereIn('status', ['upcoming', 'active'])->count();
        $totalCustomers = User::where('role', 'customer')->count();

        $admins = User::where('role', 'admin')
            ->with('event:id,title')
            ->latest()
            ->paginate(10);

        $events = Event::with(['city:id,name,province'])
            ->latest()
            ->take(5)
            ->get();

        return view('super-admin.dashboard', compact(
            'totalAdmins',
            'unassignedAdmins',
            'totalEvents',
            'activeEvents',
            'totalCustomers',
            'admins',
            'events'
        ));
    }

    public function admins()
    {
        $admins = User::where('role', 'admin')
            ->with('event:id,title')
            ->latest()
            ->paginate(15);

        return view('super-admin.admins.index', compact('admins'));
    }

    public function adminCreate()
    {
        $events = Event::orderBy('title')->get();
        return view('super-admin.admins.create', compact('events'));
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'event_id' => 'required|exists:events,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'admin',
            'event_id' => $validated['event_id'],
        ]);

        return redirect()->route('super-admin.admins.index')
            ->with('status', 'Admin user created successfully!');
    }

    public function adminEdit(User $user)
    {
        abort_unless($user->role === 'admin', 403);

        $events = Event::orderBy('title')->get();
        return view('super-admin.admins.edit', compact('user', 'events'));
    }

    public function adminUpdate(Request $request, User $user)
    {
        abort_unless($user->role === 'admin', 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'event_id' => 'required|exists:events,id',
        ]);

        $user->update($validated);

        return redirect()->route('super-admin.admins.edit', $user)
            ->with('status', 'Admin user updated successfully!');
    }

    public function adminDelete(User $user)
    {
        abort_unless($user->role === 'admin', 403);

        $user->delete();

        return redirect()->route('super-admin.admins.index')
            ->with('status', 'Admin user deleted successfully!');
    }

    public function events()
    {
        $events = Event::with(['city:id,name,province'])
            ->latest()
            ->paginate(15);

        return view('super-admin.events.index', compact('events'));
    }

    public function eventView(Event $event)
    {
        $event->load([
            'city:id,name,province',
            'admins' => function ($q) {
                $q->where('role', 'admin');
            },
            'sports:id,name,code',
            'venueLocations:id,event_id,nama',
            'jabatan:id,event_id,nama_jabatan',
            'disciplins:id,event_id,nama_disiplin',
            'accreditations:id,event_id,nama_akreditasi',
            'accommodationCodes:id,event_id,kode',
            'transportationCodes:id,event_id,kode',
            'zoneAccessCodes:id,event_id,kode_zona',
            'venueAccesses:id,event_id,nama_vanue',
            'accessCodes:id,event_id,code',
        ]);

        // Get statistics for this event
        $totalWorkerOpenings = \App\Models\WorkerOpening::where('event_id', $event->id)->count();
        $totalApplications = \App\Models\Application::whereHas('opening', function ($q) use ($event) {
            $q->where('event_id', $event->id);
        })->count();
        $acceptedApplications = \App\Models\Application::whereHas('opening', function ($q) use ($event) {
            $q->where('event_id', $event->id);
        })->where('status', 'accepted')->count();
        $rejectedApplications = \App\Models\Application::whereHas('opening', function ($q) use ($event) {
            $q->where('event_id', $event->id);
        })->where('status', 'rejected')->count();

        // Get recent applications for this event
        $recentApplications = \App\Models\Application::with(['user', 'opening'])
            ->whereHas('opening', function ($q) use ($event) {
                $q->where('event_id', $event->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('super-admin.events.show', compact(
            'event',
            'totalWorkerOpenings',
            'totalApplications',
            'acceptedApplications',
            'rejectedApplications',
            'recentApplications'
        ));
    }

    public function profile()
    {
        $user = User::findOrFail(session('super_admin_id'));
        return view('super-admin.profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(session('super_admin_id'));

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('status', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $user = User::findOrFail(session('super_admin_id'));

        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => bcrypt($validated['password']),
        ]);

        return back()->with('status', 'Password updated successfully!');
    }

    // Event Management
    public function eventCreate()
    {
        $event = new Event([
            'status' => 'planning',
            'stage' => 'province',
        ]);

        return view('super-admin.events.create', [
            'event' => $event,
            'statuses' => ['planning', 'upcoming', 'active', 'completed'],
            'stages' => ['province', 'national', 'asean/sea', 'asia', 'world'],
            'cities' => City::active()->orderBy('province')->orderBy('name')->get(),
            'sports' => Sport::orderBy('name')->get(),
        ]);
    }

    public function eventStore(Request $request)
    {
        $data = $this->validateEventData($request);
        $sports = $data['sports'] ?? [];
        unset($data['sports']);

        $event = Event::create($data);

        // Handle access codes
        $codes = collect($request->input('access_codes', []))
            ->filter(fn($r) => !empty(trim($r['code'] ?? '')))
            ->map(fn($r) => [
                'code' => strtoupper(trim($r['code'])),
                'label' => trim($r['label'] ?? ''),
                'color_hex' => $r['color_hex'] ?? '#EF4444',
            ])
            ->values()
            ->all();

        $event->accessCodes()->createMany($codes);

        // Update status based on dates
        EventStatusService::updateStatus($event);

        // Attach sports
        if (!empty($sports)) {
            $event->sports()->sync($sports);
        }

        return redirect()->route('super-admin.events.index')->with('success', 'Event created successfully!');
    }

    public function eventEdit(Event $event)
    {
        return view('super-admin.events.edit', [
            'event' => $event,
            'statuses' => ['planning', 'upcoming', 'active', 'completed'],
            'stages' => ['province', 'national', 'asean/sea', 'asia', 'world'],
            'cities' => City::active()->orderBy('province')->orderBy('name')->get(),
            'sports' => Sport::orderBy('name')->get(),
        ]);
    }

    public function eventUpdate(Request $request, Event $event)
    {
        $data = $this->validateEventData($request);
        $sports = $data['sports'] ?? [];
        unset($data['sports']);

        $event->update($data);

        // Handle access codes
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

        // Update status based on dates
        EventStatusService::updateStatus($event);

        // Update sports
        if (!empty($sports)) {
            $event->sports()->sync($sports);
        } else {
            $event->sports()->detach();
        }

        return redirect()->route('super-admin.events.index')->with('success', 'Event updated successfully!');
    }

    public function eventDelete(Event $event)
    {
        $event->delete();
        return redirect()->route('super-admin.events.index')->with('success', 'Event deleted successfully!');
    }

    // ── Volunteer Management ──────────────────────────────────────────────

    public function volunteers(Request $request)
    {
        $query = User::where('role', 'customer')
            ->with(['profile', 'applications.opening.event']);

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Profile completion filter
        if ($filter = $request->input('filter')) {
            if ($filter === 'complete') {
                $query->whereHas('profile', function ($q) {
                    $q->whereNotNull('phone')
                      ->whereNotNull('summary')
                      ->whereNotNull('cv_file');
                });
            } elseif ($filter === 'incomplete') {
                $query->where(function ($q) {
                    $q->whereDoesntHave('profile')
                      ->orWhereHas('profile', function ($q2) {
                          $q2->where(function ($q3) {
                              $q3->whereNull('phone')
                                 ->orWhereNull('summary')
                                 ->orWhereNull('cv_file');
                          });
                      });
                });
            }
        }

        // Sort
        $sortBy = $request->input('sort', 'latest');
        if ($sortBy === 'name') {
            $query->orderBy('name');
        } elseif ($sortBy === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $volunteers = $query->paginate(15)->withQueryString();

        // Stats
        $totalVolunteers = User::where('role', 'customer')->count();
        $withProfile = User::where('role', 'customer')
            ->whereHas('profile', function ($q) {
                $q->whereNotNull('phone')->whereNotNull('summary');
            })->count();
        $withApplications = User::where('role', 'customer')
            ->has('applications')->count();
        $totalApplications = \App\Models\Application::whereHas('user', fn($q) => $q->where('role', 'customer'))->count();

        // ── Chart Data ───────────────────────────────────────────────────

        // 1. Registration trend (last 6 months)
        $registrationTrend = collect(range(5, 0))->map(function ($i) {
            $date = now()->subMonths($i);
            return [
                'label' => $date->translatedFormat('M Y'),
                'count' => User::where('role', 'customer')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        // 2. Domicile / Address distribution (extract city-ish from address)
        $domicileData = \App\Models\UserProfile::whereHas('user', fn($q) => $q->where('role', 'customer'))
            ->whereNotNull('address')
            ->where('address', '!=', '')
            ->pluck('address')
            ->map(function ($addr) {
                // Take last meaningful segment as city proxy
                $parts = preg_split('/[,\n]/', $addr);
                $city = trim(end($parts));
                return $city ?: 'Unknown';
            })
            ->countBy()
            ->sortDesc()
            ->take(8);

        $noAddress = $totalVolunteers - $domicileData->sum();
        if ($noAddress > 0) {
            $domicileData->put('Belum diisi', $noAddress);
        }

        // 3. Profile completion distribution
        $allCustomers = User::where('role', 'customer')->with('profile')->get();
        $profileDistribution = [
            '0-25%'   => $allCustomers->filter(fn($u) => $u->profile_completion <= 25)->count(),
            '26-50%'  => $allCustomers->filter(fn($u) => $u->profile_completion > 25 && $u->profile_completion <= 50)->count(),
            '51-75%'  => $allCustomers->filter(fn($u) => $u->profile_completion > 50 && $u->profile_completion <= 75)->count(),
            '76-100%' => $allCustomers->filter(fn($u) => $u->profile_completion > 75)->count(),
        ];

        // 4. Application status distribution
        $appStatusData = \App\Models\Application::whereHas('user', fn($q) => $q->where('role', 'customer'))
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // 5. Education level distribution
        $educationData = \App\Models\UserProfile::whereHas('user', fn($q) => $q->where('role', 'customer'))
            ->whereNotNull('last_education')
            ->where('last_education', '!=', '')
            ->selectRaw('last_education, COUNT(*) as total')
            ->groupBy('last_education')
            ->pluck('total', 'last_education');

        // 6. Applications per event
        $perEventData = Event::select('id', 'title')
            ->withCount(['workerOpenings as applications_count' => function ($q) {
                // count applications through worker_openings
            }])
            ->get()
            ->map(function ($event) {
                $total = \App\Models\Application::whereHas('opening', fn($q) => $q->where('event_id', $event->id))->count();
                $accepted = \App\Models\Application::whereHas('opening', fn($q) => $q->where('event_id', $event->id))->where('status', 'accepted')->count();
                $pending = \App\Models\Application::whereHas('opening', fn($q) => $q->where('event_id', $event->id))->where('status', 'pending')->count();
                $rejected = \App\Models\Application::whereHas('opening', fn($q) => $q->where('event_id', $event->id))->where('status', 'rejected')->count();
                return [
                    'title' => Str::limit($event->title, 20),
                    'total' => $total,
                    'accepted' => $accepted,
                    'pending' => $pending,
                    'rejected' => $rejected,
                ];
            })
            ->filter(fn($e) => $e['total'] > 0)
            ->values();

        // Accepted rate
        $acceptedRate = $totalApplications > 0
            ? round(($appStatusData['accepted'] ?? 0) / $totalApplications * 100, 1)
            : 0;

        return view('super-admin.volunteers.index', compact(
            'volunteers', 'totalVolunteers', 'withProfile', 'withApplications', 'totalApplications',
            'registrationTrend', 'domicileData', 'profileDistribution', 'appStatusData', 'educationData',
            'perEventData', 'acceptedRate'
        ));
    }

    public function volunteerShow(User $user)
    {
        abort_unless($user->role === 'customer', 404);

        $user->load([
            'profile',
            'certificates',
            'applications' => function ($q) {
                $q->with(['opening.event', 'opening.jobCategory', 'reviewer'])
                  ->latest();
            },
        ]);

        return view('super-admin.volunteers.show', compact('user'));
    }

    public function volunteerEdit(User $user)
    {
        abort_unless($user->role === 'customer', 404);
        $user->load('profile');

        return view('super-admin.volunteers.edit', compact('user'));
    }

    public function volunteerUpdate(Request $request, User $user)
    {
        abort_unless($user->role === 'customer', 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('super-admin.volunteers.show', $user)
            ->with('status', 'Volunteer data updated successfully!');
    }

    public function volunteerDelete(User $user)
    {
        abort_unless($user->role === 'customer', 404);

        // Delete profile and related data
        if ($user->profile) {
            $user->profile->delete();
        }
        $user->applications()->delete();
        $user->certificates()->delete();
        $user->delete();

        return redirect()->route('super-admin.volunteers.index')
            ->with('status', 'Volunteer deleted successfully!');
    }

    public function volunteerResetPassword(Request $request, User $user)
    {
        abort_unless($user->role === 'customer', 404);

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => bcrypt($validated['password']),
        ]);

        return back()->with('status', 'Password reset successfully!');
    }

    // ── Event Data Validation ────────────────────────────────────────────

    private function validateEventData(Request $request, ?Event $event = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'venue' => ['nullable', 'string', 'max:255'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'status' => ['required', Rule::in(['planning', 'upcoming', 'active', 'completed'])],
            'stage' => ['required', Rule::in(['province', 'national', 'asean/sea', 'asia', 'world'])],
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
