@extends('layouts.user')

@section('title', 'Detail Pesanan ' . $order->order_id)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('user.orders.index') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Pesanan
    </a>

    @php
        $statusConfig = [
            'diproses' => ['color' => 'yellow', 'icon' => 'clock', 'bg' => 'bg-yellow-100 text-yellow-700'],
            'dikirim' => ['color' => 'blue', 'icon' => 'truck', 'bg' => 'bg-blue-100 text-blue-700'],
            'selesai' => ['color' => 'green', 'icon' => 'check-circle', 'bg' => 'bg-green-100 text-green-700'],
            'dibatalkan' => ['color' => 'red', 'icon' => 'times-circle', 'bg' => 'bg-red-100 text-red-700'],
        ];
        $config = $statusConfig[$order->status] ?? $statusConfig['diproses'];
    @endphp

    <!-- Order Header Card -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6">
        <div class="p-6 border-b bg-gradient-to-r from-emerald-50 to-emerald-100">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nomor Pesanan</p>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $order->order_id }}</h2>
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ $order->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                <span class="px-4 py-2 {{ $config['bg'] }} rounded-full text-sm font-semibold self-start">
                    <i class="fas fa-{{ $config['icon'] }} mr-1"></i>
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <!-- Order Items -->
        <div class="p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Detail Produk</h3>
            <div class="space-y-4">
                @foreach($order->products as $item)
                @php
                    $productImage = $item['image'] ?? null;
                    if (!$productImage && isset($item['id'])) {
                        $productImage = \App\Models\Product::find($item['id'])->image ?? null;
                    }
                @endphp
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center flex-shrink-0 border overflow-hidden p-1">
                        @if($productImage)
                            <img src="{{ asset('storage/'.$productImage) }}" 
                                 alt="{{ $item['name'] }}"
                                 class="max-h-full max-w-full object-contain"
                                 onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-box text-2xl text-gray-400\'></i>'">
                        @else
                            <i class="fas fa-box text-2xl text-gray-400"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-gray-800 truncate">{{ $item['name'] }}</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $item['qty'] }} × Rp {{ number_format($item['price'], 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="border-t border-gray-200 mt-6 pt-6">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-700">Total Pembayaran</span>
                    <span class="text-3xl font-bold text-emerald-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Aksi</h3>
        <div class="flex flex-wrap gap-3">
            @if($order->status == 'diproses')
            <form action="{{ route('user.orders.cancel', $order->id) }}" method="POST" 
                  onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                @csrf
                <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition">
                    <i class="fas fa-times mr-2"></i> Batalkan Pesanan
                </button>
            </form>
            @endif

            @if($order->status == 'selesai')
            <a href="{{ route('user.products.index') }}" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition">
                <i class="fas fa-redo mr-2"></i> Beli Lagi
            </a>
            @endif

            <a href="{{ route('user.orders.index') }}" class="px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg font-medium transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection