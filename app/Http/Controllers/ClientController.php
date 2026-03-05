<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index()
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        $clients = Client::orderBy('sort_order')->orderBy('name')->paginate(15);

        return view('super-admin.clients.index', compact('clients'));
    }

    public function create()
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        return view('super-admin.clients.create');
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
            $data['logo'] = $request->file('logo')->store('clients', 'public');
        } else {
            unset($data['logo']);
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Client::create($data);

        return redirect()->route('super-admin.clients.index')
            ->with('status', 'Client created successfully!');
    }

    public function edit(Client $client)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        return view('super-admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
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
            // Delete old logo if stored locally
            if ($client->logo && !str_starts_with($client->logo, 'http')) {
                Storage::disk('public')->delete($client->logo);
            }
            $data['logo'] = $request->file('logo')->store('clients', 'public');
        } else {
            unset($data['logo']);
        }

        if ($request->boolean('remove_logo')) {
            if ($client->logo && !str_starts_with($client->logo, 'http')) {
                Storage::disk('public')->delete($client->logo);
            }
            $data['logo'] = null;
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $client->update($data);

        return redirect()->route('super-admin.clients.index')
            ->with('status', 'Client updated successfully!');
    }

    public function destroy(Client $client)
    {
        if (!session('super_admin_authenticated')) return redirect('/super-admin/login');

        if ($client->logo && !str_starts_with($client->logo, 'http')) {
            Storage::disk('public')->delete($client->logo);
        }

        $client->delete();

        return redirect()->route('super-admin.clients.index')
            ->with('status', 'Client deleted successfully!');
    }
}
