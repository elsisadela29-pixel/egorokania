@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Seluruh Transaksi Stok')
@section('page-subtitle', 'Laporan mutasi persediaan barang masuk dan keluar secara lengkap')

@section('content')
<div class="space-y-6">

    <!-- FILTER TOOLBAR -->
    <div class="p-5 rounded-3xl bg-white border border-brand-100/70 shadow-soft">
        <form action="{{ route('transactions.riwayat') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">

            <!-- Search nama barang (4 cols) -->
            <div class="lg:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs text-brand-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama atau kode barang..."
                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-800 outline-none transition-all">
            </div>

            <!-- Tipe Transaksi (2 cols) -->
            <div class="lg:col-span-2">
                <select name="tipe" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-700 outline-none transition-all">
                    <option value="">Semua Tipe</option>
                    <option value="masuk" {{ request('tipe') === 'masuk' ? 'selected' : '' }}>↓ Barang Masuk</option>
                    <option value="keluar" {{ request('tipe') === 'keluar' ? 'selected' : '' }}>↑ Barang Keluar</option>
                </select>
            </div>

            <!-- Dari Tanggal (2 cols) -->
            <div class="lg:col-span-2">
                <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}" title="Dari Tanggal"
                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-700 outline-none transition-all">
            </div>

            <!-- Sampai Tanggal (2 cols) -->
            <div class="lg:col-span-2">
                <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}" title="Sampai Tanggal"
                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-700 outline-none transition-all">
            </div>

            <!-- Submit Buttons (2 cols) -->
            <div class="lg:col-span-2 flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2.5 px-3 rounded-2xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-900 transition-colors flex items-center justify-center space-x-1.5 shadow-xs">
                    <i class="fas fa-filter text-xs"></i>
                    <span>Terapkan</span>
                </button>
                @if(request()->hasAny(['search', 'tipe', 'dari_tanggal', 'sampai_tanggal']))
                    <a href="{{ route('transactions.riwayat') }}" class="p-2.5 rounded-2xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors text-xs" title="Reset Filter">
                        <i class="fas fa-rotate-left"></i>
                    </a>
                @endif
                <button type="button" onclick="window.print()" class="p-2.5 rounded-2xl bg-brand-50 text-brand-600 hover:bg-brand-100 transition-colors text-xs" title="Cetak Riwayat">
                    <i class="fas fa-print"></i>
                </button>
            </div>

        </form>
    </div>

    <!-- MAIN TABLE -->
    <div class="p-6 rounded-3xl bg-white border border-brand-100/70 shadow-soft">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h4 class="text-base font-bold text-slate-800 flex items-center space-x-2">
                    <i class="fas fa-clock-rotate-left text-brand-500"></i>
                    <span>Jurnal Log Mutasi Stok Persediaan</span>
                </h4>
                <p class="text-xs text-slate-400 mt-0.5">Menampilkan total {{ $transactions->total() }} catatan transaksi</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                    <i class="fas fa-arrow-down mr-1"></i> Masuk (Beli)
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                    <i class="fas fa-arrow-up mr-1"></i> Keluar (Pakai)
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-3">Tanggal</th>
                        <th class="py-3 px-3">Kode</th>
                        <th class="py-3 px-3">Nama Barang</th>
                        <th class="py-3 px-3">Kategori</th>
                        <th class="py-3 px-3 text-center">Tipe Transaksi</th>
                        <th class="py-3 px-3 text-center">Jumlah</th>
                        <th class="py-3 px-3">Keterangan / Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-xs">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-3 text-slate-500 font-medium whitespace-nowrap">
                                {{ $tx->tanggal_transaksi->format('d/m/Y') }}
                            </td>
                            <td class="py-3.5 px-3 font-mono font-medium text-slate-500 text-[11px]">
                                {{ $tx->item->kode_barang ?? '-' }}
                            </td>
                            <td class="py-3.5 px-3 font-bold text-slate-800">
                                {{ $tx->item->nama_barang ?? 'Barang Terhapus' }}
                            </td>
                            <td class="py-3.5 px-3 text-slate-500">
                                <span class="px-2 py-0.5 rounded-lg bg-lilac-50 border border-lilac-100 text-lilac-700 text-[11px]">
                                    {{ $tx->item->category->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                @if($tx->tipe === 'masuk')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700">
                                        <i class="fas fa-arrow-down mr-1 text-[10px]"></i> Barang Masuk
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700">
                                        <i class="fas fa-arrow-up mr-1 text-[10px]"></i> Barang Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 text-center font-extrabold whitespace-nowrap">
                                <span class="{{ $tx->tipe === 'masuk' ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $tx->tipe === 'masuk' ? '+' : '-' }}{{ $tx->jumlah }} {{ $tx->item->satuan ?? 'unit' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-slate-600 max-w-xs text-[11px]">
                                {{ $tx->keterangan ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i class="fas fa-clock-rotate-left text-3xl mb-2 text-slate-300"></i>
                                <p>Tidak ada transaksi yang cocok dengan kriteria pencarian / filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="pt-5 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
