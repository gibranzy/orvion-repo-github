@extends('layouts.user')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-shopping-cart mr-2 text-emerald-600"></i>
        Keranjang Belanja
    </h2>

    @if(count($cartItems) > 0)
    <!-- Select All / Checklist Section -->
    <div class="bg-white rounded-xl shadow-sm border p-4 mb-4 flex items-center gap-3">
        <input type="checkbox" id="selectAll" class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer" checked onchange="toggleSelectAll(this)">
        <label for="selectAll" class="text-sm font-semibold text-gray-700 cursor-pointer select-none">Pilih Semua</label>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6">
        <div class="divide-y divide-gray-100">
            @foreach($cartItems as $item)
            <div class="p-6 flex flex-col sm:flex-row items-center gap-4">
                <!-- Checkbox Pilihan -->
                <div class="flex items-center flex-shrink-0">
                    <input type="checkbox" 
                           value="{{ $loop->index }}" 
                           data-subtotal="{{ $item['subtotal'] }}"
                           class="cart-item-checkbox w-5 h-5 text-emerald-600 border-gray-300 rounded-full focus:ring-emerald-500 cursor-pointer" 
                           checked 
                           onchange="updateSelection()">
                </div>

                <!-- Product Image -->
                <div class="w-full sm:w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if($item['product']->image)
                        <img src="{{ asset('storage/'.$item['product']->image) }}" 
                             alt="{{ $item['product']->name }}"
                             class="max-h-full max-w-full object-contain"
                             onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-box text-3xl text-gray-400\'></i>'">
                    @else
                        <i class="fas fa-box text-3xl text-gray-400"></i>
                    @endif
                </div>
 
                <!-- Product Info -->
                <div class="flex-1 w-full">
                    <h3 class="font-semibold text-gray-800 text-lg">{{ $item['product']->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $item['product']->category }}</p>
                    
                    @if(isset($item['variants']) && is_array($item['variants']) && count($item['variants']) > 0)
                        <div class="mt-2 text-sm text-gray-600 bg-gray-50 p-2 rounded-lg border border-gray-200 inline-block">
                            @foreach($item['variants'] as $k => $v)
                                <span class="mr-3 last:mr-0"><strong class="text-gray-700">{{ $k }}:</strong> {{ $v }}</span>
                            @endforeach
                        </div>
                    @endif
 
                    <p class="text-emerald-600 font-bold mt-2">Rp {{ number_format($item['product']->price, 0, ',', '.') }}</p>
                    
                    <!-- Quantity Controls -->
                    <div class="flex items-center gap-3 mt-3">
                        <div class="flex items-center border-2 border-gray-300 rounded-lg bg-white">
                            <button onclick='updateQty({{ $item['product']->id }}, -1, @json($item['variants'] ?? null), {{ $loop->index }})' class="px-3 py-1 hover:bg-gray-100 transition">
                                <i class="fas fa-minus text-xs"></i>
                            </button>
                            <input type="number" id="qty-{{ $loop->index }}" value="{{ $item['qty'] }}" min="1" max="{{ $item['product']->stock }}" 
                                   class="w-12 text-center border-0 font-semibold focus:ring-0" readonly>
                            <button onclick='updateQty({{ $item['product']->id }}, 1, @json($item['variants'] ?? null), {{ $loop->index }})' class="px-3 py-1 hover:bg-gray-100 transition">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        </div>
                        <span class="text-sm text-gray-500">Stok: {{ $item['product']->stock }}</span>
                    </div>
                </div>
 
                <!-- Subtotal & Actions -->
                <div class="text-right w-full sm:w-auto">
                    <p class="text-lg font-bold text-gray-800 mb-3">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                    <button onclick='removeItem({{ $item['product']->id }}, @json($item['variants'] ?? null))' class="text-red-500 hover:text-red-700 text-sm font-medium">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </div>
            @endforeach
        </div>
 
        <!-- Cart Footer -->
        <div class="bg-gray-50 p-6 border-t">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <p class="text-gray-600 mb-1">Total Belanja</p>
                    <p class="text-3xl font-bold text-emerald-600" id="cartTotal">Rp {{ number_format($total, 0, ',', '.') }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('user.products.index') }}" class="px-6 py-3 border-2 border-gray-300 hover:bg-gray-100 text-gray-700 rounded-lg font-medium transition">
                        <i class="fas fa-arrow-left mr-2"></i>Lanjut Belanja
                    </a>
                    <a href="{{ route('user.cart.checkout') }}" id="checkoutBtn" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition">
                        <i class="fas fa-credit-card mr-2"></i>Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty Cart -->
    <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-shopping-cart text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Keranjang Kosong</h3>
        <p class="text-gray-500 mb-6">Mulai belanja untuk menambahkan produk ke keranjang</p>
        <a href="{{ route('user.products.index') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-lg font-medium transition">
            <i class="fas fa-shopping-bag mr-2"></i>Mulai Belanja
        </a>
    </div>
    @endif
</div>

<script>
function toggleSelectAll(selectAllCheckbox) {
    const isChecked = selectAllCheckbox.checked;
    document.querySelectorAll('.cart-item-checkbox').forEach(checkbox => {
        checkbox.checked = isChecked;
    });
    updateSelection();
}

function updateSelection() {
    let total = 0;
    let selectedIndices = [];
    const checkboxes = document.querySelectorAll('.cart-item-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    
    let allChecked = true;
    let anyChecked = false;
    
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            total += parseFloat(checkbox.getAttribute('data-subtotal'));
            selectedIndices.push(checkbox.value);
            anyChecked = true;
        } else {
            allChecked = false;
        }
    });
    
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = allChecked && checkboxes.length > 0;
    }
    
    // Update total display
    document.getElementById('cartTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    
    // Update checkout button URL
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (anyChecked) {
        checkoutBtn.href = "{{ route('user.cart.checkout') }}?selected_indices=" + selectedIndices.join(',');
        checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
    } else {
        checkoutBtn.href = '#';
        checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
    }
}

// Jalankan kalkulasi saat halaman selesai dimuat
document.addEventListener('DOMContentLoaded', updateSelection);

function updateQty(productId, change, variants, index) {
    const qtyInput = document.getElementById('qty-' + index);
    let newValue = parseInt(qtyInput.value) + change;
    if (newValue < 1) newValue = 1;
    qtyInput.value = newValue;
    
    fetch('/user/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            qty: newValue,
            variants: variants
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    });
}

function removeItem(productId, variants) {
    if (!confirm('Hapus produk dari keranjang?')) return;
    
    fetch('/user/cart/remove/' + productId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            variants: variants
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endsection