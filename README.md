# Sistem Perpustakaan

Aplikasi manajemen perpustakaan berbasis web yang dibangun menggunakan Laravel 11. Sistem ini menyediakan fitur lengkap untuk pengelolaan buku, anggota, peminjaman, dan pengembalian buku secara digital.

## 🌐 Live Preview

**URL:** https://sistemperpustakaan.page.gd/

**Demo Account:**
- Username: `admin`
- Password: `admin123`

## ✨ Fitur Utama

- 📚 **Manajemen Buku** - CRUD lengkap untuk pengelolaan data buku
- 👥 **Manajemen Anggota** - Kelola data anggota perpustakaan
- 📖 **Peminjaman Buku** - Sistem peminjaman dengan tracking status
- 🔄 **Pengembalian Buku** - Proses pengembalian dengan validasi
- 💰 **Manajemen Denda** - Pengelolaan dan perhitungan denda otomatis
- 🧮 **Perhitungan Denda Otomatis** - Sistem menghitung denda keterlambatan secara otomatis
- 🔍 **Pencarian Buku** - Fitur pencarian buku berdasarkan judul, pengarang, kategori
- 📊 **Laporan Lengkap** - Generate laporan peminjaman, pengembalian, dan denda
- 🖨️ **Cetak Laporan** - Export dan cetak laporan dalam format PDF
- 📑 **Cetak Bukti Peminjaman** - Print bukti transaksi peminjaman
- 📈 **Dashboard & Statistik** - Visualisasi data perpustakaan
- 📱 **Responsive Design** - Tampilan optimal di semua perangkat

## 🛠️ Teknologi yang Digunakan

- **Framework:** Laravel 11.x
- **PHP:** >= 8.2
- **Database:** MySQL / MariaDB
- **Frontend:** Blade Template Engine, Bootstrap 5
- **Authentication:** Laravel Breeze 
- **PDF Generator:** TCPDF

## 📋 Spesifikasi Sistem

### Minimum Requirements

- PHP >= 8.2
- Composer
- MySQL >= 5.7 atau MariaDB >= 10.3
- Node.js >= 18.x & NPM
- Web Server (Apache/Nginx)

### PHP Extensions Required

- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone [https://github.com/rafdwn/Sistem-Manajemen-Perpustakaan.git]
cd sistem-perpustakaan
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Jalankan Migration & Seeder

```bash
php artisan migrate --seed
```

### 6. Build Assets

```bash
npm run build
```

Untuk development:
```bash
npm run dev
```

### 7. Storage Link (Opsional)

Jika menggunakan upload file:
```bash
php artisan storage:link
```

### 8. Jalankan Aplikasi

```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## 👤 Default Login

Setelah menjalankan seeder, gunakan kredensial berikut:

- **Username:** admin
- **Password:** admin123

## 🔧 Konfigurasi Tambahan

### Timezone

Ubah timezone di `config/app.php`:

```php
'timezone' => 'Asia/Jakarta',
```

### Locale

```php
'locale' => 'id',
```

### Konfigurasi PDF

Jika menggunakan DomPDF, pastikan sudah terinstall:

```bash
composer require barryvdh/laravel-dompdf
```

Publish konfigurasi:

```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

## 📝 Catatan Pengembangan

### Fitur Pencarian

Sistem pencarian buku mendukung filter berdasarkan:
- Judul buku
- Nama pengarang
- Kategori/Genre
- ISBN
- Status ketersediaan

### Sistem Perhitungan Denda

Denda dihitung otomatis berdasarkan:
- Tanggal jatuh tempo
- Tanggal pengembalian aktual
- Tarif denda per hari (konfigurasi)

Formula: `Denda = Jumlah Hari Terlambat × Tarif Denda Per Hari`

### Jenis Laporan yang Tersedia

1. **Laporan Peminjaman**
   - Berdasarkan periode
   - Status peminjaman
   - Per anggota

2. **Laporan Pengembalian**
   - Tepat waktu / terlambat
   - Berdasarkan periode

3. **Laporan Denda**
   - Total denda per periode
   - Denda per anggota
   - Status pembayaran

4. **Laporan Statistik Buku**
   - Buku paling sering dipinjam
   - Ketersediaan buku

### Format Export

- PDF (untuk cetak)

👨‍💻 Developer
<br>Dikembangkan oleh [@rafdwn](https://github.com/rafdwn)

📞 Kontak & Support <br>
Untuk pertanyaan atau dukungan, silakan hubungi:<br>
📧 Email: [rafdwn@gmail.com](mailto:rafdwn@gmail.com)

