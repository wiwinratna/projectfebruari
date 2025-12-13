@extends('layouts.public')

@section('title', 'NOCIS - National Olympic Academy of Indonesia')

@section('content')
<style>
    /* Modern UI Styles */
    :root {
        --primary: #3182ce;
        --primary-dark: #2c5282;
        --primary-light: #ebf8ff;
        --secondary: #1a365d;
        --accent: #667eea;
        --text-dark: #1a202c;
        --text-light: #4a5568;
        --border: #e2e8f0;
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --red: #ef4444;
        --red-dark: #dc2626;
        --red-light: #fee2e2;
    }

    body {
        font-family: 'Inter', sans-serif;
    }

    .hero-gradient {
        background: linear-gradient(180deg, #ef4444 0%, #fca5a5 30%, #fef2f2 70%, #ffffff 100%);
        position: relative;
        overflow: hidden;
    }

    .hero-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></svg>');
        pointer-events: none;
    }

    .card-hover {
        transition: var(--transition);
        transform: translateY(0);
        box-shadow: var(--shadow);
    }

    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        transition: var(--transition);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -5px rgba(49, 130, 206, 0.4);
    }

    .btn-red {
        background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
        transition: var(--transition);
    }

    .btn-red:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -5px rgba(220, 38, 38, 0.4);
    }

    .job-badge {
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: var(--primary);
    }

    .stats-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .floating-animation {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(2deg); }
    }

    .pulse-animation {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .8; }
    }

    .scroll-indicator {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40%, 60% { transform: translateY(-10px); }
    }

    /* Glassmorphism Effect */
    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Rotating Text Cursor Animation */
    #cursor-rotating {
        animation: blink 1s infinite;
    }

    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0; }
    }

    /* Rotating text styling */
    #typed-rotating {
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        line-height: 1.2;
        text-align: center;
        display: inline-block;
        min-height: 2rem;
    }

    /* Animated background elements */
    .floating-icon {
        animation: float 6s ease-in-out infinite;
    }

    .floating-icon:nth-child(2) {
        animation-delay: -2s;
    }

    .floating-icon:nth-child(3) {
        animation-delay: -4s;
    }

    /* Pulse animation for elements */
    .pulse-glow {
        animation: pulse-glow 2s ease-in-out infinite alternate;
    }

    @keyframes pulse-glow {
        from {
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
        }
        to {
            box-shadow: 0 0 40px rgba(239, 68, 68, 0.6);
        }
    }

    /* Slide in animation */
    .slide-in {
        animation: slideIn 0.8s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Modern Web3 Hero Section -->
<section class="relative min-h-[90vh] flex items-center overflow-hidden">
    <!-- Main Background with Seamless Transition -->
    <div class="absolute inset-0 bg-gradient-to-b from-white via-white to-gray-50 -z-30"></div>
    
    <!-- Hero Background Orbs (Web3 Style) -->
    <div class="absolute top-[-10%] right-[-5%] w-[800px] h-[800px] bg-gradient-to-br from-red-100/30 to-orange-100/30 rounded-full filter blur-[120px] -z-20 animate-pulse-slow"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[800px] h-[800px] bg-gradient-to-tr from-blue-50/30 to-red-50/20 rounded-full filter blur-[120px] -z-20 animate-pulse-slow" style="animation-delay: 2s;"></div>
    
    <!-- Floating Glass Elements -->
    <div class="absolute top-1/4 left-10 w-24 h-24 bg-white/40 border border-white/60 rounded-2xl backdrop-blur-md -z-10 rotate-12 animate-float shadow-lg shadow-red-500/5"></div>
    <div class="absolute bottom-1/4 right-10 w-32 h-32 bg-gradient-to-br from-red-500/5 to-white/40 border border-white/60 rounded-full backdrop-blur-md -z-10 -rotate-12 animate-float shadow-lg shadow-red-500/5" style="animation-delay: -3s;"></div>

    <div class="container mx-auto px-4 relative z-10 pt-20">
        <div class="max-w-5xl mx-auto text-center">
            <!-- Main Content -->
            <div class="space-y-8 slide-in">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-xl border border-white/60 px-5 py-2.5 rounded-full shadow-lg shadow-gray-200/20 hover:shadow-red-500/10 transition-all duration-300 ring-1 ring-white/50">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                    <span class="text-sm font-semibold text-gray-600 tracking-wide">Welcome to <span class="text-red-600">NOCIS</span></span>
                </div>

                <!-- Hero Title -->
                <div class="space-y-6 relative">
                    <!-- Text Glow Effect behind title -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-white/50 filter blur-3xl -z-10"></div>
                    
                    <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight text-gray-900 leading-[1.1] drop-shadow-sm">
                        National Olympic<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 via-red-500 to-orange-500">
                            Academy of Indonesia
                        </span>
                    </h1>
                    
                    <!-- Typewriter Container -->
                    <div class="h-16 flex items-center justify-center">
                        <div class="bg-white/40 backdrop-blur-sm px-6 py-2 rounded-2xl border border-white/50 inline-flex items-center gap-3 shadow-sm">
                            <span class="text-red-400/70 text-xl"><i class="fas fa-terminal"></i></span>
                            <span class="text-xl lg:text-2xl text-gray-600 font-medium font-mono min-w-[200px] text-left">
                                <span id="typed-rotating"></span>
                                <span id="cursor-rotating" class="inline-block w-2.5 h-6 bg-red-500/80 ml-1 align-middle rounded-sm"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto pt-8">
                    <!-- Stat 1 -->
                    <div class="group bg-white/40 backdrop-blur-xl border border-white/60 p-6 rounded-2xl hover:-translate-y-1 transition-all duration-500 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(239,68,68,0.1)]">
                        <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-red-50 to-white rounded-xl mb-4 mx-auto text-red-600 group-hover:scale-110 transition-transform shadow-sm">
                            <i class="fas fa-medal text-xl"></i>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">500+</div>
                        <div class="text-sm font-medium text-gray-500">Athletes Trained</div>
                    </div>

                    <!-- Stat 2 -->
                    <div class="group bg-white/40 backdrop-blur-xl border border-white/60 p-6 rounded-2xl hover:-translate-y-1 transition-all duration-500 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(239,68,68,0.1)]">
                        <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-red-50 to-white rounded-xl mb-4 mx-auto text-red-600 group-hover:scale-110 transition-transform shadow-sm">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">50+</div>
                        <div class="text-sm font-medium text-gray-500">Events Organized</div>
                    </div>

                    <!-- Stat 3 -->
                    <div class="group bg-white/40 backdrop-blur-xl border border-white/60 p-6 rounded-2xl hover:-translate-y-1 transition-all duration-500 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(239,68,68,0.1)]">
                        <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-red-50 to-white rounded-xl mb-4 mx-auto text-red-600 group-hover:scale-110 transition-transform shadow-sm">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">98%</div>
                        <div class="text-sm font-medium text-gray-500">Success Rate</div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-8 pb-12">
                    <a href="{{ session('customer_authenticated') ? route('jobs.index') : route('login') }}" class="group relative px-8 py-4 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white rounded-xl font-bold text-lg shadow-xl shadow-red-500/20 hover:shadow-red-500/40 transition-all hover:-translate-y-0.5 overflow-hidden">
                        <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full transition-transform duration-500 -skew-x-12"></div>
                        <span class="relative flex items-center gap-2">
                            <i class="fas fa-rocket"></i> Get Started
                        </span>
                    </a>

                    <a href="{{ route('jobs.index') }}" class="group px-8 py-4 bg-white/80 backdrop-blur-sm hover:bg-white text-gray-700 hover:text-red-600 border border-gray-200/60 hover:border-red-200 rounded-xl font-bold text-lg shadow-lg shadow-gray-200/20 hover:shadow-red-500/10 transition-all hover:-translate-y-0.5">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-briefcase group-hover:text-red-500 transition-colors"></i> Browse Jobs
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Seamless Gradient Fade into Next Section -->
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-b from-transparent to-gray-50 pointer-events-none"></div>
</section>

<!-- Modern Web3 Job Listings Section -->
<section id="jobs" class="relative py-20 overflow-hidden bg-gray-50">
    <!-- Continuing Background Decor -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] -z-10 animate-pulse"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] -z-10 animate-pulse" style="animation-duration: 4s;"></div>

    <div class="container mx-auto px-4 relative z-10 max-w-7xl">
        <!-- Header -->
        <div class="max-w-3xl mx-auto text-center mb-16 slide-in">
            <div class="inline-flex items-center gap-2 bg-white/50 backdrop-blur-sm border border-red-100 text-red-600 px-4 py-2 rounded-full text-sm font-medium mb-6 shadow-sm hover:shadow-md transition-all cursor-default">
                <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                Career Opportunities
            </div>
            
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6 tracking-tight">
                Join the <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-red-400">Revolution</span> in<br>Sports Management
            </h2>
            
            <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                Be part of the future. Discover roles that challenge the status quo and push the boundaries of what's possible in the Olympic movement.
            </p>
        </div>

        @if($recentJobs && $recentJobs->count() > 0)
            <div class="grid lg:grid-cols-3 gap-8 mb-16">
                @foreach($recentJobs->take(3) as $job)
                <div class="group relative bg-white/60 backdrop-blur-xl border border-white/50 p-1 rounded-2xl hover:-translate-y-2 transition-all duration-500 shadow-xl hover:shadow-2xl hover:shadow-red-500/10">
                    <!-- Gradient Border Effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-blue-500/5 rounded-2xl -z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <div class="relative h-full bg-white/40 rounded-xl p-6 flex flex-col">
                        <!-- Card Header -->
                        <div class="flex justify-between items-start mb-6">
                            <span class="inline-block bg-white rounded-lg px-3 py-1 text-xs font-semibold text-red-600 shadow-sm border border-red-50">
                                {{ $job->jobCategory->name ?? 'General' }}
                            </span>
                            <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-arrow-right -rotate-45 group-hover:rotate-0 transition-transform duration-300"></i>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="mb-6 flex-grow">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight group-hover:text-red-600 transition-colors">
                                <a href="{{ route('jobs.show', $job) }}">
                                    {{ Str::limit($job->title, 40) }}
                                </a>
                            </h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-4">
                                {{ Str::limit($job->description ?? 'Join our dynamic team in the Olympic movement.', 80) }}
                            </p>
                            
                            <!-- Tags -->
                            <div class="flex flex-wrap gap-2 text-xs font-medium text-gray-500">
                                <div class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded">
                                    <i class="fas fa-map-marker-alt text-red-400"></i>
                                    {{ $job->event->city->name ?? 'Location' }}
                                </div>
                                <div class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded">
                                    <i class="fas fa-trophy text-red-400"></i>
                                    {{ Str::limit($job->event->title ?? 'Event', 15) }}
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="pt-6 border-t border-gray-100/50 mt-auto">
                            <div class="flex items-center justify-between mb-4">
                                <div class="text-xs text-gray-500">
                                    <span class="block text-gray-400 text-[10px] uppercase tracking-wider">Deadline</span>
                                    {{ $job->application_deadline->format('M d, Y') }}
                                </div>
                                <div class="text-right">
                                    <span class="block text-gray-400 text-[10px] uppercase tracking-wider">Slots</span>
                                    <span class="font-bold text-gray-700">{{ $job->slots_filled }}/{{ $job->slots_total }}</span>
                                </div>
                            </div>
                            
                            <a href="{{ route('jobs.show', $job) }}" class="block w-full py-3 bg-red-600 hover:bg-red-700 text-white text-center rounded-xl font-medium transition-all duration-300 shadow-lg group-hover:shadow-red-500/25">
                                Apply Now
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white/60 backdrop-blur-xl border border-white/50 rounded-3xl p-12 text-center max-w-2xl mx-auto shadow-xl mb-12">
                <div class="w-20 h-20 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-6 animate-bounce">
                    <i class="fas fa-search text-3xl text-red-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Openings Currently</h3>
                <p class="text-gray-500 mb-8">We're expanding our horizons. Check back soon for new opportunities.</p>
                <a href="{{ route('jobs.index') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-xl font-medium transition-all shadow-lg hover:shadow-red-500/30">
                    View Archive
                </a>
            </div>
        @endif

        <div class="text-center">
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition-colors group">
                View All Opportunities
                <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>

