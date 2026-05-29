# Product Requirements Document (PRD)
**Project Name:** Sistem Manajemen Inventori Toko Kelontong
**Tech Stack:** Laravel (PHP), MySQL, Tailwind CSS (rekomendasi untuk styling)
**Document Status:** Draft (Tugas Akhir)

---

## 1. Latar Belakang & Objektif
Toko kelontong seringkali menghadapi masalah dalam pencatatan stok barang yang masih manual, menyebabkan selisih barang, stok mati (*dead stock*), atau kehabisan barang terlaris tanpa disadari. 

**Objektif:**
Membangun sistem informasi berbasis web untuk mengelola masuk-keluarnya barang, memberikan peringatan saat stok menipis, dan menghasilkan laporan periodeik yang akurat untuk pemilik toko.

## 2. Target Pengguna (User Roles)
* **Owner / Pemilik Toko:** Memiliki akses penuh ke semua fitur, termasuk laporan keuangan, manajemen pengguna (pegawai), dan riwayat log.
* **Admin / Pegawai:** Hanya dapat mengakses fitur transaksi (barang masuk & barang keluar) dan melihat daftar stok.

---

## 3. Desain & Color Palette (Pastel Aquamarine & Pink)
Sistem ini menggunakan palet warna yang modern, *calm*, dan tidak generik untuk menghindari kesan kaku pada aplikasi admin panel biasa.

| Kategori | Hex Code | Preview / Penggunaan |
| :--- | :--- | :--- |
| **Primary (Aquamarine)** | `#84DCC6` | Warna utama (Navbar, Button Primary, Active states). Memberikan kesan segar dan dapat diandalkan. |
| **Accent (Pastel Pink)** | `#FFA69E` | Warna aksen (Notifikasi, Badge stok menipis, Button Secondary/Delete). Cukup *eye-catching* tanpa terlalu mencolok. |
| **Background (Off-White)**| `#F7FAF8` | Warna latar belakang aplikasi. Lebih lembut dari putih murni untuk mengurangi *eye strain*. |
| **Surface (Card/Box)** | `#FFFFFF` | Warna latar belakang *container*, *card*, atau *table*. |
| **Text Primary** | `#4A5568` | Warna teks utama (Charcoal/Dark Slate). Lebih nyaman dibaca daripada hitam pekat (`#000000`). |

---

## 4. Fitur Utama (Core Features)

### 4.1. Autentikasi & Otorisasi
* Login dengan email dan password.
* Middleware untuk membedakan akses *Owner* dan *Admin*.

### 4.2. Dashboard Interaktif
* **Statistik Singkat:** Total item barang, total kategori, transaksi hari ini.
* **Alert Box:** Menampilkan daftar barang dengan kuantitas di bawah batas minimum (menggunakan aksen `#FFA69E`).
* **Grafik Sederhana:** Tren barang keluar 7 hari terakhir.

### 4.3. Master Data (Manajemen Inventori)
* **Manajemen Kategori:** CRUD (Create, Read, Update, Delete) kategori barang (Misal: Makanan, Minuman, Sembako).
* **Manajemen Satuan:** CRUD satuan barang (Misal: Pcs, Dus, Kg).
* **Manajemen Barang:**
    * Input SKU / Kode Barang (Bisa auto-generate).
    * Nama Barang, Kategori, Harga Beli, Harga Jual.
    * Limit Stok Minimum.

### 4.4. Transaksi
* **Barang Masuk (Inbound):** Mencatat penambahan stok dari *supplier* lengkap dengan tanggal dan keterangan.
* **Barang Keluar (Outbound):** Mencatat pengurangan stok (baik karena penjualan kasir, retur, atau barang rusak).

### 4.5. Laporan & Riwayat
* Riwayat lengkap aktivitas (Log).
* Export data ke PDF / Excel untuk laporan bulanan (menggunakan *package* seperti `maatwebsite/excel` atau `barryvdh/laravel-dompdf`).

---

## 5. Arsitektur Database (High-Level Schema MySQL)

* `users` (id, name, email, password, role)
* `categories` (id, name, description)
* `units` (id, name)
* `products` (id, sku, name, category_id, unit_id, purchase_price, selling_price, stock, min_stock)
* `transactions` (id, type [in/out], date, notes, user_id)
* `transaction_details` (id, transaction_id, product_id, quantity)

---

## 6. Fase Pengembangan (Timeline)
1.  **Requirement & Database Design:** Setup struktur database MySQL dan relasi Eloquent di Laravel.
2.  **Authentication & UI Setup:** Setup Laravel Breeze/Jetstream dan integrasi palet warna Aquamarine & Pink ke Tailwind config.
3.  **Master Data Module:** Implementasi CRUD Kategori, Satuan, dan Barang.
4.  **Transaction Module:** Implementasi logika *increment/decrement* stok saat transaksi masuk/keluar.
5.  **Dashboard & Reporting:** Menarik data kalkulasi untuk *chart* dan fitur export PDF/Excel.
6.  **Testing & Bug Fixing:** Uji coba skenario *edge case* (misal: menginput barang keluar melebihi stok yang ada).