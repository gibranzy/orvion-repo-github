<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Orvion Mart</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .floating-shapes div {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 8s infinite ease-in-out;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        .btn-white {
            background: white;
            color: #667eea;
            transition: all 0.3s ease;
        }
        .btn-white:hover {
            background: #f8f9ff;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.3);
        }
        .btn-outline {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: white;
            transform: translateY(-2px);
        }
        .logo-hover {
            transition: transform 0.3s ease;
        }
        .logo-hover:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="gradient-bg relative">
    
    <!-- Floating Background Shapes -->
    <div class="floating-shapes pointer-events-none fixed inset-0 z-0">
        <div class="w-24 h-24 top-10 left-10" style="animation-delay: 0s;"></div>
        <div class="w-32 h-32 top-40 right-20" style="animation-delay: 1.5s;"></div>
        <div class="w-16 h-16 bottom-20 left-1/4" style="animation-delay: 3s;"></div>
        <div class="w-20 h-20 bottom-40 right-1/3" style="animation-delay: 4.5s;"></div>
        <div class="w-12 h-12 top-1/3 left-1/3" style="animation-delay: 6s;"></div>
    </div>

    <!-- Main Content - Scrollable -->
    <div class="relative z-10 min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-6xl mx-auto w-full">
            
            <!-- Logo Section (FIXED: Tanpa Background, Ukuran Lebih Besar) -->
            <div class="text-center mb-12">
                <div class="mb-6 inline-block">
                    <img src="{{ asset('images/orvion-logo.png') }}" 
                         alt="Orvion Mart" 
                         class="h-20 w-auto object-contain logo-hover drop-shadow-lg"
                         onerror="this.src='https://via.placeholder.com/200x80?text=Orvion+Mart'">
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    Selamat Datang di Orvion Mart
                </h1>
                <p class="text-xl text-indigo-100 max-w-2xl mx-auto px-4">
                    Platform e-commerce modern untuk pengalaman berbelanja yang mudah, cepat, dan aman
                </p>
            </div>

            <!-- Features Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white bg-opacity-95 backdrop-blur-sm p-6 rounded-xl card-hover">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shopping-bag text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Belanja Mudah</h3>
                    <p class="text-gray-600 text-sm">Ribuan produk berkualitas dengan harga terbaik dan proses pemesanan yang simpel</p>
                </div>

                <div class="bg-white bg-opacity-95 backdrop-blur-sm p-6 rounded-xl card-hover">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shipping-fast text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Pengiriman Cepat</h3>
                    <p class="text-gray-600 text-sm">Pengiriman ke seluruh Indonesia dengan tracking real-time dan garansi aman</p>
                </div>

                <div class="bg-white bg-opacity-95 backdrop-blur-sm p-6 rounded-xl card-hover">
                    <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Transaksi Aman</h3>
                    <p class="text-gray-600 text-sm">Sistem pembayaran terenkripsi dan perlindungan data pribadi Anda</p>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                <a href="{{ route('login') }}" class="btn-white w-full sm:w-auto font-bold py-4 px-8 rounded-xl shadow-lg flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk ke Akun</span>
                </a>
                
                <a href="{{ route('register') }}" class="btn-outline w-full sm:w-auto font-bold py-4 px-8 rounded-xl flex items-center justify-center gap-2">
                    <i class="fas fa-user-plus"></i>
                    <span>Daftar Sekarang</span>
                </a>
            </div>

            <!-- Stats Section -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white bg-opacity-10 backdrop-blur-sm p-6 rounded-xl text-center border border-white border-opacity-20">
                    <p class="text-3xl font-bold text-white mb-1">10K+</p>
                    <p class="text-indigo-100 text-sm">Produk Tersedia</p>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-blur-sm p-6 rounded-xl text-center border border-white border-opacity-20">
                    <p class="text-3xl font-bold text-white mb-1">50K+</p>
                    <p class="text-indigo-100 text-sm">Pelanggan Puas</p>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-blur-sm p-6 rounded-xl text-center border border-white border-opacity-20">
                    <p class="text-3xl font-bold text-white mb-1">100+</p>
                    <p class="text-indigo-100 text-sm">Kota Terjangkau</p>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-blur-sm p-6 rounded-xl text-center border border-white border-opacity-20">
                    <p class="text-3xl font-bold text-white mb-1">24/7</p>
                    <p class="text-indigo-100 text-sm">Customer Support</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-indigo-200 text-sm mt-8 pb-4">
                <p>&copy; {{ date('Y') }} <span class="font-semibold">Orvion Mart</span>. All rights reserved.</p>
                <p class="mt-2">Platform E-Commerce Terpercaya Indonesia</p>
            </div>
        </div>
    </div>
</body>
</html>