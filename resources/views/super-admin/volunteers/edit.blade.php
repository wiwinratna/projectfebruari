@extends('layouts.app')

@section('title', 'Edit Volunteer - ARISE')
@section('page-title')
    Edit Volunteer <span class="bg-blue-600 text-white text-sm px-2.5 py-0.5 rounded-full ml-2">{{ $user->name }}</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Back Button --}}
    <a href="{{ route('super-admin.volunteers.show', $user) }}"
       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors shadow-xs">
        <i class="fas fa-arrow-left mr-2"></i> Back to Profile
    </a>

    {{-- Flash Messages --}}
    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3 text-sm shadow-xs" role="alert">
            <i class="fas fa-check-circle text-green-500 shrink-0"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-6 sm:p-8">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mr-4 overflow-hidden flex-shrink-0">
                @if($user->profile?->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile->profile_photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-blue-700 font-bold">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Edit Volunteer Account</h2>
                <p class="text-sm text-gray-500">Update basic account information for {{ $user->name }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('super-admin.volunteers.update', $user) }}" class="space-y-6 max-w-2xl">
            @csrf
            @method('PUT')

            {{-- Name Field --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       placeholder="e.g., John Doe"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Username Field --}}
            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                <input type="text"
                       id="username"
                       name="username"
                       value="{{ old('username', $user->username) }}"
                       placeholder="e.g., john_doe"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                       required>
                @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email Field --}}
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email', $user->email) }}"
                       placeholder="e.g., john@example.com"
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Read-only Profile Information --}}
            <div class="pt-6 border-t border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">
                    <i class="fas fa-info-circle mr-1.5"></i> Profile Information (Read Only)
                </h3>
                <p class="text-xs text-gray-500 mb-4">
                    Profile details below are managed by the volunteer themselves. Only account information above can be edited by the super admin.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500">Phone</p>
                        <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $user->profile?->phone ?? '—' }}</p>
                    </div>
                    <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500">Date of Birth</p>
                        <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $user->profile?->date_of_birth?->format('M d, Y') ?? '—' }}</p>
                    </div>
                    <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500">Last Education</p>
                        <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $user->profile?->last_education ?? '—' }}</p>
                    </div>
                    <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500">University</p>
                        <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $user->profile?->university ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                <a href="{{ route('super-admin.volunteers.show', $user) }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition-colors text-sm">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium transition-colors text-sm shadow-xs flex items-center">
                    <i class="fas fa-save mr-2"></i> Update Volunteer
                </button>
            </div>
        </form>
    </div>

    {{-- Volunteer Info Section --}}
    <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-6">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">
            <i class="fas fa-chart-bar mr-1.5"></i> Volunteer Statistics
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 bg-blue-50/75 border border-blue-100 rounded-xl text-center">
                <p class="text-2xl font-bold text-blue-700">{{ $user->profile_completion }}%</p>
                <p class="text-xs text-blue-600 mt-1">Profile Completion</p>
            </div>
            <div class="p-4 bg-purple-50/75 border border-purple-100 rounded-xl text-center">
                <p class="text-2xl font-bold text-purple-700">{{ $user->applications()->count() }}</p>
                <p class="text-xs text-purple-600 mt-1">Total Applications</p>
            </div>
            <div class="p-4 bg-emerald-50/75 border border-emerald-100 rounded-xl text-center">
                <p class="text-2xl font-bold text-emerald-700">{{ $user->applications()->where('status', 'accepted')->count() }}</p>
                <p class="text-xs text-emerald-600 mt-1">Accepted Applications</p>
            </div>
        </div>
    </div>
</div>
@endsection
