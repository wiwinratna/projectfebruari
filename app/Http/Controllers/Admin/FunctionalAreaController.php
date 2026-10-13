<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Jabatan;
use App\Models\JobCategory;
use App\Models\WorkerType;
use Illuminate\Http\Request;

class FunctionalAreaController extends Controller
{
    private function getEvent(): Event
    {
        $eventId = session('admin_event_id');
        abort_unless($eventId, 403, 'Admin belum ditugaskan ke event.');
        return Event::findOrFail($eventId);
    }

    public function index()
    {
        $event = $this->getEvent();
        
        // Fetch only Job Categories created as Functional Areas for this event
        $functionalAreas = JobCategory::where('event_id', $event->id)
            ->whereNotNull('jabatan_id')
            ->with(['jabatan', 'workerType'])
            ->orderBy('name')
            ->get();

        return view('menu.events.functional-areas.index', compact('event', 'functionalAreas'));
    }

    public function create()
    {
        $event = $this->getEvent();
        
        // Need to provide jabatans and worker types for the dropdowns
        $jabatans = $event->jabatan()->orderBy('nama_jabatan')->get();
        $workerTypes = WorkerType::orderBy('name')->get();
        
        return view('menu.events.functional-areas.create', compact('event', 'jabatans', 'workerTypes'));
    }

    public function store(Request $request)
    {
        $event = $this->getEvent();

        $validated = $request->validate([
            'jabatan_id' => 'required|exists:jabatan,id',
            'fa_name' => 'required|string|max:200',
            'worker_type_id' => 'nullable|exists:worker_types,id',
        ]);

        $jabatan = Jabatan::findOrFail($validated['jabatan_id']);
        
        // Ensure the jabatan belongs to the current event
        abort_unless($jabatan->event_id === $event->id, 403, 'Invalid jabatan.');

        // The magic: Concatenate Jabatan Name and FA Name to form the standard JobCategory Name
        $jobCategoryName = $jabatan->nama_jabatan . ' - ' . trim($validated['fa_name']);

        // Check if name already exists in this event
        if (JobCategory::where('event_id', $event->id)->where('name', $jobCategoryName)->exists()) {
            return back()->withErrors(['fa_name' => 'Kategori Pekerjaan (FA) dengan kombinasi ini sudah ada di event ini.'])->withInput();
        }

        JobCategory::create([
            'event_id' => $event->id,
            'jabatan_id' => $jabatan->id,
            'fa_name' => trim($validated['fa_name']),
            'worker_type_id' => $validated['worker_type_id'] ?? null,
            'name' => $jobCategoryName,
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.master-data.functional-areas.index')
            ->with('status', 'Functional Area berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $event = $this->getEvent();
        
        $jobCategory = JobCategory::where('event_id', $event->id)
            ->whereNotNull('jabatan_id')
            ->findOrFail($id);

        $jabatans = $event->jabatan()->orderBy('nama_jabatan')->get();
        $workerTypes = WorkerType::orderBy('name')->get();

        return view('menu.events.functional-areas.edit', [
            'event' => $event,
            'jobCategory' => $jobCategory,
            'functionalArea' => $jobCategory,
            'jabatans' => $jabatans,
            'workerTypes' => $workerTypes,
        ]);
    }

    public function update(Request $request, $id)
    {
        $event = $this->getEvent();
        
        $functionalArea = JobCategory::where('event_id', $event->id)
            ->whereNotNull('jabatan_id')
            ->findOrFail($id);

        $validated = $request->validate([
            'jabatan_id' => 'required|exists:jabatan,id',
            'fa_name' => 'required|string|max:200',
            'worker_type_id' => 'nullable|exists:worker_types,id',
        ]);

        $jabatan = Jabatan::findOrFail($validated['jabatan_id']);
        abort_unless($jabatan->event_id === $event->id, 403, 'Invalid jabatan.');

        $jobCategoryName = $jabatan->nama_jabatan . ' - ' . trim($validated['fa_name']);

        // Check if name already exists in this event (excluding itself)
        if (JobCategory::where('event_id', $event->id)->where('name', $jobCategoryName)->where('id', '!=', $functionalArea->id)->exists()) {
            return back()->withErrors(['fa_name' => 'Kategori Pekerjaan (FA) dengan kombinasi ini sudah ada di event ini.'])->withInput();
        }

        $functionalArea->update([
            'jabatan_id' => $jabatan->id,
            'fa_name' => trim($validated['fa_name']),
            'worker_type_id' => $validated['worker_type_id'] ?? null,
            'name' => $jobCategoryName,
        ]);

        return redirect()
            ->route('admin.master-data.functional-areas.index')
            ->with('status', 'Functional Area berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $event = $this->getEvent();
        
        $functionalArea = JobCategory::where('event_id', $event->id)
            ->whereNotNull('jabatan_id')
            ->findOrFail($id);

        // Check if used in worker_openings
        if ($functionalArea->workerOpenings()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus, Functional Area masih digunakan pada Lowongan Pekerjaan.',
            ], 422);
        }

        $functionalArea->delete();

        return response()->json([
            'success' => true,
            'message' => 'Functional Area berhasil dihapus.',
        ]);
    }
}
