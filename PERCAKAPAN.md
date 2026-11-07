# Dokumentasi Sesi Pembaruan & Modernisasi Sistem Absensi-Bot

**Tanggal Sesi:** 07 November 2026  
**Repositori:** [Absensi-Bot (GitHub)](https://github.com/bagussatoto/Absensi-Bot)  
**Versi Rilis:** `v1.0.0`

---

## Ringkasan Eksekutif

Pada sesi ini, telah dilakukan audit, perbaikan keamanan menyeluruh, pembaruan skema database, pembuatan rilis resmi perdana `v1.0.0`, pembuatan landing page baru, serta penambahan fleksibilitas integrasi IoT pada proyek **Absensi-Bot**.

---

## Kronologi & Alur Percakapan

### 1. Pengecekan Versi Awal
- **Pertanyaan Pengguna:** *Apakah ada perubahan versi sejauh ini?*
- **Temuan Awal:**
  - Repositori belum memiliki riwayat *release tag* resmi di Git.
  - Belum ada manifest versi sistem formal.
  - Kode masih menggunakan perakitan SQL mentah (*string concatenation*) yang rentan terhadap *SQL Injection* dan hashing kata sandi lama berbasis `md5()`.

### 2. Rekomendasi & Persetujuan Modernisasi Kode
- **Pertanyaan Pengguna:** *Saran: apakah saya perlu perbaikan tidak?*
- **Saran:** Sangat direkomendasikan untuk melakukan perbaikan keamanan kritis (SQL Injection, XSS, password hashing) serta kompatibilitas PHP 8.x.
- **Tanggapan Pengguna:** Menyetujui pelaksanaan **Modernisasi Kode**.

### 3. Implementasi Modernisasi Keamanan & Arsitektur
Telah dilakukan refactor mendalam pada seluruh file inti:
- **`etc/config.php`**: Penanganan error terstandar, pengujian koneksi MySQLi, dan penetapan charset `utf8mb4`.
- **`etc/function.php`**:
  - Penambahan fungsi sanitasi anti-XSS `e($string)`.
  - Fungsi query aman `dbQueryPrepared()` dengan *Prepared Statements*.
  - Sanitasi whitelist kolom dan nama tabel.
- **`login.php`**:
  - Penggunaan *Prepared Statements* untuk autentikasi user.
  - Migrasi ke `password_verify()` dan standar bcrypt modern.
  - Fitur **Auto-Rehash**: otomatis memperbarui kata sandi lama (MD5) ke hash modern saat user berhasil masuk.
  - Proteksi *Session Fixation* melalui `session_regenerate_id(true)`.
- **`session.php` & `logout.php`**: Validasi status aktif pengguna secara realtime dan pembersihan sesi/cookie secara menyeluruh.
- **Modul `pages/`** (`pengguna`, `cabang`, `jabatan`, `cuti`, `libur`, `profil`, `absensi`):
  - Seluruh query `INSERT`, `UPDATE`, `DELETE`, dan `SELECT` dimigrasikan ke *Prepared Statements*.
  - Proteksi otorisasi sesi pada seluruh halaman `edit.php`.
- **`machine.php`, `scan.php`, & `absen.php`**:
  - Penghapusan kredensial *hardcoded*, mengacu ke konfigurasi tunggal `etc/config.php`.
  - Pengamanan proses tap kartu RFID dan verifikasi jam kerja.

### 4. Publikasi Tag Rilis Resmi `v1.0.0`
- **Pembaruan Database (`db/absensi.sql`)**: Data akun default (`123` / password `admin`) diperbarui ke hash modern bawaan PHP.
- **Pembaruan Panduan (`README.md`)**: Petunjuk instalasi diperbarui, dukungan PHP 7.4 - PHP 8.x ditambahkan, serta disertakan catatan rilis resmi.
- **Git Tag**: Dibuat tag rilis resmi pertama **`v1.0.0`** dan berhasil didorong (*push*) ke remote repository.

### 5. Peningkatan Mesin IoT & Template Konfigurasi
- **`machine.php`**:
  - Mendukung respon ganda: **Mode Teks** biasa (kompatibel mikrokontroler lama) dan **Mode JSON** (`?format=json` / `Accept: application/json`) untuk mikrokontroler baru (ESP32/ESP8266).
- **`etc/config.example.php`**: Disediakan template konfigurasi bersih untuk deployment publik.

### 6. Pembuatan Tampilan Landing Page Baru (`home.php`)
- **Permintaan Pengguna:** *Buatkan tampilan landing page baru untuk website ini*.
- **Implementasi:**
  - Dibuatkan file `home.php` dengan desain modern berbasis *Plus Jakarta Sans*, gradasi warna biru, dan mobile-responsive.
  - Memuat *Hero Section*, *Live Status Feed Mockup*, *Stats Counter*, ringkasan fitur unggulan, dan diagram alur komunikasi mesin RFID IoT.
  - Navigasi terhubung langsung ke `login.php` dan `scan.php`.

---

## Ringkasan Commit Git yang Diunggah ke GitHub

| Commit ID | Deskripsi Pembaruan |
|---|---|
| `c24b355` | refactor: modernisasi keamanan PHP, prepared statements, sanitasi XSS, dan password hashing |
| `dc47372` | docs: perbarui panduan instalasi README dan skema database default v1.0.0 |
| `v1.0.0`  | **[Git Tag]** Release v1.0.0: Modernisasi arsitektur kode dan keamanan |
| `883986c` | fix: terapkan prepared statements dan sanitasi XSS pada scan.php dan absen.php |
| `f4c55c0` | feat: tambahkan dukungan format JSON pada machine.php dan sediakan config.example.php |
| `c22305c` | refactor: amankan query dan filter tanggal pada pages/absensi/pengguna.php |
| `fde66c2` | feat: tambahkan tampilan landing page modern (home.php) |

---
*Dokumen ini dibuat secara otomatis sebagai rekam jejak resmi pengembangan sistem Absensi-Bot.*
