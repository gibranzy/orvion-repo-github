<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- ✅ TAMBAHAN: Favicon untuk Tab Browser -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    
    <title>@yield('title', 'User Dashboard')</title>
    
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
        .nav-item.active { background-color: #ecfdf5; color: #059669; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-white shadow-lg hidden lg:flex flex-col fixed lg:static inset-y-0 left-0 z-30">
            
            <!-- Logo -->
            <div class="p-4 border-b border-gray-100 flex items-center justify-center">
                <img src="{{ asset('images/orvion-logo.png') }}" 
                     alt="Orvion Mart" 
                     class="h-12 w-auto object-contain drop-shadow-md hover:scale-105 transition-transform duration-300"
                     onerror="this.src='https://via.placeholder.com/150x60?text=Orvion+Mart'">
            </div>
            
            <div class="p-4 bg-emerald-50 border-b border-emerald-100">
                <p class="text-xs text-emerald-600 font-semibold uppercase">Akun User</p>
                <p class="text-sm text-gray-700 font-medium mt-1">{{ auth()->user()->name }}</p>
            </div>
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="{{ route('user.dashboard') }}" class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : 'text-gray-600' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-home w-5 mr-3"></i> Beranda
                </a>
                <a href="{{ route('user.products.index') }}" class="nav-item {{ request()->routeIs('user.products.*') ? 'active' : 'text-gray-600' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-store w-5 mr-3"></i> Katalog Produk
                </a>
                <a href="{{ route('user.cart.index') }}" class="nav-item {{ request()->routeIs('user.cart.*') ? 'active' : 'text-gray-600' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-shopping-cart w-5 mr-3"></i> Keranjang
                    @if(count(session('cart', [])) > 0)
                        <span class="ml-auto bg-emerald-500 text-white text-xs px-2 py-0.5 rounded-full">{{ count(session('cart', [])) }}</span>
                    @endif
                </a>
                <a href="{{ route('user.orders.index') }}" class="nav-item {{ request()->routeIs('user.orders.*') ? 'active' : 'text-gray-600' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-receipt w-5 mr-3"></i> Pesanan Saya
                </a>
                <a href="{{ route('user.wishlist.index') }}" class="nav-item {{ request()->routeIs('user.wishlist.*') ? 'active' : 'text-gray-600' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-heart w-5 mr-3"></i> Wishlist
                    @if(auth()->user()->wishlists()->count() > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ auth()->user()->wishlists()->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('user.settings.index') }}" class="nav-item {{ request()->routeIs('user.settings.*') ? 'active' : 'text-gray-600' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
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
        <main class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <!-- Header -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-40">
                <div class="flex items-center space-x-4">
                    <button class="lg:hidden text-gray-600" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Search Bar -->
                    <div class="relative hidden sm:block">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 z-10">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="globalSearch" 
                               value="{{ request('search') }}"
                               placeholder="Cari produk..." 
                               autocomplete="off"
                               class="w-64 pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                        
                        <!-- Search Dropdown -->
                        <div id="searchDropdown" class="hidden absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto">
                            <div class="p-4 text-center text-gray-500" id="searchLoading" style="display: none;">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Mencari...
                            </div>
                            <div id="searchResults"></div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                    
                    <!-- Notification Button -->
                    <div class="relative">
                        <button id="userNotificationButton" class="relative p-2 text-gray-500 hover:text-emerald-600 hover:bg-gray-100 rounded-full transition" onclick="toggleUserNotifications()" type="button">
                            <i class="fas fa-bell text-xl"></i>
                        </button>
                        <div id="userNotifications" class="hidden absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden z-50">
                            <div class="p-4 border-b border-gray-100 font-semibold text-gray-800">Notifikasi</div>
                            <div class="p-4 text-sm text-gray-600 text-center">Tidak ada notifikasi baru.</div>
                        </div>
                    </div>

                    <!-- Wishlist Button -->
                    <a href="{{ route('user.wishlist.index') }}" class="relative p-2 text-gray-500 hover:text-red-500 hover:bg-gray-100 rounded-full transition hidden sm:block">
                        <i class="fas fa-heart text-xl"></i>
                        @if(auth()->user()->wishlists()->count() > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                {{ auth()->user()->wishlists()->count() }}
                            </span>
                        @endif
                    </a>

                    <!-- Cart Button -->
                    <a href="{{ route('user.cart.index') }}" class="relative p-2 text-gray-500 hover:text-emerald-600 hover:bg-gray-100 rounded-full transition">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        @if(count(session('cart', [])) > 0)
                            <span id="cartBadge" class="absolute -top-1 -right-1 w-5 h-5 bg-emerald-500 text-white text-xs rounded-full flex items-center justify-center">
                                {{ count(session('cart', [])) }}
                            </span>
                        @endif
                    </a>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" x-cloak>
                        <button @click="open = ! open" class="flex items-center space-x-3 focus:outline-none">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/'.auth()->user()->avatar) }}?t={{ time() }}" 
                                     alt="{{ auth()->user()->name }}" 
                                     class="w-10 h-10 rounded-full object-cover border-2 border-emerald-200">
                            @else
                                <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center text-white font-semibold">
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
                            <a href="{{ route('user.settings.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
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
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('hidden');
        }

        function toggleUserNotifications() {
            document.getElementById('userNotifications').classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const notifBtn = document.getElementById('userNotificationButton');
            const notifBox = document.getElementById('userNotifications');
            if (notifBtn && notifBox && !notifBtn.contains(event.target) && !notifBox.contains(event.target)) {
                notifBox.classList.add('hidden');
            }
        });

        // Global Search
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('globalSearch');
            const searchDropdown = document.getElementById('searchDropdown');
            const searchResults = document.getElementById('searchResults');
            const searchLoading = document.getElementById('searchLoading');
            let searchTimeout;

            if (!searchInput) return;

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    searchDropdown.classList.add('hidden');
                    return;
                }

                searchLoading.style.display = 'block';
                searchResults.innerHTML = '';
                searchDropdown.classList.remove('hidden');

                searchTimeout = setTimeout(() => {
                    fetch(`/user/products/search?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            searchLoading.style.display = 'none';
                            
                            if (data.success && data.products.length > 0) {
                                let html = '<div class="divide-y divide-gray-100">';
                                data.products.forEach(product => {
                                    const formattedPrice = new Intl.NumberFormat('id-ID').format(product.price);
                                    html += `
                                        <a href="${product.url}" class="flex items-center gap-3 p-3 hover:bg-gray-50 transition">
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                                ${product.image 
                                                    ? `<img src="${product.image}" class="w-full h-full object-cover rounded-lg">`
                                                    : `<i class="fas fa-box text-gray-400"></i>`
                                                }
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium text-gray-800 truncate">${product.name}</p>
                                                <p class="text-xs text-gray-500">${product.category}</p>
                                                <p class="text-sm font-semibold text-emerald-600">Rp ${formattedPrice}</p>
                                            </div>
                                            <i class="fas fa-chevron-right text-gray-300"></i>
                                        </a>
                                    `;
                                });
                                html += '</div>';
                                html += `
                                    <div class="p-3 border-t border-gray-100 text-center bg-gray-50">
                                        <a href="/user/products?search=${encodeURIComponent(query)}" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                                            Lihat semua hasil pencarian <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                `;
                                searchResults.innerHTML = html;
                            } else {
                                searchResults.innerHTML = `
                                    <div class="p-6 text-center text-gray-500">
                                        <i class="fas fa-box-open text-3xl mb-2 text-gray-300"></i>
                                        <p class="text-sm">Tidak ada produk ditemukan untuk "<strong>${query}</strong>"</p>
                                    </div>
                                `;
                            }
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            searchLoading.style.display = 'none';
                            searchDropdown.classList.add('hidden');
                        });
                }, 300);
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const query = this.value.trim();
                    if (query.length > 0) {
                        window.location.href = `/user/products?search=${encodeURIComponent(query)}`;
                    }
                }
            });

            document.addEventListener('click', function(event) {
                if (!searchInput.contains(event.target) && !searchDropdown.contains(event.target)) {
                    searchDropdown.classList.add('hidden');
                }
            });

            searchInput.addEventListener('focus', function() {
                if (this.value.trim().length >= 2 && searchResults.innerHTML !== '') {
                    searchDropdown.classList.remove('hidden');
                }
            });
        });
    </script>
    
    <!-- Alpine.js untuk dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
