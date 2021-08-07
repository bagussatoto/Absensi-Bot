<?php
if (isset($_GET['tag'])) {
    //ambil waktu UTC, letakan di awal agar mengurangi jeda waktu antara data terkirim dan data diproses
    $waktu = gmdate("Y-m-d H:i:s");

    //Variabel basis data
    $databaseHost = 'localhost';
    $databaseName = 'absensi';
    $databaseUsername = 'root';
    $databasePassword = '';

    $mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

    $idmesin = $_GET['idmesin'];
    $tag = $_GET['tag'];
    $nomor_induk = getNomorInduk($mysqli, $tag);
    $kategori = $_GET['kategori'];

    //ambil jam absen maksimal
    if ($kategori == 1) {
        $jam_maksimal = getAbsenMaks($mysqli, "jam_masuk", getCabangGedung($mysqli, $tag));
    } else if ($kategori == 2) {
        $jam_maksimal = getAbsenMaks($mysqli, "istirahat_mulai", getCabangGedung($mysqli, $tag));
    } else if ($kategori == 3) {
        $jam_maksimal = getAbsenMaks($mysqli, "istirahat_selesai", getCabangGedung($mysqli, $tag));
    } else if ($kategori == 4) {
        $jam_maksimal = getAbsenMaks($mysqli, "jam_pulang", getCabangGedung($mysqli, $tag));
    }

    $maksimal = gmdate("Y-m-d $jam_maksimal");
    
    // Statement SQL
    $sql = "INSERT INTO absensi (nomor_induk, absen, absen_maks, kategori, idmesin)
    VALUES ('$nomor_induk','$waktu', '$maksimal', '$kategori', '$idmesin')";

    $result = mysqli_query($mysqli, $sql);

    if (!$result) {
        die('Query salah: ' . mysqli_error($conn));
    }
}

//ambil nomor induk berdasarkan tag
function getNomorInduk($mysqli, $tag)
{
    $sql = "SELECT nomor_induk FROM pengguna WHERE tag = '$tag'";
    $result = mysqli_fetch_row(mysqli_query($mysqli, $sql));
    return $result['0'];
}

function getCabangGedung($mysqli, $tag)
{
    $sql = "SELECT cabang_gedung FROM pengguna WHERE tag = '$tag'";
    $result = mysqli_fetch_row(mysqli_query($mysqli, $sql));
    return $result['0'];
}

function getAbsenMaks($mysqli, $absen, $id)
{
    $sql = "SELECT $absen FROM cabang_gedung WHERE id = '$id'";
    $result = mysqli_fetch_row(mysqli_query($mysqli, $sql));
    return $result['0'];
}
