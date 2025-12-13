<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'NOCIS - National Olympic Academy of Indonesia')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Meta Tags -->
    <meta name="description" content="NOCIS Job Opportunities - Find exciting career opportunities at major sporting events">
    <meta name="keywords" content="NOCIS, jobs, career, sports, olympics, Indonesia">
    <meta name="author" content="NOCIS">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:title" content="NOCIS Job Opportunities">
    <meta property="og:description" content="Find exciting career opportunities at major sporting events">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
</head>
<body class="bg-gray-50 font-sans">
    <!-- Modern Web3 Floating Header -->
    <header class="fixed top-4 left-0 right-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-4 lg:px-6">
            <div class="bg-white/70 backdrop-blur-xl border border-white/40 shadow-lg shadow-gray-200/20 rounded-2xl mx-auto max-w-7xl transition-all duration-300">
                <div class="flex justify-between items-center h-16 px-4 lg:px-6">
                    <!-- Logo -->
                    <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('images/nocis logo3.png') }}" alt="NOC Logo" class="h-10 w-auto group-hover:scale-105 transition-transform duration-300">
                    </a>

                    <!-- Desktop Navigation -->
                    <nav class="hidden lg:flex items-center gap-8">
                        @php
                            // Robust Profile Photo Logic
                            $headerProfilePhoto = session('customer_profile_photo');
                            
                            // Fallback: Check DB if session is empty but user is logged in
                            if (empty($headerProfilePhoto) && session('customer_id')) {
                                $headerUser = \App\Models\User::with('profile')->find(session('customer_id'));
                                if ($headerUser && $headerUser->profile && $headerUser->profile->profile_photo) {
                                    $headerProfilePhoto = $headerUser->profile->profile_photo;
                                    // Self-heal session
                                    session(['customer_profile_photo' => $headerProfilePhoto]);
                                }
                            }
                        @endphp

                        <a href="{{ route('jobs.index') }}" 
                           class="text-sm font-semibold {{ request()->routeIs('jobs.*') ? 'text-red-600' : 'text-gray-600' }} hover:text-red-600 relative py-2 group transition-colors">
                            Jobs
                            <span class="absolute bottom-0 left-0 h-0.5 bg-red-600 transition-all duration-300 {{ request()->routeIs('jobs.*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                        </a>
                        @if(session('customer_authenticated'))
                            <a href="{{ route('customer.dashboard') }}" 
                               class="text-sm font-semibold {{ request()->routeIs('customer.dashboard') ? 'text-red-600' : 'text-gray-600' }} hover:text-red-600 relative py-2 group transition-colors">
                                Dashboard
                                <span class="absolute bottom-0 left-0 h-0.5 bg-red-600 transition-all duration-300 {{ request()->routeIs('customer.dashboard') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                            </a>
                        @endif
                    </nav>

                    <!-- Desktop Actions -->
                    <div class="hidden lg:flex items-center gap-3">
                        @if(session('customer_authenticated'))
                                <!-- Profile Dropdown -->
                            <div class="relative group" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 pl-2 pr-1 py-1 rounded-full border border-gray-200 hover:border-red-200 hover:bg-red-50 transition-all">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 ring-2 ring-white shadow-md overflow-hidden flex items-center justify-center">
                                        @if(!empty($headerProfilePhoto))
                                            <img src="{{ asset('storage/' . $headerProfilePhoto) }}" alt="Profile" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-600 text-white flex items-center justify-center text-xs font-bold">
                                                {{ strtoupper(substr(session('customer_username') ?? 'U', 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <i class="fas fa-chevron-down text-xs text-gray-400 mr-2 group-hover:text-red-400"></i>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div class="absolute right-0 mt-3 w-56 bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/50 ring-1 ring-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right">
                                    <div class="p-4 border-b border-gray-100/50">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ session('customer_username') }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ session('customer_email') }}</p>
                                    </div>
                                    <div class="p-2 space-y-1">
                                        <a href="{{ route('customer.profile') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                            <div class="w-6 h-6 rounded-lg bg-gray-100 flex items-center justify-center text-xs"><i class="fas fa-user"></i></div>
                                            Profile
                                        </a>
                                        <a href="{{ route('customer.settings') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                            <div class="w-6 h-6 rounded-lg bg-gray-100 flex items-center justify-center text-xs"><i class="fas fa-cog"></i></div>
                                            Settings
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                                <div class="w-6 h-6 rounded-lg bg-red-100 flex items-center justify-center text-xs"><i class="fas fa-sign-out-alt"></i></div>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-all shadow-lg shadow-red-500/20 hover:shadow-red-500/40 hover:-translate-y-0.5">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-semibold text-red-600 bg-red-50/50 hover:bg-red-100 border border-red-100 rounded-xl transition-all">
                                Sign Up
                            </a>
                        @endif
                    </div>

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl text-gray-600 hover:text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>

                <!-- Mobile Menu -->
                <div id="mobile-menu" class="lg:hidden hidden border-t border-gray-100">
                    <div class="p-4 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('jobs.index') }}" class="flex flex-col items-center justify-center p-3 rounded-xl {{ request()->routeIs('jobs.*') ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-600' }} hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fas fa-briefcase mb-1 text-lg"></i>
                                <span class="text-xs font-semibold">Jobs</span>
                            </a>
                            @if(session('customer_authenticated'))
                                <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center justify-center p-3 rounded-xl {{ request()->routeIs('customer.dashboard') ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-600' }} hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <i class="fas fa-chart-pie mb-1 text-lg"></i>
                                    <span class="text-xs font-semibold">Dashboard</span>
                                </a>
                            @endif
                        </div>

                        @if(session('customer_authenticated'))
                            <div class="pt-2 border-t border-gray-100">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl mb-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 overflow-hidden flex items-center justify-center">
                                        @if(!empty($headerProfilePhoto))
                                            <img src="{{ asset('storage/' . $headerProfilePhoto) }}" alt="Profile" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-red-500 text-white flex items-center justify-center text-sm font-bold">
                                                {{ strtoupper(substr(session('customer_username') ?? 'U', 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ session('customer_username') }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ session('customer_email') }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-2">
                                    <a href="{{ route('customer.profile') }}" class="flex items-center px-4 py-3 rounded-xl hover:bg-gray-50 text-sm font-medium text-gray-600">
                                        <i class="fas fa-user w-6 text-center mr-2"></i> Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="w-full flex items-center px-4 py-3 rounded-xl hover:bg-red-50 text-sm font-medium text-red-600">
                                            <i class="fas fa-sign-out-alt w-6 text-center mr-2"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col gap-3 pt-2">
                                <a href="{{ route('login') }}" class="w-full py-3 text-center text-sm font-semibold text-white bg-red-600 rounded-xl shadow-lg shadow-red-500/20">
                                    Login to Account
                                </a>
                                <a href="{{ route('register') }}" class="w-full py-3 text-center text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl">
                                    Create Account
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    @unless(request()->routeIs('customer.profile', 'customer.settings'))
    <footer class="bg-white border-t border-gray-200 py-12 relative z-50">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                <!-- Brand Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-4 mb-6">
                        <!-- Logo Container -->
                        <div class="flex-shrink-0">
                            <img src="{{ asset('images/nocis logo.png') }}" 
                                 alt="NOC Indonesia Logo" 
                                 class="w-96 h-auto object-contain">
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">
                        Transforming Olympic management with cutting-edge technology and seamless operations.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ session('customer_authenticated') ? route('jobs.index') : route('login') }}" class="bg-red-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-600 transition-colors text-center">
                            <i class="fas fa-rocket mr-2"></i>
                            Get Started
                        </a>
                        <a href="{{ route('jobs.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center">
                            <i class="fas fa-briefcase mr-2"></i>
                            Browse Jobs
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-bold text-lg text-gray-900 mb-4">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-red-600 transition-colors">Job Openings</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-red-600 transition-colors">About NOCIS</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-red-600 transition-colors">Events Calendar</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-red-600 transition-colors">Career Tips</a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h3 class="font-bold text-lg text-gray-900 mb-4">Resources</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-600 hover:text-red-600 transition-colors">FAQ & Help</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-red-600 transition-colors">Blog</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-red-600 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-red-600 transition-colors">Terms of Service</a></li>
                    </ul>
                </div>

                <!-- Connect & Newsletter -->
                <div>
                    <h3 class="font-bold text-lg text-gray-900 mb-4">Connect With Us</h3>
                    <div class="flex space-x-3 mb-6">
                        <a href="#" class="text-gray-600 hover:text-red-600 transition-colors"><i class="fab fa-facebook-f text-lg"></i></a>
                        <a href="#" class="text-gray-600 hover:text-red-600 transition-colors"><i class="fab fa-twitter text-lg"></i></a>
                        <a href="#" class="text-gray-600 hover:text-red-600 transition-colors"><i class="fab fa-instagram text-lg"></i></a>
                        <a href="#" class="text-gray-600 hover:text-red-600 transition-colors"><i class="fab fa-linkedin-in text-lg"></i></a>
                    </div>

                    <h4 class="font-semibold text-gray-800 mb-3">Newsletter</h4>
                    <p class="text-sm text-gray-600 mb-3">Get the latest job opportunities delivered to your inbox</p>
                    <form class="space-y-3">
                        <input type="email" placeholder="Your email address"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-medium transition-colors">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-gray-200 mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center">
                <div class="text-sm text-gray-500 mb-4 sm:mb-0">
                    &copy; {{ date('Y') }}  of Indonesia. All rights reserved.
                </div>
                <div class="flex space-x-6 text-sm text-gray-500">
                    <a href="#" class="hover:text-red-600 transition-colors">Privacy</a>
                    <a href="#" class="hover:text-red-600 transition-colors">Terms</a>
                    <a href="#" class="hover:text-red-600 transition-colors">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>
    @endunless

    <!-- Flash Messages -->
    @include('components.flash')

    <!-- Scripts -->
    <script src="{{ asset('js/jobs.js') }}"></script>
    <script>
        // Initialize all functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuToggle = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');

            }
        });

        // --- Public Flash Message System ---
        function showPublicFlash(message, type = 'success') {
            let container = document.getElementById('public-flash-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'public-flash-container';
                container.className = 'fixed top-24 right-4 z-[9999] space-y-3';
                document.body.appendChild(container);
            }

            const isSuccess = type === 'success';
            const bgColor = isSuccess ? 'bg-green-50/90 border-green-200 text-green-800' : 'bg-red-50/90 border-red-200 text-red-800';
            const icon = isSuccess ? 'fa-check-circle text-green-500' : 'fa-exclamation-circle text-red-500';

            const toast = document.createElement('div');
            toast.className = `flex items-center gap-3 px-4 py-3 rounded-xl border shadow-lg backdrop-blur-md transition-all duration-300 transform translate-x-full opacity-0 ${bgColor}`;
            toast.style.maxWidth = '350px';
            toast.innerHTML = `
                <i class="fas ${icon} text-lg"></i>
                <div class="flex-1 text-sm font-semibold">${message}</div>
                <button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100 transition"><i class="fas fa-times"></i></button>
            `;

            container.appendChild(toast);

            // Animate In
            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            });

            // Auto Dismiss
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Check for server-side flashes
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', () => showPublicFlash("{{ session('success') }}", 'success'));
        @endif
        @if(session('error'))
            document.addEventListener('DOMContentLoaded', () => showPublicFlash("{{ session('error') }}", 'error'));
        @endif
    </script>
</body>
</html>