<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NailArtStockTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $category;
    protected Item $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::where('email', 'admin@nailart.com')->first();
        $this->category = Category::first();
        $this->item = Item::first();
    }

    /**
     * 1. Test Halaman Login & Autentikasi
     */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('NAIL ART STOCK');
        $response->assertSee('admin@nailart.com');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@nailart.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_admin_cannot_login_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@nailart.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * 2. Test Dashboard Utama
     */
    public function test_authenticated_admin_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Total Barang');
        $response->assertSee('Total Kategori');
        $response->assertSee('Stok Tersedia');
        $response->assertSee('Hampir Habis');
        $response->assertSee('Aktivitas Stok');
        $response->assertSee('Reorder Warning');
    }

    /**
     * 3. Test Manajemen Kategori (CRUD)
     */
    public function test_can_create_new_category(): void
    {
        $response = $this->actingAs($this->admin)->post('/categories', [
            'nama_kategori' => 'Kategori Baru Test',
            'deskripsi' => 'Deskripsi untuk kategori testing',
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', [
            'nama_kategori' => 'Kategori Baru Test',
        ]);
    }

    public function test_can_update_category(): void
    {
        $category = Category::create([
            'nama_kategori' => 'Kategori Lama',
            'deskripsi' => 'Deskripsi lama',
        ]);

        $response = $this->actingAs($this->admin)->put("/categories/{$category->id}", [
            'nama_kategori' => 'Kategori Updated',
            'deskripsi' => 'Deskripsi updated',
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'nama_kategori' => 'Kategori Updated',
        ]);
    }

    public function test_can_delete_empty_category(): void
    {
        $emptyCategory = Category::create([
            'nama_kategori' => 'Kategori Kosong',
            'deskripsi' => 'Tidak punya item',
        ]);

        $response = $this->actingAs($this->admin)->delete("/categories/{$emptyCategory->id}");

        $response->assertRedirect('/categories');
        $this->assertDatabaseMissing('categories', [
            'id' => $emptyCategory->id,
        ]);
    }

    /**
     * 4. Test Manajemen Data Barang (CRUD)
     */
    public function test_can_view_items_index(): void
    {
        $response = $this->actingAs($this->admin)->get('/items');
        $response->assertStatus(200);
        $response->assertSee('Katalog Persediaan Barang Nail Art');
    }

    public function test_can_create_new_item(): void
    {
        $response = $this->actingAs($this->admin)->post('/items', [
            'kode_barang' => 'NA-TEST-999',
            'nama_barang' => 'Kutek Gel Glitter Ungu Pastel',
            'category_id' => $this->category->id,
            'merek' => 'Gelish Pro',
            'satuan' => 'Botol (15ml)',
            'harga_beli' => 65000,
            'jumlah_stok' => 20,
            'stok_minimum' => 5,
        ]);

        $response->assertRedirect('/items');
        $this->assertDatabaseHas('items', [
            'kode_barang' => 'NA-TEST-999',
            'nama_barang' => 'Kutek Gel Glitter Ungu Pastel',
            'jumlah_stok' => 20,
        ]);
    }

    public function test_can_update_item(): void
    {
        $response = $this->actingAs($this->admin)->put("/items/{$this->item->id}", [
            'kode_barang' => $this->item->kode_barang,
            'nama_barang' => 'Nama Barang Telah Diedit',
            'category_id' => $this->item->category_id,
            'merek' => $this->item->merek,
            'satuan' => $this->item->satuan,
            'harga_beli' => 90000,
            'jumlah_stok' => $this->item->jumlah_stok,
            'stok_minimum' => $this->item->stok_minimum,
        ]);

        $response->assertRedirect('/items');
        $this->assertDatabaseHas('items', [
            'id' => $this->item->id,
            'nama_barang' => 'Nama Barang Telah Diedit',
            'harga_beli' => 90000,
        ]);
    }

    /**
     * 5. Test Transaksi Barang Masuk (Stok Otomatis Bertambah)
     */
    public function test_barang_masuk_increases_stock_automatically(): void
    {
        $initialStock = $this->item->jumlah_stok;
        $inAmount = 15;

        $response = $this->actingAs($this->admin)->post('/transactions/masuk', [
            'item_id' => $this->item->id,
            'jumlah' => $inAmount,
            'tanggal_transaksi' => now()->format('Y-m-d'),
            'keterangan' => 'Restok tambahan untuk testing',
        ]);

        $response->assertRedirect('/transactions/masuk');

        $this->item->refresh();
        $this->assertEquals($initialStock + $inAmount, $this->item->jumlah_stok);

        $this->assertDatabaseHas('transactions', [
            'item_id' => $this->item->id,
            'tipe' => 'masuk',
            'jumlah' => $inAmount,
        ]);
    }

    /**
     * 6. Test Transaksi Barang Keluar (Stok Otomatis Berkurang)
     */
    public function test_barang_keluar_decreases_stock_automatically(): void
    {
        // Pastikan item memiliki stok awal
        $this->item->update(['jumlah_stok' => 20]);
        $outAmount = 5;

        $response = $this->actingAs($this->admin)->post('/transactions/keluar', [
            'item_id' => $this->item->id,
            'jumlah' => $outAmount,
            'tanggal_transaksi' => now()->format('Y-m-d'),
            'keterangan' => 'Pemakaian treatment salon kuku',
        ]);

        $response->assertRedirect('/transactions/keluar');

        $this->item->refresh();
        $this->assertEquals(15, $this->item->jumlah_stok);

        $this->assertDatabaseHas('transactions', [
            'item_id' => $this->item->id,
            'tipe' => 'keluar',
            'jumlah' => $outAmount,
        ]);
    }

    /**
     * 7. Test Mencegah Stok Menjadi Negatif
     */
    public function test_system_prevents_negative_stock(): void
    {
        $this->item->update(['jumlah_stok' => 3]);
        $outAmountExcess = 10; // Lebih besar dari stok yang tersedia (3)

        $response = $this->actingAs($this->admin)->post('/transactions/keluar', [
            'item_id' => $this->item->id,
            'jumlah' => $outAmountExcess,
            'tanggal_transaksi' => now()->format('Y-m-d'),
            'keterangan' => 'Mencoba mengambil stok berlebih',
        ]);

        // Harus gagal dan menampilkan pesan error
        $response->assertSessionHas('error');

        $this->item->refresh();
        // Stok tidak boleh berubah
        $this->assertEquals(3, $this->item->jumlah_stok);
    }

    /**
     * 8. Test Notifikasi Stok Menipis
     */
    public function test_notification_page_lists_low_stock_items(): void
    {
        // Set item agar stok di bawah minimum
        $this->item->update([
            'jumlah_stok' => 2,
            'stok_minimum' => 5,
        ]);

        $response = $this->actingAs($this->admin)->get('/notifications');

        $response->assertStatus(200);
        $response->assertSee('Peringatan Stok Menipis & Habis');
        $response->assertSee($this->item->nama_barang);
    }

    /**
     * 9. Test Riwayat Transaksi & Filter
     */
    public function test_riwayat_transaksi_shows_records(): void
    {
        $response = $this->actingAs($this->admin)->get('/transactions/riwayat');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Seluruh Transaksi Stok');
        $response->assertSee('Barang Masuk');
        $response->assertSee('Barang Keluar');
    }
}
