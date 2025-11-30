<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - NOCIS</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50 p-8">
    
    <div class="w-full max-w-md">
        
        {{-- Logo --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/indonesia-olympic-logo.png') }}?v={{ time() }}" 
                 alt="Indonesia Olympic Committee" 
                 class="h-20 w-auto mx-auto mb-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Reset Password</h1>
            <p class="text-gray-600">Enter your email to receive reset instructions</p>
        </div>

        {{-- Reset Form --}}
        <form method="POST" action="{{ route('password.request') }}" class="space-y-6">
            @csrf
            
            {{-- Email Field --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Enter your email address"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit" 
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                <i class="fas fa-paper-plane mr-2"></i>
                Send Reset Instructions
            </button>

            {{-- Success Message --}}
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-400 mr-2 mt-0.5"></i>
                        <div>
                            <h3 class="text-sm font-medium text-green-800">Email Sent!</h3>
                            <p class="mt-1 text-sm text-green-700">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Footer Links --}}
            <div class="text-center space-y-2">
                <p class="text-sm text-gray-600">
                    Remember your password? 
                    <a href="{{ route('login') }}" class="text-red-600 hover:text-red-700 font-medium">Back to Login</a>
                </p>
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-red-600 hover:text-red-700 font-medium">Register here</a>
                </p>
            </div>
        </form>

        {{-- Help Section --}}
        <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex">
                <i class="fas fa-info-circle text-blue-400 mr-2 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-medium text-blue-800">Need Help?</h3>
                    <p class="mt-1 text-sm text-blue-700">
                        If you don't receive the email within a few minutes, please check your spam folder or contact support.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>









