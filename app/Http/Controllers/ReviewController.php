<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\WorkerOpening;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Exports\ApplicationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use App\Models\AccessCard;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index()
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Get admin's assigned event
        $adminEventId = session('admin_event_id');
        if (!$adminEventId) {
            return back()->withErrors(['message' => 'Akun admin belum ditugaskan ke event manapun. Hubungi super admin.']);
        }

        // Calculate statistics for admin's assigned event only
        $stats = [
            'total_applicants' => Application::whereHas('opening', function($q) use ($adminEventId) {
                $q->where('event_id', $adminEventId);
            })->count(),
            'pending_review' => Application::where('status', 'pending')
                ->whereHas('opening', function($q) use ($adminEventId) {
                    $q->where('event_id', $adminEventId);
                })->count(),
            'approved_members' => Application::where('status', 'approved')
                ->whereHas('opening', function($q) use ($adminEventId) {
                    $q->where('event_id', $adminEventId);
                })->count(),
            'rejected_members' => Application::where('status', 'rejected')
                ->whereHas('opening', function($q) use ($adminEventId) {
                    $q->where('event_id', $adminEventId);
                })->count(),
        ];

        // Only show admin's assigned event
        $adminEvent = Event::findOrFail($adminEventId);
        $events = collect([$adminEvent]);

        // Get query builder - filter by admin's assigned event
        $query = Application::with(['user.profile', 'opening.jobCategory', 'opening.event.city'])
            ->whereHas('opening', function($q) use ($adminEventId) {
                $q->where('event_id', $adminEventId);
            })
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
            'events' => $events,
        ]);
    }

    public function export()
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Get admin's assigned event
        $adminEventId = session('admin_event_id');
        if (!$adminEventId) {
            return back()->withErrors(['message' => 'Akun admin belum ditugaskan ke event manapun.']);
        }

        $search = request('search');
        $event = Event::find($adminEventId);
        $eventName = $event ? $event->title : null;

        $baseName = $eventName ? Str::slug($eventName, '_') : 'applications';
        $filename = $baseName . '_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(new ApplicationsExport($adminEventId, $search), $filename);
    }

public function update(Request $request, Application $application)
{
    \Log::info('REVIEWS UPDATE HIT', [
        'app_id' => $application->id,
        'incoming' => $request->all(),
    ]);

    if (!session('admin_authenticated')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Check if application belongs to admin's assigned event
    $adminEventId = session('admin_event_id');
    if ($application->opening->event_id !== $adminEventId) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $validated = $request->validate([
        'status' => 'required|in:pending,approved,rejected',
        'review_notes' => 'nullable|string|max:1000',
    ]);

    DB::transaction(function () use ($validated, $application) {

        $application->update([
            'status' => $validated['status'],
            'review_notes' => $validated['review_notes'],
            'reviewed_by' => session('admin_id'),
            'reviewed_at' => now(),
        ]);

        // kalau bukan approved → hapus kartu (opsi A)
        if ($validated['status'] !== 'approved') {
            $card = AccessCard::where('application_id', $application->id)->first();
            if ($card) {
                $card->accessCodes()->detach();
                $card->delete();
            }
            return;
        }

        // approved → buat/update kartu + sync akses
        $application->load('opening.accessCodes');

        $existingCode = AccessCard::where('application_id', $application->id)->value('registration_code');

        $card = AccessCard::updateOrCreate(
            ['application_id' => $application->id],
            [
                'user_id' => $application->user_id,
                'event_id' => $application->opening->event_id,
                'worker_opening_id' => $application->worker_opening_id,
                'registration_code' => $existingCode ?: strtoupper(Str::random(10)),
                'qr_token'       => Str::uuid(), // ⬅️ INI PENTING
                'issued_at' => now(),
            ]
        );

        $card->accessCodes()->sync(
            $application->opening->accessCodes->pluck('id')->all()
        );
    });

    return response()->json([
        'success' => true,
        'message' => 'Application status updated successfully',
    ]);
}
}
