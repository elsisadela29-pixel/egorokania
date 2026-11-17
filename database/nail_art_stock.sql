-- ============================================================
-- NAIL ART STOCK MANAGEMENT - Database Schema & Initial Data
-- Database Engine: MySQL / MariaDB (XAMPP Compatible)
-- Database Name: nail_art_stock
-- ============================================================

CREATE DATABASE IF NOT EXISTS `nail_art_stock` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nail_art_stock`;

-- 1. Tabel users
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabel categories
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_nama_kategori_unique` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabel items
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `merek` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `satuan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_beli` decimal(12,2) NOT NULL DEFAULT '0.00',
  `jumlah_stok` int(11) NOT NULL DEFAULT '0',
  `stok_minimum` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_kode_barang_unique` (`kode_barang`),
  KEY `items_category_id_foreign` (`category_id`),
  CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabel transactions
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `tipe` enum('masuk','keluar') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_item_id_foreign` (`item_id`),
  CONSTRAINT `transactions_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DATA AWAL (SEED DATA)
-- ============================================================

-- Data Admin (Password: password123)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Administrator Nail Art', 'admin@nailart.com', '$2y$12$Zp40oYn4g2aC8/i4kO7YwO2K1eE8yP.2n1174wE95B/m3yV53U5rO', NOW(), NOW());

-- Data Kategori
INSERT INTO `categories` (`id`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Kutek & Cat Kuku', 'Berbagai pilihan warna kutek gel, regular polish, dan cat kuku khusus nail art', NOW(), NOW()),
(2, 'Base & Top Coat', 'Lapisan dasar pelindung kuku dan lapisan akhir glossy/matte UV LED', NOW(), NOW()),
(3, 'Aksesoris & Ornamen', 'Nail tips palsu, stiker kuku, rhinestone berlian, nail charm 3D, dan glitter', NOW(), NOW()),
(4, 'Alat & Kuas', 'Kuas nail art, dotting tool, pinset khusus, kikir kuku, dan buffer', NOW(), NOW()),
(5, 'Pembersih & Perawatan', 'Nail polish remover, pure acetone, cuticle oil vitamin, dan cleaner', NOW(), NOW()),
(6, 'Lem & Perekat', 'Lem kuku cair, lem gel aksesoris, dan double tape kuku palsu', NOW(), NOW());

-- Data Barang
INSERT INTO `items` (`id`, `kode_barang`, `nama_barang`, `category_id`, `merek`, `satuan`, `harga_beli`, `jumlah_stok`, `stok_minimum`, `created_at`, `updated_at`) VALUES
(1, 'NA-KUT-001', 'Kutek Gel OPI Bubble Bath (Soft Pink)', 1, 'OPI', 'Botol (15ml)', 85000.00, 24, 5, NOW(), NOW()),
(2, 'NA-KUT-002', 'Kutek Gel Essie Ballet Slippers', 1, 'Essie', 'Botol (15ml)', 75000.00, 18, 5, NOW(), NOW()),
(3, 'NA-KUT-003', 'Kutek Gel Pastel Lavender Dreams', 1, 'Rosalind', 'Botol (10ml)', 45000.00, 15, 5, NOW(), NOW()),
(4, 'NA-COT-001', 'Base Coat UV/LED Gelish Soak-Off', 2, 'Gelish', 'Botol (15ml)', 65000.00, 12, 4, NOW(), NOW()),
(5, 'NA-COT-002', 'Top Coat Super Glossy No Wipe', 2, 'Gelish', 'Botol (15ml)', 70000.00, 10, 4, NOW(), NOW()),
(6, 'NA-COT-003', 'Matte Velvet Top Coat UV', 2, 'Born Pretty', 'Botol (10ml)', 48000.00, 3, 5, NOW(), NOW()),
(7, 'NA-ACC-001', 'Nail Tips Coffin / Almond Natural Clear', 3, 'Generic Pro', 'Box (500 pcs)', 35000.00, 30, 8, NOW(), NOW()),
(8, 'NA-ACC-002', 'Nail Sticker 3D Embossed Floral Sakura', 3, 'ArtNail', 'Lembar', 12000.00, 45, 10, NOW(), NOW()),
(9, 'NA-ACC-003', 'Rhinestone Crystal AB Swarovski Mix Size', 3, 'Swarovski Style', 'Pot Jar', 42000.00, 16, 5, NOW(), NOW()),
(10, 'NA-ACC-004', 'Nail Charm Bow Pita Ribbon Gold/Silver', 3, 'Kawaii Nail', 'Pack (20 pcs)', 28000.00, 2, 5, NOW(), NOW()),
(11, 'NA-ALT-001', 'Kuas Nail Art Liner & Detailer Set 5 pcs', 4, 'Kolinsky Pro', 'Set', 85000.00, 8, 3, NOW(), NOW()),
(12, 'NA-ALT-002', 'Dotting Tool Marbling Set 5 pcs', 4, 'Generic Pro', 'Set', 25000.00, 6, 2, NOW(), NOW()),
(13, 'NA-RAW-001', 'Nail Polish Remover Pure Acetone 500ml', 5, 'Cutex Professional', 'Botol (500ml)', 38000.00, 1, 5, NOW(), NOW()),
(14, 'NA-RAW-002', 'Cuticle Oil Pen Vitamin Kuku Rose', 5, 'OPI Care', 'Pcs', 15000.00, 0, 6, NOW(), NOW()),
(15, 'NA-LEM-001', 'Lem Kuku Kuat Quick Dry Brush-On', 6, 'MXBON', 'Botol (10g)', 18000.00, 14, 5, NOW(), NOW());

-- Data Transaksi Contoh
INSERT INTO `transactions` (`id`, `item_id`, `tipe`, `jumlah`, `tanggal_transaksi`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 'masuk', 20, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Restok awal kutek gel OPI dari supplier distributor', NOW(), NOW()),
(2, 4, 'masuk', 10, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Pembelian base coat UV', NOW(), NOW()),
(3, 1, 'keluar', 2, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'Pemakaian treatment nail art pelanggan meja 1 & 2', NOW(), NOW()),
(4, 8, 'masuk', 30, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Pembelian stiker kuku floral sakura', NOW(), NOW()),
(5, 10, 'keluar', 5, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Pemakaian nail charm bow untuk wisuda customer', NOW(), NOW()),
(6, 13, 'keluar', 3, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Pemakaian remover acetone di salon cabang utama', NOW(), NOW()),
(7, 5, 'masuk', 10, CURDATE(), 'Restok top coat super glossy', NOW(), NOW()),
(8, 15, 'keluar', 2, CURDATE(), 'Pemasangan nail tips customer wedding set', NOW(), NOW());
