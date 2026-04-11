<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - NOCIS</title>
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
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Register Account</h1>
            <p class="text-gray-600">Create your NOCIS account</p>
        </div>

        {{-- General Flash Error --}}
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2 mt-0.5"></i>
                    <div>
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-red-800">We couldn't create your account. Please check the form and try again.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Register Form --}}
        <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                {{-- First Name --}}
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                    <input type="text"
                           id="first_name"
                           name="first_name"
                           value="{{ old('first_name') }}"
                           class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors {{ $errors->has('first_name') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-red-500 focus:border-red-500' }}"
                           >
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-600 err-msg">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Last Name --}}
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                    <input type="text"
                           id="last_name"
                           name="last_name"
                           value="{{ old('last_name') }}"
                           class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors {{ $errors->has('last_name') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-red-500 focus:border-red-500' }}"
                           >
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600 err-msg">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors {{ $errors->has('email') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-red-500 focus:border-red-500' }}"
                       >
                @error('email')
                    <p class="mt-1 text-sm text-red-600 err-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Username --}}
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text"
                       id="username"
                       name="username"
                       value="{{ old('username') }}"
                       class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors {{ $errors->has('username') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-red-500 focus:border-red-500' }}"
                       >
                @error('username')
                    <p class="mt-1 text-sm text-red-600 err-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <input type="password"
                           id="password"
                           name="password"
                           class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors pr-12 {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-gray-300 focus:border-gray-400' }}"
                           >
                    <button type="button"
                            id="toggle-password"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                
                {{-- Password Criteria Indicator --}}
                <div id="password-criteria" class="mt-3 text-sm space-y-1">
                    <p class="flex items-center text-gray-400 transition-colors" data-criterion="length">
                        <i class="fas fa-circle text-[8px] mr-2"></i> At least 8 characters
                    </p>
                    <p class="flex items-center text-gray-400 transition-colors" data-criterion="uppercase">
                        <i class="fas fa-circle text-[8px] mr-2"></i> Contains uppercase letter
                    </p>
                    <p class="flex items-center text-gray-400 transition-colors" data-criterion="lowercase">
                        <i class="fas fa-circle text-[8px] mr-2"></i> Contains lowercase letter
                    </p>
                    <p class="flex items-center text-gray-400 transition-colors" data-criterion="number">
                        <i class="fas fa-circle text-[8px] mr-2"></i> Contains number
                    </p>
                    <p class="flex items-center text-gray-400 transition-colors" data-criterion="symbol">
                        <i class="fas fa-circle text-[8px] mr-2"></i> Contains symbol
                    </p>
                </div>

                @error('password')
                    <p class="mt-1 text-sm text-red-600 err-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <div class="relative">
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 transition-colors pr-12 {{ $errors->has('password_confirmation') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-red-500 focus:border-red-500' }}"
                           >
                    <button type="button"
                            id="toggle-password-confirm"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600 err-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Terms & Conditions --}}
            <div>
                <div class="flex items-center">
                    <input type="checkbox"
                           id="terms"
                           name="terms"
                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded {{ $errors->has('terms') ? 'border-red-500 ring-1 ring-red-500' : '' }}"
                           >
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        I agree to the <a href="#" class="text-red-600 hover:text-red-700">Terms & Conditions</a> and <a href="#" class="text-red-600 hover:text-red-700">Privacy Policy</a>
                    </label>
                </div>
                @error('terms')
                    <p class="mt-1 text-sm text-red-600 err-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Register Button --}}
            <button type="submit"
                    id="submitBtn"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                Create Account
            </button>

            {{-- Footer Links --}}
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-red-600 hover:text-red-700 font-medium">Login here</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto focus on first error
            const firstErrorMsg = document.querySelector('.err-msg');
            if (firstErrorMsg) {
                const parentDiv = firstErrorMsg.closest('div');
                if (parentDiv) {
                    const input = parentDiv.querySelector('input');
                    if (input) {
                        input.focus();
                    }
                }
            }
        });

        document.getElementById('registerForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');

            // Disable button gently to prevent double submit
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        });

        // Toggle password visibility
        function setupPasswordToggle(toggleBtnId, inputId) {
            const toggleBtn = document.getElementById(toggleBtnId);
            if (!toggleBtn) return;
            
            toggleBtn.addEventListener('click', function() {
                const passwordInput = document.getElementById(inputId);

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    passwordInput.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        }

        setupPasswordToggle('toggle-password', 'password');
        setupPasswordToggle('toggle-password-confirm', 'password_confirmation');

        // Real-time password criteria check
        const passwordInput = document.getElementById('password');
        const criteriaItems = document.querySelectorAll('#password-criteria p');

        if (passwordInput && criteriaItems.length > 0) {
            passwordInput.addEventListener('input', function() {
                const val = passwordInput.value;
                const hasStartedTyping = val.length > 0;

                const checks = {
                    length: val.length >= 8,
                    uppercase: /[A-Z]/.test(val),
                    lowercase: /[a-z]/.test(val),
                    number: /[0-9]/.test(val),
                    symbol: /[^A-Za-z0-9]/.test(val),
                };

                criteriaItems.forEach(item => {
                    const criterion = item.dataset.criterion;
                    const icon = item.querySelector('i');
                    
                    if (!hasStartedTyping) {
                        item.className = 'flex items-center text-gray-400 transition-colors duration-200';
                        icon.className = 'fas fa-circle text-[8px] mr-2';
                    } else if (checks[criterion]) {
                        item.className = 'flex items-center text-green-600 transition-colors duration-200';
                        icon.className = 'fas fa-check-circle text-xs mr-2';
                    } else {
                        item.className = 'flex items-center text-red-600 transition-colors duration-200';
                        icon.className = 'fas fa-times-circle text-xs mr-2';
                    }
                });
            });
        }
    </script>
</body>
</html>
