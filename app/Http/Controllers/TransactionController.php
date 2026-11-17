<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Halaman Transaksi Barang Masuk
     */
    public function masukIndex()
    {
        $items = Item::orderBy('nama_barang', 'asc')->get();
        $transactions = Transaction::with('item.category')
            ->where('tipe', 'masuk')
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('transactions.masuk', compact('items', 'transactions'));
    }

    /**
     * Simpan Transaksi Barang Masuk
     */
    public function masukStore(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'item_id.required' => 'Pilih barang yang masuk.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'jumlah.required' => 'Jumlah barang masuk wajib diisi.',
            'jumlah.min' => 'Jumlah barang masuk minimal 1.',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi.',
            'tanggal_transaksi.date' => 'Format tanggal transaksi tidak valid.',
        ]);

        DB::transaction(function () use ($request) {
            $item = Item::lockForUpdate()->findOrFail($request->item_id);

            // Buat catatan transaksi
            Transaction::create([
                'item_id' => $item->id,
                'tipe' => 'masuk',
                'jumlah' => $request->jumlah,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'keterangan' => $request->keterangan ?: 'Pembelian / Restok barang',
            ]);

            // Tambah stok otomatis
            $item->increment('jumlah_stok', $request->jumlah);
        });

        return redirect()->route('transactions.masuk')
            ->with('success', 'Transaksi barang masuk berhasil dicatat dan stok bertambah secara otomatis!');
    }

    /**
     * Halaman Transaksi Barang Keluar
     */
    public function keluarIndex()
    {
        $items = Item::orderBy('nama_barang', 'asc')->get();
        $transactions = Transaction::with('item.category')
            ->where('tipe', 'keluar')
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('transactions.keluar', compact('items', 'transactions'));
    }

    /**
     * Simpan Transaksi Barang Keluar
     */
    public function keluarStore(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'item_id.required' => 'Pilih barang yang keluar/dipakai.',
            'item_id.exists' => 'Barang yang dipilih tidak valid.',
            'jumlah.required' => 'Jumlah barang keluar wajib diisi.',
            'jumlah.min' => 'Jumlah barang keluar minimal 1.',
            'tanggal_transaksi.required' => 'Tanggal transaksi wajib diisi.',
        ]);

        $item = Item::findOrFail($request->item_id);

        // Pencegahan stok negatif
        if ($item->jumlah_stok < $request->jumlah) {
            return back()
                ->withInput()
                ->with('error', "Stok tidak mencukupi! Stok saat ini untuk '{$item->nama_barang}' hanya tersedia {$item->jumlah_stok} {$item->satuan}. Transaksi dibatalkan untuk mencegah stok negatif.");
        }

        DB::transaction(function () use ($request, $item) {
            $item = Item::lockForUpdate()->findOrFail($request->item_id);

            // Double check inside transaction lock
            if ($item->jumlah_stok < $request->jumlah) {
                throw new \Exception("Stok tidak mencukupi saat proses penyimpanan.");
            }

            // Simpan transaksi keluar
            Transaction::create([
                'item_id' => $item->id,
                'tipe' => 'keluar',
                'jumlah' => $request->jumlah,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'keterangan' => $request->keterangan ?: 'Pemakaian bahan / treatment nail art',
            ]);

            // Kurangi stok otomatis
            $item->decrement('jumlah_stok', $request->jumlah);
        });

        return redirect()->route('transactions.keluar')
            ->with('success', 'Transaksi barang keluar berhasil dicatat dan stok berkurang secara otomatis!');
    }

    /**
     * Halaman Riwayat Seluruh Transaksi
     */
    public function riwayat(Request $request)
    {
        $query = Transaction::with('item.category');

        // Filter pencarian nama barang / kode barang
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        // Filter tipe transaksi (masuk / keluar)
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Filter rentang tanggal
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->sampai_tanggal);
        }

        $transactions = $query->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('transactions.riwayat', compact('transactions'));
    }

    /**
     * Halaman Notifikasi Stok Menipis & Habis
     */
    public function notifikasi()
    {
        $lowStockItems = Item::with('category')
            ->whereColumn('jumlah_stok', '<=', 'stok_minimum')
            ->orderBy('jumlah_stok', 'asc')
            ->get();

        $emptyStockCount = $lowStockItems->where('jumlah_stok', '<=', 0)->count();
        $warningStockCount = $lowStockItems->where('jumlah_stok', '>', 0)->count();

        return view('notifications.index', compact('lowStockItems', 'emptyStockCount', 'warningStockCount'));
    }
}