<!-- About Section with Modern Design -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="relative">
                <div class="bg-primary/5 rounded-3xl p-4 relative overflow-hidden flex items-center justify-center">
                    <img src="{{ asset('images/logo NOA indonesia.png') }}" alt="Indonesia Olympic Logo" class="w-full h-auto object-contain">
                    <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-primary/20 rounded-full"></div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <span class="inline-block bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        About NOCIS
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                        Revolutionizing <span class="text-primary">Olympic Management</span>
                    </h2>
                    <p class="text-lg text-gray-600 mb-6">
                        The National Olympic Academy of Indonesia System (NOCIS) is a comprehensive digital platform designed to streamline and enhance the management of Olympic committee operations.
                    </p>
                    <p class="text-gray-600 mb-8">
                        Our system integrates event management, worker coordination, job categorization, and advanced analytics to provide a unified solution for national Olympic Academy.
                    </p>
                </div>

                <!-- Stats with Modern Design -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-primary/5 p-6 rounded-2xl text-center">
                        <div class="text-3xl font-bold text-primary mb-2">10+</div>
                        <div class="text-sm text-gray-600 font-medium">Managed Events</div>
                        <div class="text-xs text-gray-500 mt-1">International & National</div>
                    </div>
                    <div class="bg-primary/5 p-6 rounded-2xl text-center">
                        <div class="text-3xl font-bold text-primary mb-2">500+</div>
                        <div class="text-sm text-gray-600 font-medium">Registered Workers</div>
                        <div class="text-xs text-gray-500 mt-1">Active Professionals</div>
                    </div>
                    <div class="bg-primary/5 p-6 rounded-2xl text-center">
                        <div class="text-3xl font-bold text-primary mb-2">20+</div>
                        <div class="text-sm text-gray-600 font-medium">Job Categories</div>
                        <div class="text-xs text-gray-500 mt-1">Specialized Roles</div>
                    </div>
                    <div class="bg-primary/5 p-6 rounded-2xl text-center">
                        <div class="text-3xl font-bold text-primary mb-2">95%</div>
                        <div class="text-sm text-gray-600 font-medium">Satisfaction Rate</div>
                        <div class="text-xs text-gray-500 mt-1">Client Feedback</div>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="pt-6">
                    <a href="{{ session('customer_authenticated') ? route('jobs.index') : route('login') }}" class="bg-red-500 w-full text-center text-white px-6 py-3 rounded-lg font-medium inline-flex items-center justify-center text-lg shadow-lg hover:bg-red-600 transition-colors">
                        <i class="fas fa-play mr-3"></i>
                        Experience NOCIS Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section with Modern Form -->
