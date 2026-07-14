@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Pesanan</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola dan pantau pesanan dari pelanggan Anda</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-2 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="bg-white rounded-xl shadow-sm border p-2 mb-6">
        <div class="flex overflow-x-auto gap-2">
            <a href="{{ route('admin.orders.index') }}" 
               class="px-4 py-2 {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg text-sm font-medium whitespace-nowrap transition">
                <i class="fas fa-list mr-1"></i> Semua Pesanan
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'diproses']) }}" 
               class="px-4 py-2 {{ request('status') == 'diproses' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg text-sm font-medium whitespace-nowrap transition">
                <i class="fas fa-clock mr-1"></i> Diproses
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'dikirim']) }}" 
               class="px-4 py-2 {{ request('status') == 'dikirim' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg text-sm font-medium whitespace-nowrap transition">
                <i class="fas fa-truck mr-1"></i> Dikirim
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'selesai']) }}" 
               class="px-4 py-2 {{ request('status') == 'selesai' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg text-sm font-medium whitespace-nowrap transition">
                <i class="fas fa-check-circle mr-1"></i> Selesai
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'dibatalkan']) }}" 
               class="px-4 py-2 {{ request('status') == 'dibatalkan' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg text-sm font-medium whitespace-nowrap transition">
                <i class="fas fa-times-circle mr-1"></i> Dibatalkan
            </a>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">No. Pesanan</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($orders as $order)
                    @php
                        $statusConfig = [
                            'diproses' => ['bg' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'icon' => 'clock'],
                            'dikirim' => ['bg' => 'bg-blue-100 text-blue-800 border-blue-200', 'icon' => 'truck'],
                            'selesai' => ['bg' => 'bg-green-100 text-green-800 border-green-200', 'icon' => 'check-circle'],
                            'dibatalkan' => ['bg' => 'bg-red-100 text-red-800 border-red-200', 'icon' => 'times-circle'],
                        ];
                        $config = $statusConfig[$order->status] ?? $statusConfig['diproses'];
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $order->order_id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $order->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $order->created_at->format('d M Y, H:i') }} WIB
                        </td>
                        <td class="px-6 py-4 font-bold text-indigo-600">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 border rounded-full text-xs font-semibold {{ $config['bg'] }}">
                                <i class="fas fa-{{ $config['icon'] }} mr-1"></i>
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white rounded-lg font-medium transition shadow-sm text-xs">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-receipt text-5xl text-gray-300 mb-4"></i>
                            <p class="text-lg">Tidak ada pesanan ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-4 border-t bg-gray-50">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
