/*
 * =========================================================================
 * Contoh Firmware IoT Mesin Absensi RFID (ESP32 / ESP8266)
 * Sistem Absensi-Bot (https://github.com/bagussatoto/Absensi-Bot)
 * =========================================================================
 * 
 * Modul yang Digunakan:
 * 1. Mikrokontroler: NodeMCU ESP8266 atau ESP32 Dev Module
 * 2. RFID Reader: MFRC522 (RC522) RFID Reader SPI
 * 3. Tag / Kartu: MIFARE Classic 1K (13.56MHz)
 * 4. (Opsional) LCD I2C 16x2 atau Buzzer
 * 
 * Pinout MFRC522:
 * -------------------------------------------------------------
 * RC522 PIN    | ESP8266 (NodeMCU) | ESP32
 * -------------------------------------------------------------
 * SDA (SS)     | D8 (GPIO 15)      | GPIO 5
 * SCK          | D5 (GPIO 14)      | GPIO 18
 * MOSI         | D7 (GPIO 13)      | GPIO 23
 * MISO         | D6 (GPIO 12)      | GPIO 19
 * IRQ          | Tidak Terhubung   | Tidak Terhubung
 * GND          | GND               | GND
 * RST          | D3 (GPIO 0) / D4  | GPIO 22
 * 3.3V         | 3.3V              | 3.3V (PERHATIAN: Jangan gunakan 5V!)
 * -------------------------------------------------------------
 */

#if defined(ESP8266)
  #include <ESP8266WiFi.h>
  #include <ESP8266HTTPClient.h>
#elif defined(ESP32)
  #include <WiFi.h>
  #include <HTTPClient.h>
#endif

#include <SPI.h>
#include <MFRC522.h>

// --- KONFIGURASI WIFI & SERVER ---
const char* WIFI_SSID     = "NAMA_WIFI_ANDA";
const char* WIFI_PASSWORD = "PASSWORD_WIFI_ANDA";

// URL server endpoint machine.php
// Jika menggunakan XAMPP lokal: ganti IP dengan IP Laptop/Komputer Anda di jaringan lokal (cth: 192.168.1.10)
const String SERVER_URL   = "http://192.168.1.10/Absensi-Bot/machine.php";
const String ID_MESIN     = "ESP-LOBBY-01"; // Pengenal unik mesin absensi

// Kategori Absen:
// 1 = Jam Masuk
// 2 = Mulai Istirahat
// 3 = Selesai Istirahat
// 4 = Jam Pulang
int kategoriAbsen = 1; 

// Pin Konfigurasi SPI RFID
#if defined(ESP8266)
  #define SS_PIN  D8
  #define RST_PIN D3
#elif defined(ESP32)
  #define SS_PIN  5
  #define RST_PIN 22
#endif

MFRC522 rfid(SS_PIN, RST_PIN);

void setup() {
  Serial.begin(115200);
  delay(1000);
  Serial.println("\n--- Sistem Absensi Bot IoT Starting ---");

  // Inisialisasi SPI & RFID
  SPI.begin();
  rfid.PCD_Init();
  Serial.println("Modul RFID RC522 Siap.");

  // Koneksi WiFi
  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.print("Menghubungkan ke WiFi: ");
  Serial.print(WIFI_SSID);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("\nWiFi Terhubung!");
  Serial.print("IP Address ESP: ");
  Serial.println(WiFi.localIP());
  Serial.println("Silakan tempelkan Kartu / Tag RFID Anda...\n");
}

void loop() {
  // Cek apakah ada kartu baru
  if (!rfid.PICC_IsNewCardPresent()) {
    return;
  }

  // Baca nomor UID kartu
  if (!rfid.PICC_ReadCardSerial()) {
    return;
  }

  // Format UID kartu menjadi string HEX (misal: "A1B2C3D4")
  String tagUID = "";
  for (byte i = 0; i < rfid.uid.size; i++) {
    tagUID += String(rfid.uid.uidByte[i] < 0x10 ? "0" : "");
    tagUID += String(rfid.uid.uidByte[i], HEX);
  }
  tagUID.toUpperCase();

  Serial.print("Kartu Terdeteksi! Tag UID: ");
  Serial.println(tagUID);

  // Kirim data absensi ke server web
  kirimDataAbsensi(tagUID, kategoriAbsen);

  // Hentikan komunikasi dengan kartu saat ini dan tunggu sesaat
  rfid.PICC_HaltA();
  rfid.PCD_StopCrypto1();
  delay(2000); // Mencegah multi-scan dalam interval cepat
}

void kirimDataAbsensi(String tag, int kategori) {
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;

    // Buat URL dengan format JSON
    String endpoint = SERVER_URL + "?tag=" + tag + 
                      "&kategori=" + String(kategori) + 
                      "&idmesin=" + ID_MESIN + 
                      "&format=json";

    Serial.print("Mengirim request ke: ");
    Serial.println(endpoint);

    http.begin(client, endpoint);
    http.addHeader("Accept", "application/json");

    int httpResponseCode = http.GET();

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.print("Kode Respon HTTP: ");
      Serial.println(httpResponseCode);
      Serial.print("Respon Server: ");
      Serial.println(response);

      if (httpResponseCode == 200) {
        Serial.println("=> SUKSES: Absensi berhasil dicatat oleh server!\n");
      } else {
        Serial.println("=> GAGAL: Kartu tidak terdaftar atau kategori salah.\n");
      }
    } else {
      Serial.print("Error saat HTTP GET: ");
      Serial.println(http.errorToString(httpResponseCode).c_str());
    }

    http.end();
  } else {
    Serial.println("Koneksi WiFi Terputus!");
  }
}