<section class="py-20 bg-gradient-to-br from-primary/5 to-secondary/5">
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="grid lg:grid-cols-2 gap-12">
            <div class="space-y-6">
                <div>
                    <span class="inline-block bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium mb-4">
                        <i class="fas fa-envelope mr-2"></i>
                        Get in Touch
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        Connect With <span class="text-primary">Our Team</span>
                    </h2>
                    <p class="text-lg text-gray-600 mb-8">
                        Have questions or need support? Our dedicated team is ready to assist you with any inquiries about the NOCIS platform.
                    </p>
                </div>

                <!-- Contact Info with Icons -->
                <div class="space-y-6">
                    <div class="flex items-start p-4 bg-white rounded-xl shadow-sm">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-primary/10 mr-4 flex-shrink-0">
                            <i class="fas fa-envelope text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Email Support</h3>
                            <a href="mailto:contact@noc-indonesia.org" class="text-primary hover:text-primary-dark transition-colors">contact@noc-indonesia.org</a>
                        </div>
                    </div>

                    <div class="flex items-start p-4 bg-white rounded-xl shadow-sm">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-primary/10 mr-4 flex-shrink-0">
                            <i class="fas fa-phone text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Phone Support</h3>
                            <a href="tel:+62211234567" class="text-primary hover:text-primary-dark transition-colors">+62 21 123 4567</a>
                        </div>
                    </div>

                    <div class="flex items-start p-4 bg-white rounded-xl shadow-sm">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-primary/10 mr-4 flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Headquarters</h3>
                            <address class="not-italic text-gray-600">Jl. Olympic No. 123, Jakarta, Indonesia</address>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Contact Form -->
            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                    Send Us a Message
                </h3>

                <form class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" placeholder="Your full name"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="your@email.com"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="Brief description"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Your Message</label>
                        <textarea id="message" name="message" rows="5" placeholder="How can we help you?"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white py-3 px-6 rounded-lg font-medium transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
