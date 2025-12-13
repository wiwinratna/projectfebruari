@extends('layouts.app')

@section('title', 'Admin Profile - NOCIS')
@section('page-title')
    Profile
@endsection

@section('content')
<div class="space-y-6">
    
    {{-- Greeting Header --}}
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800">Hi, {{ $admin->name }}!</h3>
        <p class="text-sm text-gray-600">Ubah informasi tentang diri Anda di halaman ini.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Edit Profile Form --}}
        <div class="bg-white p-6 rounded-lg shadow-sm h-full">
            <h4 class="text-lg font-bold text-red-600 mb-6">Edit Profile</h4>
            
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Change Password Form --}}
        <div class="bg-white p-6 rounded-lg shadow-sm h-full">
            <h4 class="text-lg font-bold text-red-600 mb-6">Ganti Password</h4>
            
            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                        <input type="password" name="current_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="new_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                        @error('new_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors shadow-sm">
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
