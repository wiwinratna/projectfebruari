<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\WorkerOpening;
use Illuminate\Http\Request;
use App\Models\AccessCard;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class ApplicationController extends Controller
{
    public function show(Application $application)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }
        $application->load([
            'user.profile',
            'user.certificates' => fn($q) => $q->latest(), //paling baru cenah
            'opening.event',
            'opening.jobCategory',
        ]);

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

    DB::transaction(function () use ($validated, $application) {

        $oldStatus = $application->status;

        $application->update([
            'status' => $validated['status'],
            'review_notes' => $validated['review_notes'],
            'reviewed_by' => session('admin_id'),
            'reviewed_at' => now(),
        ]);

        // ===== slots_filled update =====
        $job = $application->opening;

        if ($oldStatus !== 'approved' && $validated['status'] === 'approved') {
            $job->increment('slots_filled');
        } elseif ($oldStatus === 'approved' && $validated['status'] !== 'approved') {
            $job->decrement('slots_filled');
        }

        // ===== auto close/open job =====
        $job->refresh();
        if ($job->slots_filled >= $job->slots_total && $job->status === 'open') {
            $job->update(['status' => 'closed']);
        } elseif ($job->slots_filled < $job->slots_total && $job->status === 'closed') {
            if ($job->application_deadline > now()) {
                $job->update(['status' => 'open']);
            }
        }

        // ===== 🔥 ACCESS CARD LOGIC =====
        if ($validated['status'] === 'approved') {

            // ambil akses dari opening (yang bisa diubah-ubah admin)
            $application->load('opening.accessCodes');

            $card = AccessCard::updateOrCreate(
                ['application_id' => $application->id],
                [
                    'user_id' => $application->user_id,
                    'event_id' => $application->opening->event_id,
                    'worker_opening_id' => $application->worker_opening_id,
                    'registration_code' => AccessCard::where('application_id', $application->id)->value('registration_code')
                        ?? strtoupper(Str::random(10)),
                    'issued_at' => now(),
                ]
            );

            // PENTING: pivot table kamu namanya access_card_access_codes
            $card->accessCodes()->sync(
                $application->opening->accessCodes->pluck('id')->all()
            );

        } else {
            // kalau status bukan approved -> hapus kartu (opsional tapi recommended)
            $card = AccessCard::where('application_id', $application->id)->first();
            if ($card) {
                $card->accessCodes()->detach();
                $card->delete();
            }
        }
    });

    $job = $application->opening; // sudah ada, tapi biar aman ambil ulang
    return redirect()->route('admin.workers.show', $job->id)
        ->with('status', "Application for {$application->user->name} has been {$validated['status']}.");
}
}
