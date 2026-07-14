<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Orvion Mart</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            transition: color 0.2s;
            z-index: 10;
        }
        
        .input-field:focus + .input-icon,
        .input-field:focus ~ .input-icon {
            color: #667eea;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .floating-shapes div {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            animation: float 8s infinite ease-in-out;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }
        
        .checkbox-custom {
            accent-color: #667eea;
        }
        
        /* Logo Animation */
        .logo-hover {
            transition: transform 0.3s ease;
        }
        .logo-hover:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Floating Background Shapes -->
    <div class="floating-shapes pointer-events-none">
        <div class="w-24 h-24 top-10 left-10" style="animation-delay: 0s;"></div>
        <div class="w-32 h-32 top-40 right-20" style="animation-delay: 1.5s;"></div>
        <div class="w-16 h-16 bottom-20 left-1/4" style="animation-delay: 3s;"></div>
        <div class="w-20 h-20 bottom-40 right-1/3" style="animation-delay: 4.5s;"></div>
        <div class="w-12 h-12 top-1/3 left-1/3" style="animation-delay: 6s;"></div>
    </div>

    <!-- Login Card -->
    <div class="login-card rounded-3xl w-full max-w-md relative z-10 overflow-hidden">
        
        <!-- Top Accent Bar -->
        <div class="h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
        
        <div class="p-8 sm:p-10">
            
            <!-- Logo & Header (LOGO ORVION - TANPA BACKGROUND, UKURAN BESAR) -->
            <div class="text-center mb-8">
                <div class="mb-6 inline-block">
                    <img src="{{ asset('images/orvion-logo.png') }}" 
                         alt="Orvion Mart" 
                         class="h-20 w-auto object-contain logo-hover drop-shadow-lg"
                         onerror="this.src='https://via.placeholder.com/200x80?text=Orvion+Mart'">
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang</h1>
                <p class="text-gray-500 text-sm">Masuk ke akun Orvion Mart Anda</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 p-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Alamat Email
                    </label>
                    <div class="input-wrapper">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" 
                               placeholder="nama@email.com"
                               class="input-field w-full px-4 py-3 pl-11 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-indigo-500 outline-none transition text-gray-700" 
                               required autofocus autocomplete="username">
                        <span class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-500 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">
                            Kata Sandi
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition" href="{{ route('password.request') }}">
                                Lupa kata sandi?
                            </a>
                        @endif
                    </div>
                    <div class="input-wrapper">
                        <input id="password" type="password" name="password" 
                               placeholder="••••••••"
                               class="input-field w-full px-4 py-3 pl-11 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-indigo-500 outline-none transition text-gray-700" 
                               required autocomplete="current-password">
                        <span class="input-icon">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-500 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" 
                           class="checkbox-custom w-4 h-4 rounded border-gray-300">
                    <label for="remember_me" class="ms-2 text-sm text-gray-600 cursor-pointer">
                        Ingat saya
                    </label>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-login w-full text-white font-semibold py-3.5 rounded-xl shadow-lg flex items-center justify-center gap-2 text-base">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk ke Dashboard
                </button>
            </form>

            <!-- Back to Welcome Link -->
            <div class="mt-6 text-center">
                <a href="{{ route('welcome') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
                </a>
            </div>

            <!-- Footer -->
            <div class="mt-6 pt-6 border-t border-gray-100">
                <p class="text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} <span class="font-semibold text-gray-500">Orvion Mart</span>. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <!-- Auto-focus Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>