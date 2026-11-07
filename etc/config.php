<?php
/**
 * Konfigurasi Database dan Sistem
 * Mendukung PHP 7.4+ hingga PHP 8.x
 */

// Error reporting disesuaikan untuk development
error_reporting(E_ALL);
ini_set('display_errors', '0');

$databaseHost = 'localhost';
$databaseName = 'absensi';
$databaseUsername = 'root';
$databasePassword = '';

$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if (!$mysqli) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset utf8mb4 untuk dukungan karakter penuh
mysqli_set_charset($mysqli, "utf8mb4");
