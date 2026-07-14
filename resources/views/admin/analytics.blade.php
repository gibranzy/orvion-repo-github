@extends('layouts.admin')

@section('title', 'Analitik')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Analitik Dashboard</h2>
        <p class="text-gray-500 text-sm mt-1">Statistik dan laporan toko Anda</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Produk</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalProducts }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                <span class="text-orange-600 font-medium">
                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ $outOfStockProducts }}
                </span>
                produk habis
            </p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Pengguna</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                <span class="text-green-600 font-medium">
                    <i class="fas fa-check-circle mr-1"></i>Aktif
                </span>
                pengguna terdaftar
            </p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Pesanan</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalOrders }}</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-emerald-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                <span class="text-blue-600 font-medium">
                    <i class="fas fa-clock mr-1"></i>{{ $ordersByStatus->firstWhere('status', 'diproses')->count ?? 0 }}
                </span>
                sedang diproses
            </p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wallet text-orange-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                <span class="text-green-600 font-medium">
                    <i class="fas fa-check-circle mr-1"></i>Selesai
                </span>
                dari semua pesanan
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-bar mr-2 text-indigo-600"></i>
                Pendapatan 6 Bulan Terakhir
            </h3>
            @if($revenueByMonth->count() > 0)
                <div class="h-64 flex items-end justify-between gap-2">
                    @php
                        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                        $maxRevenue = $revenueByMonth->max('total') ?: 1;
                    @endphp
                    @foreach(range(0, 5) as $index)
                        @php
                            $monthNum = now()->subMonths(5 - $index)->month;
                            $monthData = $revenueByMonth->firstWhere('month', $monthNum);
                            $revenue = $monthData ? $monthData->total : 0;
                            $height = $maxRevenue > 0 ? ($revenue / $maxRevenue) * 100 : 0;
                            $monthName = $months[$monthNum - 1];
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <div class="w-full bg-gray-100 rounded-t-lg relative" style="height: 200px;">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-indigo-600 to-indigo-400 rounded-t-lg transition-all" 
                                     style="height: {{ $height }}%;"></div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $monthName }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="h-64 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <i class="fas fa-chart-line text-4xl mb-2"></i>
                        <p>Belum ada data pendapatan</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Category Distribution -->
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-tags mr-2 text-indigo-600"></i>
                Distribusi Kategori
            </h3>
            @if($categoryDistribution->count() > 0)
                <div class="space-y-4">
                    @foreach($categoryDistribution as $category)
                        @php
                            $percentage = $totalProducts > 0 ? ($category->count / $totalProducts) * 100 : 0;
                            $colors = ['bg-blue-500', 'bg-purple-500', 'bg-emerald-500', 'bg-orange-500', 'bg-red-500', 'bg-pink-500'];
                            $color = $colors[$loop->index % count($colors)];
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">{{ $category->category }}</span>
                                <span class="text-sm text-gray-500">{{ $category->count }} produk ({{ number_format($percentage, 1) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="{{ $color }} h-2 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="h-64 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <i class="fas fa-folder-open text-4xl mb-2"></i>
                        <p>Belum ada kategori</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Products -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="p-6 border-b">
                <h3 class="font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-boxes mr-2 text-indigo-500"></i>
                    Produk dengan Stok Terbanyak
                </h3>
            </div>
            <div class="divide-y">
                @forelse($topProducts as $product)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <i class="fas fa-box text-gray-400"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $product->category }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500">Stok: {{ $product->stock }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-box-open text-3xl mb-2"></i>
                        <p>Belum ada produk</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="p-6 border-b">
                <h3 class="font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-clock mr-2 text-blue-500"></i>
                    Pesanan Terbaru
                </h3>
            </div>
            <div class="divide-y">
                @forelse($recentOrders as $order)
                    @php
                        $statusColors = [
                            'diproses' => 'bg-yellow-100 text-yellow-700',
                            'dikirim' => 'bg-blue-100 text-blue-700',
                            'selesai' => 'bg-green-100 text-green-700',
                            'dibatalkan' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                        <div>
                            <p class="font-medium text-gray-800">{{ $order->order_id }}</p>
                            <p class="text-sm text-gray-500">{{ $order->user->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            <span class="px-2 py-1 {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-box-open text-3xl mb-2"></i>
                        <p>Belum ada pesanan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Low Stock Warning -->
    @if($lowStockProducts->count() > 0)
    <div class="mt-8 bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="p-6 border-b bg-orange-50">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2 text-orange-500"></i>
                Peringatan Stok Rendah
            </h3>
        </div>
        <div class="divide-y">
            @foreach($lowStockProducts as $product)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-orange-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $product->name }}</p>
                            <p class="text-sm text-gray-500">{{ $product->category }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-sm font-medium">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Sisa {{ $product->stock }} unit
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection