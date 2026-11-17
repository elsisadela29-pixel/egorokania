@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Utama')
@section('page-subtitle', 'Ringkasan Statistik Persediaan & Aktivitas Stok Nail Art')

@section('content')
<div class="space-y-6">

    <!-- WELCOME BANNER -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-brand-500 via-pink-500 to-lilac-500 p-6 sm:p-8 text-white shadow-soft">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-md mb-2">
                    ✨ Selamat Datang di Nail Art Stock System
                </span>
                <h3 class="text-xl sm:text-2xl font-extrabold tracking-tight">
                    Halo, {{ Auth::user()->name ?? 'Administrator' }}! 👋
                </h3>
                <p class="text-xs sm:text-sm text-pink-100 max-w-xl mt-1">
                    Kelola seluruh persediaan kutek gel, aksesoris, kuas, dan bahan nail art secara akurat dan mudah.
                </p>
            </div>
            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('items.create') }}" class="px-4 py-2.5 rounded-2xl bg-white text-brand-600 font-bold text-xs shadow-md hover:bg-brand-50 transition-all flex items-center space-x-2">
                    <i class="fas fa-plus-circle text-sm"></i>
                    <span>Tambah Barang</span>
                </a>
                <a href="{{ route('transactions.masuk') }}" class="px-4 py-2.5 rounded-2xl bg-white/20 hover:bg-white/30 backdrop-blur-md text-white font-bold text-xs border border-white/30 transition-all flex items-center space-x-2">
                    <i class="fas fa-arrow-down text-xs"></i>
                    <span>Barang Masuk</span>
                </a>
                <a href="{{ route('transactions.keluar') }}" class="px-4 py-2.5 rounded-2xl bg-white/20 hover:bg-white/30 backdrop-blur-md text-white font-bold text-xs border border-white/30 transition-all flex items-center space-x-2">
                    <i class="fas fa-arrow-up text-xs"></i>
                    <span>Barang Keluar</span>
                </a>
            </div>
        </div>
    </div>

    <!-- STATS CARDS (6 Metrik Utama) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        <!-- 1. Total Seluruh Barang -->
        <div class="p-4 rounded-3xl bg-white border border-brand-100/70 shadow-soft hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500">Total Barang</span>
                <div class="w-9 h-9 rounded-2xl bg-brand-50 text-brand-500 flex items-center justify-center text-sm shadow-xs">
                    <i class="fas fa-gem"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">{{ number_format($totalBarang) }}</div>
            <p class="text-[11px] text-slate-400 mt-1 flex items-center">
                <i class="fas fa-box-open mr-1 text-brand-300"></i> SKU Terdaftar
            </p>
        </div>

        <!-- 2. Total Kategori -->
        <div class="p-4 rounded-3xl bg-white border border-lilac-100/70 shadow-soft hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500">Kategori</span>
                <div class="w-9 h-9 rounded-2xl bg-lilac-50 text-lilac-500 flex items-center justify-center text-sm shadow-xs">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">{{ number_format($totalKategori) }}</div>
            <p class="text-[11px] text-slate-400 mt-1 flex items-center">
                <i class="fas fa-folder mr-1 text-lilac-300"></i> Kelompok Bahan
            </p>
        </div>

        <!-- 3. Total Stok Tersedia -->
        <div class="p-4 rounded-3xl bg-white border border-blue-100/70 shadow-soft hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500">Stok Tersedia</span>
                <div class="w-9 h-9 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-sm shadow-xs">
                    <i class="fas fa-cubes"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">{{ number_format($totalStok) }}</div>
            <p class="text-[11px] text-slate-400 mt-1 flex items-center">
                <i class="fas fa-check-circle mr-1 text-blue-300"></i> Unit Siap Pakai
            </p>
        </div>

        <!-- 4. Hampir Habis / Perlu Reorder -->
        <div class="p-4 rounded-3xl bg-white border border-rose-100/70 shadow-soft hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500">Hampir Habis</span>
                <div class="w-9 h-9 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-sm shadow-xs">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-rose-600 tracking-tight">{{ number_format($barangHampirHabis) }}</div>
            <p class="text-[11px] text-rose-400 mt-1 flex items-center font-medium">
                <i class="fas fa-bell mr-1"></i> Perlu Dibeli Ulang
            </p>
        </div>

        <!-- 5. Ringkasan Barang Masuk Bulan Ini -->
        <div class="p-4 rounded-3xl bg-white border border-emerald-100/70 shadow-soft hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500">Masuk Bln Ini</span>
                <div class="w-9 h-9 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-sm shadow-xs">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 tracking-tight">+{{ number_format($barangMasukBulanIni) }}</div>
            <p class="text-[11px] text-slate-400 mt-1 flex items-center">
                <i class="fas fa-cart-shopping mr-1 text-emerald-300"></i> Unit Masuk
            </p>
        </div>

        <!-- 6. Ringkasan Barang Keluar Bulan Ini -->
        <div class="p-4 rounded-3xl bg-white border border-amber-100/70 shadow-soft hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500">Keluar Bln Ini</span>
                <div class="w-9 h-9 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-sm shadow-xs">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-amber-600 tracking-tight">-{{ number_format($barangKeluarBulanIni) }}</div>
            <p class="text-[11px] text-slate-400 mt-1 flex items-center">
                <i class="fas fa-hand-sparkles mr-1 text-amber-300"></i> Pemakaian Salon
            </p>
        </div>
    </div>

    <!-- GRAFIK & RECENT TRANSACTIONS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Aktivitas Stok (2 Cols) -->
        <div class="lg:col-span-2 p-5 sm:p-6 rounded-3xl bg-white border border-brand-100/70 shadow-soft">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-5">
                <div>
                    <h4 class="text-base font-bold text-slate-800 flex items-center space-x-2">
                        <i class="fas fa-chart-line text-brand-500"></i>
                        <span>Grafik Aktivitas Stok (7 Hari Terakhir)</span>
                    </h4>
                    <p class="text-xs text-slate-400 mt-0.5">Tren kuantitas barang masuk (pembelian) vs barang keluar (pemakaian)</p>
                </div>
                <div class="flex items-center space-x-3 text-xs">
                    <span class="flex items-center space-x-1 text-emerald-600 font-semibold">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                        <span>Masuk</span>
                    </span>
                    <span class="flex items-center space-x-1 text-amber-600 font-semibold">
                        <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                        <span>Keluar</span>
                    </span>
                </div>
            </div>
            <div class="relative h-64 sm:h-72 w-full">
                <canvas id="stockActivityChart"></canvas>
            </div>
        </div>

        <!-- Riwayat Transaksi Terbaru (1 Col) -->
        <div class="p-5 sm:p-6 rounded-3xl bg-white border border-lilac-100/70 shadow-soft flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-base font-bold text-slate-800 flex items-center space-x-2">
                        <i class="fas fa-clock-rotate-left text-lilac-500"></i>
                        <span>Aktivitas Terkini</span>
                    </h4>
                    <p class="text-xs text-slate-400 mt-0.5">Catatan mutasi stok terbaru</p>
                </div>
                <a href="{{ route('transactions.riwayat') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 hover:underline">
                    Lihat Semua
                </a>
            </div>

            <div class="space-y-3 flex-1 overflow-y-auto">
                @forelse($recentTransactions as $tx)
                    <div class="p-3 rounded-2xl border transition-all duration-150 {{ $tx->tipe === 'masuk' ? 'bg-emerald-50/40 border-emerald-100 hover:bg-emerald-50/80' : 'bg-amber-50/40 border-amber-100 hover:bg-amber-50/80' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 {{ $tx->tipe === 'masuk' ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white' }}">
                                    <i class="fas {{ $tx->tipe === 'masuk' ? 'fa-arrow-down' : 'fa-arrow-up' }} text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ $tx->item->nama_barang ?? 'Barang Terhapus' }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $tx->tanggal_transaksi->format('d M Y') }} &bull; {{ $tx->keterangan ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0 ml-2">
                                <span class="text-xs font-extrabold {{ $tx->tipe === 'masuk' ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $tx->tipe === 'masuk' ? '+' : '-' }}{{ $tx->jumlah }}
                                </span>
                                <p class="text-[10px] text-slate-400 uppercase font-medium">{{ $tx->item->satuan ?? 'unit' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="h-48 flex flex-col items-center justify-center text-slate-400 text-xs">
                        <i class="fas fa-inbox text-3xl mb-2 text-slate-300"></i>
                        <span>Belum ada transaksi tercatat</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- DAFTAR BARANG YANG PERLU DIBELI KEMBALI (REORDER LIST) -->
    <div class="p-5 sm:p-6 rounded-3xl bg-white border border-rose-100 shadow-soft">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <h4 class="text-base font-bold text-slate-800 flex items-center space-x-2">
                    <i class="fas fa-triangle-exclamation text-rose-500"></i>
                    <span>Daftar Barang yang Perlu Dibeli Kembali (Reorder Warning)</span>
                </h4>
                <p class="text-xs text-slate-400 mt-0.5">Barang dengan stok saat ini sama dengan atau berada di bawah batas minimum</p>
            </div>
            <a href="{{ route('transactions.masuk') }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-xs font-bold hover:bg-rose-100 transition-colors">
                <i class="fas fa-cart-plus"></i>
                <span>Beli / Restok Barang Sekarang</span>
            </a>
        </div>

        @if($reorderItems->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-3">Kode</th>
                            <th class="py-3 px-3">Nama Barang Nail Art</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3 text-center">Stok Saat Ini</th>
                            <th class="py-3 px-3 text-center">Stok Minimum</th>
                            <th class="py-3 px-3 text-center">Defisit (Kurang)</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-3 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-xs">
                        @foreach($reorderItems as $item)
                            <tr class="hover:bg-rose-50/40 transition-colors">
                                <td class="py-3 px-3 font-mono font-medium text-slate-500">{{ $item->kode_barang }}</td>
                                <td class="py-3 px-3 font-semibold text-slate-800">{{ $item->nama_barang }}</td>
                                <td class="py-3 px-3 text-slate-500">
                                    <span class="px-2 py-0.5 rounded-lg bg-lilac-50 border border-lilac-100 text-lilac-700 text-[11px]">
                                        {{ $item->category->nama_kategori ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="font-extrabold text-sm {{ $item->jumlah_stok == 0 ? 'text-rose-600' : 'text-amber-600' }}">
                                        {{ $item->jumlah_stok }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 ml-0.5">{{ $item->satuan }}</span>
                                </td>
                                <td class="py-3 px-3 text-center text-slate-600 font-medium">
                                    {{ $item->stok_minimum }} {{ $item->satuan }}
                                </td>
                                <td class="py-3 px-3 text-center font-bold text-rose-500">
                                    {{ max(0, $item->stok_minimum - $item->jumlah_stok) }} {{ $item->satuan }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if($item->jumlah_stok <= 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-700">
                                            🔴 Habis
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700">
                                            🟠 Hampir Habis
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <a href="{{ route('transactions.masuk') }}?item_id={{ $item->id }}" class="inline-flex items-center px-2.5 py-1 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-[11px] shadow-xs transition-colors">
                                        <i class="fas fa-plus mr-1"></i> Restok
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-10 text-center text-slate-400">
                <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-2xl mx-auto mb-3">
                    <i class="fas fa-circle-check"></i>
                </div>
                <h5 class="text-sm font-bold text-slate-700">Persediaan Stok Aman & Lengkap!</h5>
                <p class="text-xs text-slate-400 mt-1">Tidak ada barang nail art yang stoknya di bawah batas minimum saat ini.</p>
            </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('stockActivityChart').getContext('2d');
        const labels = {!! json_encode($chartLabels) !!};
        const masukData = {!! json_encode($chartMasuk) !!};
        const keluarData = {!! json_encode($chartKeluar) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: masukData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Barang Keluar',
                        data: keluarData,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.12)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { family: 'Plus Jakarta Sans', size: 12 },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        padding: 10,
                        cornerRadius: 10,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            color: '#94a3b8'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            precision: 0,
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
