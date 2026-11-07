# Sistem Absensi Bot

**Absensi** adalah sistem absensi gratis yang dapat diterapkan untuk berbagai kepentingan absensi seperti pabrik, toko, kantor, dan sebagainya. Sistem ini termasuk manajemen absensi dan mesin absensi yang dapat dipelajari oleh siapapun.
Sistem ini dibuat tanpa framework dan menggunakan bahasa struktural sehingga mudah dipelajari oleh pemula.

Proyek ini adalah bagian dari proyek **FOSS** sebagai wujud kepedulian saya terhadap pendidikan teknologi di Indonesia yang bertujuan untuk membuat dan mengembangkan proyek-proyek _open source_ yang memiliki kualitas baik sehingga dapat diterapkan secara real di industri sekaligus dapat dipelajari oleh seluruh masyarakat Indonesia.

Untuk _source code_ dan skematik dapat mengunjungi repo berikut:
https://github.com/bagussatoto/Absensi-Bot

## Fitur Absensi

Absensi memiliki fitur yang tidak dimiliki mesin absensi pada umumnya dan sistem ini adalah sistem **IoT Based** data yang direkam oleh mesin dikirimkan melalui jaringan internet secara realtime sehingga memungkinkan integrasi data dan pengolahan data secara terpusat dan dapat diakses darimana saja.



- Web based sehingga aplikasi bisa dibuka dari perangkat apapun.
- Perhitungan keterlambatan absensi (Masuk, Istirahat, Pulang).
- Multi cabang / gedung ataupun sistem shift.
- Absensi merekam MAC Address dari alat absen sehingga tidak dapat melakukan absen menggunakan alat yang belum didaftarkan ke sistem.
- Pembagian zona waktu Indonesia (WIB, WITA, WIT).
- Absensi melalui RFID (bisa dikembangkan menggunakan media lain), source code dan skematik disediakan.
- Absen masuk, mulai istirahat, selesai istirahat, dan pulang.
- Dapat digunakan secara gratis tanpa perlu izin.
- Pelaporan absensi secara realtime, dapat memilih rentang waktu.
- Pengembangan menggunakan bahasa struktural tanpa framework agar mudah dipelajari bagi pemula.

## Instalasi

