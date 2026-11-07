<?php
require_once(__DIR__ . "/etc/config.php");
require_once(__DIR__ . "/etc/function.php");

// Pastikan parameter GET tag dikirim
if (isset($_GET['tag'])) {
    $waktu = gmdate("Y-m-d H:i:s");

    $idmesin = trim($_GET['idmesin'] ?? '');
    $tag = trim($_GET['tag'] ?? '');
    $kategori = (int)($_GET['kategori'] ?? 0);

    // Ambil nomor induk dan cabang berdasarkan tag (prepared statements)
    $nomor_induk = getNomorInduk($mysqli, $tag);
    $cabang_id = getCabangGedung($mysqli, $tag);

    if (!$nomor_induk) {
        http_response_code(404);
        die("Tag tidak terdaftar");
    }

    // Tentukan kolom jam maksimal berdasarkan kategori
    $kolom_kategori = [
        1 => "jam_masuk",
        2 => "istirahat_mulai",
        3 => "istirahat_selesai",
        4 => "jam_pulang"
    ];

    if (!isset($kolom_kategori[$kategori])) {
        http_response_code(400);
        die("Kategori tidak valid");
    }

    $jam_maksimal = getAbsenMaks($mysqli, $kolom_kategori[$kategori], $cabang_id);
    if (!$jam_maksimal) {
        $jam_maksimal = "00:00:00";
    }

    $maksimal = gmdate("Y-m-d $jam_maksimal");

    // Simpan data absensi menggunakan Prepared Statements
    $stmt = mysqli_prepare(
        $mysqli,
        "INSERT INTO absensi (nomor_induk, absen, absen_maks, kategori, idmesin) VALUES (?, ?, ?, ?, ?)"
    );

    if ($stmt) {
        $kat_str = (string)$kategori;
        mysqli_stmt_bind_param($stmt, "sssss", $nomor_induk, $waktu, $maksimal, $kat_str, $idmesin);
        if (mysqli_stmt_execute($stmt)) {
            echo "BERHASIL";
        } else {
            http_response_code(500);
            echo "Gagal mencatat absensi: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        http_response_code(500);
        echo "Kesalahan database: " . mysqli_error($mysqli);
    }
}

function getNomorInduk($mysqli, $tag)
{
    $stmt = mysqli_prepare($mysqli, "SELECT nomor_induk FROM pengguna WHERE tag = ? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $tag);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_row($result)) {
            mysqli_stmt_close($stmt);
            return $row[0];
        }
        mysqli_stmt_close($stmt);
    }
    return null;
}

function getCabangGedung($mysqli, $tag)
{
    $stmt = mysqli_prepare($mysqli, "SELECT cabang_gedung FROM pengguna WHERE tag = ? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $tag);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_row($result)) {
            mysqli_stmt_close($stmt);
            return $row[0];
        }
        mysqli_stmt_close($stmt);
    }
    return null;
}

function getAbsenMaks($mysqli, $kolom, $id)
{
    // Whitelist kolom yang diizinkan
    $allowed_cols = ["jam_masuk", "istirahat_mulai", "istirahat_selesai", "jam_pulang"];
    if (!in_array($kolom, $allowed_cols, true)) {
        return null;
    }

    $stmt = mysqli_prepare($mysqli, "SELECT $kolom FROM cabang_gedung WHERE id = ? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_row($result)) {
            mysqli_stmt_close($stmt);
            return $row[0];
        }
        mysqli_stmt_close($stmt);
    }
    return null;
}
