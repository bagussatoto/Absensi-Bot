<?php
require_once(__DIR__ . '/etc/config.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah terautentikasi
if (!isset($_SESSION['login']) || $_SESSION['login'] !== 1 || empty($_SESSION['nomor_induk'])) {
    header("Location: login.php");
    exit();
}

$user_check = $_SESSION['nomor_induk'];

// Validasi user di database menggunakan prepared statements
$stmt = mysqli_prepare($mysqli, "SELECT nomor_induk, nama, aktif FROM pengguna WHERE nomor_induk = ? LIMIT 1");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $user_check);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Jika user tidak ditemukan atau dinonaktifkan, paksa logout
    if (!$row || (isset($row['aktif']) && $row['aktif'] == 0)) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }

    $login_session = $row['nomor_induk'];
    $_SESSION['nama'] = $row['nama']; // Selalu sinkronkan nama terbaru
} else {
    // Jika koneksi/query gagal
    die("Gagal memvalidasi sesi pengguna.");
}