Aplikasi ini membutuhkan sebuah komputer/server lokal yang sudah terpasang Web Server (Apache/Nginx), PHP (mendukung **PHP 7.4 hingga PHP 8.x**), dan MySQL / MariaDB. Anda dapat menggunakan [XAMPP](https://www.apachefriends.org/download.html "XAMPP") untuk mempermudah instalasi dan pengembangan.
*Catatan: XAMPP disarankan untuk lingkungan pengembangan (development).*

Pada langkah ini akan dijelaskan instalasi sistem menggunakan XAMPP:

1. Unduh rilis stabil Absensi-Bot dari tab [Releases](https://github.com/bagussatoto/Absensi-Bot/releases) di GitHub.
2. Buat folder baru di dalam folder `htdocs` dari XAMPP (misal: `absensi`).
3. Ekstrak seluruh isi file rilis ke dalam folder tersebut.
4. Buat database baru (misalnya bernama `absensi`), bisa melalui **phpMyAdmin**.
5. Impor file database skema awal yang berada di **/db/absensi.sql**.
6. Sesuaikan pengaturan koneksi database pada file **/etc/config.php** (kredensial host, username, password, dan nama basis data Anda).
7. Buka aplikasi melalui web browser (contoh: `http://localhost/absensi/login.php`) dan login menggunakan akun default:
   - **Nomor Induk**: `123`
   - **Password**: `admin`
8. Setelah berhasil masuk, Anda dapat menambahkan pengguna baru atau mengubah password default pada menu Profil demi keamanan.

## Catatan Rilis & Keamanan (v1.0.0)

Pembaruan arsitektur dan modernisasi keamanan:
- **Prepared Statements**: Mengamankan seluruh query dari potensi celah *SQL Injection*.
- **Password Hashing Modern**: Mengganti MD5 lawas dengan algoritma `password_hash()` bawaan PHP, dilengkapi mekanisme *auto-rehash* saat login untuk migrasi akun lama.
- **Proteksi Session & Anti-XSS**: Memperkuat autentikasi sesi internal serta fungsi sanitasi output.
- **Kompatibilitas PHP 8.x**: Memperbaiki deprecation warnings dan standarisasi konfigurasi database terpusat.

## Dukungan Web Browser

| [<img src="https://raw.githubusercontent.com/alrra/browser-logos/master/src/edge/edge_48x48.png" alt="IE / Edge" width="24px" height="24px" />](http://godban.github.io/browsers-support-badges/)<br/>IE / Edge | [<img src="https://raw.githubusercontent.com/alrra/browser-logos/master/src/firefox/firefox_48x48.png" alt="Firefox" width="24px" height="24px" />](http://godban.github.io/browsers-support-badges/)<br/>Firefox | [<img src="https://raw.githubusercontent.com/alrra/browser-logos/master/src/chrome/chrome_48x48.png" alt="Chrome" width="24px" height="24px" />](http://godban.github.io/browsers-support-badges/)<br/>Chrome | [<img src="https://raw.githubusercontent.com/alrra/browser-logos/master/src/safari/safari_48x48.png" alt="Safari" width="24px" height="24px" />](http://godban.github.io/browsers-support-badges/)<br/>Safari | [<img src="https://raw.githubusercontent.com/alrra/browser-logos/master/src/safari-ios/safari-ios_48x48.png" alt="iOS Safari" width="24px" height="24px" />](http://godban.github.io/browsers-support-badges/)<br/>iOS Safari | [<img src="https://raw.githubusercontent.com/alrra/browser-logos/master/src/samsung-internet/samsung-internet_48x48.png" alt="Samsung" width="24px" height="24px" />](http://godban.github.io/browsers-support-badges/)<br/>Samsung | [<img src="https://raw.githubusercontent.com/alrra/browser-logos/master/src/opera/opera_48x48.png" alt="Opera" width="24px" height="24px" />](http://godban.github.io/browsers-support-badges/)<br/>Opera | [<img src="https://raw.githubusercontent.com/alrra/browser-logos/master/src/vivaldi/vivaldi_48x48.png" alt="Vivaldi" width="24px" height="24px" />](http://godban.github.io/browsers-support-badges/)<br/>Vivaldi | [<img src="https://raw.githubusercontent.com/alrra/browser-logos/master/src/electron/electron_48x48.png" alt="Electron" width="24px" height="24px" />](http://godban.github.io/browsers-support-badges/)<br/>Electron |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| IE10, IE11, Edge                                                                                                                                                                                                | 2 versi terakhir                                                                                                                                                                                                  | 2 versi terakhir                                                                                                                                                                                              | 2 versi terakhir                                                                                                                                                                                              | 2 versi terakhir                                                                                                                                                                                                              | l2 versi terakhir                                                                                                                                                                                                                   | 2 versi terakhir                                                                                                                                                                                          | 2 versi terakhir                                                                                                                                                                                                  | 2 versi terakhir                                                                                                                                                                                                      |

## Request Fitur Baru dan Pelaporan Bug

Anda dapat meminta fitur baru maupun melaporkan bug melalui menu **issues** yang sudah disediakan oleh GitHub (lihat menu di atas), posting issues baru dan kita akan berdiskusi disana.

## Berkontribusi

Siapapun dapat berkontribusi pada proyek ini mulai dari pemrograman, pembuakan buku manual, sampai dengan mengenalkan produk ini kepada masyarakat Indonesia agar mengurangi kesenjangan pendidikan teknologi dengan cara membuat postingan issue di repository ini.

# Lisensi

Absensi - Bot di lisensikan dimana Anda diperbolehkan:

- **Berbagi** - Menyalin dan menyebarluaskan kembali produk ini dalam bentuk format apapun
- **Adaptasi** - Menggubah, mengubah, dan membuat turunan dari proyek ini.

### Berdasarkan ketentuan berikut:

- **NonKomersial** - Anda tidak dapat menggunakan produk ini untuk kepentingan komersial (yaitu penggunaan yang ditujukan untuk memperoleh keuntungan komersial atau kompensasi dalam bentuk uang), kecuali jika Anda menjadikan produk ini untuk bahan ajar atau memberikan pelatihan penggunaan produk dan Anda menerima upah.
- **Berbagi Serupa** - Apabila Anda menggubah, mengubah, atau membuat turunan dari proyek ini, Anda harus menyebarluaskan kontribusi Anda dibawah lisensi yang sama dengan produk ini



