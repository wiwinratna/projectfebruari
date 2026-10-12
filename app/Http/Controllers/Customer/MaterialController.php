<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\EventTrainingMaterial;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MaterialController extends Controller
{
    public function index()
    {
        $userId = session('customer_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Volunteer must have 'approved' status on applications to access materials
        $approvedEventIds = Application::where('applications.user_id', $userId)
            ->where('applications.status', 'approved')
            ->whereHas('workerOpening')
            ->join('worker_openings', 'applications.worker_opening_id', '=', 'worker_openings.id')
            ->pluck('worker_openings.event_id')
            ->unique();

        if ($approvedEventIds->isEmpty()) {
            $events = collect();
        } else {
            $now = Carbon::now();
            $events = \App\Models\Event::whereIn('id', $approvedEventIds)
                ->with(['city', 'trainingMaterials' => function($query) use ($now) {
                    $query->where(function($q) use ($now) {
                        $q->whereNull('available_from')
                              ->orWhere('available_from', '<=', $now);
                    })
                    ->where(function($q) use ($now) {
                        $q->whereNull('available_until')
                              ->orWhere('available_until', '>=', $now);
                    })
                    ->latest();
                }])
                ->orderBy('title')
                ->get();
        }

        return view('menu.customer.materials.index', compact('events'));
    }

    public function download(EventTrainingMaterial $material)
    {
        $userId = session('customer_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Verify volunteer is approved for the event of this material
        $isApproved = Application::where('user_id', $userId)
            ->where('status', 'approved')
            ->whereHas('workerOpening', function($q) use ($material) {
                $q->where('event_id', $material->event_id);
            })->exists();

        if (!$isApproved) {
            abort(403, 'Anda tidak memiliki akses ke materi event ini.');
        }

        // Verify availability period strictly
        $now = Carbon::now();
        if ($material->available_from && $material->available_from > $now) {
            abort(403, 'Materi belum tersedia untuk diunduh.');
        }
        if ($material->available_until && $material->available_until < $now) {
            abort(403, 'Materi sudah tidak tersedia (kedaluwarsa).');
        }

        // If file_path is empty but link_url exists, redirect to external link/video
        if (!$material->file_path && $material->link_url) {
            return redirect()->away($material->link_url);
        }

        // Check if file exists in private storage
        if (!$material->file_path || !Storage::disk('local')->exists($material->file_path)) {
            abort(404, 'File materi tidak ditemukan atau telah dihapus oleh Admin.');
        }

        return Storage::disk('local')->download($material->file_path, $material->original_filename ?? 'materi-pelatihan');
    }
}
