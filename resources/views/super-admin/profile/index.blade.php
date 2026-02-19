@extends('layouts.app')

@section('title', 'Profile - NOCIS')
@section('page-title')
    Profile <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Super Admin</span>
@endsection

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Flash Messages --}}
    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
        </div>
    @endif

    {{-- Profile Card --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Profile Information</h2>

        <form method="POST" action="{{ route('super-admin.profile.update') }}" class="space-y-6">
            @csrf

            {{-- Name Field --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       placeholder="Enter your full name"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email Field --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email', $user->email) }}"
                       placeholder="Enter your email"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Username Field (Read-only) --}}
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text"
                       id="username"
                       value="{{ $user->username }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                       readonly>
                <p class="text-xs text-gray-500 mt-1">Username cannot be changed</p>
            </div>

            {{-- Submit Button --}}
            <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                <button type="submit"
                        class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-colors">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Password Card --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Change Password</h2>

        <form method="POST" action="{{ route('super-admin.profile.password') }}" class="space-y-6">
            @csrf

            {{-- Current Password --}}
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input type="password"
                       id="current_password"
                       name="current_password"
                       placeholder="Enter your current password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input type="password"
                       id="password"
                       name="password"
                       placeholder="Enter new password (min 8 characters)"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       placeholder="Confirm new password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            {{-- Password Requirements --}}
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm font-semibold text-blue-900 mb-2">Password Requirements:</p>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li><i class="fas fa-check text-green-600 mr-2"></i> At least 8 characters</li>
                    <li><i class="fas fa-check text-green-600 mr-2"></i> Mix of uppercase and lowercase letters</li>
                    <li><i class="fas fa-check text-green-600 mr-2"></i> Include numbers and symbols for stronger security</li>
                </ul>
            </div>

            {{-- Submit Button --}}
            <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                <button type="submit"
                        class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-colors">
                    <i class="fas fa-lock mr-2"></i> Update Password
                </button>
            </div>
        </form>
    </div>

    {{-- Account Info --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Account Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">User ID</p>
                <p class="text-lg font-semibold text-gray-900">#{{ $user->id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Role</p>
                <p class="text-lg font-semibold text-gray-900 flex items-center">
                    <span class="inline-block w-3 h-3 rounded-full bg-blue-600 mr-2"></span>
                    Super Administrator
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Member Since</p>
                <p class="text-lg font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Last Updated</p>
                <p class="text-lg font-semibold text-gray-900">{{ $user->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
