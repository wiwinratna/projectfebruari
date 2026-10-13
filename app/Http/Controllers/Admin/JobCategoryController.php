<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\JobCategory;
use App\Models\WorkerType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobCategoryController extends Controller
{
    private function requireAdmin()
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }
        return null;
    }

    private function getEvent(): Event
    {
        $eventId = session('admin_event_id');
        abort_unless($eventId, 403, 'Akun admin belum ditugaskan ke event manapun.');
        return Event::findOrFail($eventId);
    }

    public function index()
    {
        if ($r = $this->requireAdmin()) return $r;

        $event = $this->getEvent();

        $categories = JobCategory::where('event_id', $event->id)
            ->with(['workerOpenings', 'workerType'])
            ->withCount('workerOpenings')
            ->orderBy('name')
            ->get();

        $globalCategories = JobCategory::whereNull('event_id')
            ->with('workerType')
            ->orderBy('name')
            ->get();

        return view('menu.admin.job-categories.index', compact('event', 'categories', 'globalCategories'));
    }

    public function create()
    {
        if ($r = $this->requireAdmin()) return $r;

        $event = $this->getEvent();
        $category = new JobCategory();
        $workerTypes = WorkerType::orderBy('name')->get();

        return view('menu.admin.job-categories.create', compact('event', 'category', 'workerTypes'));
    }

    public function store(Request $request)
    {
        if ($r = $this->requireAdmin()) return $r;

        $event = $this->getEvent();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('job_categories')->where(fn ($q) => $q->where('event_id', $event->id)),
            ],
            'description' => 'nullable|string',
            'worker_type_id' => 'required|exists:worker_types,id',
            'requires_certification' => 'nullable|boolean',
        ], [
            'name.unique' => 'Kategori dengan nama ini sudah ada di event kamu.',
        ]);

        $validated['event_id'] = $event->id;
        $validated['is_active'] = true;
        $validated['requires_certification'] = $request->boolean('requires_certification');

        JobCategory::create($validated);

        return redirect()
            ->route('admin.job-categories.index')
            ->with('status', 'Job Category berhasil ditambahkan untuk event ' . $event->title);
    }

    public function edit($id)
    {
        if ($r = $this->requireAdmin()) return $r;

        $event = $this->getEvent();
        $category = JobCategory::where('event_id', $event->id)->findOrFail($id);
        $workerTypes = WorkerType::orderBy('name')->get();

        return view('menu.admin.job-categories.edit', compact('event', 'category', 'workerTypes'));
    }

    public function update(Request $request, $id)
    {
        if ($r = $this->requireAdmin()) return $r;

        $event = $this->getEvent();
        $category = JobCategory::where('event_id', $event->id)->findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('job_categories')->ignore($category->id)->where(fn ($q) => $q->where('event_id', $event->id)),
            ],
            'description' => 'nullable|string',
            'worker_type_id' => 'required|exists:worker_types,id',
            'requires_certification' => 'nullable|boolean',
        ], [
            'name.unique' => 'Kategori dengan nama ini sudah ada di event kamu.',
        ]);

        $validated['requires_certification'] = $request->boolean('requires_certification');

        $category->update($validated);

        return redirect()
            ->route('admin.job-categories.index')
            ->with('status', 'Job Category berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if ($r = $this->requireAdmin()) return $r;

        $event = $this->getEvent();
        $category = JobCategory::where('event_id', $event->id)->findOrFail($id);

        if ($category->workerOpenings()->exists()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus, kategori ini masih digunakan pada Lowongan Pekerjaan.',
                ], 422);
            }
            return back()->withErrors(['message' => 'Tidak dapat menghapus, kategori ini masih digunakan pada Lowongan Pekerjaan.']);
        }

        $category->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Job Category berhasil dihapus.',
            ]);
        }

        return redirect()
            ->route('admin.job-categories.index')
            ->with('status', 'Job Category berhasil dihapus.');
    }
}
