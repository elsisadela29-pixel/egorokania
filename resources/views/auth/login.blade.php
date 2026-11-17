<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - NAIL ART STOCK MANAGEMENT</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fdf2f8',
                            100: '#fce7f3',
                            200: '#fbcfe8',
                            300: '#f9a8d4',
                            400: '#f472b6',
                            500: '#ec4899',
                            600: '#db2777',
                            700: '#be185d',
                        },
                        lilac: {
                            50: '#faf5ff',
                            100: '#f3e8ff',
                            200: '#e9d5ff',
                            300: '#d8b4fe',
                            400: '#c084fc',
                            500: '#a855f7',
                            600: '#9333ea',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-pink-100 via-purple-50 to-pink-50 flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Decorative background elements -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-brand-200/50 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-lilac-200/50 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-pink-300/30 rounded-full blur-2xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-tr from-brand-400 via-pink-400 to-lilac-400 text-white text-4xl shadow-lg shadow-brand-300/50 mb-4 transform hover:scale-105 transition-transform duration-300">
                💅
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight bg-gradient-to-r from-brand-600 via-pink-600 to-lilac-600 bg-clip-text text-transparent">
                NAIL ART STOCK
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">
                Sistem Pengelolaan Stok Persediaan Salon Kuku
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-6 sm:p-8 shadow-xl shadow-pink-200/40 border border-white">
            <div class="mb-6 text-center">
                <h2 class="text-lg font-bold text-slate-800">Masuk Akun Admin</h2>
                <p class="text-xs text-slate-400 mt-1">Silakan masukkan email dan password untuk melanjutkan</p>
            </div>

            <!-- Error Alerts -->
            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start space-x-2.5">
                    <i class="fas fa-circle-exclamation text-rose-500 mt-0.5 text-sm"></i>
                    <div>
                        <strong class="font-semibold">Login Gagal:</strong>
                        <p class="mt-0.5">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs flex items-center space-x-2.5">
                    <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Alamat Email Admin
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-envelope text-sm text-brand-400"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email', 'admin@nailart.com') }}" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50/70 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs sm:text-sm text-slate-800 outline-none transition-all"
                            placeholder="admin@nailart.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-lock text-sm text-brand-400"></i>
                        </div>
                        <input type="password" name="password" id="password" value="password123" required
                            class="w-full pl-10 pr-10 py-2.5 bg-slate-50/70 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs sm:text-sm text-slate-800 outline-none transition-all"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-brand-500">
                            <i class="fas fa-eye text-sm" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-brand-500 focus:ring-brand-400 border-slate-300">
                        <span class="text-slate-600 font-medium">Ingat Saya</span>
                    </label>
                    <span class="text-slate-400">Role: Administrator</span>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-brand-500 via-pink-500 to-lilac-500 text-white font-bold text-sm shadow-md shadow-brand-300/40 hover:shadow-lg hover:shadow-brand-300/60 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 flex items-center justify-center space-x-2">
                    <i class="fas fa-right-to-bracket"></i>
                    <span>Masuk ke Dashboard</span>
                </button>
            </form>

            <!-- Quick Access Test Account Box -->
            <div class="mt-6 p-3.5 rounded-2xl bg-brand-50/60 border border-brand-100 text-xs text-slate-600">
                <div class="flex items-center space-x-2 text-brand-600 font-bold mb-1">
                    <i class="fas fa-key"></i>
                    <span>Akun Pengujian Demo (Otomatis Terisi):</span>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-1.5 font-mono text-[11px] bg-white p-2 rounded-xl border border-brand-100/50">
                    <div>
                        <span class="text-slate-400">Email:</span>
                        <p class="font-semibold text-slate-700 select-all">admin@nailart.com</p>
                    </div>
                    <div>
                        <span class="text-slate-400">Password:</span>
                        <p class="font-semibold text-slate-700 select-all">password123</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} NAIL ART STOCK MANAGEMENT</p>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
