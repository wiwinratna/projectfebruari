<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $partners = Partner::orderBy('sort_order')->orderBy('name')->paginate(15);

        return view('super-admin.partners.index', compact('partners'));
    }

    public function create()
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        return view('super-admin.partners.create');
    }

    public function store(Request $request)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'logo'        => 'nullable|image|max:2048',
            'website'     => 'nullable|url|max:255',
            'description' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('partners', 'public');
        } else {
            unset($data['logo']);
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Partner::create($data);

        return redirect()->route('super-admin.partners.index')
            ->with('status', 'Partner created successfully!');
    }

    public function edit(Partner $partner)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        return view('super-admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'logo'        => 'nullable|image|max:2048',
            'website'     => 'nullable|url|max:255',
            'description' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($partner->logo && !str_starts_with($partner->logo, 'http')) {
                Storage::disk('public')->delete($partner->logo);
            }
            $data['logo'] = $request->file('logo')->store('partners', 'public');
        } else {
            unset($data['logo']);
        }

        if ($request->boolean('remove_logo')) {
            if ($partner->logo && !str_starts_with($partner->logo, 'http')) {
                Storage::disk('public')->delete($partner->logo);
            }
            $data['logo'] = null;
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $partner->update($data);

        return redirect()->route('super-admin.partners.index')
            ->with('status', 'Partner updated successfully!');
    }

    public function destroy(Partner $partner)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        if ($partner->logo && !str_starts_with($partner->logo, 'http')) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        return redirect()->route('super-admin.partners.index')
            ->with('status', 'Partner deleted successfully!');
    }
}
