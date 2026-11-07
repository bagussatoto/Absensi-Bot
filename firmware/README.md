# Panduan Firmware IoT Mesin Absensi RFID (ESP32 / ESP8266)

Dokumen ini menjelaskan cara merakit dan memprogram perangkat keras (hardware) mesin absensi berbasis mikrokontroler agar terhubung langsung dengan backend **Absensi-Bot**.

---

## 1. Komponen yang Dibutuhkan

| Komponen | Spesifikasi / Keterangan |
|---|---|
| **Mikrokontroler** | ESP32 NodeMCU / ESP8266 (NodeMCU v3 / Wemos D1 Mini) |
| **RFID Reader** | Modul MFRC522 (RC522) Frekuensi 13.56 MHz (SPI) |
| **Kartu / Tag** | Kartu RFID MIFARE Classic 1K / Gantungan Kunci RFID Tag |
| **Kabel Jumper** | Female-to-Female (7 jalur) |
| **Catu Daya** | Kabel Micro-USB / Adaptor 5V 1A |

---

## 2. Diagram Perkabelan (Wiring Pinout)

> [!WARNING]
> **Tegangan Operasional RC522 adalah 3.3V.**  
> Jangan menyambungkan pin VCC RC522 ke pin 5V mikrokontroler karena dapat merusak modul pembaca RFID secara permanen.

| Pin RC522 | Pin ESP8266 (NodeMCU) | Pin ESP32 (DevKit v1) |
|---|---|---|
| **VCC (3.3V)** | 3V3 | 3V3 |
| **RST** | D3 (GPIO 0) / D4 | GPIO 22 |
| **GND** | GND | GND |
| **IRQ** | *Tidak Dihubungkan* | *Tidak Dihubungkan* |
| **MISO** | D6 (GPIO 12) | GPIO 19 |
| **MOSI** | D7 (GPIO 13) | GPIO 23 |
| **SCK** | D5 (GPIO 14) | GPIO 18 |
| **SDA (SS)** | D8 (GPIO 15) | GPIO 5 |

---

## 3. Persiapan Software (Arduino IDE)

1. **Pasang Board Driver di Arduino IDE**:
   - Jika memakai ESP32: Tambahkan URL `https://raw.githubusercontent.com/espressif/arduino-esp32/gh-pages/package_esp32_index.json` di *Preferences*.
   - Jika memakai ESP8266: Tambahkan URL `http://arduino.esp8266.com/stable/package_esp8266com_index.json`.
2. **Install Library MFRC522**:
   - Buka menu: **Sketch** -> **Include Library** -> **Manage Libraries...**
   - Cari kata kunci: `MFRC522` (pilih karya *GithubCommunity / miguelbalboa*).
   - Klik **Install**.

---

## 4. Konfigurasi Sketch Firmware

Buka file firmware yang telah disediakan di:
`firmware/esp32_rfid_reader.ino`

Sesuaikan baris konfigurasi berikut:
```cpp
// 1. Nama dan Password WiFi
const char* WIFI_SSID     = "Nama_WiFi_Anda";
const char* WIFI_PASSWORD = "Password_WiFi_Anda";

// 2. Alamat IP Server Web Absensi-Bot
// Gunakan IP Laptop jika dijalankan di XAMPP (contoh: 192.168.1.15)
// Atau gunakan domain jika sudah dipasang di hosting (contoh: http://absensi.domainanda.com/machine.php)
const String SERVER_URL   = "http://192.168.1.15/Absensi-Bot/machine.php";

// 3. ID Unik Mesin
const String ID_MESIN     = "ESP-LOBBY-01";

// 4. Kategori Absensi
// 1 = Jam Masuk, 2 = Mulai Istirahat, 3 = Selesai Istirahat, 4 = Jam Pulang
int kategoriAbsen = 1; 
```

---

## 5. Cara Registrasi Kartu Pengguna Baru

1. Tempelkan kartu baru ke modul RC522.
2. Buka **Serial Monitor** pada Arduino IDE (kecepatan baud rate: `115200`).
3. Catat UID kartu yang muncul di Serial Monitor, misalnya: `A1B2C3D4`.
4. Masuk ke antarmuka web admin Absensi-Bot:
   - Pilih menu **Pengguna** -> **Tambah Data Baru**.
   - Masukkan UID tadi ke dalam input kolom **Tag**.
   - Simpan.
5. Kartu kini telah aktif dan terdaftar di database untuk melakukan absensi otomatis.
