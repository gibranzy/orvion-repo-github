@extends('layouts.user')

@section('title', $product->name)

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('user.products.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Katalog
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Product Image -->
        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl h-96 lg:h-[500px] flex items-center justify-center relative group overflow-hidden">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" 
                     alt="{{ $product->name }}"
                     class="max-h-full max-w-full object-contain group-hover:scale-110 transition duration-300"
                     onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-box text-8xl text-gray-400\'></i>'">
            @else
                <i class="fas fa-box text-8xl text-gray-400"></i>
            @endif
            @if($product->popular)
                <span class="absolute top-4 left-4 px-3 py-1.5 bg-red-500 text-white text-sm font-semibold rounded-full">
                    🔥 Produk Populer
                </span>
            @endif
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
            <div>
                <p class="text-emerald-600 font-semibold text-sm mb-2">{{ $product->category }}</p>
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-3">{{ $product->name }}</h1>
                
                <!-- Rating -->
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="text-gray-500 text-sm">(4.5) • 120 terjual</span>
                </div>

                <!-- Price -->
                <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 p-4 rounded-xl mb-4">
                    <p class="text-sm text-gray-600 mb-1">Harga</p>
                    <p class="text-4xl font-bold text-emerald-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>

                <!-- Stock -->
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 {{ $product->stock > 0 ? 'bg-green-500' : 'bg-red-500' }} rounded-full"></span>
                    <span class="{{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                        {{ $product->stock > 0 ? $product->stock . ' stok tersedia' : 'Stok habis' }}
                    </span>
                </div>

                <!-- Description -->
                <p class="text-gray-600 leading-relaxed">
                    {{ $product->description ?? 'Produk berkualitas tinggi dengan garansi resmi. Pengiriman cepat dan aman.' }}
                </p>
            </div>

            <!-- Varian Produk -->
            @if(is_array($product->variants) && count($product->variants) > 0)
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Pilihan Produk</h3>
                <div id="productVariants" class="flex flex-wrap gap-x-8 gap-y-4">
                    @foreach($product->variants as $variantName => $options)
                    <div class="variant-group" data-name="{{ $variantName }}">
                        <p class="text-xs font-medium text-gray-500 mb-2 uppercase tracking-wide">{{ $variantName }} <span class="text-red-500">*</span></p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($options as $option)
                            <label class="variant-option cursor-pointer" onclick="selectVariant(this)">
                                <input type="radio" name="variant_{{ Str::slug($variantName) }}" value="{{ $option }}" class="hidden">
                                <div class="variant-btn px-4 py-2 border-2 border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:border-emerald-300 transition-all select-none">
                                    {{ $option }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Quantity Selector -->
            <div class="border-t border-gray-200 pt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                <div class="flex items-center gap-4">
                    <div class="flex items-center border-2 border-gray-300 rounded-lg">
                        <button onclick="updateQty(-1)" class="px-4 py-3 hover:bg-gray-100 transition" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-minus text-sm"></i>
                        </button>
                        <input type="number" id="qty" value="1" min="1" max="{{ $product->stock }}" 
                               class="w-16 text-center border-0 font-semibold focus:ring-0" readonly>
                        <button onclick="updateQty(1)" class="px-4 py-3 hover:bg-gray-100 transition" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                    </div>
                    <span class="text-sm text-gray-500">Maksimal {{ $product->stock }} unit</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                @if($product->stock > 0)
                <button onclick="addToCart()" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-4 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Tambah ke Keranjang</span>
                </button>
                
                <button onclick="buyNow()" 
                        class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold py-4 rounded-xl transition flex items-center justify-center gap-2 shadow-lg">
                    <i class="fas fa-bolt"></i>
                    <span>Beli Sekarang</span>
                </button>
                @else
                <button disabled class="w-full bg-gray-400 text-white font-semibold py-4 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                    <i class="fas fa-ban"></i>
                    <span>Stok Habis</span>
                </button>
                @endif

                <button onclick="toggleWishlist()" 
                        id="wishlistBtn"
                        class="w-full border-2 border-gray-300 hover:border-red-400 hover:text-red-500 font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fas fa-heart" id="wishlistIcon"></i>
                    <span id="wishlistText">{{ $inWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-12">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Produk Terkait</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $rp)
            <a href="{{ route('user.products.show', $rp->id) }}" class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-lg transition group">
                <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center relative overflow-hidden">
                    @if($rp->image)
                        <img src="{{ asset('storage/'.$rp->image) }}" 
                             alt="{{ $rp->name }}"
                             class="max-h-full max-w-full object-contain group-hover:scale-110 transition">
                    @else
                        <i class="fas fa-box text-4xl text-gray-400"></i>
                    @endif
                </div>
                <div class="p-4">
                    <p class="text-xs text-emerald-600 font-medium">{{ $rp->category }}</p>
                    <h4 class="font-semibold text-gray-800 mt-1 truncate">{{ $rp->name }}</h4>
                    <p class="text-lg font-bold text-emerald-600 mt-2">Rp {{ number_format($rp->price, 0, ',', '.') }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
const productId = {{ $product->id }};
const maxStock = {{ $product->stock }};
const inWishlist = {{ $inWishlist ? 'true' : 'false' }};

function updateQty(change) {
    const qtyInput = document.getElementById('qty');
    let newValue = parseInt(qtyInput.value) + change;
    if (newValue < 1) newValue = 1;
    if (newValue > maxStock) newValue = maxStock;
    qtyInput.value = newValue;
}

function isMutuallyExclusive() {
    const groups = document.querySelectorAll('.variant-group');
    const independentKeys = ['warna', 'color', 'ukuran', 'size', 'ram', 'penyimpanan', 'storage', 'memory', 'spesifikasi', 'versi', 'type', 'tipe'];
    
    for (let group of groups) {
        const name = group.getAttribute('data-name').toLowerCase();
        let isIndependent = false;
        for (let key of independentKeys) {
            if (name.includes(key)) {
                isIndependent = true;
                break;
            }
        }
        if (!isIndependent) {
            return true;
        }
    }
    return false;
}

function selectVariant(element) {
    const group = element.closest('.variant-group');
    const optionBtn = element.querySelector('.variant-btn');
    const input = element.querySelector('input[type="radio"]');
    
    if (isMutuallyExclusive()) {
        document.querySelectorAll('.variant-option').forEach(opt => {
            const btn = opt.querySelector('.variant-btn');
            const inp = opt.querySelector('input[type="radio"]');
            inp.checked = false;
            btn.classList.remove('border-emerald-500', 'bg-emerald-50', 'text-emerald-700');
            btn.classList.add('border-gray-200', 'text-gray-600');
        });
    } else {
        group.querySelectorAll('.variant-option').forEach(opt => {
            const btn = opt.querySelector('.variant-btn');
            const inp = opt.querySelector('input[type="radio"]');
            inp.checked = false;
            btn.classList.remove('border-emerald-500', 'bg-emerald-50', 'text-emerald-700');
            btn.classList.add('border-gray-200', 'text-gray-600');
        });
    }
    
    input.checked = true;
    optionBtn.classList.remove('border-gray-200', 'text-gray-600');
    optionBtn.classList.add('border-emerald-500', 'bg-emerald-50', 'text-emerald-700');
}

function getSelectedVariants() {
    const variantGroups = document.querySelectorAll('.variant-group');
    if (variantGroups.length === 0) return null;
    
    let selected = {};
    
    if (isMutuallyExclusive()) {
        let anySelected = false;
        variantGroups.forEach(group => {
            const name = group.getAttribute('data-name');
            const checked = group.querySelector('input[type="radio"]:checked');
            if (checked) {
                selected[name] = checked.value;
                anySelected = true;
            }
        });
        
        if (!anySelected) {
            alert('Mohon pilih salah satu pilihan produk terlebih dahulu.');
            return false;
        }
    } else {
        let allSelected = true;
        variantGroups.forEach(group => {
            const name = group.getAttribute('data-name');
            const checked = group.querySelector('input[type="radio"]:checked');
            if (checked) {
                selected[name] = checked.value;
            } else {
                allSelected = false;
            }
        });
        
        if (!allSelected) {
            alert('Mohon pilih semua opsi produk (seperti Ukuran, Warna) terlebih dahulu.');
            return false;
        }
    }
    
    return selected;
}

function addToCart() {
    const qty = parseInt(document.getElementById('qty').value);
    const variants = getSelectedVariants();
    
    if (variants === false) return; // Validation failed
    
    fetch('/user/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            qty: qty,
            variants: variants
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Produk berhasil ditambahkan ke keranjang!');
            updateCartCount();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

function buyNow() {
    const qty = parseInt(document.getElementById('qty').value);
    const variants = getSelectedVariants();
    
    if (variants === false) return; // Validation failed
    
    fetch('/user/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            qty: qty,
            variants: variants
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/user/cart/checkout';
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

function toggleWishlist() {
    fetch('/user/wishlist/' + productId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const added = data.action === 'added';
            document.getElementById('wishlistText').textContent = added ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist';
            const icon = document.getElementById('wishlistIcon');
            if (added) {
                icon.classList.add('text-red-500');
            } else {
                icon.classList.remove('text-red-500');
            }
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function updateCartCount() {
    fetch('/user/cart/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('cartBadge');
            if (badge && data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'flex';
            }
        })
        .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', function() {
    if (inWishlist) {
        document.getElementById('wishlistIcon').classList.add('text-red-500');
    }
});
</script>
@endsection
