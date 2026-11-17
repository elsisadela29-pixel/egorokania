@extends('layouts.app')

@section('title', 'Transaksi Barang Keluar')
@section('page-title', 'Pencatatan Barang Keluar (Pemakaian Salon)')
@section('page-subtitle', 'Catat pemakaian bahan nail art untuk customer — sistem mencegah stok minus')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- FORM TRANSAKSI KELUAR (5 Cols) -->
        <div class="lg:col-span-5">
            <div class="p-6 rounded-3xl bg-white border border-amber-100/80 shadow-soft sticky top-24">
                <div class="flex items-center space-x-3 pb-4 border-b border-slate-100 mb-5">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-amber-400 to-orange-400 text-white flex items-center justify-center text-lg shadow-sm">
                        <i class="fas fa-hand-sparkles"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Form Pemakaian / Barang Keluar</h4>
                        <p class="text-[11px] text-slate-400">Stok berkurang otomatis & aman dari stok negatif</p>
                    </div>
                </div>

                <form action="{{ route('transactions.keluar.store') }}" method="POST" class="space-y-4" id="form-keluar">
                    @csrf

                    <!-- Tanggal Transaksi -->
                    <div>
                        <label for="tanggal_transaksi" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Tanggal Pemakaian / Keluar <span class="text-brand-500">*</span>
                        </label>
                        <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-amber-400 focus:ring-4 focus:ring-amber-100 rounded-2xl text-xs text-slate-800 outline-none transition-all">
                    </div>

                    <!-- Pilih Barang -->
                    <div>
                        <label for="item_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Pilih Bahan Nail Art <span class="text-brand-500">*</span>
                        </label>
                        <select name="item_id" id="item_id" required onchange="updateStockInfo(this)"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-amber-400 focus:ring-4 focus:ring-amber-100 rounded-2xl text-xs text-slate-700 outline-none transition-all">
                            <option value="" data-stock="0" data-unit="unit">-- Pilih Bahan / Barang --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" data-stock="{{ $item->jumlah_stok }}" data-unit="{{ $item->satuan }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    [{{ $item->kode_barang }}] {{ $item->nama_barang }} (Sisa: {{ $item->jumlah_stok }} {{ $item->satuan }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Info Stok Saat Ini Box -->
                    <div id="stock-info-box" class="p-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Stok Tersedia:</span>
                            <span id="available-stock-text" class="font-bold text-slate-800">0 Unit</span>
                        </div>
                    </div>

                    <!-- Jumlah Barang Keluar -->
                    <div>
                        <label for="jumlah" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Jumlah Pemakaian / Keluar <span class="text-brand-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', 1) }}" min="1" required
                                placeholder="Contoh: 1"
                                class="w-full pl-4 pr-12 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-amber-400 focus:ring-4 focus:ring-amber-100 rounded-2xl text-xs text-slate-800 font-semibold outline-none transition-all">
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-xs text-slate-400 pointer-events-none">Unit</span>
                        </div>
                        <p class="text-[11px] text-amber-600 mt-1 flex items-center">
                            <i class="fas fa-shield-halved mr-1"></i> Sistem akan menolak jika jumlah melebihi stok yang tersedia.
                        </p>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Keterangan / Keperluan Pemakaian (Opsional)
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            placeholder="Contoh: Treatment nail art pelanggan wedding meja 2, bahan rusak/expired"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-amber-400 focus:ring-4 focus:ring-amber-100 rounded-2xl text-xs text-slate-800 outline-none transition-all resize-none">{{ old('keterangan') }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold text-xs shadow-md shadow-amber-200 hover:shadow-lg hover:scale-[1.01] transition-all flex items-center justify-center space-x-2">
                        <i class="fas fa-minus"></i>
                        <span>Simpan & Kurangi Stok Otomatis</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- TABEL RIWAYAT TRANSAKSI KELUAR (7 Cols) -->
        <div class="lg:col-span-7">
            <div class="p-6 rounded-3xl bg-white border border-brand-100/70 shadow-soft">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h4 class="text-base font-bold text-slate-800 flex items-center space-x-2">
                            <i class="fas fa-list-check text-amber-500"></i>
                            <span>Riwayat Barang Keluar (Pemakaian)</span>
                        </h4>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar transaksi mutasi barang keluar di salon</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="py-3 px-3">Tanggal</th>
                                <th class="py-3 px-3">Nama Barang</th>
                                <th class="py-3 px-3 text-center">Jumlah Keluar</th>
                                <th class="py-3 px-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-xs">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-amber-50/30 transition-colors">
                                    <td class="py-3 px-3 text-slate-500 font-medium whitespace-nowrap">
                                        {{ $tx->tanggal_transaksi->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <p class="font-bold text-slate-800">{{ $tx->item->nama_barang ?? 'Barang Terhapus' }}</p>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $tx->item->kode_barang ?? '-' }}</span>
                                    </td>
                                    <td class="py-3 px-3 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-700">
                                            -{{ $tx->jumlah }} {{ $tx->item->satuan ?? 'unit' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-slate-500 text-[11px]">
                                        {{ $tx->keterangan ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-slate-400">
                                        <i class="fas fa-hand-sparkles text-3xl mb-2 text-slate-300"></i>
                                        <p>Belum ada catatan pemakaian barang keluar.</p>
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

    </div>

</div>
@endsection

@section('scripts')
<script>
    function updateStockInfo(select) {
        const option = select.options[select.selectedIndex];
        const stockBox = document.getElementById('stock-info-box');
        const text = document.getElementById('available-stock-text');

        if (option && option.value) {
            const stock = parseInt(option.dataset.stock) || 0;
            const unit = option.dataset.unit || 'Unit';
            text.textContent = `${stock} ${unit}`;
            if (stock <= 0) {
                text.className = 'font-bold text-rose-600';
            } else {
                text.className = 'font-bold text-emerald-600';
            }
            stockBox.classList.remove('hidden');
        } else {
            stockBox.classList.add('hidden');
        }
    }

    // Trigger on load if previously selected
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('item_id');
        if (select.value) {
            updateStockInfo(select);
        }
    });
</script>
@endsection
