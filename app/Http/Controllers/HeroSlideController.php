<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $heroSlides = HeroSlide::query()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(15);

        return view('super-admin.hero-slides.index', compact('heroSlides'));
    }

    public function create()
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        return view('super-admin.hero-slides.create');
    }

    public function store(Request $request)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image'       => 'required|image|max:4096',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $data['image'] = $request->file('image')->store('hero-slides', 'public');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        HeroSlide::create($data);

        return redirect()->route('super-admin.hero-slides.index')
            ->with('status', 'Hero slide created successfully!');
    }

    public function edit(HeroSlide $heroSlide)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        return view('super-admin.hero-slides.edit', compact('heroSlide'));
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image'       => 'nullable|image|max:4096',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($heroSlide->image && !str_starts_with($heroSlide->image, 'http')) {
                Storage::disk('public')->delete($heroSlide->image);
            }
            $data['image'] = $request->file('image')->store('hero-slides', 'public');
        } else {
            unset($data['image']);
        }

        if ($request->boolean('remove_image')) {
            if ($heroSlide->image && !str_starts_with($heroSlide->image, 'http')) {
                Storage::disk('public')->delete($heroSlide->image);
            }
            $data['image'] = null;
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $heroSlide->update($data);

        return redirect()->route('super-admin.hero-slides.index')
            ->with('status', 'Hero slide updated successfully!');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        if ($heroSlide->image && !str_starts_with($heroSlide->image, 'http')) {
            Storage::disk('public')->delete($heroSlide->image);
        }

        $heroSlide->delete();

        return redirect()->route('super-admin.hero-slides.index')
            ->with('status', 'Hero slide deleted successfully!');
    }
}
