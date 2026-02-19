<header class="bg-white shadow-md h-16 flex items-center justify-between px-4 lg:px-6 sticky top-0 z-50">
    <div class="flex items-center">
        <!-- Mobile hamburger menu -->
        <button id="sidebar-toggle" class="lg:hidden mr-4 text-gray-600 hover:text-gray-800 focus:outline-none">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <h2 class="text-lg lg:text-xl font-semibold text-gray-800">
            @if(request()->is('jobs*'))
                Job Opportunities
            @elseif(request()->is('admin*'))
                Admin Dashboard
            @elseif(request()->is('super-admin*'))
                Super Admin Dashboard
            @else
                @yield('page-title', 'NOCIS')
            @endif
        </h2>
    </div>

    <div class="flex items-center space-x-2 lg:space-x-4">
        <!-- Search - hidden on small screens -->
        <div class="relative hidden md:block">
            <input type="text" placeholder="Search..." class="border border-gray-300 rounded-lg py-2 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-red-500 w-48 lg:w-64">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>

        <!-- Mobile search icon -->
        <button class="md:hidden text-gray-500 hover:text-gray-700">
            <i class="fas fa-search text-lg"></i>
        </button>

        <div class="relative">
            <i class="fas fa-bell text-gray-500 cursor-pointer text-lg lg:text-xl"></i>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 lg:w-5 lg:h-5 flex items-center justify-center text-xs">3</span>
        </div>

        <!-- Profile Dropdown -->
        @if(session('customer_authenticated'))
            @php
                $customerId = session('customer_id');
                $user = \App\Models\User::find($customerId);
                $profilePhoto = $user->profile_photo ? asset('storage/' . $user->profile_photo) : null;
                $initials = strtoupper(substr($user->name ?? $user->username ?? 'U', 0, 2));
            @endphp
            <div class="relative">
                <div class="profile-dropdown-trigger w-8 h-8 lg:w-10 lg:h-10 rounded-full overflow-hidden bg-red-500 flex items-center justify-center text-white font-semibold text-sm cursor-pointer border-2 border-gray-200 hover:border-red-300 transition-colors">
                    @if($profilePhoto)
                        <img src="{{ $profilePhoto }}" alt="Profile" class="w-full h-full object-cover">
                    @else
                        {{ $initials }}
                    @endif
                </div>
                <!-- Dropdown Menu -->
                <div class="profile-dropdown-menu absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible transition-all duration-200 z-50">
                    <div class="py-2">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-red-500 flex items-center justify-center text-white font-semibold text-sm mr-3">
                                    @if($profilePhoto)
                                        <img src="{{ $profilePhoto }}" alt="Profile" class="w-full h-full object-cover">
                                    @else
                                        {{ $initials }}
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $user->name ?? session('customer_username') }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email ?? 'user@email.com' }}</p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('customer.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-user mr-3"></i>Profile Saya
                        </a>
                        <a href="{{ route('customer.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-cog mr-3"></i>Pengaturan
                        </a>
                        <div class="border-t border-gray-100 mt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-3"></i>Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(session('super_admin_authenticated'))
            @php
                $superAdminName = session('super_admin_username') ?? 'SA';
                $initials = strtoupper(substr($superAdminName, 0, 2));
            @endphp
            <div class="relative">
                <div class="profile-dropdown-trigger w-8 h-8 lg:w-10 lg:h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold text-sm cursor-pointer border-2 border-transparent hover:border-blue-300">
                    {{ $initials }}
                </div>
                <!-- Dropdown Menu -->
                <div class="profile-dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible transition-all duration-200 z-50">
                    <div class="py-2">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">Super Admin</p>
                            <p class="text-xs text-gray-500">{{ session('super_admin_username') ?? 'admin@nocis.id' }}</p>
                        </div>
                        <a href="{{ route('super-admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i>Profile
                        </a>
                        <a href="{{ route('super-admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i>Settings
                        </a>
                        <div class="border-t border-gray-100 mt-1">
                            <form method="POST" action="{{ route('super-admin.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(session('admin_authenticated'))
            @php
                $adminName = session('admin_username') ?? 'A';
                $initials = strtoupper(substr($adminName, 0, 2));
            @endphp
            <div class="relative">
                <div class="profile-dropdown-trigger w-8 h-8 lg:w-10 lg:h-10 rounded-full bg-red-500 flex items-center justify-center text-white font-semibold text-sm cursor-pointer border-2 border-transparent hover:border-red-300">
                    {{ $initials }}
                </div>
                <!-- Dropdown Menu -->
                <div class="profile-dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible transition-all duration-200 z-50">
                    <div class="py-2">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">Admin</p>
                            <p class="text-xs text-gray-500">{{ session('admin_username') ?? 'admin@nocis.id' }}</p>
                        </div>
                        <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i>Profile
                        </a>
                        <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i>Settings
                        </a>
                        <div class="border-t border-gray-100 mt-1">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="relative">
                <div class="profile-dropdown-trigger w-8 h-8 lg:w-10 lg:h-10 rounded-full bg-gray-500 flex items-center justify-center text-white font-semibold text-sm cursor-pointer border-2 border-transparent hover:border-gray-400">
                    G
                </div>
                <!-- Dropdown Menu -->
                <div class="profile-dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible transition-all duration-200 z-50">
                    <div class="py-2">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">Guest</p>
                            <p class="text-xs text-gray-500">Not logged in</p>
                        </div>
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-plus mr-2"></i>Sign Up
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const triggers = document.querySelectorAll('.profile-dropdown-trigger');

            triggers.forEach(trigger => {
                trigger.addEventListener('click', function(e) {
                    e.stopPropagation();

                    // Get the corresponding menu
                    const menu = this.nextElementSibling;
                    const isVisible = !menu.classList.contains('invisible');

                    // Close all other menus first
                    document.querySelectorAll('.profile-dropdown-menu').forEach(d => {
                        d.classList.add('invisible', 'opacity-0');
                    });

                    // Toggle current menu
                    if (!isVisible) {
                        menu.classList.remove('invisible', 'opacity-0');
                    }
                });
            });

            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.profile-dropdown-menu') && !e.target.closest('.profile-dropdown-trigger')) {
                    document.querySelectorAll('.profile-dropdown-menu').forEach(d => {
                        d.classList.add('invisible', 'opacity-0');
                    });
                }
            });
        });
    </script>
</header>
