# HMIF Internal Management System

HMIF Internal Management System adalah aplikasi internal berbasis Laravel dan Filament yang digunakan untuk membantu pengelolaan data organisasi HMIF, mulai dari data anggota, divisi, kegiatan, program kerja, absensi, hingga kebutuhan administrasi internal.

Aplikasi ini dibuat untuk mempermudah pengurus HMIF dalam mengelola data secara terpusat melalui admin panel.

---

## Tech Stack

Project ini menggunakan beberapa teknologi berikut:

- Laravel
- Filament Admin Panel
- PHP
- MySQL
- Tailwind CSS
- Vite

---

## Fitur Utama

### 1. Dashboard Admin Internal

Dashboard digunakan sebagai halaman utama admin untuk melihat dan mengelola data internal HMIF.

---

### 2. Manajemen User

Fitur ini digunakan untuk mengelola data user atau anggota yang memiliki akses ke sistem.

Data yang dikelola antara lain:

- Nama
- Email
- NIM
- Divisi
- Tanggal lahir
- Alamat
- Status keanggotaan
- Password
- Foto profil

---

### 3. Manajemen Divisi

Fitur ini digunakan untuk mengelola data divisi yang ada di HMIF.

Contoh divisi:

- Super Admin
- PWTI
- Kominfo
- Kesma
- Kesra
- KWU
- Sosma
- PAO

Setiap divisi dapat memiliki hak akses fitur yang berbeda.

---

### 4. Role dan Permission Admin

Sistem memiliki pengaturan hak akses berdasarkan divisi.

Contohnya:

- Super Admin dapat mengakses semua fitur
- Divisi tertentu hanya dapat mengakses fitur yang diberikan
- User biasa tidak dapat mengakses fitur yang bukan haknya

Fitur ini membantu membatasi akses agar data internal tetap aman.

---

### 5. Manajemen Pengurus HMIF

Fitur ini digunakan untuk mengelola data struktur kepengurusan HMIF.

Data yang dapat dikelola:

- Nama pengurus
- Jabatan
- Divisi
- Periode kepengurusan
- Foto

---

### 6. Manajemen Kegiatan

Fitur ini digunakan untuk mengelola data kegiatan HMIF.

Data kegiatan meliputi:

- Nama kegiatan
- Deskripsi kegiatan
- Tanggal kegiatan
- Lokasi
- Gambar kegiatan
- Status kegiatan

---

### 7. Manajemen Program Kerja

Fitur ini digunakan untuk mencatat dan mengelola program kerja setiap divisi.

Data yang dikelola:

- Nama program kerja
- Divisi
- Deskripsi
- Tanggal pelaksanaan
- Status program kerja

---

### 8. Manajemen Informasi Internal

Fitur ini digunakan untuk mengelola informasi atau pengumuman internal HMIF.

Contohnya:

- Informasi rapat
- Pengumuman kegiatan
- Informasi organisasi
- Berita internal

---

### 9. Manajemen IF-Pedia

Fitur IF-Pedia digunakan untuk mengelola dokumen atau informasi akademik yang dapat diakses oleh mahasiswa.

Data yang dikelola:

- Judul
- Deskripsi
- File PDF
- Kategori
- Status publikasi

---

### 10. Manajemen Lapak KWU

Fitur ini digunakan untuk mengelola data produk atau lapak dari divisi KWU.

Data yang dikelola:

- Nama produk
- Harga
- Deskripsi
- Gambar produk
- Status produk

---

### 11. Manajemen RFID dan Absensi

Fitur ini digunakan untuk mengelola kartu RFID dan data absensi anggota.

Fitur yang tersedia:

- Data kartu RFID
- Scan UID RFID
- Absensi masuk
- Absensi keluar
- Riwayat absensi
- Status kartu aktif atau tidak aktif

---

## Menjalankan dengan Docker

Pastikan file `.env` sudah ada dan `APP_KEY` sudah terisi, lalu jalankan:

```powershell
docker compose up --build -d
```

Aplikasi tersedia di `http://localhost:8000`. Saat container aplikasi dimulai,
migrasi database dijalankan otomatis. MySQL dan folder `storage` disimpan di
Docker volume sehingga data tetap ada ketika container dibuat ulang.

Batas unggah di dalam container adalah 100 MB (`upload_max_filesize=100M`),
dengan `post_max_size=110M` untuk memberi ruang pada data request lainnya.
Validasi Filament dan Livewire juga menggunakan batas 102400 KB (100 MB).

Periksa konfigurasi yang sedang aktif dengan:

```powershell
docker compose exec app php -r "echo ini_get('upload_max_filesize'), PHP_EOL, ini_get('post_max_size'), PHP_EOL;"
```

Untuk menghentikan container tanpa menghapus database atau file unggahan:

```powershell
docker compose down
```

## Cara Install Project

### 1. Clone Repository

```bash
git clone <url-repository>
cd internal