// Rotating Typewriter Effect for Multiple Phrases
document.addEventListener('DOMContentLoaded', function() {
    const typedTextElement = document.getElementById('typed-rotating');
    const cursorElement = document.getElementById('cursor-rotating');
    
    // Array of rotating phrases
    const phrases = [
        "Empowering Olympic Excellence",
        "Shaping Future Champions",
        "Building Sports Leadership",
        "Creating Olympic Opportunities",
        "Developing Athletic Potential",
        "Fostering Sports Innovation"
    ];
    
    let phraseIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    let typingSpeed = 80;
    
    function typeWriter() {
        const currentPhrase = phrases[phraseIndex];
        
        if (isDeleting) {
            // Deleting characters
            typedTextElement.textContent = currentPhrase.substring(0, charIndex - 1);
            charIndex--;
        } else {
            // Typing characters
            typedTextElement.textContent = currentPhrase.substring(0, charIndex + 1);
            charIndex++;
        }
        
        let typeSpeed = typingSpeed;
        
        // Pause at the end of typing
        if (!isDeleting && charIndex === currentPhrase.length) {
            typeSpeed = 2000;
            isDeleting = true;
        }
        // Pause before starting next phrase
        else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            phraseIndex = (phraseIndex + 1) % phrases.length; // Loop through phrases
            typeSpeed = 500;
        }
        
        // Faster deleting than typing
        if (isDeleting) {
            typeSpeed /= 2;
        }
        
        setTimeout(typeWriter, typeSpeed);
    }
    
    // Start typing effect after a short delay
    setTimeout(typeWriter, 1500);
});
</script>

@endsection
