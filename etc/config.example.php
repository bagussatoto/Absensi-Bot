<?php
/**
 * Salin file ini menjadi `config.php` dan sesuaikan nilainya dengan konfigurasi server Anda.
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

$databaseHost     = 'localhost';
$databaseName     = 'absensi';
$databaseUsername = 'root';
$databasePassword = '';

$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if (!$mysqli) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($mysqli, "utf8mb4");
