<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    private function guard()
    {
        if (!session('super_admin_authenticated')) {
            return redirect('/super-admin/login');
        }
        return null;
    }

    public function index()
    {
        if ($r = $this->guard()) return $r;

        $categories = JobCategory::with(['workerOpenings', 'workerType'])
            ->withCount('workerOpenings')
            ->orderBy('name')
            ->get();

        return view('menu.categories.index', compact('categories'));
    }

    public function create()
    {
        if ($r = $this->guard()) return $r;

        $category = new JobCategory();
        return view('menu.categories.create', compact('category'));
    }

    public function store(Request $request)
    {
        if ($r = $this->guard()) return $r;

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:job_categories,name',
            'description' => 'nullable|string',
            'worker_type_id' => 'required|exists:worker_types,id',
            'requires_certification' => 'boolean',
        ]);

        $validated['is_active'] = true;
        $validated['default_shift_hours'] = null;

        $category = JobCategory::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Category created successfully']);
        }

        return redirect()->route('super-admin.job-categories.index')->with('success', 'Category created.');
    }

    public function edit(JobCategory $jobCategory)
    {
        if ($r = $this->guard()) return $r;

        // supaya view yang sama tetap bisa dipakai:
        $category = $jobCategory;

        return view('menu.categories.edit', compact('category'));
    }

    public function update(Request $request, JobCategory $jobCategory)
    {
        if ($r = $this->guard()) return $r;

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:job_categories,name,' . $jobCategory->id,
            'description' => 'nullable|string',
            'worker_type_id' => 'required|exists:worker_types,id',
            'requires_certification' => 'boolean',
        ]);

        $validated['is_active'] = true;
        $validated['default_shift_hours'] = null;

        $jobCategory->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Category updated successfully']);
        }

        return redirect()->route('super-admin.job-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(JobCategory $jobCategory)
    {
        if (!session('super_admin_authenticated')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $openingsCount = $jobCategory->workerOpenings()->count();
        if ($openingsCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category used by ' . $openingsCount . ' worker openings'
            ], 422);
        }

        $jobCategory->delete();

        return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
    }
}