<?php
require_once(__DIR__ . "/etc/config.php");
require_once(__DIR__ . "/etc/function.php");

// Mendukung format JSON jika diminta melalui parameter format=json atau header Accept: application/json
$is_json = (isset($_GET['format']) && $_GET['format'] === 'json') ||
           (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

function sendResponse($status_code, $success, $message, $data = [], $is_json = false)
{
    http_response_code($status_code);
    if ($is_json) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ]);
    } else {
        echo $message;
    }
    exit();
}

// Pastikan parameter GET tag dikirim
if (isset($_GET['tag'])) {
    $waktu = gmdate("Y-m-d H:i:s");

    $idmesin = trim($_GET['idmesin'] ?? '');
    $tag = trim($_GET['tag'] ?? '');
    $kategori = (int)($_GET['kategori'] ?? 0);

    // Ambil detail pengguna berdasarkan tag (prepared statements)
    $pengguna = getDetailPengguna($mysqli, $tag);

    if (!$pengguna) {
        sendResponse(404, false, "Tag tidak terdaftar", [], $is_json);
    }

    $nomor_induk = $pengguna['nomor_induk'];
    $nama = $pengguna['nama'];
    $cabang_id = $pengguna['cabang_gedung'];

    // Tentukan kolom jam maksimal berdasarkan kategori
    $kolom_kategori = [
        1 => "jam_masuk",
        2 => "istirahat_mulai",
        3 => "istirahat_selesai",
        4 => "jam_pulang"
    ];

    if (!isset($kolom_kategori[$kategori])) {
        sendResponse(400, false, "Kategori tidak valid", [], $is_json);
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
            sendResponse(200, true, "BERHASIL", [
                'nomor_induk' => $nomor_induk,
                'nama'        => $nama,
                'waktu'       => $waktu,
                'kategori'    => $kategori,
                'idmesin'     => $idmesin
            ], $is_json);
        } else {
            sendResponse(500, false, "Gagal mencatat absensi: " . mysqli_stmt_error($stmt), [], $is_json);
        }
        mysqli_stmt_close($stmt);
    } else {
        sendResponse(500, false, "Kesalahan database: " . mysqli_error($mysqli), [], $is_json);
    }
} else {
    sendResponse(400, false, "Parameter tag tidak ditemukan", [], $is_json);
}

function getDetailPengguna($mysqli, $tag)
{
    $stmt = mysqli_prepare($mysqli, "SELECT nomor_induk, nama, cabang_gedung FROM pengguna WHERE tag = ? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $tag);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            mysqli_stmt_close($stmt);
            return $row;
        }
        mysqli_stmt_close($stmt);
    }
    return null;
}

function getAbsenMaks($mysqli, $kolom, $id)
{
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
