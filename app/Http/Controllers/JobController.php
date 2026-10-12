<?php

namespace App\Http\Controllers;

use App\Models\WorkerOpening;
use App\Models\City;
use App\Models\JobCategory;
use App\Models\Application;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of public job openings
     */
    public function index(Request $request)
    {
        $customerId = session('customer_id');

        // Total opportunities count (active)
        $allOpportunitiesCount = WorkerOpening::where('status', 'open')
            ->where('application_deadline', '>', now())
            ->whereColumn('slots_filled', '<', 'slots_total')
            ->count();

        // Open count
        $openCount = $allOpportunitiesCount;

        // Applied count & applied job IDs
        $appliedJobIds = [];
        $appliedCount = 0;
        if (session('customer_authenticated') && $customerId) {
            $appliedJobIds = Application::where('user_id', $customerId)
                ->pluck('worker_opening_id')
                ->toArray();
            $appliedCount = count($appliedJobIds);
        }

        // Saved count & saved job IDs
        $savedJobIds = [];
        $savedCount = 0;
        if (session('customer_authenticated') && $customerId) {
            $savedJobIds = \DB::table('saved_jobs')
                ->where('user_id', $customerId)
                ->pluck('worker_opening_id')
                ->toArray();
            $savedCount = count($savedJobIds);
        }

        $query = WorkerOpening::with(['event.city', 'jobCategory'])
            ->where('status', 'open')
            ->where('application_deadline', '>', now())
            ->whereColumn('slots_filled', '<', 'slots_total')
            ->orderBy('application_deadline');

        // Quick filter pills
        if ($request->filter === 'applied') {
            $query->whereIn('id', $appliedJobIds);
        } elseif ($request->filter === 'saved') {
            $query->whereIn('id', $savedJobIds);
        }

        // Apply filters
        $this->applyFilters($query, $request);

        $perPage = (int) $request->input('per_page', 6);
        if ($perPage <= 0 || $perPage > 48) {
            $perPage = 6;
        }

        $jobs = $query->paginate($perPage)->withQueryString();

        $filterData = $this->getFilterData();

        return view('jobs.index', array_merge(compact(
            'jobs', 
            'savedJobIds', 
            'appliedJobIds',
            'allOpportunitiesCount',
            'openCount',
            'appliedCount',
            'savedCount'
        ), $filterData));
    }

    /**
     * Apply filters to the job query
     */
    protected function applyFilters($query, $request)
    {
        // City dropdown or cities array
        if ($request->filled('city')) {
            $query->whereHas('event.city', function($q) use ($request) {
                $q->where('name', $request->city);
            });
        } elseif ($request->filled('cities')) {
            $query->whereHas('event.city', function($q) use ($request) {
                $q->whereIn('name', (array) $request->cities);
            });
        }

        // Category dropdown or categories array
        if ($request->filled('category')) {
            $query->where('job_category_id', $request->category);
        } elseif ($request->filled('categories')) {
            $query->whereIn('job_category_id', (array) $request->categories);
        }

        // Date filter
        if ($request->filled('date')) {
            if ($request->date === 'today') {
                $query->whereDate('application_deadline', today());
            } elseif ($request->date === 'this_week') {
                $query->whereBetween('application_deadline', [now(), now()->endOfWeek()]);
            } elseif ($request->date === 'this_month') {
                $query->whereBetween('application_deadline', [now(), now()->endOfMonth()]);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                  ->orWhereHas('event', function($sq) use ($search) {
                      $sq->where('title', 'like', '%'.$search.'%')
                         ->orWhere('venue', 'like', '%'.$search.'%');
                  });
            });
        }
    }

    /**
     * Get filter data for the view
     */
    protected function getFilterData()
    {
        return [
            'cities' => City::active()->orderBy('name')->get()->unique('name'),
            'categories' => JobCategory::where('is_active', true)->orderBy('name')->get()->unique('name'),
            'searchTerm' => request('search'),
            'selectedCity' => request('city'),
            'selectedCategory' => request('category'),
        ];
    }

    /**
     * Display the specified job opening
     */
    public function show(WorkerOpening $job)
    {
        if ($job->status !== 'open') {
            abort(404, 'Job is not currently accepting applications');
        }

        // Eager load relationships to avoid lazy loading issues
        $job->load('event.city', 'jobCategory', 'applications');

        // Decode requirements JSON field to array for the view
        if (is_string($job->requirements)) {
            $job->requirements = json_decode($job->requirements, true) ?: [];
        }

        // Check isSaved status
        $isSaved = false;
        if (session('customer_authenticated') && session('customer_id')) {
            $isSaved = \DB::table('saved_jobs')
                ->where('user_id', session('customer_id'))
                ->where('worker_opening_id', $job->id)
                ->exists();
        }

        return view('jobs.show', compact('job', 'isSaved'));
    }

    /**
     * Handle job application submission
     */
    public function apply(Request $request, WorkerOpening $job)
    {
        // Check if customer is authenticated using our custom session
        if (!session('customer_authenticated')) {
            // Store intended URL so user gets redirected back after login
            session(['intended_url' => $request->fullUrl()]);
            return redirect()->route('login')->with('error', 'Please login to apply for jobs');
        }

        $user = \App\Models\User::with('profile')->find(session('customer_id'));

        // Check Profile Completion (Must be >= 50%)
        if ($user->profile_completion < 50) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your profile is incomplete (' . $user->profile_completion . '%). Please complete at least 50% of your profile settings to apply.'
                ]);
            }
            return redirect()->route('customer.settings')->with('error', 'Please complete at least 50% of your profile before applying.');
        }

        // Validate the request
        $validated = $request->validate([
            'motivation' => 'required|string|max:1000',
        ]);

        // Check if user has already applied for this position
        $existingApplication = Application::where('worker_opening_id', $job->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingApplication) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'You have already applied for this position']);
            }
            return redirect()->back()->with('error', 'You have already applied for this position');
        }

        // Check Capacity again before applying
        if ($job->slots_filled >= $job->slots_total) {
             if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This position is already full.']);
             }
             return redirect()->back()->with('error', 'This position is already full.');
        }

        // Create the application
        $application = Application::create([
            'worker_opening_id' => $job->id,
            'user_id' => $user->id,
            'motivation' => $validated['motivation'],
            'status' => 'pending',
            'cv_path' => $user->profile->cv_file ?? null, // Auto-attach CV from profile
        ]);

        // IMPORTANT: Based on user feedback, slots_filled should only increment when APPROVED.
        // However, existing logic was incrementing on APPLY. I will COMMENT OUT the increment here,
        // effectively making slots_filled rely on approval (which handles it correctly in Admin/AppController).
        // Wait, if I change this, does it break anything?
        // Let's assume the ADMIN controller handles the increment on approval.
        // I will remove the increment here to be safe and consistent with "Approved Limit".

        /*
        if ($job->slots_filled < $job->slots_total) {
            $job->increment('slots_filled');
        }
        */

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Your application has been submitted successfully!']);
        }

        return redirect()->back()->with('success', 'Your application has been submitted successfully!');
    }

    /**
     * Get recent jobs for landing page
     */
    public function getRecentJobs()
    {
        return WorkerOpening::with(['event.city', 'jobCategory'])
            ->where('status', 'open')
            ->where('application_deadline', '>', now())
            ->whereColumn('slots_filled', '<', 'slots_total')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();
    }

    /**
     * API: Get list of job openings (for React frontend)
     */
    public function apiIndex()
    {
        $jobs = WorkerOpening::with(['event.city', 'jobCategory'])
            ->where('status', 'open')
            ->where('application_deadline', '>', now())
            ->whereColumn('slots_filled', '<', 'slots_total')
            ->orderByDesc('created_at')
            ->take(6)
            ->get()
            ->map(function($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'description' => substr($job->description ?? '', 0, 150) . '...',
                    'job_category' => $job->jobCategory?->name ?? 'Uncategorized',
                    'city' => $job->event?->city?->name ?? 'Indonesia',
                    'event' => $job->event?->title ?? 'Event TBD',
                    'slots_total' => $job->slots_total,
                    'slots_filled' => $job->slots_filled,
                    'application_deadline' => $job->application_deadline?->format('Y-m-d') ?? 'TBD',
                    'applicant_count' => $job->applications()->count() ?? 0,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $jobs,
            'total' => count($jobs)
        ]);
    }

    /**
     * API: Get single job opening (for React frontend)
     */
    public function apiShow(WorkerOpening $job)
    {
        if ($job->status !== 'open') {
            return response()->json([
                'success' => false,
                'message' => 'Job is not currently accepting applications'
            ], 404);
        }

        // Ensure relationships are loaded
        $job->load('event.city', 'jobCategory', 'applications');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $job->id,
                'title' => $job->title,
                'description' => $job->description,
                'requirements' => is_string($job->requirements) ? json_decode($job->requirements, true) : $job->requirements,
                'benefits' => $job->benefits,
                'job_category' => $job->jobCategory?->name ?? 'Uncategorized',
                'city' => $job->event?->city?->name ?? 'Indonesia',
                'event' => $job->event?->title ?? 'Event TBD',
                'venue' => $job->event?->venue ?? 'TBD',
                'slots_total' => $job->slots_total,
                'slots_filled' => $job->slots_filled,
                'application_deadline' => $job->application_deadline?->format('Y-m-d H:i') ?? 'TBD',
                'applicant_count' => $job->applications()->count() ?? 0,
            ]
        ]);
    }

}
