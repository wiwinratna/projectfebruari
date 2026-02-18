<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use App\Models\WorkerOpening;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    public function index()
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        $categories = JobCategory::with(['workerOpenings', 'workerType'])
            ->withCount('workerOpenings')
            ->orderBy('name')
            ->get();

        return view('menu.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        $category = new JobCategory();

        return view('menu.categories.create', [
            'category' => $category,
        ]);
    }

    public function store(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:job_categories,name',
            'description' => 'nullable|string',
            'worker_type_id' => 'required|exists:worker_types,id',
            'requires_certification' => 'boolean',
        ]);

        // Auto set is_active to true and default_shift_hours to null
        $validated['is_active'] = true;
        $validated['default_shift_hours'] = null;

        $category = JobCategory::create($validated);

        // Check if this is an AJAX request
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Category created successfully']);
        }

        return redirect()->route('admin.categories.index', ['flash' => 'created', 'name' => $category->name]);
    }

    public function edit(JobCategory $category)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        return view('menu.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, JobCategory $category)
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:job_categories,name,' . $category->id,
            'description' => 'nullable|string',
            'worker_type_id' => 'required|exists:worker_types,id',
            'requires_certification' => 'boolean',
        ]);

        // Auto set is_active to true and default_shift_hours to null
        $validated['is_active'] = true;
        $validated['default_shift_hours'] = null;

        $category->update($validated);

        // Check if this is an AJAX request
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Category updated successfully']);
        }

        return redirect()->route('admin.categories.index', ['flash' => 'updated', 'name' => $category->name]);
    }

    public function destroy(JobCategory $category)
    {
        if (!session('admin_authenticated')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Check if category is being used
        $openingsCount = $category->workerOpenings()->count();
        if ($openingsCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category that is being used by ' . $openingsCount . ' worker openings'
            ], 422);
        }

        $category->delete();

        return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
    }

    /**
     * API: Get all active job categories
     */
    public function apiIndex()
    {
        $categories = JobCategory::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                ];
            });

        return response()->json(['success' => true, 'data' => $categories, 'total' => count($categories)]);
    }
}
