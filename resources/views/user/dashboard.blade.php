@extends('layouts.user')

@section('title', 'Dashboard User')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h2>
    <p class="text-gray-500 mt-1">Selamat datang kembali di Orvion Mart</p>
</div>

<!-- User Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 rounded-xl text-white shadow-lg">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-emerald-100 text-sm">Total Pesanan</p>
                <h3 class="text-3xl font-bold mt-1">{{ auth()->user()->orders()->count() }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-receipt text-xl"></i>
            </div>
        </div>
        <p class="text-emerald-100 text-sm mt-4">{{ auth()->user()->orders()->where('status', 'diproses')->count() }} sedang diproses</p>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-xl text-white shadow-lg">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-blue-100 text-sm">Total Belanja</p>
                <h3 class="text-3xl font-bold mt-1">Rp {{ number_format(auth()->user()->orders()->where('status', 'selesai')->sum('total') / 1000000, 1) }} Jt</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-wallet text-xl"></i>
            </div>
        </div>
        <p class="text-blue-100 text-sm mt-4">Terima kasih atas kepercayaan Anda</p>
    </div>

    <div class="bg-gradient-to-br from-pink-500 to-pink-600 p-6 rounded-xl text-white shadow-lg">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-pink-100 text-sm">Wishlist</p>
                <h3 class="text-3xl font-bold mt-1">{{ auth()->user()->wishlists()->count() }}</h3>
            </div>
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-heart text-xl"></i>
            </div>
        </div>
        <p class="text-pink-100 text-sm mt-4">Produk favorit Anda</p>
    </div>
</div>

<!-- Featured Products - FIXED: Menampilkan gambar dari database -->
<div class="mb-8">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">🔥 Produk Populer</h3>
        <a href="{{ route('user.products.index') }}" class="text-emerald-600 text-sm font-medium hover:underline">Lihat Semua</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @forelse(\App\Models\Product::where('popular', true)->take(4)->get() as $product)
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-lg transition group flex flex-col justify-between">
            <a href="{{ route('user.products.show', $product->id) }}">
                <!-- Product Image -->
                <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center relative overflow-hidden p-4">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" 
                             alt="{{ $product->name }}"
                             class="max-w-full max-h-full object-contain group-hover:scale-110 transition duration-300"
                             onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-box text-4xl text-gray-400\'></i>'">
                    @else
                        <i class="fas fa-box text-4xl text-gray-400 group-hover:scale-110 transition"></i>
                    @endif
                    <span class="absolute top-3 left-3 px-2 py-1 bg-red-500 text-white text-[10px] rounded-full font-semibold">🔥 Populer</span>
                </div>
            </a>
            <div class="p-4">
                <p class="text-xs text-emerald-600 font-medium">{{ $product->category }}</p>
                <h4 class="font-semibold text-gray-800 mt-1 truncate">{{ $product->name }}</h4>
                <div class="flex items-center mt-2 text-xs text-yellow-400">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    <span class="text-gray-500 ml-1">(4.5)</span>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <a href="{{ route('user.products.show', $product->id) }}" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-700 transition">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-8 text-gray-500">Belum ada produk populer</div>
        @endforelse
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="p-6 border-b flex justify-between items-center">
        <h3 class="text-lg font-semibold">Pesanan Terbaru</h3>
        <a href="{{ route('user.orders.index') }}" class="text-emerald-600 text-sm font-medium hover:underline">Lihat Semua</a>
    </div>
    <div class="divide-y">
        @forelse(auth()->user()->orders()->latest()->take(3)->get() as $order)
        @php
            $statusColors = [
                'diproses' => 'bg-yellow-100 text-yellow-700',
                'dikirim' => 'bg-blue-100 text-blue-700',
                'selesai' => 'bg-green-100 text-green-700',
                'dibatalkan' => 'bg-red-100 text-red-700',
            ];
        @endphp
        <div class="p-4 flex items-center justify-between hover:bg-gray-50">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $order->order_id }}</p>
                    <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                <span class="px-2 py-1 {{ $statusColors[$order->status] }} rounded-full text-xs">{{ ucfirst($order->status) }}</span>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-gray-500">Belum ada pesanan</div>
        @endforelse
    </div>
</div>
@endsection