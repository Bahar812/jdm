# JDM Web - Juragan Daging Morowali

JDM Web adalah aplikasi company profile, katalog produk frozen food, keranjang belanja, checkout Midtrans, dan admin panel untuk mengelola member, produk, order, inventory, serta laporan penjualan.

## Stack

- PHP 8.2+
- Laravel 12
- SQLite/MySQL compatible via Laravel database config
- Vite 7, Tailwind CSS 4
- Midtrans Snap
- PHPUnit 11

## Setup Lokal

1. Install dependency PHP dan JavaScript.

   ```bash
   composer install
   npm install
   ```

2. Buat file environment dan application key.

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Atur database di `.env`.

   Untuk SQLite lokal:

   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   ```

   Lalu buat file database:

   ```bash
   touch database/database.sqlite
   ```

4. Jalankan migration dan seeder.

   ```bash
   php artisan migrate --seed
   ```

5. Jalankan aplikasi.

   ```bash
   php artisan serve
   npm run dev
   ```

6. Build asset production.

   ```bash
   npm run build
   ```

## Akun Demo

Seeder membuat akun berikut:

- Admin: `admin@juragandaging.test` / `admin12345`
- Member: `member@juragandaging.test` / `member12345`

Segera ganti password untuk environment production.

## Konfigurasi Midtrans

Isi konfigurasi berikut di `.env`.

```env
MIDTRANS_MERCHANT_ID=
MIDTRANS_CLIENT_KEY=
MIDTRANS_SERVER_KEY=
MIDTRANS_IS_PRODUCTION=false
```

Endpoint webhook:

```text
POST /payments/midtrans/notification
```

Webhook memvalidasi signature Midtrans, menyinkronkan status pembayaran, mencatat histori status, dan membuat pergerakan inventory ketika order berubah menjadi lunas.

## Panduan Admin

1. Buka `/login`, masuk dengan akun admin.
2. Dashboard menampilkan revenue, jumlah order, penjualan harian/mingguan, produk terlaris, aktivitas order, status pembayaran, dan low stock alert.
3. Menu Member digunakan untuk menambah, mengubah, dan menghapus akun. Admin tidak dapat menghapus akunnya sendiri.
4. Menu Produk digunakan untuk CRUD katalog. Slug dibuat otomatis dari nama produk jika dikosongkan, dan harus unik.
5. Menu Order Masuk digunakan untuk memantau order, melihat detail item, mengubah status pembayaran/order, dan mengunduh invoice PDF untuk order lunas.
6. Alur status order berjalan bertahap: `pending -> processing -> shipped -> completed`. Status `processing`, `shipped`, dan `completed` hanya valid untuk order lunas.
7. Menu Inventory digunakan untuk stock in, stock out, dan adjustment manual. Stock out manual ditolak jika quantity lebih besar dari stok tersedia.

## Validasi Input

Validasi utama dipindahkan ke Form Request agar controller lebih ringkas dan maintainable.

- Login: email RFC dan password wajib.
- Cart: quantity dibatasi angka valid.
- Checkout: nama, email, nomor HP, alamat, dan catatan divalidasi lebih ketat.
- Produk: slug otomatis, format slug valid, harga/stok non-negatif, URL gambar valid.
- Member: email unik, nomor HP valid, role terbatas, password minimal 8 karakter dengan huruf dan angka.
- Inventory: tipe movement terbatas, quantity positif, stock out tidak boleh melebihi stok tersedia.
- Order: status dan payment status divalidasi sesuai alur bisnis.

## Refactor dan Optimasi

- Mutasi stok dipusatkan di `App\Services\InventoryService`.
- Duplikasi stock-out order di admin order dan Midtrans webhook dihapus.
- Controller admin dibuat lebih tipis dengan Form Request.
- Dashboard mengurangi query berulang untuk grafik penjualan dengan mengambil order lunas rentang grafik satu kali.
- Query listing admin menggunakan select kolom yang dibutuhkan dan eager loading terbatas.
- Ditambahkan index database untuk produk aktif/kategori, order berdasarkan status/pembayaran/waktu, item order, dan inventory movement.

## Bug yang Diperbaiki

- Stock out manual sebelumnya dapat mengurangi stok melewati batas lalu dipaksa menjadi 0. Sekarang input ditolak jika quantity melebihi stok tersedia.
- Checkout sekarang memvalidasi ulang quantity keranjang terhadap stok terkini dengan lock database sebelum order dibuat.
- Stock-out order dari admin dan webhook sekarang memakai service yang sama sehingga histori inventory lebih konsisten.

## Testing

Perintah yang dijalankan pada 2026-05-04:

```bash
php artisan test
vendor/bin/pint --dirty
npm run build
```

Hasil:

- PHPUnit: 13 test passed, 57 assertions.
- Pint: passed.
- Vite build: passed.

Cakupan test utama:

- Admin membuat produk dengan slug otomatis.
- Slug produk wajib unik.
- Password member admin wajib kuat.
- Admin melakukan stock in dan adjustment inventory.
- Stock out manual ditolak saat melebihi stok.
- Customer menambah produk ke cart dan checkout.
- Checkout ditolak ketika stok berubah dan tidak lagi mencukupi.
- Admin update status order bertahap dan histori tersimpan.
- Admin menandai order sebagai lunas dan inventory movement stock-out dibuat.
- Admin tidak dapat melewati status `processing` langsung ke `completed`.
- Webhook Midtrans lunas mengubah status order dan mencatat histori.

## Deployment Singkat

1. Set `.env` production, database, `APP_KEY`, dan Midtrans key.
2. Jalankan:

   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci
   npm run build
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. Pastikan storage writable oleh web server.
4. Arahkan document root ke folder `public`.
