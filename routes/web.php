<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes - NAIL ART STOCK MANAGEMENT
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Hanya untuk Admin yang sudah login)
Route::middleware('auth')->group(function () {
    // 1. Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Manajemen Kategori Barang
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // 3. Manajemen Data Barang (CRUD)
    Route::resource('items', ItemController::class)->except(['show']);

    // 4. Transaksi Barang Masuk
    Route::get('/transactions/masuk', [TransactionController::class, 'masukIndex'])->name('transactions.masuk');
    Route::post('/transactions/masuk', [TransactionController::class, 'masukStore'])->name('transactions.masuk.store');

    // 5. Transaksi Barang Keluar
    Route::get('/transactions/keluar', [TransactionController::class, 'keluarIndex'])->name('transactions.keluar');
    Route::post('/transactions/keluar', [TransactionController::class, 'keluarStore'])->name('transactions.keluar.store');

    // 6. Riwayat Seluruh Transaksi
    Route::get('/transactions/riwayat', [TransactionController::class, 'riwayat'])->name('transactions.riwayat');

    // 7. Notifikasi Stok Menipis
    Route::get('/notifications', [TransactionController::class, 'notifikasi'])->name('notifications.index');
});
