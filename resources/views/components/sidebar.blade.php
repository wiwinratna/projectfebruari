<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="lg:hidden"></div>

<div class="main-sidebar sidebar-style-2 bg-white text-gray-700 shadow-lg" id="sidebar">
    <aside id="sidebar-wrapper">

        <div class="sidebar-brand p-4 border-b border-gray-200 relative">
            <div class="flex items-center justify-center">
                @if(session('admin_authenticated'))
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center">
                @else
                    <a href="{{ route('jobs.index') }}" class="flex items-center justify-center">
                @endif
                        <img src="{{ asset('images/Logo ARISE PNG.png') }}?v={{ time() }}"
                             alt="NOA Indonesia"
                             class="logo-img block">
                    </a>
            </div>
            <button id="sidebar-close" class="lg:hidden text-gray-500 hover:text-gray-700 absolute top-4 right-4 transition-colors duration-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <ul class="sidebar-menu mt-8">

            @if(session('admin_authenticated'))
                {{-- Dashboard Section --}}
                <li class="px-6 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Dashboard</li>

                <li class="menu-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home mr-3"></i>
                        <span>Admin Dashboard</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('admin/analytics') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.analytics') }}">
                        <i class="fas fa-chart-line mr-3"></i>
                        <span>Analytics Dashboard</span>
                    </a>
                </li>

                {{-- Event Management Section --}}
                <li class="px-6 py-2 mt-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Event Management</li>

                <li class="menu-item {{ Request::is('admin/events*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.events.index') }}">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        <span>Events</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('admin/sports*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.sports.index') }}">
                        <i class="fas fa-running mr-3"></i>
                        <span>Sports Master</span>
                    </a>
                </li>

                {{-- Recruitment Section --}}
                <li class="px-6 py-2 mt-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Recruitment</li>

                <li class="menu-item {{ Request::is('admin/workers*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.workers.index') }}">
                        <i class="fas fa-users mr-3"></i>
                        <span>Job Openings</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('admin/reviews*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.reviews.index') }}">
                        <i class="fas fa-edit mr-3"></i>
                        <span>Applications</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::is('admin/categories*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.categories.index') }}">
                        <i class="fas fa-tags mr-3"></i>
                        <span>Job Categories</span>
                    </a>
                </li>

                {{-- News / Updates Section --}}
                <li class="px-6 py-2 mt-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Content</li>

                <li class="menu-item {{ Request::is('admin/news*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.news.index') }}">
                        <i class="fas fa-newspaper mr-3"></i>
                        <span>News</span>
                    </a>
                </li>


                {{-- Account Section --}}
                <li class="px-6 py-2 mt-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Account</li>

                <li class="menu-item {{ Request::is('admin/profile*') ? 'active' : '' }}">
                    <a class="nav-link text-red-600 font-bold hover:bg-red-50" href="{{ route('admin.profile') }}">
                        <i class="fas fa-user-cog mr-3"></i>
                        <span>Profile & Settings</span>
                    </a>
                </li>
            @else
                {{-- Guest/Customer Menu Items (same for both) --}}
                <li class="menu-item {{ Request::is('jobs*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('jobs.index') }}">
                        <i class="fas fa-briefcase mr-3"></i>
                        <span>Browse Jobs</span>
                    </a>
                </li>

                @if(session('customer_authenticated'))
                    <li class="menu-item">
                        <a class="nav-link" href="{{ route('customer.applications') }}">
                            <i class="fas fa-file-alt mr-3"></i>
                            <span>My Applications</span>
                        </a>
                    </li>
                @endif
            @endif

        </ul>

        <div class="sidebar-footer p-4 mt-auto">
             <div class="flex justify-around">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                <span class="w-3 h-3 rounded-full bg-pink-500"></span>
            </div>
        </div>

    </aside>
</div>
