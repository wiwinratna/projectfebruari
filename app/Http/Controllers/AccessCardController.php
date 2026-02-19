<?php

namespace App\Http\Controllers;

use App\Models\AccessCard;
use App\Models\Application;
use Illuminate\Http\Request;

class AccessCardController extends Controller
{
    // =========================
    // ADMIN PRINT
    // =========================
    public function adminPrint(AccessCard $accessCard)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        // Check if access card belongs to admin's assigned event
        $adminEventId = session('admin_event_id');
        if ($accessCard->event_id !== $adminEventId) {
            return back()->withErrors(['message' => 'You are not authorized to view this access card.']);
        }

        $accessCard->load([
            'accessCodes',
            'workerOpening.event',
            'user.profile', // foto
        ]);

        return view('admin.access-cards.print', compact('accessCard'));
    }

    // =========================
    // CUSTOMER PRINT
    // /dashboard/applications/{application}/card
    // =========================
    public function customerPrint(Application $application)
    {
        $customerId = (int) session('customer_id');

        // pastikan milik user login
        abort_unless($application->user_id === $customerId, 403);

        // pastikan sudah approved
        abort_unless($application->status === 'approved', 403);

        // 🔥 QUERY PALING TEPAT SESUAI DB
        $accessCard = AccessCard::query()
            ->where('application_id', $application->id)
            ->where('user_id', $application->user_id)
            ->firstOrFail();

        $accessCard->load([
            'accessCodes',
            'workerOpening.event',
            'user.profile', // foto dari user_profiles
        ]);

        return view('access-cards.print', compact('accessCard'));
    }

    // =========================
    // OPTIONAL: PRINT TERAKHIR (kalau masih mau)
    // =========================
    public function myPrint()
    {
        $customerId = (int) session('customer_id');
        abort_unless($customerId > 0, 403);

        $accessCard = AccessCard::query()
            ->where('user_id', $customerId)
            ->latest()
            ->firstOrFail();

        $accessCard->load([
            'accessCodes',
            'workerOpening.event',
            'user.profile',
        ]);

        return view('access-cards.print', compact('accessCard'));
    }
}
