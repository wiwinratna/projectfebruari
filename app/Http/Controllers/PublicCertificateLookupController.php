<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Event;
use Illuminate\Http\Request;

class PublicCertificateLookupController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['application.user', 'application.opening.event'])
            ->where('status', 'published');

        $name  = trim($request->get('name', ''));
        $event = trim($request->get('event', ''));
        $from  = $request->get('from');
        $to    = $request->get('to');

        if ($name !== '') {
            $query->where(function($q) use ($name) {
                $q->where('payload->volunteer_name', 'like', '%' . $name . '%')
                  ->orWhere('snapshot->volunteer_name', 'like', '%' . $name . '%');
            });
        }

        if ($event !== '') {
            $query->whereHas('application.opening.event', function ($q) use ($event) {
                $q->where('title', 'like', '%' . $event . '%');
            });
        }

        if ($from) {
            $query->whereDate('published_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('published_at', '<=', $to);
        }

        $searched = ($name !== '' || $event !== '' || $from || $to);
        $certificates = $searched ? $query->orderByDesc('published_at')->limit(50)->get() : collect();

        // All distinct events that have published certificates (for autocomplete hints)
        $events = Event::whereHas('certificates', function ($q) {
            $q->where('status', 'published');
        })->orderByDesc('end_at')->get(['id', 'title', 'start_at', 'end_at']);

        return view('public.certificates.lookup', compact(
            'certificates', 'events', 'name', 'event', 'from', 'to', 'searched'
        ));
    }
}
