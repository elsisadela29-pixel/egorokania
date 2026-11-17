<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Item::count();
        $totalKategori = Category::count();
        $totalStok = (int) Item::sum('jumlah_stok');
        $barangHampirHabis = Item::whereColumn('jumlah_stok', '<=', 'stok_minimum')->count();

        // Ringkasan bulan ini
        $barangMasukBulanIni = (int) Transaction::where('tipe', 'masuk')
            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->sum('jumlah');

        $barangKeluarBulanIni = (int) Transaction::where('tipe', 'keluar')
            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->sum('jumlah');

        // Total akumulasi masuk & keluar
        $totalMasukAll = (int) Transaction::where('tipe', 'masuk')->sum('jumlah');
        $totalKeluarAll = (int) Transaction::where('tipe', 'keluar')->sum('jumlah');

        // Data 7 hari terakhir untuk grafik aktivitas stok
        $chartLabels = [];
        $chartMasuk = [];
        $chartKeluar = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->format('Y-m-d');
            $chartLabels[] = $date->translatedFormat('d M');

            $chartMasuk[] = (int) Transaction::where('tipe', 'masuk')
                ->whereDate('tanggal_transaksi', $formattedDate)
                ->sum('jumlah');

            $chartKeluar[] = (int) Transaction::where('tipe', 'keluar')
                ->whereDate('tanggal_transaksi', $formattedDate)
                ->sum('jumlah');
        }

        // Daftar barang yang perlu dibeli kembali (stok <= stok_minimum)
        $reorderItems = Item::with('category')
            ->whereColumn('jumlah_stok', '<=', 'stok_minimum')
            ->orderBy('jumlah_stok', 'asc')
            ->get();

        // 5 Transaksi terbaru
        $recentTransactions = Transaction::with('item.category')
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'totalBarang',
            'totalKategori',
            'totalStok',
            'barangHampirHabis',
            'barangMasukBulanIni',
            'barangKeluarBulanIni',
            'totalMasukAll',
            'totalKeluarAll',
            'chartLabels',
            'chartMasuk',
            'chartKeluar',
            'reorderItems',
            'recentTransactions'
        ));
    }
}
