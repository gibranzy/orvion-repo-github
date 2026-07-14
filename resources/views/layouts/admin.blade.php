<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .nav-item.active { background-color: #eef2ff; color: #4f46e5; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-white shadow-lg hidden lg:flex flex-col fixed inset-y-0 left-0 z-30 lg:static">
            <!-- Logo -->
            <div class="p-4 border-b border-gray-100 flex items-center justify-center">
                <img src="{{ asset('images/orvion-logo.png') }}" 
                     alt="Orvion Mart" 
                     class="h-12 w-auto object-contain drop-shadow-md hover:scale-105 transition-transform duration-300"
                     onerror="this.src='https://via.placeholder.com/150x60?text=Orvion+Mart'">
            </div>
            
            <div class="p-4 bg-indigo-50 border-b border-indigo-100">
                <p class="text-xs text-indigo-600 font-semibold uppercase">Mode Admin</p>
                <p class="text-sm text-gray-700 font-medium mt-1">{{ auth()->user()->name }}</p>
            </div>
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-600' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-home w-5 mr-3"></i> Dasbor
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : 'text-gray-600' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-users w-5 mr-3"></i> Pengguna
                </a>
                <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : 'text-gray-600' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-box w-5 mr-3"></i> Produk
                </a>
                <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : 'text-gray-600' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-shopping-bag w-5 mr-3"></i> Pesanan
                </a>
                <!-- UPDATED: Analitik sekarang AKTIF dan bisa diklik -->
                <a href="{{ route('admin.analytics') }}" class="nav-item {{ request()->routeIs('admin.analytics') ? 'active' : 'text-gray-600' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-chart-line w-5 mr-3"></i> Analitik
                </a>
                <!-- Pengaturan mengarah ke settings -->
                <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : 'text-gray-600' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-cog w-5 mr-3"></i> Pengaturan
                </a>
            </nav>
            <div class="p-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 text-red-500 hover:bg-red-50 rounded-lg transition font-medium">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-10">
                <div class="flex items-center space-x-4">
                    <button class="lg:hidden text-gray-600" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="relative hidden sm:block">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" placeholder="Cari..." class="w-64 pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        @php $adminUnread = auth()->user()->unreadNotifications->count() ?? 0; @endphp
                        <button id="adminNotificationButton" class="relative p-2 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-full transition" onclick="toggleAdminNotifications()" type="button">
                            <i class="fas fa-bell text-xl"></i>
                            @if($adminUnread > 0)
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                        </button>
                        <div id="adminNotifications" class="hidden absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden z-50">
                            <div class="p-4 border-b border-gray-100 font-semibold">Notifikasi</div>
                            <div class="p-4 text-sm text-gray-600">Tidak ada notifikasi baru.</div>
                        </div>
                    </div>
                    
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" x-cloak>
                        <button @click="open = ! open" class="flex items-center space-x-3 focus:outline-none">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/'.auth()->user()->avatar) }}?t={{ time() }}" 
                                     alt="{{ auth()->user()->name }}" 
                                     class="w-10 h-10 rounded-full object-cover border-2 border-indigo-200">
                            @else
                                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <span class="text-gray-700 font-medium hidden md:block">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50"
                             style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> Profil
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i> Pengaturan
                            </a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-4 md:p-6">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('hidden');
        }

        function toggleAdminNotifications() {
            const panel = document.getElementById('adminNotifications');
            panel.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const adminButton = document.getElementById('adminNotificationButton');
            const adminPanel = document.getElementById('adminNotifications');

            if (adminPanel && adminButton && !adminPanel.contains(event.target) && !adminButton.contains(event.target)) {
                adminPanel.classList.add('hidden');
            }
        });
    </script>
    
    <!-- Alpine.js untuk dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>