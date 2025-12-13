<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\WorkerOpening;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function show(Application $application)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        $application->load(['user.profile', 'opening.event', 'opening.jobCategory']);

        return view('menu.admin.applications.show', compact('application'));
    }

    public function update(Request $request, Application $application)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'review_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $application->status;
        
        $application->update([
            'status' => $validated['status'],
            'review_notes' => $validated['review_notes'],
            'reviewed_by' => session('admin_id'),
            'reviewed_at' => now(),
        ]);

        // Logic to update slots_filled if status changed to/from approved
        $job = $application->opening;
        if ($oldStatus !== 'approved' && $validated['status'] === 'approved') {
            $job->increment('slots_filled');
        } elseif ($oldStatus === 'approved' && $validated['status'] !== 'approved') {
            $job->decrement('slots_filled');
        }

        return redirect()->route('admin.workers.show', $job->id)
                         ->with('status', "Application for {$application->user->name} has been {$validated['status']}.");
    }
}
