@extends('layouts.user')

@section('title', 'Pengaturan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Pengaturan</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola preferensi dan konfigurasi sistem</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Settings -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden sticky top-20">
                <nav class="p-2">
                    <a href="#notifications" class="settings-nav block px-4 py-3 text-emerald-600 bg-emerald-50 rounded-lg font-medium mb-1">
                        <i class="fas fa-bell mr-2"></i> Notifikasi
                    </a>
                    <a href="#security" class="settings-nav block px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-medium mb-1">
                        <i class="fas fa-shield-alt mr-2"></i> Keamanan
                    </a>
                    <a href="#preferences" class="settings-nav block px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-medium">
                        <i class="fas fa-sliders-h mr-2"></i> Preferensi
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
                            <input type="hidden" name="email_notifications" value="0">
                            <input type="checkbox" name="email_notifications" value="1" {{ isset($settings['email_notifications']) && $settings['email_notifications'] ? 'checked' : '' }}
                                   class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 mt-0.5">
                            <div class="ml-3 flex-1">
                                <p class="font-medium text-gray-700">Notifikasi Email</p>
                                <p class="text-sm text-gray-500">Terima notifikasi pesanan dan promo via email</p>
                            </div>
                        </label>

                        <label class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                            <input type="hidden" name="sms_notifications" value="0">
                            <input type="checkbox" name="sms_notifications" value="1" {{ isset($settings['sms_notifications']) && $settings['sms_notifications'] ? 'checked' : '' }}
                                   class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 mt-0.5">
                            <div class="ml-3 flex-1">
                                <p class="font-medium text-gray-700">Notifikasi SMS</p>
                                <p class="text-sm text-gray-500">Terima notifikasi pesanan via SMS</p>
                            </div>
                        </label>

                        <label class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                            <input type="hidden" name="push_notifications" value="0">
                            <input type="checkbox" name="push_notifications" value="1" {{ isset($settings['push_notifications']) && $settings['push_notifications'] ? 'checked' : '' }}
                                   class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 mt-0.5">
                            <div class="ml-3 flex-1">
                                <p class="font-medium text-gray-700">Push Notification</p>
                                <p class="text-sm text-gray-500">Terima notifikasi real-time di browser</p>
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

            <!-- Security Settings -->
            <div id="security" class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-shield-alt mr-2 text-emerald-600"></i>
                    Pengaturan Keamanan
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-700">Two-Factor Authentication</p>
                            <p class="text-sm text-gray-500">Tambahkan lapisan keamanan ekstra pada akun Anda</p>
                        </div>
                        <button class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                            Aktifkan
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-700">Ganti Password</p>
                            <p class="text-sm text-gray-500">Update password Anda secara berkala</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="px-4 py-2 border border-emerald-600 text-emerald-600 rounded-lg hover:bg-emerald-50 transition">
                            Ubah Password
                        </a>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-700">Session Aktif</p>
                            <p class="text-sm text-gray-500">Kelola perangkat yang terhubung ke akun Anda</p>
                        </div>
                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                            Lihat Semua
                        </button>
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
                                <option value="id" {{ (isset($settings['language']) && $settings['language'] == 'id') ? 'selected' : '' }}>Bahasa Indonesia</option>
                                <option value="en" {{ (isset($settings['language']) && $settings['language'] == 'en') ? 'selected' : '' }}>English</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Zona Waktu</label>
                            <select name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="Asia/Jakarta" {{ (isset($settings['timezone']) && $settings['timezone'] == 'Asia/Jakarta') ? 'selected' : '' }}>Jakarta (GMT+7)</option>
                                <option value="Asia/Makassar" {{ (isset($settings['timezone']) && $settings['timezone'] == 'Asia/Makassar') ? 'selected' : '' }}>Makassar (GMT+8)</option>
                                <option value="Asia/Jayapura" {{ (isset($settings['timezone']) && $settings['timezone'] == 'Asia/Jayapura') ? 'selected' : '' }}>Jayapura (GMT+9)</option>
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
        </div>
    </div>
</div>

<script>
// Smooth scroll untuk navigation settings
document.querySelectorAll('.settings-nav').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const target = document.getElementById(targetId);
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        // Update active state
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
