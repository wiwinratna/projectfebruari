<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminProfileController extends Controller
{
    public function index()
    {
        $admin = User::findOrFail(session('admin_id'));
        return view('menu.admin.profile.index', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . session('admin_id'),
        ]);

        $admin = User::findOrFail(session('admin_id'));
        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('status', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $admin = User::findOrFail(session('admin_id'));

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $admin->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('status', 'Password changed successfully!');
    }
}
