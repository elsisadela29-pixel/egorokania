@extends('layouts.app')

@section('title', 'Edit Barang')
@section('page-title', 'Edit Data Barang Nail Art')
@section('page-subtitle', 'Perbarui rincian informasi atau harga barang persediaan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Tombol Kembali -->
    <div>
        <a href="{{ route('items.index') }}" class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-500 hover:text-brand-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Daftar Barang</span>
        </a>
    </div>

    <div class="p-6 sm:p-8 rounded-3xl bg-white border border-brand-100/70 shadow-soft">
        <div class="flex items-center space-x-3 pb-6 border-b border-slate-100 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-400 to-lilac-400 text-white flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-pen-to-square"></i>
            </div>
            <div>
                <h4 class="text-base font-bold text-slate-800">Ubah Data: {{ $item->nama_barang }}</h4>
                <p class="text-xs text-slate-400">Kode Barang: <span class="font-mono font-semibold text-slate-600">{{ $item->kode_barang }}</span></p>
            </div>
        </div>

        <form action="{{ route('items.update', $item) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Informasi Utama -->
            <div>
                <h5 class="text-xs font-bold text-brand-600 uppercase tracking-wider mb-4 flex items-center space-x-1.5">
                    <i class="fas fa-circle-info"></i>
                    <span>Informasi Dasar Produk</span>
                </h5>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kode Barang -->
                    <div>
                        <label for="kode_barang" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            ID / Kode Barang <span class="text-brand-500">*</span>
                        </label>
                        <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang', $item->kode_barang) }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-800 font-mono outline-none transition-all">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="category_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Kategori Barang <span class="text-brand-500">*</span>
                        </label>
                        <select name="category_id" id="category_id" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-700 outline-none transition-all">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nama Barang -->
                    <div class="sm:col-span-2">
                        <label for="nama_barang" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Nama Barang & Varian Warna <span class="text-brand-500">*</span>
                        </label>
                        <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang', $item->nama_barang) }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-800 outline-none transition-all">
                    </div>

                    <!-- Merek -->
                    <div>
                        <label for="merek" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Merek / Brand
                        </label>
                        <input type="text" name="merek" id="merek" value="{{ old('merek', $item->merek) }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-800 outline-none transition-all">
                    </div>

                    <!-- Satuan -->
                    <div>
                        <label for="satuan" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Satuan Barang <span class="text-brand-500">*</span>
                        </label>
                        <input type="text" name="satuan" id="satuan" value="{{ old('satuan', $item->satuan) }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-800 outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Section 2: Harga & Stok -->
            <div class="pt-4 border-t border-slate-100">
                <h5 class="text-xs font-bold text-lilac-600 uppercase tracking-wider mb-4 flex items-center space-x-1.5">
                    <i class="fas fa-coins"></i>
                    <span>Harga Beli & Ketentuan Stok</span>
                </h5>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Harga Beli -->
                    <div>
                        <label for="harga_beli" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Harga Beli (Rp) <span class="text-brand-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-bold text-slate-400">Rp</span>
                            <input type="number" name="harga_beli" id="harga_beli" value="{{ old('harga_beli', (int)$item->harga_beli) }}" min="0" required
                                class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-800 font-semibold outline-none transition-all">
                        </div>
                    </div>

                    <!-- Jumlah Stok -->
                    <div>
                        <label for="jumlah_stok" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Jumlah Stok Saat Ini <span class="text-brand-500">*</span>
                        </label>
                        <input type="number" name="jumlah_stok" id="jumlah_stok" value="{{ old('jumlah_stok', $item->jumlah_stok) }}" min="0" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-800 font-semibold outline-none transition-all">
                    </div>

                    <!-- Stok Minimum -->
                    <div>
                        <label for="stok_minimum" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Batas Stok Minimum <span class="text-brand-500">*</span>
                        </label>
                        <input type="number" name="stok_minimum" id="stok_minimum" value="{{ old('stok_minimum', $item->stok_minimum) }}" min="0" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-400 focus:ring-4 focus:ring-brand-100 rounded-2xl text-xs text-slate-800 font-semibold outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('items.index') }}" class="px-5 py-2.5 rounded-2xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-blue-500 via-brand-500 to-lilac-500 text-white font-bold text-xs shadow-md shadow-blue-200 hover:shadow-lg hover:scale-[1.01] transition-all flex items-center space-x-2">
                    <i class="fas fa-check"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
