<?php

require_once("config.php");

function getAnyTampil($mysqli, $tampil, $tabel, $where, $valueWhere)
{
    $result = mysqli_fetch_row(mysqli_query($mysqli, "SELECT $tampil FROM $tabel where $where = $valueWhere"));

    return $result[0];
}

function comboBoxSelect($mysqli, $name, $id, $tampil, $tabel, $ignore)
{
    $result = mysqli_query($mysqli, "SELECT $id, $tampil FROM $tabel");

    echo '<select class="custom-select" name="' . $name . '">';
    while ($data = mysqli_fetch_array($result)) {
        if ($data[$id] == $ignore) {
            continue;
        }
        echo '<option value=' . $data[$id] . '>' . $data[$tampil] . '</option>';
    }
    echo '</select>';
}

function comboBoxSelectEdit($mysqli, $name, $id, $tampil, $tabel, $ignore, $value, $tampilValue)
{
    $result = mysqli_query($mysqli, "SELECT $id, $tampil FROM $tabel");

    echo '<select class="custom-select" name="' . $name . '">';
    echo '<option value=' . $value . '>' . $tampilValue . '</option>';
    while ($data = mysqli_fetch_array($result)) {
        if ($data[$id] == $ignore || $data[$id] == $value) {
            continue;
        }
        echo '<option value=' . $data[$id] . '>' . $data[$tampil] . '</option>';
    }
    echo '</select>';
}

function dateDifference($date_1, $date_2, $differenceFormat = '%i')
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($differenceFormat);
}

function countRow($mysqli, $tabel, $where, $valueWhere)
{
    $sql = "select count(*) from $tabel where $where = '$valueWhere'";
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_array($result);

    return $row[0];
}

function gethariLibur($mysqli, $nomor_induk)
{
    $sql = "SELECT cabang_gedung.hari_libur
    FROM cabang_gedung
    INNER JOIN pengguna ON cabang_gedung.id = pengguna.cabang_gedung
    WHERE pengguna.nomor_induk = '$nomor_induk'";

    $result = mysqli_fetch_row(mysqli_query($mysqli, $sql));

    return $result[0];
}