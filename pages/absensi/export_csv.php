<?php
require_once("../../etc/config.php");
require_once("../../etc/function.php");
require_once("../../session.php");

// Set header download file CSV
$filename = "rekap_absensi_" . date('Ymd_His') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Buka output stream PHP
$output = fopen('php://output', 'w');

// Tulis BOM UTF-8 agar karakter terbaca rapi di Microsoft Excel
fputs($output, "\xEF\xBB\xBF");

// Tulis baris header kolom CSV
fputcsv($output, [
    'Nomor Induk',
    'Nama Pengguna',
    'Cabang / Gedung',
    'Waktu Absen (UTC)',
    'Kategori',
    'Batas Waktu',
    'Status Keterlambatan',
    'ID Mesin'
]);

$cabang_gedung = trim($_GET['cabang_gedung'] ?? '');
$awal = trim($_GET['awal'] ?? '');
$akhir = trim($_GET['akhir'] ?? '');
$kategori = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;

$nama_kategori = [
    1 => 'Masuk',
    2 => 'Mulai Istirahat',
    3 => 'Selesai Istirahat',
    4 => 'Pulang'
];

if (!empty($awal) && !empty($akhir)) {
    if ($kategori === 0) {
        $sql = "SELECT absensi.nomor_induk, pengguna.nama, cabang_gedung.lokasi, absensi.absen, absensi.kategori, absensi.absen_maks, absensi.idmesin
                FROM absensi
                INNER JOIN pengguna ON absensi.nomor_induk = pengguna.nomor_induk
                LEFT JOIN cabang_gedung ON pengguna.cabang_gedung = cabang_gedung.id
                WHERE pengguna.cabang_gedung = ? AND (absensi.absen BETWEEN ? AND ?)
                ORDER BY absensi.absen DESC";
        $stmt = mysqli_prepare($mysqli, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $cabang_gedung, $awal, $akhir);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        }
    } else {
        $sql = "SELECT absensi.nomor_induk, pengguna.nama, cabang_gedung.lokasi, absensi.absen, absensi.kategori, absensi.absen_maks, absensi.idmesin
                FROM absensi
                INNER JOIN pengguna ON absensi.nomor_induk = pengguna.nomor_induk
                LEFT JOIN cabang_gedung ON pengguna.cabang_gedung = cabang_gedung.id
                WHERE pengguna.cabang_gedung = ? AND absensi.kategori = ? AND (absensi.absen BETWEEN ? AND ?)
                ORDER BY absensi.absen DESC";
        $stmt = mysqli_prepare($mysqli, $sql);
        if ($stmt) {
            $kat_str = (string)$kategori;
            mysqli_stmt_bind_param($stmt, "ssss", $cabang_gedung, $kat_str, $awal, $akhir);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        }
    }
} else {
    // Ambil default 500 data terbaru
    $sql = "SELECT absensi.nomor_induk, pengguna.nama, cabang_gedung.lokasi, absensi.absen, absensi.kategori, absensi.absen_maks, absensi.idmesin
            FROM absensi
            INNER JOIN pengguna ON absensi.nomor_induk = pengguna.nomor_induk
            LEFT JOIN cabang_gedung ON pengguna.cabang_gedung = cabang_gedung.id
            ORDER BY absensi.absen DESC LIMIT 500";
    $result = mysqli_query($mysqli, $sql);
}

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $kat_text = $nama_kategori[(int)$row['kategori']] ?? 'Tidak Diketahui';
        
        // Status keterlambatan
        $status_telat = "Tepat Waktu";
        if (!empty($row['absen']) && !empty($row['absen_maks'])) {
            if (strtotime($row['absen']) > strtotime($row['absen_maks'])) {
                $status_telat = "Terlambat (" . dateDifference($row['absen_maks'], $row['absen'], '%h jam %i mnt') . ")";
            }
        }

        fputcsv($output, [
            $row['nomor_induk'],
            $row['nama'],
            $row['lokasi'] ?? '-',
            $row['absen'],
            $kat_text,
            $row['absen_maks'],
            $status_telat,
            $row['idmesin']
        ]);
    }
}

fclose($output);
exit();
