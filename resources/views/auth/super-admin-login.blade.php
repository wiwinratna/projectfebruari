<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login - NOCIS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-8">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/Logo ARISE PNG.png') }}?v={{ time() }}"
                 alt="NOA Indonesia"
                 class="h-20 w-auto mx-auto mb-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Super Admin Login</h1>
            <p class="text-gray-600">System Administrator Access Only</p>
        </div>

        {{-- Super Admin Login Form --}}
        <form method="POST" action="{{ route('super-admin.login.submit') }}" class="bg-white rounded-lg shadow-lg p-8 space-y-6">
            @csrf

            {{-- Username Field --}}
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Super Admin Username</label>
                <input type="text"
                       id="username"
                       name="username"
                       value="{{ old('username') }}"
                       placeholder="Enter super admin username"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       required>
                @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Field --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Super Admin Password</label>
                <div class="relative">
                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="Enter your password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           required>
                    <button type="button"
                            onclick="togglePassword('password')"
                            class="absolute right-3 top-3 text-gray-600 hover:text-gray-800">
                        <i class="fas fa-eye" id="password-icon"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                    class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                <i class="fas fa-sign-in-alt mr-2"></i> Login as Super Admin
            </button>

            {{-- Back to Landing --}}
            <div class="text-center">
                <a href="/" class="text-sm text-gray-600 hover:text-gray-800">← Back to Landing Page</a>
            </div>
        </form>

        {{-- Footer Info --}}
        <div class="mt-8 p-4 bg-white bg-opacity-50 rounded-lg text-center">
            <p class="text-xs text-gray-600">This is the system administrator portal.</p>
            <p class="text-xs text-gray-600">Only authorized personnel should login here.</p>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const field = document.getElementById(id);
            const icon = document.getElementById(id + '-icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
