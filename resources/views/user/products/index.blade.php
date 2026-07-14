@extends('layouts.user')
@section('title', 'Katalog Produk')
@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Katalog Produk</h2>
        <p class="text-gray-500 text-sm mt-1">Temukan produk terbaik untuk Anda</p>
    </div>
    <form method="GET" action="{{ route('user.products.index') }}" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
        <select name="category" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <select name="sort" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
            <option value="">Urutkan</option>
            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
        </select>
        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700"><i class="fas fa-search"></i></button>
    </form>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
    @forelse($products as $product)
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-lg transition group flex flex-col justify-between">
        <a href="{{ route('user.products.show', $product->id) }}">
            <!-- Product Image - FIXED: Menampilkan gambar dari database secara utuh dan aspect-square -->
            <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center relative overflow-hidden p-4">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" 
                         alt="{{ $product->name }}"
                         class="max-w-full max-h-full object-contain group-hover:scale-110 transition duration-300"
                         onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-box text-4xl text-gray-400\'></i>'">
                @else
                    <i class="fas fa-box text-4xl text-gray-400 group-hover:scale-110 transition"></i>
                @endif
                @if($product->popular)
                    <span class="absolute top-3 left-3 px-2 py-1 bg-red-500 text-white text-[10px] rounded-full">🔥 Populer</span>
                @endif
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
                <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($product->price,0,',','.') }}</p>
                <a href="{{ route('user.products.show', $product->id) }}" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-700"><i class="fas fa-eye"></i></a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12 text-gray-500"><i class="fas fa-box-open text-4xl mb-3"></i><p>Tidak ada produk</p></div>
    @endforelse
</div>
<div class="mt-6">{{ $products->links() }}</div>
@endsection