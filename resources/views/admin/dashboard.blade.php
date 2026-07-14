@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Ringkasan Dasbor Admin</h2>
    <span class="text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Total Pengguna</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
        <p class="text-green-500 text-sm mt-4"><i class="fas fa-arrow-up mr-1"></i> 12% dari bulan lalu</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Total Produk</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalProducts }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-box text-xl"></i>
            </div>
        </div>
        <p class="text-green-500 text-sm mt-4"><i class="fas fa-arrow-up mr-1"></i> 5 produk baru</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Total Pesanan</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalOrders }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart text-xl"></i>
            </div>
        </div>
        <p class="text-green-500 text-sm mt-4"><i class="fas fa-arrow-up mr-1"></i> 8 pesanan baru</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Pendapatan</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-xl"></i>
            </div>
        </div>
        <p class="text-green-500 text-sm mt-4"><i class="fas fa-arrow-up mr-1"></i> 15% dari bulan lalu</p>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Pesanan Terbaru</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 text-sm font-medium hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 text-sm uppercase">
                <tr>
                    <th class="px-6 py-3">ID Pesanan</th>
                    <th class="px-6 py-3">Pelanggan</th>
                    <th class="px-6 py-3 hidden md:table-cell">Total</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $order->order_id }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $order->user->name }}</td>
                    <td class="px-6 py-4 text-gray-800 font-medium hidden md:table-cell">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'diproses' => 'bg-yellow-100 text-yellow-700',
                                'dikirim' => 'bg-blue-100 text-blue-700',
                                'selesai' => 'bg-green-100 text-green-700',
                                'dibatalkan' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="px-2 py-1 {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-medium">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>   
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada pesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Welcome Card -->
<div class="mt-6 bg-gradient-to-r from-indigo-500 to-purple-600 p-6 rounded-xl text-white shadow-lg">
    <h3 class="text-xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h3>
    <p class="text-indigo-100">Anda login sebagai Administrator. Kelola sistem dengan bijak.</p>
</div>
@endsection