@extends('layouts.user')

@section('title', 'Checkout')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-credit-card mr-2 text-emerald-600"></i>
        Checkout
    </h2>

    <form action="{{ route('user.cart.processCheckout') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="selected_indices" value="{{ $selectedIndicesStr }}">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Shipping Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <h3 class="font-semibold text-gray-800 mb-4">
                        <i class="fas fa-truck mr-2 text-emerald-600"></i>
                        Informasi Pengiriman
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
                            <input type="text" value="{{ auth()->user()->name }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" readonly>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                            <input type="tel" name="phone" value="{{ auth()->user()->phone }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
                            <textarea name="address" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" required>{{ auth()->user()->address }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <h3 class="font-semibold text-gray-800 mb-4">
                        <i class="fas fa-credit-card mr-2 text-emerald-600"></i>
                        Metode Pembayaran
                    </h3>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 border-emerald-500 bg-emerald-50 rounded-lg cursor-pointer">
                            <input type="radio" name="payment_method" value="cod" checked class="text-emerald-600">
                            <div class="ml-3 flex-1">
                                <p class="font-semibold text-gray-800">Bayar di Tempat (COD)</p>
                                <p class="text-sm text-gray-500">Bayar saat pesanan diterima</p>
                            </div>
                            <i class="fas fa-money-bill-wave text-emerald-600 text-2xl"></i>
                        </label>
                        
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-emerald-300">
                            <input type="radio" name="payment_method" value="transfer" class="text-emerald-600">
                            <div class="ml-3 flex-1">
                                <p class="font-semibold text-gray-800">Transfer Bank</p>
                                <p class="text-sm text-gray-500">Transfer ke rekening BCA/Mandiri</p>
                            </div>
                            <i class="fas fa-university text-gray-400 text-2xl"></i>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-sm border sticky top-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Ringkasan Pesanan</h3>
                    
                    <div class="space-y-3 mb-4 max-h-60 overflow-y-auto">
                        @foreach($cartItems as $item)
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $item['product']->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $item['product']->category }}</p>
                            
                            @if(isset($item['variants']) && is_array($item['variants']) && count($item['variants']) > 0)
                                <div class="mt-1 text-xs text-gray-500">
                                    @foreach($item['variants'] as $k => $v)
                                        <span class="mr-2">{{ $k }}: {{ $v }}</span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="flex justify-between mt-2">
                                <span class="text-gray-600">{{ $item['qty'] }} x Rp {{ number_format($item['product']->price, 0, ',', '.') }}</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Ongkir</span>
                            <span class="text-green-600">Gratis</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-800 pt-2 border-t">
                            <span>Total</span>
                            <span class="text-emerald-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition">
                        <i class="fas fa-check-circle mr-2"></i>
                        Konfirmasi Pesanan
                    </button>
                    
                    <a href="{{ route('user.cart.index') }}" class="block text-center mt-3 text-emerald-600 hover:text-emerald-700 font-medium text-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection