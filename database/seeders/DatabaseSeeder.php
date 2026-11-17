<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@nailart.com'],
            [
                'name' => 'Administrator Nail Art',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Kategori Barang
        $categoriesData = [
            ['nama_kategori' => 'Kutek & Cat Kuku', 'deskripsi' => 'Berbagai pilihan warna kutek gel, regular polish, dan cat kuku khusus nail art'],
            ['nama_kategori' => 'Base & Top Coat', 'deskripsi' => 'Lapisan dasar pelindung kuku dan lapisan akhir glossy/matte UV LED'],
            ['nama_kategori' => 'Aksesoris & Ornamen', 'deskripsi' => 'Nail tips palsu, stiker kuku, rhinestone berlian, nail charm 3D, dan glitter'],
            ['nama_kategori' => 'Alat & Kuas', 'deskripsi' => 'Kuas nail art, dotting tool, pinset khusus, kikir kuku, dan buffer'],
            ['nama_kategori' => 'Pembersih & Perawatan', 'deskripsi' => 'Nail polish remover, pure acetone, cuticle oil vitamin, dan cleaner'],
            ['nama_kategori' => 'Lem & Perekat', 'deskripsi' => 'Lem kuku cair, lem gel aksesoris, dan double tape kuku palsu'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['nama_kategori']] = Category::firstOrCreate(
                ['nama_kategori' => $cat['nama_kategori']],
                ['deskripsi' => $cat['deskripsi']]
            );
        }

        // 3. Data Barang Nail Art
        $itemsData = [
            [
                'kode_barang' => 'NA-KUT-001',
                'nama_barang' => 'Kutek Gel OPI Bubble Bath (Soft Pink)',
                'category_id' => $categories['Kutek & Cat Kuku']->id,
                'merek' => 'OPI',
                'satuan' => 'Botol (15ml)',
                'harga_beli' => 85000,
                'jumlah_stok' => 24,
                'stok_minimum' => 5,
            ],
            [
                'kode_barang' => 'NA-KUT-002',
                'nama_barang' => 'Kutek Gel Essie Ballet Slippers',
                'category_id' => $categories['Kutek & Cat Kuku']->id,
                'merek' => 'Essie',
                'satuan' => 'Botol (15ml)',
                'harga_beli' => 75000,
                'jumlah_stok' => 18,
                'stok_minimum' => 5,
            ],
            [
                'kode_barang' => 'NA-KUT-003',
                'nama_barang' => 'Kutek Gel Pastel Lavender Dreams',
                'category_id' => $categories['Kutek & Cat Kuku']->id,
                'merek' => 'Rosalind',
                'satuan' => 'Botol (10ml)',
                'harga_beli' => 45000,
                'jumlah_stok' => 15,
                'stok_minimum' => 5,
            ],
            [
                'kode_barang' => 'NA-COT-001',
                'nama_barang' => 'Base Coat UV/LED Gelish Soak-Off',
                'category_id' => $categories['Base & Top Coat']->id,
                'merek' => 'Gelish',
                'satuan' => 'Botol (15ml)',
                'harga_beli' => 65000,
                'jumlah_stok' => 12,
                'stok_minimum' => 4,
            ],
            [
                'kode_barang' => 'NA-COT-002',
                'nama_barang' => 'Top Coat Super Glossy No Wipe',
                'category_id' => $categories['Base & Top Coat']->id,
                'merek' => 'Gelish',
                'satuan' => 'Botol (15ml)',
                'harga_beli' => 70000,
                'jumlah_stok' => 10,
                'stok_minimum' => 4,
            ],
            [
                'kode_barang' => 'NA-COT-003',
                'nama_barang' => 'Matte Velvet Top Coat UV',
                'category_id' => $categories['Base & Top Coat']->id,
                'merek' => 'Born Pretty',
                'satuan' => 'Botol (10ml)',
                'harga_beli' => 48000,
                'jumlah_stok' => 3,
                'stok_minimum' => 5, // Status: Menipis
            ],
            [
                'kode_barang' => 'NA-ACC-001',
                'nama_barang' => 'Nail Tips Coffin / Almond Natural Clear',
                'category_id' => $categories['Aksesoris & Ornamen']->id,
                'merek' => 'Generic Pro',
                'satuan' => 'Box (500 pcs)',
                'harga_beli' => 35000,
                'jumlah_stok' => 30,
                'stok_minimum' => 8,
            ],
            [
                'kode_barang' => 'NA-ACC-002',
                'nama_barang' => 'Nail Sticker 3D Embossed Floral Sakura',
                'category_id' => $categories['Aksesoris & Ornamen']->id,
                'merek' => 'ArtNail',
                'satuan' => 'Lembar',
                'harga_beli' => 12000,
                'jumlah_stok' => 45,
                'stok_minimum' => 10,
            ],
            [
                'kode_barang' => 'NA-ACC-003',
                'nama_barang' => 'Rhinestone Crystal AB Swarovski Mix Size',
                'category_id' => $categories['Aksesoris & Ornamen']->id,
                'merek' => 'Swarovski Style',
                'satuan' => 'Pot Jar',
                'harga_beli' => 42000,
                'jumlah_stok' => 16,
                'stok_minimum' => 5,
            ],
            [
                'kode_barang' => 'NA-ACC-004',
                'nama_barang' => 'Nail Charm Bow Pita Ribbon Gold/Silver',
                'category_id' => $categories['Aksesoris & Ornamen']->id,
                'merek' => 'Kawaii Nail',
                'satuan' => 'Pack (20 pcs)',
                'harga_beli' => 28000,
                'jumlah_stok' => 2,
                'stok_minimum' => 5, // Status: Menipis
            ],
            [
                'kode_barang' => 'NA-ALT-001',
                'nama_barang' => 'Kuas Nail Art Liner & Detailer Set 5 pcs',
                'category_id' => $categories['Alat & Kuas']->id,
                'merek' => 'Kolinsky Pro',
                'satuan' => 'Set',
                'harga_beli' => 85000,
                'jumlah_stok' => 8,
                'stok_minimum' => 3,
            ],
            [
                'kode_barang' => 'NA-ALT-002',
                'nama_barang' => 'Dotting Tool Marbling Set 5 pcs',
                'category_id' => $categories['Alat & Kuas']->id,
                'merek' => 'Generic Pro',
                'satuan' => 'Set',
                'harga_beli' => 25000,
                'jumlah_stok' => 6,
                'stok_minimum' => 2,
            ],
            [
                'kode_barang' => 'NA-RAW-001',
                'nama_barang' => 'Nail Polish Remover Pure Acetone 500ml',
                'category_id' => $categories['Pembersih & Perawatan']->id,
                'merek' => 'Cutex Professional',
                'satuan' => 'Botol (500ml)',
                'harga_beli' => 38000,
                'jumlah_stok' => 1,
                'stok_minimum' => 5, // Status: Menipis
            ],
            [
                'kode_barang' => 'NA-RAW-002',
                'nama_barang' => 'Cuticle Oil Pen Vitamin Kuku Rose',
                'category_id' => $categories['Pembersih & Perawatan']->id,
                'merek' => 'OPI Care',
                'satuan' => 'Pcs',
                'harga_beli' => 15000,
                'jumlah_stok' => 0,
                'stok_minimum' => 6, // Status: Habis
            ],
            [
                'kode_barang' => 'NA-LEM-001',
                'nama_barang' => 'Lem Kuku Kuat Quick Dry Brush-On',
                'category_id' => $categories['Lem & Perekat']->id,
                'merek' => 'MXBON',
                'satuan' => 'Botol (10g)',
                'harga_beli' => 18000,
                'jumlah_stok' => 14,
                'stok_minimum' => 5,
            ],
        ];

        $items = [];
        foreach ($itemsData as $it) {
            $items[$it['kode_barang']] = Item::firstOrCreate(
                ['kode_barang' => $it['kode_barang']],
                $it
            );
        }

        // 4. Sample Transaksi Masuk dan Keluar (untuk riwayat dan chart)
        $transactionsData = [
            [
                'item_id' => $items['NA-KUT-001']->id,
                'tipe' => 'masuk',
                'jumlah' => 20,
                'tanggal_transaksi' => Carbon::now()->subDays(6)->format('Y-m-d'),
                'keterangan' => 'Restok awal kutek gel OPI dari supplier distributor',
            ],
            [
                'item_id' => $items['NA-COT-001']->id,
                'tipe' => 'masuk',
                'jumlah' => 10,
                'tanggal_transaksi' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'keterangan' => 'Pembelian base coat UV',
            ],
            [
                'item_id' => $items['NA-KUT-001']->id,
                'tipe' => 'keluar',
                'jumlah' => 2,
                'tanggal_transaksi' => Carbon::now()->subDays(4)->format('Y-m-d'),
                'keterangan' => 'Pemakaian treatment nail art pelanggan meja 1 & 2',
            ],
            [
                'item_id' => $items['NA-ACC-002']->id,
                'tipe' => 'masuk',
                'jumlah' => 30,
                'tanggal_transaksi' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'keterangan' => 'Pembelian stiker kuku floral sakura',
            ],
            [
                'item_id' => $items['NA-ACC-004']->id,
                'tipe' => 'keluar',
                'jumlah' => 5,
                'tanggal_transaksi' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'keterangan' => 'Pemakaian nail charm bow untuk wisuda customer',
            ],
            [
                'item_id' => $items['NA-RAW-001']->id,
                'tipe' => 'keluar',
                'jumlah' => 3,
                'tanggal_transaksi' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'keterangan' => 'Pemakaian remover acetone di salon cabang utama',
            ],
            [
                'item_id' => $items['NA-COT-002']->id,
                'tipe' => 'masuk',
                'jumlah' => 10,
                'tanggal_transaksi' => Carbon::now()->format('Y-m-d'),
                'keterangan' => 'Restok top coat super glossy',
            ],
            [
                'item_id' => $items['NA-LEM-001']->id,
                'tipe' => 'keluar',
                'jumlah' => 2,
                'tanggal_transaksi' => Carbon::now()->format('Y-m-d'),
                'keterangan' => 'Pemasangan nail tips customer wedding set',
            ],
        ];

        foreach ($transactionsData as $tx) {
            Transaction::firstOrCreate(
                [
                    'item_id' => $tx['item_id'],
                    'tanggal_transaksi' => $tx['tanggal_transaksi'],
                    'tipe' => $tx['tipe'],
                    'jumlah' => $tx['jumlah'],
                ],
                $tx
            );
        }
    }
}
