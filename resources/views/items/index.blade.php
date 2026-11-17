@extends('layouts.app')

@section('title', 'Data Barang & Bahan')
@section('page-title', 'Manajemen Data Barang Nail Art')
@section('page-subtitle', 'Kelola persediaan kutek gel, aksesoris, base/top coat, dan peralatan')

@section('content')
<div class="space-y-6">

    <!-- SEARCH & FILTER TOOLBAR -->
    <div class="p-5 rounded-3xl bg-white border border-brand-100/70 shadow-soft">
        <form action="{{ route('items.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">

            <!-- Search input (5 cols) -->
            <div class="lg:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs text-brand-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama barang, kode, atau merek..."
                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-800 outline-none transition-all">
            </div>

            <!-- Filter Kategori (3 cols) -->
            <div class="lg:col-span-3">
                <select name="category_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-700 outline-none transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status Stok (2 cols) -->
            <div class="lg:col-span-2">
                <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-700 outline-none transition-all">
                    <option value="">Semua Status</option>
                    <option value="safe" {{ request('status') === 'safe' ? 'selected' : '' }}>🟢 Stok Aman</option>
                    <option value="low" {{ request('status') === 'low' ? 'selected' : '' }}>🟠 Menipis</option>
                    <option value="empty" {{ request('status') === 'empty' ? 'selected' : '' }}>🔴 Habis</option>
                </select>
            </div>

            <!-- Action buttons (2 cols) -->
            <div class="lg:col-span-2 flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2.5 px-3 rounded-2xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-900 transition-colors flex items-center justify-center space-x-1.5 shadow-xs">
                    <i class="fas fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['search', 'category_id', 'status']))
                    <a href="{{ route('items.index') }}" class="p-2.5 rounded-2xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors text-xs" title="Reset Filter">
                        <i class="fas fa-rotate-left"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- MAIN DATA TABLE -->
    <div class="p-6 rounded-3xl bg-white border border-brand-100/70 shadow-soft">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
            <div>
                <h4 class="text-base font-bold text-slate-800 flex items-center space-x-2">
                    <i class="fas fa-boxes-stacked text-brand-500"></i>
                    <span>Katalog Persediaan Barang Nail Art</span>
                </h4>
                <p class="text-xs text-slate-400 mt-0.5">Menampilkan {{ $items->total() }} barang terdaftar</p>
            </div>
            <a href="{{ route('items.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-brand-500 via-pink-500 to-lilac-500 text-white font-bold text-xs shadow-md shadow-brand-200 hover:shadow-lg hover:scale-[1.01] transition-all">
                <i class="fas fa-plus-circle"></i>
                <span>Tambah Barang Baru</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-3">Kode</th>
                        <th class="py-3 px-3">Nama Barang</th>
                        <th class="py-3 px-3">Kategori</th>
                        <th class="py-3 px-3">Merek</th>
                        <th class="py-3 px-3">Satuan</th>
                        <th class="py-3 px-3 text-right">Harga Beli</th>
                        <th class="py-3 px-3 text-center">Stok</th>
                        <th class="py-3 px-3 text-center">Min.</th>
                        <th class="py-3 px-3 text-center">Status</th>
                        <th class="py-3 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-xs">
                    @forelse($items as $item)
                        <tr class="hover:bg-brand-50/20 transition-colors">
                            <td class="py-3.5 px-3 font-mono font-medium text-slate-500 text-[11px]">
                                {{ $item->kode_barang }}
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="font-bold text-slate-800">{{ $item->nama_barang }}</span>
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="px-2.5 py-0.5 rounded-lg bg-lilac-50 border border-lilac-100 text-lilac-700 text-[11px] font-medium">
                                    {{ $item->category->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-slate-500">
                                {{ $item->merek ?: '-' }}
                            </td>
                            <td class="py-3.5 px-3 text-slate-600 font-medium">
                                {{ $item->satuan }}
                            </td>
                            <td class="py-3.5 px-3 text-right font-semibold text-slate-800">
                                Rp {{ number_format($item->harga_beli, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <span class="font-extrabold text-sm {{ $item->jumlah_stok <= 0 ? 'text-rose-600' : ($item->jumlah_stok <= $item->stok_minimum ? 'text-amber-600' : 'text-emerald-600') }}">
                                    {{ $item->jumlah_stok }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-center text-slate-400 font-medium">
                                {{ $item->stok_minimum }}
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                @if($item->jumlah_stok <= 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700">
                                        🔴 Habis
                                    </span>
                                @elseif($item->jumlah_stok <= $item->stok_minimum)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">
                                        🟠 Menipis
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                        🟢 Aman
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <!-- Edit -->
                                    <a href="{{ route('items.edit', $item) }}" class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-colors" title="Edit Data Barang">
                                        <i class="fas fa-pen-to-square text-xs"></i>
                                    </a>
                                    <!-- Hapus -->
                                    <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang \'{{ addslashes($item->nama_barang) }}\'? Semua riwayat transaksi barang ini juga akan ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-7 h-7 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition-colors" title="Hapus Barang">
                                            <i class="fas fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 text-center text-slate-400">
                                <div class="w-14 h-14 rounded-full bg-brand-50 text-brand-400 flex items-center justify-center text-2xl mx-auto mb-3">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h5 class="text-sm font-bold text-slate-700">Tidak ada barang yang ditemukan</h5>
                                <p class="text-xs text-slate-400 mt-1">Coba kata kunci pencarian lain atau tambahkan barang baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="pt-5 border-t border-slate-100">
                {{ $items->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
