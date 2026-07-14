@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_id)

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium mb-6 transition">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Pesanan
    </a>

    @php
        $statusConfig = [
            'diproses' => ['color' => 'yellow', 'icon' => 'clock', 'bg' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
            'dikirim' => ['color' => 'blue', 'icon' => 'truck', 'bg' => 'bg-blue-100 text-blue-800 border-blue-200'],
            'selesai' => ['color' => 'green', 'icon' => 'check-circle', 'bg' => 'bg-green-100 text-green-800 border-green-200'],
            'dibatalkan' => ['color' => 'red', 'icon' => 'times-circle', 'bg' => 'bg-red-100 text-red-800 border-red-200'],
        ];
        $config = $statusConfig[$order->status] ?? $statusConfig['diproses'];
    @endphp

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center shadow-sm">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details & Products -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Products Card -->
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">No. Pesanan</p>
                        <h3 class="text-lg font-bold text-gray-800">{{ $order->order_id }}</h3>
                    </div>
                    <span class="px-3 py-1 border rounded-full text-xs font-semibold {{ $config['bg'] }}">
                        <i class="fas fa-{{ $config['icon'] }} mr-1"></i>
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="p-6">
                    <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-box text-indigo-600"></i> Detail Produk
                    </h4>
                    <div class="space-y-4">
                        @foreach($order->products as $item)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-14 h-14 bg-white rounded-lg flex items-center justify-center flex-shrink-0 border border-gray-200 shadow-sm">
                                <i class="fas fa-box text-2xl text-gray-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-semibold text-gray-800 truncate text-sm sm:text-base">{{ $item['name'] }}</h5>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $item['qty'] }} × Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800 text-sm sm:text-base">
                                    Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-100 mt-6 pt-6 flex justify-between items-center">
                        <span class="text-base font-semibold text-gray-700">Total Pembayaran</span>
                        <span class="text-2xl font-bold text-indigo-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-truck text-indigo-600"></i> Informasi Pengiriman
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 font-medium">Nama Penerima</p>
                        <p class="font-semibold text-gray-800 mt-0.5">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium">No. Telepon</p>
                        <p class="font-semibold text-gray-800 mt-0.5">{{ $order->user->phone ?? '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-gray-500 font-medium">Alamat Lengkap</p>
                        <p class="font-semibold text-gray-800 mt-0.5 leading-relaxed">{{ $order->user->address ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Action / Status Management -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-6">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-cog text-indigo-600"></i> Kelola Status
                </h4>
                
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Status Baru</label>
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-semibold">
                            <option value="diproses" {{ $order->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="dikirim" {{ $order->status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="selesai" {{ $order->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $order->status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition text-sm flex items-center justify-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> Perbarui Status
                    </button>
                </form>

                <div class="border-t border-gray-100 mt-6 pt-6 text-xs text-gray-500 space-y-2">
                    <p><i class="fas fa-info-circle mr-1"></i> <strong>Diproses</strong>: Pesanan sedang disiapkan.</p>
                    <p><i class="fas fa-info-circle mr-1"></i> <strong>Dikirim</strong>: Pesanan diserahkan ke kurir.</p>
                    <p><i class="fas fa-info-circle mr-1"></i> <strong>Selesai</strong>: Pelanggan telah menerima barang.</p>
                    <p><i class="fas fa-info-circle mr-1"></i> <strong>Dibatalkan</strong>: Stok dikembalikan otomatis ke database.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
