<?php

require_once("config.php");

/**
 * Sanitasi string untuk proteksi XSS pada tampilan HTML
 */
function e($value)
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Helper untuk menjalankan prepared query dengan aman
 * Mencegah SQL Injection
 */
function dbQueryPrepared($mysqli, $sql, $params = [], $types = "")
{
    $stmt = mysqli_prepare($mysqli, $sql);
    if (!$stmt) {
        return false;
    }

    if (!empty($params)) {
        if (empty($types)) {
            $types = str_repeat('s', count($params));
        }
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}

function getAnyTampil($mysqli, $tampil, $tabel, $where, $valueWhere)
{
    // Filter nama kolom & tabel yang diizinkan untuk keamanan
    $tampil = preg_replace('/[^a-zA-Z0-9_]/', '', $tampil);
    $tabel = preg_replace('/[^a-zA-Z0-9_]/', '', $tabel);
    $where = preg_replace('/[^a-zA-Z0-9_]/', '', $where);

    $sql = "SELECT $tampil FROM $tabel WHERE $where = ? LIMIT 1";
    $result = dbQueryPrepared($mysqli, $sql, [$valueWhere], "s");

    if ($result && ($row = mysqli_fetch_row($result))) {
        return $row[0];
    }

    return null;
}

function comboBoxSelect($mysqli, $name, $id, $tampil, $tabel, $ignore)
{
    $id = preg_replace('/[^a-zA-Z0-9_]/', '', $id);
    $tampil = preg_replace('/[^a-zA-Z0-9_]/', '', $tampil);
    $tabel = preg_replace('/[^a-zA-Z0-9_]/', '', $tabel);

    $result = mysqli_query($mysqli, "SELECT $id, $tampil FROM $tabel");

    echo '<select class="custom-select" name="' . e($name) . '">';
    if ($result) {
        while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            if ($data[$id] == $ignore) {
                continue;
            }
            echo '<option value="' . e($data[$id]) . '">' . e($data[$tampil]) . '</option>';
        }
    }
    echo '</select>';
}

function comboBoxSelectEdit($mysqli, $name, $id, $tampil, $tabel, $ignore, $value, $tampilValue)
{
    $id = preg_replace('/[^a-zA-Z0-9_]/', '', $id);
    $tampil = preg_replace('/[^a-zA-Z0-9_]/', '', $tampil);
    $tabel = preg_replace('/[^a-zA-Z0-9_]/', '', $tabel);

    $result = mysqli_query($mysqli, "SELECT $id, $tampil FROM $tabel");

    echo '<select class="custom-select" name="' . e($name) . '">';
    echo '<option value="' . e($value) . '">' . e($tampilValue) . '</option>';
    if ($result) {
        while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            if ($data[$id] == $ignore || $data[$id] == $value) {
                continue;
            }
            echo '<option value="' . e($data[$id]) . '">' . e($data[$tampil]) . '</option>';
        }
    }
    echo '</select>';
}

function dateDifference($date_1, $date_2, $differenceFormat = '%i')
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    if (!$datetime1 || !$datetime2) {
        return 0;
    }

    $interval = date_diff($datetime1, $datetime2);
    return $interval->format($differenceFormat);
}

function countRow($mysqli, $tabel, $where, $valueWhere)
{
    $tabel = preg_replace('/[^a-zA-Z0-9_]/', '', $tabel);
    $where = preg_replace('/[^a-zA-Z0-9_]/', '', $where);

    $sql = "SELECT COUNT(*) FROM $tabel WHERE $where = ?";
    $result = dbQueryPrepared($mysqli, $sql, [$valueWhere], "s");

    if ($result && ($row = mysqli_fetch_row($result))) {
        return (int)$row[0];
    }

    return 0;
}

function gethariLibur($mysqli, $nomor_induk)
{
    $sql = "SELECT cabang_gedung.hari_libur
            FROM cabang_gedung
            INNER JOIN pengguna ON cabang_gedung.id = pengguna.cabang_gedung
            WHERE pengguna.nomor_induk = ? LIMIT 1";

    $result = dbQueryPrepared($mysqli, $sql, [$nomor_induk], "s");

    if ($result && ($row = mysqli_fetch_row($result))) {
        return $row[0];
    }

    return null;
}
