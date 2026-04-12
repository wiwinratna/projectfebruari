<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password - NOCIS</title>
    <link rel="icon" href="{{ asset('images/Logo ARISE PNG.png') }}?v=2" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50 p-8">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/Logo ARISE PNG.png') }}?v={{ time() }}"
                 alt="NOA Indonesia"
                 class="h-20 w-auto mx-auto mb-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Set New Password</h1>
            <p class="text-gray-600">Please enter your new password below.</p>
        </div>

        {{-- Reset Form --}}
        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email Field --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ $email ?? old('email') }}"
                       class="w-full px-4 py-3 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                       readonly
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password Field --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <div class="relative">
                    <input type="password"
                           id="password"
                           name="password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                           placeholder="Enter new password"
                           required>
                    <button type="button" onclick="togglePasswordVisibility('password', 'eye-icon-pass')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye" id="eye-icon-pass"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password Field --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                <div class="relative">
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                           placeholder="Confirm new password"
                           required>
                    <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eye-icon-confirm')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye" id="eye-icon-confirm"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                <i class="fas fa-save mr-2"></i>
                Reset Password
            </button>
        </form>
    </div>

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
