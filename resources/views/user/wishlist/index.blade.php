@extends('layouts.user')

@section('title', 'Wishlist Saya')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Wishlist Saya 💗</h2>
        <p class="text-gray-500 text-sm mt-1">Produk favorit yang Anda simpan</p>
    </div>

    @if($wishlists->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
            @foreach($wishlists as $wishlist)
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-lg transition group flex flex-col justify-between">
                <a href="{{ route('user.products.show', $wishlist->product->id) }}">
                    <!-- Product Image -->
                    <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center relative overflow-hidden p-4">
                        @if($wishlist->product->image)
                            <img src="{{ asset('storage/'.$wishlist->product->image) }}" 
                                 alt="{{ $wishlist->product->name }}"
                                 class="max-w-full max-h-full object-contain group-hover:scale-110 transition duration-300"
                                 onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-box text-4xl text-gray-400\'></i>'">
                        @else
                            <i class="fas fa-box text-4xl text-gray-400 group-hover:scale-110 transition"></i>
                        @endif
                        @if($wishlist->product->popular)
                            <span class="absolute top-3 left-3 px-2 py-1 bg-red-500 text-white text-[10px] rounded-full">🔥 Populer</span>
                        @endif
                    </div>
                </a>
                <div class="p-4">
                    <p class="text-xs text-emerald-600 font-medium">{{ $wishlist->product->category }}</p>
                    <h4 class="font-semibold text-gray-800 mt-1 truncate">{{ $wishlist->product->name }}</h4>
                    <div class="flex items-center mt-2 text-xs text-yellow-400">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        <span class="text-gray-500 ml-1">(4.5)</span>
                    </div>
                    <div class="flex justify-between items-center mt-3">
                        <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($wishlist->product->price, 0, ',', '.') }}</p>
                        <div class="flex gap-2">
                            <a href="{{ route('user.products.show', $wishlist->product->id) }}" 
                               class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-700">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('user.wishlist.destroy', $wishlist->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-600 rounded-lg text-sm hover:bg-red-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
            <i class="fas fa-heart text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Wishlist Anda Kosong</h3>
            <p class="text-gray-500 mb-6">Mulai tambahkan produk favorit Anda ke wishlist</p>
            <a href="{{ route('user.products.index') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-medium transition">
                <i class="fas fa-shopping-bag mr-2"></i>Jelajahi Produk
            </a>
        </div>
    @endif
</div>
@endsection