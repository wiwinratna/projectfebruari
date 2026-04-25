<?php

namespace App\Http\Controllers;

use App\Models\LandingSectionItem;
use Illuminate\Http\Request;

class LandingSectionItemController extends Controller
{
    public function index(Request $request)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $section = $this->resolveSection($request->query('section'));

        $items = LandingSectionItem::query()
            ->where('section', $section)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return view('super-admin.landing-section-items.index', [
            'items' => $items,
            'section' => $section,
            'sections' => LandingSectionItem::SECTIONS,
        ]);
    }

    public function create(Request $request)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $section = $this->resolveSection($request->query('section'));

        return view('super-admin.landing-section-items.create', [
            'section' => $section,
            'sections' => LandingSectionItem::SECTIONS,
        ]);
    }

    public function store(Request $request)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $data = $request->validate([
            'section' => 'required|in:' . implode(',', LandingSectionItem::SECTIONS),
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'emoji' => 'nullable|string|max:20',
            'highlight' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        LandingSectionItem::create($data);

        return redirect()->route('super-admin.landing-section-items.index', ['section' => $data['section']])
            ->with('status', 'Landing section item created successfully!');
    }

    public function edit(LandingSectionItem $landingSectionItem)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        return view('super-admin.landing-section-items.edit', [
            'item' => $landingSectionItem,
            'sections' => LandingSectionItem::SECTIONS,
        ]);
    }

    public function update(Request $request, LandingSectionItem $landingSectionItem)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $data = $request->validate([
            'section' => 'required|in:' . implode(',', LandingSectionItem::SECTIONS),
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'emoji' => 'nullable|string|max:20',
            'highlight' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $landingSectionItem->update($data);

        return redirect()->route('super-admin.landing-section-items.index', ['section' => $data['section']])
            ->with('status', 'Landing section item updated successfully!');
    }

    public function destroy(LandingSectionItem $landingSectionItem)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $section = $landingSectionItem->section;
        $landingSectionItem->delete();

        return redirect()->route('super-admin.landing-section-items.index', ['section' => $section])
            ->with('status', 'Landing section item deleted successfully!');
    }

    private function resolveSection(?string $section): string
    {
        if (in_array((string) $section, LandingSectionItem::SECTIONS, true)) {
            return (string) $section;
        }

        return LandingSectionItem::SECTIONS[0];
    }
}
