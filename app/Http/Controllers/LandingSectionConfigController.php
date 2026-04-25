<?php

namespace App\Http\Controllers;

use App\Models\LandingSectionConfig;
use Illuminate\Http\Request;

class LandingSectionConfigController extends Controller
{
    public function index(Request $request)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $section = $request->query('section');

        $configs = LandingSectionConfig::query()
            ->when(
                $section && in_array($section, LandingSectionConfig::SECTIONS, true),
                fn ($query) => $query->where('section', $section)
            )
            ->orderBy('section')
            ->paginate(20)
            ->withQueryString();

        return view('super-admin.landing-section-configs.index', [
            'activeSection' => $section,
            'sections' => LandingSectionConfig::SECTIONS,
            'configs' => $configs,
        ]);
    }

    public function create()
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $usedSections = LandingSectionConfig::query()->pluck('section')->all();

        return view('super-admin.landing-section-configs.create', [
            'sections' => LandingSectionConfig::SECTIONS,
            'usedSections' => $usedSections,
        ]);
    }

    public function store(Request $request)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $data = $request->validate([
            'section' => 'required|in:' . implode(',', LandingSectionConfig::SECTIONS) . '|unique:landing_section_configs,section',
            'badge_text' => 'nullable|string|max:255',
            'title_text' => 'nullable|string|max:255',
            'subtitle_text' => 'nullable|string|max:4000',
            'extra_text' => 'nullable|string|max:4000',
            'extra_text_2' => 'nullable|string|max:4000',
            'extra_text_3' => 'nullable|string|max:4000',
            'chip_text_1' => 'nullable|string|max:255',
            'chip_text_2' => 'nullable|string|max:255',
            'chip_text_3' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string|max:255',
            'mission_title' => 'nullable|string|max:255',
            'vision_title' => 'nullable|string|max:255',
        ]);

        LandingSectionConfig::create($data);

        return redirect()->route('super-admin.landing-section-configs.index', ['section' => $data['section']])
            ->with('status', 'Landing section copy created successfully!');
    }

    public function edit(LandingSectionConfig $landingSectionConfig)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        return view('super-admin.landing-section-configs.edit', [
            'config' => $landingSectionConfig,
            'sections' => LandingSectionConfig::SECTIONS,
        ]);
    }

    public function update(Request $request, LandingSectionConfig $landingSectionConfig)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $data = $request->validate([
            'section' => 'required|in:' . implode(',', LandingSectionConfig::SECTIONS) . '|unique:landing_section_configs,section,' . $landingSectionConfig->id,
            'badge_text' => 'nullable|string|max:255',
            'title_text' => 'nullable|string|max:255',
            'subtitle_text' => 'nullable|string|max:4000',
            'extra_text' => 'nullable|string|max:4000',
            'extra_text_2' => 'nullable|string|max:4000',
            'extra_text_3' => 'nullable|string|max:4000',
            'chip_text_1' => 'nullable|string|max:255',
            'chip_text_2' => 'nullable|string|max:255',
            'chip_text_3' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string|max:255',
            'mission_title' => 'nullable|string|max:255',
            'vision_title' => 'nullable|string|max:255',
        ]);

        $landingSectionConfig->update($data);

        return redirect()->route('super-admin.landing-section-configs.index', ['section' => $data['section']])
            ->with('status', 'Landing section copy updated successfully!');
    }

    public function destroy(LandingSectionConfig $landingSectionConfig)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $landingSectionConfig->delete();

        return redirect()->route('super-admin.landing-section-configs.index')
            ->with('status', 'Landing section copy deleted successfully!');
    }
}
