@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Produk</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola produk toko Anda</p>
        </div>
        <a href="{{ route('admin.products.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Produk</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white p-4 rounded-xl shadow-sm border mb-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." 
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <select name="category" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            @if(request('search') || request('category'))
                <a href="{{ route('admin.products.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium transition text-center flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="w-4/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="w-2/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="w-2/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="w-1/12 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="w-1/12 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="w-2/12 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition group">
                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gray-100 border border-gray-200 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden shadow-sm">
                                    @if($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover"
                                             onerror="this.parentElement.innerHTML='<i class=\'fas fa-box text-gray-400 text-xl\'></i>'">
                                    @else
                                        <i class="fas fa-box text-gray-400 text-xl"></i>
                                    @endif
                                </div>
                                <div class="flex flex-col justify-center truncate pr-2">
                                    <p class="font-semibold text-gray-800 text-sm md:text-base truncate">{{ $product->name }}</p>
                                    @if($product->popular)
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-red-700 text-[10px] uppercase font-bold tracking-wider rounded w-max">
                                            <i class="fas fa-fire mr-1"></i>Populer
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 align-middle whitespace-nowrap">
                            <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-semibold">
                                {{ $product->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 align-middle whitespace-nowrap">
                            <div class="flex items-center h-8 font-medium text-gray-800">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 align-middle whitespace-nowrap">
                            <div class="flex justify-center w-full">
                                <div class="inline-flex items-center justify-center h-8 px-4 rounded-full text-sm font-semibold {{ $product->stock > 10 ? 'bg-green-100 text-green-700' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $product->stock }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 align-middle whitespace-nowrap">
                            <div class="flex justify-center w-full">
                                @if($product->stock > 10)
                                    <div class="inline-flex items-center justify-center h-8 px-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold rounded-full">Tersedia</div>
                                @elseif($product->stock > 0)
                                    <div class="inline-flex items-center justify-center h-8 px-4 bg-amber-50 border border-amber-200 text-amber-700 text-sm font-semibold rounded-full">Stok Rendah</div>
                                @else
                                    <div class="inline-flex items-center justify-center h-8 px-4 bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold rounded-full">Habis</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 align-middle whitespace-nowrap">
                            <div class="flex justify-end gap-2 transition-opacity">
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition shadow-sm" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition shadow-sm" title="Hapus">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
                            <p class="text-lg">Tidak ada produk ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($products->hasPages())
        <div class="p-4 border-t bg-gray-50">
            {{ $products->links() }}
        </div>
        @endif
    </div>

    <!-- Product Count Info -->
    <div class="bg-white p-4 rounded-xl shadow-sm border text-center">
        <p class="text-gray-600">
            <i class="fas fa-box mr-2"></i>
            Menampilkan <strong>{{ $products->count() }}</strong> dari <strong>{{ $products->total() }}</strong> produk
        </p>
    </div>
</div>
@endsection