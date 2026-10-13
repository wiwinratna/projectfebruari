<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WorkerType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkerTypeController extends Controller
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

        $workerTypes = WorkerType::withCount('jobCategories')
            ->orderBy('name')
            ->get();

        return view('super-admin.worker-types.index', compact('workerTypes'));
    }

    public function create()
    {
        if ($r = $this->guard()) return $r;

        $workerType = new WorkerType(['is_active' => true]);
        return view('super-admin.worker-types.create', compact('workerType'));
    }

    public function store(Request $request)
    {
        if ($r = $this->guard()) return $r;

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:worker_types,name',
            'description' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        WorkerType::create($validated);

        return redirect()
            ->route('super-admin.worker-types.index')
            ->with('status', 'Worker type created successfully.');
    }

    public function edit(WorkerType $workerType)
    {
        if ($r = $this->guard()) return $r;

        return view('super-admin.worker-types.edit', compact('workerType'));
    }

    public function update(Request $request, WorkerType $workerType)
    {
        if ($r = $this->guard()) return $r;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('worker_types', 'name')->ignore($workerType->id),
            ],
            'description' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $workerType->update($validated);

        return redirect()
            ->route('super-admin.worker-types.index')
            ->with('status', 'Worker type updated successfully.');
    }

    public function destroy(WorkerType $workerType)
    {
        if ($r = $this->guard()) return $r;

        if ($workerType->jobCategories()->exists()) {
            return redirect()
                ->route('super-admin.worker-types.index')
                ->with('error', "Cannot delete '{$workerType->name}' because it is assigned to {$workerType->jobCategories()->count()} job categories.");
        }

        $workerType->delete();

        return redirect()
            ->route('super-admin.worker-types.index')
            ->with('status', 'Worker type deleted successfully.');
    }
}
