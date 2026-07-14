@extends('layouts.user')

@section('title', 'Pengaturan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Pengaturan</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola preferensi dan notifikasi akun Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Settings -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden sticky top-20">
                <nav class="p-2">
                    <a href="#notifications" class="settings-nav block px-4 py-3 text-emerald-600 bg-emerald-50 rounded-lg font-medium mb-1">
                        <i class="fas fa-bell mr-2"></i> Notifikasi
                    </a>
                    <a href="#privacy" class="settings-nav block px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-medium mb-1">
                        <i class="fas fa-user-shield mr-2"></i> Privasi
                    </a>
                    <a href="#preferences" class="settings-nav block px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-medium mb-1">
                        <i class="fas fa-sliders-h mr-2"></i> Preferensi
                    </a>
                    <a href="#payment" class="settings-nav block px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-medium">
                        <i class="fas fa-credit-card mr-2"></i> Pembayaran
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Settings Content -->
        <div class="lg:col-span-3 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Notification Settings -->
            <div id="notifications" class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-bell mr-2 text-emerald-600"></i>
                    Pengaturan Notifikasi
                </h3>
                
                <form action="{{ route('user.settings.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="space-y-3">
                        <label class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                            <input type="checkbox" name="email_notifications" value="1" checked 
                                   class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 mt-0.5">
                            <div class="ml-3 flex-1">
                                <p class="font-medium text-gray-700">Notifikasi Email</p>
                                <p class="text-sm text-gray-500">Terima konfirmasi pesanan dan promo via email</p>
                            </div>
                        </label>

                        <label class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                            <input type="checkbox" name="sms_notifications" value="1" checked 
                                   class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 mt-0.5">
                            <div class="ml-3 flex-1">
                                <p class="font-medium text-gray-700">Notifikasi SMS</p>
                                <p class="text-sm text-gray-500">Terima update status pesanan via SMS</p>
                            </div>
                        </label>

                        <label class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                            <input type="checkbox" name="push_notifications" value="1" checked 
                                   class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 mt-0.5">
                            <div class="ml-3 flex-1">
                                <p class="font-medium text-gray-700">Push Notification</p>
                                <p class="text-sm text-gray-500">Terima notifikasi real-time di browser</p>
                            </div>
                        </label>

                        <label class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                            <input type="checkbox" name="promo_notifications" value="1" 
                                   class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 mt-0.5">
                            <div class="ml-3 flex-1">
                                <p class="font-medium text-gray-700">Notifikasi Promo</p>
                                <p class="text-sm text-gray-500">Terima info diskon dan promo spesial</p>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Privacy Settings -->
            <div id="privacy" class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-shield mr-2 text-emerald-600"></i>
                    Pengaturan Privasi
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-700">Profil Publik</p>
                            <p class="text-sm text-gray-500">Izinkan orang lain melihat profil Anda</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-700">Tampilkan Riwayat Pesanan</p>
                            <p class="text-sm text-gray-500">Tampilkan riwayat pesanan di profil publik</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-700">Tampilkan Wishlist</p>
                            <p class="text-sm text-gray-500">Izinkan orang lain melihat wishlist Anda</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Preferences -->
            <div id="preferences" class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-sliders-h mr-2 text-emerald-600"></i>
                    Preferensi
                </h3>
                
                <form action="{{ route('user.settings.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bahasa</label>
                            <select name="language" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="id">Bahasa Indonesia</option>
                                <option value="en">English</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Zona Waktu</label>
                            <select name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="Asia/Jakarta">Jakarta (GMT+7)</option>
                                <option value="Asia/Makassar">Makassar (GMT+8)</option>
                                <option value="Asia/Jayapura">Jayapura (GMT+9)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mata Uang</label>
                            <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="IDR">Rupiah (IDR)</option>
                                <option value="USD">US Dollar (USD)</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Payment Settings -->
            <div id="payment" class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-credit-card mr-2 text-emerald-600"></i>
                    Metode Pembayaran
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center border">
                                <i class="fas fa-wallet text-emerald-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-700">E-Wallet</p>
                                <p class="text-sm text-gray-500">GoPay, OVO, Dana, ShopeePay</p>
                            </div>
                        </div>
                        <button class="px-4 py-2 border border-emerald-600 text-emerald-600 rounded-lg hover:bg-emerald-50 transition">
                            Kelola
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center border">
                                <i class="fas fa-university text-emerald-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-700">Transfer Bank</p>
                                <p class="text-sm text-gray-500">BCA, Mandiri, BNI, BRI</p>
                            </div>
                        </div>
                        <button class="px-4 py-2 border border-emerald-600 text-emerald-600 rounded-lg hover:bg-emerald-50 transition">
                            Kelola
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center border">
                                <i class="fas fa-money-bill-wave text-emerald-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-700">COD (Bayar di Tempat)</p>
                                <p class="text-sm text-gray-500">Bayar saat pesanan diterima</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-full font-medium">Default</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.settings-nav').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const target = document.getElementById(targetId);
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        document.querySelectorAll('.settings-nav').forEach(l => {
            l.classList.remove('text-emerald-600', 'bg-emerald-50');
            l.classList.add('text-gray-600');
        });
        this.classList.add('text-emerald-600', 'bg-emerald-50');
        this.classList.remove('text-gray-600');
    });
});
</script>
@endsection