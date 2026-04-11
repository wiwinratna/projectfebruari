<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NOCIS</title>
    <link rel="icon" href="{{ asset('images/Logo ARISE PNG.png') }}?v=2" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/Logo ARISE PNG.png') }}?v=2" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo ARISE PNG.png') }}?v=2">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50 p-8">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/Logo ARISE PNG.png') }}?v={{ time() }}"
                 alt="NOA Indonesia"
                 class="h-20 w-auto mx-auto mb-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Login Account</h1>
            <p class="text-gray-600">Welcome back to ARISE</p>
        </div>

        {{-- General Authentication Error --}}
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6" id="generalError">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Login Failed</h3>
                        <p class="mt-1 text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- General Flash Error --}}
        @if ($errors->has('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6" id="generalError">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Login Failed</h3>
                        <p class="mt-1 text-sm text-red-700">{{ $errors->first('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Success Messages --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i class="fas fa-check-circle text-green-400 mr-2 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-medium text-green-800">Success</h3>
                        <p class="mt-1 text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Login Form --}}
        <form id="loginForm" method="POST" action="{{ route('login.submit') }}" class="space-y-6">
            @csrf

            {{-- Email Field --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Enter your email"
                       class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors {{ $errors->has('email') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-red-500 focus:border-red-500' }}"
                       >
                @error('email')
                    <p class="mt-1 text-sm text-red-600 err-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Field --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="Enter your password"
                           class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors pr-12 {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-red-500 focus:border-red-500' }}"
                           >
                    <button type="button"
                            id="toggle-password"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600 err-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center">
                <input type="checkbox"
                       id="remember"
                       name="remember"
                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    Remember me
                </label>
            </div>

            {{-- Login Button --}}
            <button type="submit"
                    id="submitBtn"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                Login Account
            </button>

            {{-- Footer Links --}}
            <div class="text-center space-y-2">
                <p class="text-sm text-gray-600">
                    Forgot your password?
                    <a href="{{ route('password.request') }}" class="text-red-600 hover:text-red-700 font-medium">Reset here</a>
                </p>
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-red-600 hover:text-red-700 font-medium">Register here</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                this.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Priority 1: Focus on the first validation error field
            const firstErrorMsg = document.querySelector('.err-msg');
            if (firstErrorMsg) {
                const parentDiv = firstErrorMsg.closest('div');
                if (parentDiv) {
                    const input = parentDiv.querySelector('input');
                    if (input) {
                        input.focus();
                        return; // Exit here since we focused on error
                    }
                }
            }

            // Priority 2: Focus on general error (e.g. Email/Password incorrect)
            const generalError = document.getElementById('generalError');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            if (generalError && emailInput) {
                // Keep the cursor on email if it was a generic auth failure
                emailInput.focus();
                return;
            }

            // Priority 3: Auto-focus first empty field on initial load
            if (!emailInput.value) {
                emailInput.focus();
            } else {
                passwordInput.focus();
            }
        });

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');

            // Disable button gently to prevent double submit
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        });
    </script>
</body>
</html>
