<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\WorkerOpening;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Calculate statistics independently of search
        $stats = [
            'total_applicants' => Application::count(),
            'pending_review' => Application::where('status', 'pending')->count(),
            'approved_members' => Application::where('status', 'approved')->count(),
            'rejected_members' => Application::where('status', 'rejected')->count(),
        ];

        // Get query builder
        $query = Application::with(['user.profile', 'opening.jobCategory', 'opening.event'])
            ->orderBy('created_at', 'desc');

        // Apply search if present
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('username', 'like', '%'.$search.'%')
                       ->orWhere('email', 'like', '%'.$search.'%');
                })
                ->orWhereHas('opening', function($oq) use ($search) {
                    $oq->where('title', 'like', '%'.$search.'%')
                       ->orWhereHas('event', function($eq) use ($search) {
                           $eq->where('title', 'like', '%'.$search.'%');
                       })
                       ->orWhereHas('jobCategory', function($jcq) use ($search) {
                           $jcq->where('name', 'like', '%'.$search.'%');
                       });
                });
            });
        }

        $applications = $query->get();

        return view('menu.reviews.index', [
            'applications' => $applications,
            'stats' => $stats,
        ]);
    }

    public function updateStatus(Request $request, Application $application)
    {
        if (!session('admin_authenticated')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'review_notes' => 'nullable|string|max:1000',
        ]);

        $application->update([
            'status' => $validated['status'],
            'review_notes' => $validated['review_notes'],
            'reviewed_by' => session('admin_id'),
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application status updated successfully',
            'application' => $application->load('user.profile', 'opening'),
        ]);
    }
}