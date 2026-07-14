@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Produk Baru</h2>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium mb-1">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="category" value="{{ old('category') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Gambar Produk</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    <p class="text-xs text-gray-500 mt-1">Max 2MB. Format: JPG, PNG, GIF, WEBP</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('description') }}</textarea>
            </div>

            <!-- Varian Produk (Alpine.js) -->
            <div x-data="{ 
                variants: [],
                get jsonVariants() {
                    const validVariants = {};
                    this.variants.forEach(v => {
                        if (v.name && v.options) {
                            validVariants[v.name] = v.options.split(',').map(s => s.trim()).filter(s => s);
                        }
                    });
                    return Object.keys(validVariants).length > 0 ? JSON.stringify(validVariants) : '';
                }
            }" class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                <div class="flex justify-between items-center mb-3">
                    <label class="block text-sm font-semibold text-gray-800">Varian Produk (Opsional)</label>
                    <button type="button" @click="variants.push({name: '', options: ''})" class="text-sm bg-white border border-indigo-200 text-indigo-600 px-3 py-1 rounded-lg hover:bg-indigo-50 transition">
                        <i class="fas fa-plus mr-1"></i> Tambah Varian
                    </button>
                </div>
                
                <p class="text-xs text-gray-500 mb-4">Tambahkan opsi seperti Ukuran, Warna, atau Spesifikasi. Kosongkan jika produk ini tidak memiliki varian.</p>
                
                <template x-for="(variant, index) in variants" :key="index">
                    <div class="flex flex-col sm:flex-row gap-3 mb-3 p-3 bg-white border border-gray-100 rounded-lg shadow-sm relative group">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Nama Varian (Cth: Warna)</label>
                            <input type="text" x-model="variant.name" placeholder="Misal: Ukuran" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-indigo-500 outline-none">
                        </div>
                        <div class="flex-[2]">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Pilihan (Pisahkan dengan koma)</label>
                            <input type="text" x-model="variant.options" placeholder="Misal: S, M, L, XL" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-indigo-500 outline-none">
                        </div>
                        <button type="button" @click="variants.splice(index, 1)" class="sm:absolute sm:right-3 sm:top-1/2 sm:-translate-y-1/2 text-red-400 hover:text-red-600 sm:opacity-0 group-hover:opacity-100 transition" title="Hapus Varian">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </template>

                <input type="hidden" name="variants" :value="jsonVariants">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="popular" value="1" id="popular" class="mr-2 w-4 h-4 text-indigo-600">
                <label for="popular" class="text-sm">Tandai sebagai produk populer</label>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    <i class="fas fa-save mr-2"></i>Simpan Produk
                </button>
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-gray-300 hover:bg-gray-50 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection