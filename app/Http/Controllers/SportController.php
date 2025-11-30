<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use App\Models\Event;
use Illuminate\Http\Request;

class SportController extends Controller
{
    public function index()
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        $sports = Sport::withCount('events')
            ->orderBy('name')
            ->get();

        return view('menu.sports.index', [
            'sports' => $sports,
        ]);
    }

    public function create()
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        $sport = new Sport([
            'is_active' => true,
        ]);

        return view('menu.sports.create', [
            'sport' => $sport,
        ]);
    }

    public function store(Request $request)
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:sports,code',
            'name' => 'required|string|max:255|unique:sports,name',
            'is_active' => 'boolean',
        ]);

        $sport = Sport::create($validated);

        // Check if this is an AJAX request
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Sport created successfully']);
        }

        return redirect()->route('sports.index', ['flash' => 'created', 'name' => $sport->name]);
    }

    public function edit(Sport $sport)
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        return view('menu.sports.edit', [
            'sport' => $sport,
        ]);
    }

    public function update(Request $request, Sport $sport)
    {
        if (!session('authenticated')) {
            return redirect('/login');
        }

        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:sports,code,' . $sport->id,
            'name' => 'required|string|max:255|unique:sports,name,' . $sport->id,
            'is_active' => 'boolean',
        ]);

        $sport->update($validated);

        // Check if this is an AJAX request
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Sport updated successfully']);
        }

        return redirect()->route('sports.index', ['flash' => 'updated', 'name' => $sport->name]);
    }

    public function destroy(Sport $sport)
    {
        if (!session('authenticated')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Check if sport is being used
        $eventsCount = $sport->events()->count();
        if ($eventsCount > 0) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete sport that is being used by ' . $eventsCount . ' events'
            ], 422);
        }

        $sportName = $sport->name;
        $sport->delete();

        return response()->json(['success' => true, 'message' => 'Sport "' . $sportName . '" deleted successfully']);
    }
}