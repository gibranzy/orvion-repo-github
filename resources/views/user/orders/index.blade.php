@extends('layouts.user')

@section('title', 'Pesanan Saya')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Pesanan Saya</h2>
        <p class="text-gray-500 text-sm mt-1">Riwayat dan status pesanan Anda</p>
    </div>
    <a href="{{ route('user.products.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition">
        <i class="fas fa-shopping-bag mr-2"></i>Belanja Lagi
    </a>
</div>

<!-- Filter Tabs -->
<div class="bg-white rounded-xl shadow-sm border p-2 mb-6">
    <div class="flex overflow-x-auto gap-2">
        <a href="{{ route('user.orders.index') }}" 
           class="px-4 py-2 {{ !request('status') ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg text-sm font-medium whitespace-nowrap transition">
            <i class="fas fa-list mr-1"></i> Semua ({{ auth()->user()->orders()->count() }})
        </a>
        <a href="{{ route('user.orders.index', ['status' => 'diproses']) }}" 
           class="px-4 py-2 {{ request('status') == 'diproses' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg text-sm font-medium whitespace-nowrap transition">
            <i class="fas fa-clock mr-1"></i> Diproses
        </a>
        <a href="{{ route('user.orders.index', ['status' => 'dikirim']) }}" 
           class="px-4 py-2 {{ request('status') == 'dikirim' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg text-sm font-medium whitespace-nowrap transition">
            <i class="fas fa-truck mr-1"></i> Dikirim
        </a>
        <a href="{{ route('user.orders.index', ['status' => 'selesai']) }}" 
           class="px-4 py-2 {{ request('status') == 'selesai' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg text-sm font-medium whitespace-nowrap transition">
            <i class="fas fa-check-circle mr-1"></i> Selesai
        </a>
        <a href="{{ route('user.orders.index', ['status' => 'dibatalkan']) }}" 
           class="px-4 py-2 {{ request('status') == 'dibatalkan' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg text-sm font-medium whitespace-nowrap transition">
            <i class="fas fa-times-circle mr-1"></i> Dibatalkan
        </a>
    </div>
</div>

<!-- Orders List -->
<div class="space-y-4">
    @forelse($orders as $order)
    @php
        $statusConfig = [
            'diproses' => ['color' => 'yellow', 'icon' => 'clock', 'bg' => 'bg-yellow-100 text-yellow-700'],
            'dikirim' => ['color' => 'blue', 'icon' => 'truck', 'bg' => 'bg-blue-100 text-blue-700'],
            'selesai' => ['color' => 'green', 'icon' => 'check-circle', 'bg' => 'bg-green-100 text-green-700'],
            'dibatalkan' => ['color' => 'red', 'icon' => 'times-circle', 'bg' => 'bg-red-100 text-red-700'],
        ];
        $config = $statusConfig[$order->status] ?? $statusConfig['diproses'];
    @endphp
    
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-md transition">
        <!-- Order Header -->
        <div class="p-4 sm:p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-{{ $config['icon'] }} text-xl"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $order->order_id }}</p>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
            <span class="px-3 py-1.5 {{ $config['bg'] }} rounded-full text-sm font-semibold self-start sm:self-center">
                <i class="fas fa-{{ $config['icon'] }} mr-1"></i>
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <!-- Order Items -->
        <div class="p-4 sm:p-6">
            <div class="space-y-3 mb-4">
                @foreach($order->products as $item)
                @php
                    $productImage = $item['image'] ?? null;
                    if (!$productImage && isset($item['id'])) {
                        $productImage = \App\Models\Product::find($item['id'])->image ?? null;
                    }
                @endphp
                <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden border border-gray-200 p-1">
                            @if($productImage)
                                <img src="{{ asset('storage/'.$productImage) }}" 
                                     alt="{{ $item['name'] }}"
                                     class="max-h-full max-w-full object-contain"
                                     onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-box text-gray-400\'></i>'">
                            @else
                                <i class="fas fa-box text-gray-400"></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-gray-800 truncate">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $item['qty'] }} × Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <p class="font-semibold text-gray-800 ml-2">
                        Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                    </p>
                </div>
                @endforeach
            </div>

            <!-- Order Footer -->
            <div class="border-t border-gray-100 pt-4 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                <div>
                    <p class="text-sm text-gray-500">Total Pembayaran</p>
                    <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
                <div class="flex gap-2 flex-wrap">
                    @if($order->status == 'diproses')
                    <form action="{{ route('user.orders.cancel', $order->id) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition">
                            <i class="fas fa-times mr-1"></i> Batalkan
                        </button>
                    </form>
                    @endif
                    
                    @if($order->status == 'selesai')
                    <a href="{{ route('user.products.index') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition">
                        <i class="fas fa-redo mr-1"></i> Beli Lagi
                    </a>
                    @endif
                    
                    <a href="{{ route('user.orders.show', $order->id) }}" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-eye mr-1"></i> Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-receipt text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Pesanan</h3>
        <p class="text-gray-500 mb-6">Anda belum memiliki pesanan{{ request('status') ? ' dengan status ' . request('status') : '' }}</p>
        <a href="{{ route('user.products.index') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-medium transition">
            <i class="fas fa-shopping-bag mr-2"></i> Mulai Belanja
        </a>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($orders->hasPages())
<div class="mt-6">
    {{ $orders->links() }}
</div>
@endif
@endsection