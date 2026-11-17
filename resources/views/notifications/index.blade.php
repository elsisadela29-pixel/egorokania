@extends('layouts.app')

@section('title', 'Notifikasi Stok Menipis')
@section('page-title', 'Peringatan Stok Menipis & Habis')
@section('page-subtitle', 'Sistem pengawasan otomatis inventaris yang membutuhkan pengadaan ulang')

@section('content')
<div class="space-y-6">

    <!-- SUMMARY BANNER -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Total Perlu Restok -->
        <div class="p-5 rounded-3xl bg-white border border-rose-100 shadow-soft flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400">Total Perlu Pengadaan</span>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $lowStockItems->count() }} <span class="text-xs font-semibold text-slate-500">Barang</span></h3>
            </div>
        </div>

        <!-- Stok Kosong / Habis -->
        <div class="p-5 rounded-3xl bg-white border border-rose-200 shadow-soft flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-xl shrink-0 shadow-md shadow-rose-200">
                <i class="fas fa-ban"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-rose-500">Stok Kosong (0 Unit)</span>
                <h3 class="text-2xl font-extrabold text-rose-600">{{ $emptyStockCount }} <span class="text-xs font-semibold text-slate-500">Barang</span></h3>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="p-5 rounded-3xl bg-white border border-amber-200 shadow-soft flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-xl shrink-0 shadow-md shadow-amber-200">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-amber-500">Stok Kritis / Menipis</span>
                <h3 class="text-2xl font-extrabold text-amber-600">{{ $warningStockCount }} <span class="text-xs font-semibold text-slate-500">Barang</span></h3>
            </div>
        </div>
    </div>

    <!-- MAIN ALERT SECTION -->
    <div class="p-6 rounded-3xl bg-white border border-brand-100/70 shadow-soft">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h4 class="text-base font-bold text-slate-800 flex items-center space-x-2">
                    <i class="fas fa-triangle-exclamation text-rose-500"></i>
                    <span>Daftar Peringatan Persediaan Menipis</span>
                </h4>
                <p class="text-xs text-slate-400 mt-0.5">Daftar barang dengan stok di bawah atau sama dengan stok minimum (stok &le; stok minimum)</p>
            </div>
            <a href="{{ route('transactions.masuk') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold text-xs shadow-md shadow-emerald-200 hover:shadow-lg transition-all">
                <i class="fas fa-cart-plus"></i>
                <span>Beli / Restok Barang Masuk</span>
            </a>
        </div>

        @if($lowStockItems->count() > 0)
            <!-- CARD PREVIEW GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                @foreach($lowStockItems as $item)
                    @php
                        $isZero = $item->jumlah_stok <= 0;
                        $percent = $item->stok_minimum > 0 ? min(round(($item->jumlah_stok / $item->stok_minimum) * 100), 100) : 0;
                    @endphp
                    <div class="p-5 rounded-3xl border transition-all duration-200 {{ $isZero ? 'bg-rose-50/50 border-rose-200 hover:border-rose-300' : 'bg-amber-50/50 border-amber-200 hover:border-amber-300' }}">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold {{ $isZero ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $item->kode_barang }}
                                </span>
                                <h5 class="text-sm font-bold text-slate-800 mt-1.5 leading-snug">{{ $item->nama_barang }}</h5>
                                <p class="text-[11px] text-slate-400">{{ $item->category->nama_kategori ?? '-' }} &bull; {{ $item->merek ?: 'Generic' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase shrink-0 {{ $isZero ? 'bg-rose-500 text-white shadow-xs' : 'bg-amber-500 text-white shadow-xs' }}">
                                {{ $isZero ? 'Habis' : 'Menipis' }}
                            </span>
                        </div>

                        <!-- Level Indicator Bar -->
                        <div class="space-y-1.5 my-3">
                            <div class="flex justify-between text-[11px] font-semibold">
                                <span class="text-slate-500">Level Stok Fisik:</span>
                                <span class="{{ $isZero ? 'text-rose-600' : 'text-amber-600' }}">
                                    {{ $item->jumlah_stok }} / Min. {{ $item->stok_minimum }} {{ $item->satuan }}
                                </span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-200 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $isZero ? 'bg-rose-500' : 'bg-amber-500' }}" style="width: {{ max($percent, 4) }}%"></div>
                            </div>
                        </div>

                        <!-- Footer card: Restock action -->
                        <div class="pt-3 border-t {{ $isZero ? 'border-rose-200/60' : 'border-amber-200/60' }} flex items-center justify-between">
                            <span class="text-[11px] text-slate-500 font-medium">
                                Defisit: <strong class="text-rose-600">{{ max(0, $item->stok_minimum - $item->jumlah_stok) }} {{ $item->satuan }}</strong>
                            </span>
                            <a href="{{ route('transactions.masuk') }}?item_id={{ $item->id }}" class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-emerald-600 hover:border-emerald-300 font-bold text-[11px] transition-colors flex items-center space-x-1.5 shadow-2xs">
                                <i class="fas fa-plus text-emerald-500"></i>
                                <span>Restok</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- DETAILED TABLE -->
            <div class="border-t border-slate-100 pt-6">
                <h5 class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-4">
                    Rincian Kebutuhan Pengadaan Ulang Barang:
                </h5>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="py-3 px-3">Kode</th>
                                <th class="py-3 px-3">Nama Barang Nail Art</th>
                                <th class="py-3 px-3">Kategori</th>
                                <th class="py-3 px-3 text-center">Stok Saat Ini</th>
                                <th class="py-3 px-3 text-center">Batas Minimum</th>
                                <th class="py-3 px-3 text-center">Kekurangan Unit</th>
                                <th class="py-3 px-3 text-center">Status</th>
                                <th class="py-3 px-3 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-xs">
                            @foreach($lowStockItems as $item)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-3.5 px-3 font-mono font-medium text-slate-500 text-[11px]">
                                        {{ $item->kode_barang }}
                                    </td>
                                    <td class="py-3.5 px-3 font-bold text-slate-800">
                                        {{ $item->nama_barang }}
                                    </td>
                                    <td class="py-3.5 px-3 text-slate-500">
                                        <span class="px-2 py-0.5 rounded-lg bg-lilac-50 border border-lilac-100 text-lilac-700 text-[11px]">
                                            {{ $item->category->nama_kategori ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-3 text-center font-extrabold text-sm {{ $item->jumlah_stok <= 0 ? 'text-rose-600' : 'text-amber-600' }}">
                                        {{ $item->jumlah_stok }} {{ $item->satuan }}
                                    </td>
                                    <td class="py-3.5 px-3 text-center text-slate-600 font-medium">
                                        {{ $item->stok_minimum }} {{ $item->satuan }}
                                    </td>
                                    <td class="py-3.5 px-3 text-center font-extrabold text-rose-600">
                                        +{{ max(0, $item->stok_minimum - $item->jumlah_stok) }} {{ $item->satuan }}
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        @if($item->jumlah_stok <= 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-700">
                                                🔴 Habis
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-700">
                                                🟠 Menipis
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        <a href="{{ route('transactions.masuk') }}?item_id={{ $item->id }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs shadow-xs transition-colors">
                                            <i class="fas fa-cart-plus text-[10px]"></i>
                                            <span>Restok</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Empty state when all stock is safe -->
            <div class="py-16 text-center text-slate-400">
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fas fa-circle-check"></i>
                </div>
                <h5 class="text-base font-bold text-slate-800">Semua Stok Barang Mencukupi!</h5>
                <p class="text-xs text-slate-400 max-w-md mx-auto mt-1">
                    Tidak ada bahan nail art yang memerlukan pengadaan ulang. Seluruh inventaris salon berada di atas batas stok minimum.
                </p>
                <div class="mt-5">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
