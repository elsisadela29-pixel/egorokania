<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nail Art Stock Management') - Sistem Kelola Stok Bahan Nail Art</title>

    <!-- Google Fonts: Plus Jakarta Sans & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
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
                            700: '#7e22ce',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Poppins', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 10px 30px -5px rgba(236, 72, 153, 0.08), 0 5px 15px -5px rgba(168, 85, 247, 0.05)',
                        'glow': '0 0 25px rgba(244, 114, 182, 0.35)',
                    }
                }
            }
        }
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif;
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: #fdf2f8;
        }
        ::-webkit-scrollbar-thumb {
            background: #fbcfe8;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #f472b6;
        }
        .nav-link.active {
            background: linear-gradient(135deg, #ec4899 0%, #c084fc 100%);
            color: #ffffff;
            box-shadow: 0 8px 20px -4px rgba(236, 72, 153, 0.35);
        }
        .nav-link.active i {
            color: #ffffff;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gradient-to-br from-brand-50 via-white to-lilac-50 min-h-screen text-slate-700 antialiased selection:bg-brand-200 selection:text-brand-700">

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity"></div>

    <div class="flex min-h-screen">
        <!-- SIDEBAR -->
        <aside id="main-sidebar" class="fixed top-0 bottom-0 left-0 w-72 bg-white/95 backdrop-blur-md border-r border-brand-100/70 z-50 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 shadow-soft">
            <!-- Sidebar Header / Logo -->
            <div class="px-6 py-5 border-b border-brand-100/60 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-brand-400 via-pink-400 to-lilac-400 flex items-center justify-center text-white text-xl shadow-md group-hover:scale-105 transition-transform duration-200">
                        💅
                    </div>
                    <div>
                        <h1 class="text-base font-extrabold tracking-tight bg-gradient-to-r from-brand-600 via-pink-600 to-lilac-600 bg-clip-text text-transparent">
                            NAIL ART STOCK
                        </h1>
                        <p class="text-[11px] font-medium text-slate-400 -mt-0.5 tracking-wider uppercase">Salon Inventory</p>
                    </div>
                </a>
                <button id="close-sidebar-btn" class="lg:hidden p-1.5 text-slate-400 hover:text-brand-500 rounded-lg hover:bg-brand-50">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto px-4 py-5 space-y-1.5">
                <div class="px-3 pb-2 text-[11px] font-semibold tracking-wider text-brand-400 uppercase">
                    Menu Utama
                </div>

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="nav-link flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'active' : 'text-slate-600 hover:bg-brand-50/80 hover:text-brand-600' }}">
                    <i class="fas fa-chart-pie w-5 text-center text-base {{ request()->routeIs('dashboard') ? 'text-white' : 'text-brand-400' }}"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Data Barang -->
                <a href="{{ route('items.index') }}" class="nav-link flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('items.*') ? 'active' : 'text-slate-600 hover:bg-brand-50/80 hover:text-brand-600' }}">
                    <i class="fas fa-gem w-5 text-center text-base {{ request()->routeIs('items.*') ? 'text-white' : 'text-brand-400' }}"></i>
                    <span>Data Barang & Bahan</span>
                </a>

                <!-- Kategori -->
                <a href="{{ route('categories.index') }}" class="nav-link flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('categories.*') ? 'active' : 'text-slate-600 hover:bg-brand-50/80 hover:text-brand-600' }}">
                    <i class="fas fa-tags w-5 text-center text-base {{ request()->routeIs('categories.*') ? 'text-white' : 'text-lilac-400' }}"></i>
                    <span>Kategori Barang</span>
                </a>

                <div class="pt-4 px-3 pb-2 text-[11px] font-semibold tracking-wider text-lilac-400 uppercase">
                    Transaksi Stok
                </div>

                <!-- Barang Masuk -->
                <a href="{{ route('transactions.masuk') }}" class="nav-link flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('transactions.masuk*') ? 'active' : 'text-slate-600 hover:bg-emerald-50/80 hover:text-emerald-600' }}">
                    <i class="fas fa-arrow-down-long w-5 text-center text-base {{ request()->routeIs('transactions.masuk*') ? 'text-white' : 'text-emerald-500' }}"></i>
                    <span>Barang Masuk (Beli)</span>
                </a>

                <!-- Barang Keluar -->
                <a href="{{ route('transactions.keluar') }}" class="nav-link flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('transactions.keluar*') ? 'active' : 'text-slate-600 hover:bg-amber-50/80 hover:text-amber-600' }}">
                    <i class="fas fa-arrow-up-long w-5 text-center text-base {{ request()->routeIs('transactions.keluar*') ? 'text-white' : 'text-amber-500' }}"></i>
                    <span>Barang Keluar (Pakai)</span>
                </a>

                <!-- Riwayat Transaksi -->
                <a href="{{ route('transactions.riwayat') }}" class="nav-link flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('transactions.riwayat') ? 'active' : 'text-slate-600 hover:bg-brand-50/80 hover:text-brand-600' }}">
                    <i class="fas fa-clock-rotate-left w-5 text-center text-base {{ request()->routeIs('transactions.riwayat') ? 'text-white' : 'text-brand-400' }}"></i>
                    <span>Riwayat Transaksi</span>
                </a>

                <div class="pt-4 px-3 pb-2 text-[11px] font-semibold tracking-wider text-rose-400 uppercase">
                    Monitoring
                </div>

                <!-- Notifikasi Stok Menipis -->
                @php
                    $lowStockBadgeCount = \App\Models\Item::whereColumn('jumlah_stok', '<=', 'stok_minimum')->count();
                @endphp
                <a href="{{ route('notifications.index') }}" class="nav-link flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('notifications.*') ? 'active' : 'text-slate-600 hover:bg-rose-50/80 hover:text-rose-600' }}">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-bell w-5 text-center text-base {{ request()->routeIs('notifications.*') ? 'text-white' : 'text-rose-500' }}"></i>
                        <span>Notifikasi Stok</span>
                    </div>
                    @if($lowStockBadgeCount > 0)
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ request()->routeIs('notifications.*') ? 'bg-white text-rose-600' : 'bg-rose-500 text-white animate-pulse' }}">
                            {{ $lowStockBadgeCount }}
                        </span>
                    @endif
                </a>
            </div>

            <!-- User Card & Logout -->
            <div class="p-4 border-t border-brand-100/60 bg-gradient-to-r from-brand-50/50 to-lilac-50/50">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-400 to-lilac-400 flex items-center justify-center text-white font-bold shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'Admin', 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email ?? 'admin@nailart.com' }}</p>
                        </div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2 px-3 rounded-xl bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 text-xs font-semibold flex items-center justify-center space-x-2 transition-colors shadow-sm">
                        <i class="fas fa-arrow-right-from-bracket"></i>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="flex-1 lg:pl-72 flex flex-col min-h-screen">
            <!-- TOP NAVBAR -->
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-brand-100/60 px-4 sm:px-8 py-3.5 flex items-center justify-between shadow-xs">
                <div class="flex items-center space-x-3">
                    <button id="open-sidebar-btn" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-brand-50 hover:text-brand-600 transition-colors">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-xs text-slate-400 hidden sm:block">@yield('page-subtitle', 'Sistem Pengelolaan Stok Persediaan Nail Art')</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Quick notification indicator -->
                    <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-xl text-slate-500 hover:text-brand-600 hover:bg-brand-50 transition-colors" title="Peringatan Stok">
                        <i class="fas fa-bell text-lg"></i>
                        @if($lowStockBadgeCount > 0)
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white"></span>
                        @endif
                    </a>

                    <!-- Current Date Badge -->
                    <div class="hidden sm:flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-brand-50/80 border border-brand-100 text-brand-700 text-xs font-semibold">
                        <i class="far fa-calendar-alt text-brand-500"></i>
                        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                </div>
            </header>

            <!-- MAIN BODY CONTENT -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <!-- FLASH ALERTS -->
                @if(session('success'))
                    <div id="flash-success" class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start justify-between shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold">Berhasil!</h4>
                                <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button onclick="document.getElementById('flash-success').remove()" class="text-emerald-400 hover:text-emerald-700 p-1">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div id="flash-error" class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start justify-between shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0">
                                <i class="fas fa-exclamation text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold">Perhatian!</h4>
                                <p class="text-xs text-rose-700 mt-0.5">{{ session('error') }}</p>
                            </div>
                        </div>
                        <button onclick="document.getElementById('flash-error').remove()" class="text-rose-400 hover:text-rose-700 p-1">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
                        <div class="flex items-center space-x-2 text-sm font-bold mb-2">
                            <i class="fas fa-circle-exclamation text-rose-500"></i>
                            <span>Terdapat beberapa kesalahan input:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-1 text-rose-700 ml-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- FOOTER -->
            <footer class="bg-white/60 border-t border-brand-100/50 py-4 px-6 text-center text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} <strong class="text-slate-600 font-semibold">NAIL ART STOCK MANAGEMENT</strong>. Aplikasi Pengelolaan Persediaan Bahan & Alat Salon Kuku.</p>
            </footer>
        </div>
    </div>

    <!-- Mobile Sidebar JavaScript -->
    <script>
        const sidebar = document.getElementById('main-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('open-sidebar-btn');
        const closeBtn = document.getElementById('close-sidebar-btn');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        if (openBtn) openBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);

        // Auto close flash alerts
        setTimeout(function() {
            const successAlert = document.getElementById('flash-success');
            if (successAlert) {
                successAlert.style.transition = 'opacity 0.5s ease';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }
        }, 5000);
    </script>

    @yield('scripts')
</body>
</html>
