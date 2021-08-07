-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Agu 2021 pada 06.14
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `nomor_induk` char(30) NOT NULL,
  `absen` datetime NOT NULL,
  `absen_maks` datetime NOT NULL,
  `kategori` char(1) DEFAULT NULL COMMENT '1=jam_masuk, 2=istirahat_mulai, 3=istirahat_selesai, 4=pulang',
  `idmesin` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cabang_gedung`
--

CREATE TABLE `cabang_gedung` (
  `id` int(11) NOT NULL,
  `lokasi` text NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `istirahat_mulai` time NOT NULL,
  `istirahat_selesai` time NOT NULL,
  `hari_libur` char(15) NOT NULL,
  `zona_waktu` char(1) NOT NULL,
  `aktif` char(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `cabang_gedung`
--

INSERT INTO `cabang_gedung` (`id`, `lokasi`, `jam_masuk`, `jam_pulang`, `istirahat_mulai`, `istirahat_selesai`, `hari_libur`, `zona_waktu`, `aktif`) VALUES
(0, 'mainland', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '1,2,3,4,5,6,7', '1', '1'),
(1, 'Bantul', '01:00:00', '10:00:00', '04:00:00', '06:00:00', '0,1', '1', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cuti`
--

CREATE TABLE `cuti` (
  `id` int(11) NOT NULL,
  `nomor_induk` char(30) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hak_akses`
--

CREATE TABLE `hak_akses` (
  `id` int(11) NOT NULL,
  `hak` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `hak_akses`
--

INSERT INTO `hak_akses` (`id`, `hak`) VALUES
(0, 'nusabot'),
(1, 'Full'),
(2, 'General');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan_status`
--

CREATE TABLE `jabatan_status` (
  `id` int(11) NOT NULL,
  `jabatan_status` varchar(15) NOT NULL,
  `hak_akses` char(1) NOT NULL,
  `aktif` char(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jabatan_status`
--

INSERT INTO `jabatan_status` (`id`, `jabatan_status`, `hak_akses`, `aktif`) VALUES
(1, 'main', '0', '1'),
(2, 'Direktur', '1', '1'),
(3, 'HRD', '1', '1'),
(5, 'Office Boy', '2', '1'),
(7, 'Karyawan', '1', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `libur_khusus`
--

CREATE TABLE `libur_khusus` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `nomor_induk` char(30) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tag` varchar(50) NOT NULL,
  `jabatan_status` char(1) NOT NULL,
  `cabang_gedung` char(1) NOT NULL,
  `password` text NOT NULL,
  `aktif` char(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`nomor_induk`, `nama`, `tag`, `jabatan_status`, `cabang_gedung`, `password`, `aktif`) VALUES
('1', 'Ardi Arto Nugroho', 'Direksi', '2', '', 'c4ca4238a0b923820dcc509a6f75849b', '1'),
('123', 'Admin CS', '', '0', '0', '21232f297a57a5a743894a0e4a801fc3', '1');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cabang_gedung`
--
ALTER TABLE `cabang_gedung`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cuti`
--
ALTER TABLE `cuti`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `hak_akses`
--
ALTER TABLE `hak_akses`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jabatan_status`
--
ALTER TABLE `jabatan_status`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `libur_khusus`
--
ALTER TABLE `libur_khusus`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`nomor_induk`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `cabang_gedung`
--
ALTER TABLE `cabang_gedung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `cuti`
--
ALTER TABLE `cuti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jabatan_status`
--
ALTER TABLE `jabatan_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `libur_khusus`
--
ALTER TABLE `libur_khusus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
