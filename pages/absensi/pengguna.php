<?php
if (isset($_GET['nomor_induk'])) {

    require_once("../../etc/config.php");
    require_once("../../etc/function.php");

    //buat array untuk tanggal tidak absen
    $tanggal1 = array();
    $tanggal2 = array();
    $tanggal3 = array();
    $tanggal4 = array();

    $tanpaAbsenMasuk = array();
    $tanpaAbsenMulai = array();
    $tanpaAbsenSelesai = array();
    $tanpaAbsenPulang = array();

    $cuti = array();

    //ambil hari libur
    $hariLibur = gethariLibur($mysqli, $_GET['nomor_induk']);
    $array_hariLibur = explode(",", $hariLibur);
    array_unshift($array_hariLibur,""); //tidak tahu kenapa search dimulai dari index 1, maka dari itu index 0 harus diisi. Nantinya akan digunakan untuk mengambil bukan hari kerja

    if (!isset($_POST['tampilkan'])) {
        $firstDay = date('Y-m-01');
        $lastDay = date('Y-m-t');
        $result = mysqli_query($mysqli, "SELECT * FROM absensi where nomor_induk = '{$_GET['nomor_induk']}' AND (absen BETWEEN '$firstDay' AND '$lastDay')");
        $formCabang = 1;
    } else {
        $firstDay = $_POST['awal'];
        $lastDay = $_POST['akhir'];
        if ($_POST['kategori'] == 0) {
            $result = mysqli_query($mysqli, "
            SELECT absensi.nomor_induk, pengguna.cabang_gedung, absensi.absen, absensi.kategori, absensi.idmesin, absensi.absen_maks
            FROM absensi
            INNER JOIN pengguna ON absensi.nomor_induk=pengguna.nomor_induk
            WHERE (absensi.absen BETWEEN '{$_POST['awal']}' AND '{$_POST['akhir']}')
            ");
        } else {
            $result = mysqli_query($mysqli, "
            SELECT absensi.nomor_induk, pengguna.cabang_gedung, absensi.absen, absensi.kategori, absensi.idmesin, absensi.absen_maks
            FROM absensi
            INNER JOIN pengguna ON absensi.nomor_induk=pengguna.nomor_induk
            WHERE absensi.kategori = '{$_POST['kategori']}' AND (absensi.absen BETWEEN '{$_POST['awal']}' AND '{$_POST['akhir']}')
            ");
        }

        if ($_POST['kategori'] == 0) {
            $formKategori = "Semua Kategori";
        } else {
            if ($_POST['kategori'] == 1) {
                $formKategori = "Masuk";
            } elseif ($_POST['kategori'] == 2) {
                $formKategori = "Mulai Istirahat";
            } elseif ($_POST['kategori'] == 3) {
                $formKategori = "Selesai Istirahat";
            } elseif ($_POST['kategori'] == 4) {
                $formKategori = "Pulang";
            }
        }
        $valueKategori = $_POST['kategori'];
        $formCabang = $_POST['cabang_gedung'];
    }

    //====masukan array dari tanggal awal sampai akhir (firstDay to lastDay)====/
    $jumlahHari = dateDifference($firstDay, $lastDay, '%a');
    $hariIni = date('Y-m-d',  strtotime('-1 day', strtotime($firstDay)));

    //masukan tanggal ke array hari kerja
    for ($i = 0; $i <= $jumlahHari; $i++) {
        $nomorHari = date('w', strtotime('+1 day', strtotime($hariIni)));
        // echo $nomorHari;

        if (array_search($nomorHari, $array_hariLibur) == true) {
            $hariIni = date('Y-m-d', strtotime('+1 day', strtotime($hariIni)));
            continue;
        }

        array_push($tanpaAbsenMasuk, date('Y-m-d', strtotime('+1 day', strtotime($hariIni))));
        array_push($tanpaAbsenMulai, date('Y-m-d', strtotime('+1 day', strtotime($hariIni))));
        array_push($tanpaAbsenSelesai, date('Y-m-d', strtotime('+1 day', strtotime($hariIni))));
        array_push($tanpaAbsenPulang, date('Y-m-d', strtotime('+1 day', strtotime($hariIni))));

        $hariIni = date('Y-m-d', strtotime('+1 day', strtotime($hariIni)));
    }
    //====end====//


    $terlambatMasuk = 0;
    $tepatMasuk = 0;
    $cepatIstirahatMulai = 0;
    $tepatIstirahatMulai = 0;
    $cepatIstirahatSelesai = 0;
    $tepatIstirahatSelesai = 0;
    $cepatPulang = 0;
    $tepatPulang = 0;

    $jumlahCuti = countRow($mysqli, "cuti", "nomor_induk", $_GET['nomor_induk']); //hitung jumlah cuti

    //ambil tanggal cuti
    $sqlCuti = mysqli_query($mysqli, "SELECT tanggal FROM cuti where nomor_induk = '{$_GET['nomor_induk']}'");
    while ($row = mysqli_fetch_array($sqlCuti)) {
        array_push($cuti, $row['tanggal']);
    }
?>

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    </head>

    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Rekap Absensi <b> <?php echo getAnyTampil($mysqli, "nama", "pengguna", "nomor_induk", $_GET['nomor_induk']) ?></b></h1>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <form method="post" action="">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <input type="date" name="awal" value="<?php echo $firstDay ?>" class="form-control" required>
                                                        </div>
                                                        sampai
                                                        <div class="col-3">
                                                            <input type="date" name="akhir" value="<?php echo $lastDay ?>" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <select class="custom-select" name="kategori">
                                                        <?php if (isset($_POST['kategori'])) { ?>
                                                            <option value="<?php echo $valueKategori ?>"><?php echo $formKategori ?></option>
                                                        <?php } ?>
                                                        <option value="0">Semua Kategori</option>
                                                        <option value="1">Masuk</option>
                                                        <option value="2">Istirahat Mulai</option>
                                                        <option value="3">Istirahat Selesai</option>
                                                        <option value="4">Pulang</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="submit" name="tampilkan" class="btn btn-success">Tampilkan</button>
                                        </form>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Absen</th>
                                                    <th>Batas</th>
                                                    <th>Selisih</th>
                                                    <th>Status</th>
                                                    <th>Cabang / Gedung</th>
                                                    <th>Kategori</th>
                                                    <th>ID Mesin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($data = mysqli_fetch_array($result)) {
                                                    $selisih = dateDifference($data['absen'], $data['absen_maks']);
                                                    $jumlahData = $jumlahData + 1; //hitung jumlah data absen masuk
                                                    if ($data['kategori'] == 1) {
                                                        array_push($tanggal1, date('Y-m-d', strtotime($data['absen_maks'])));
                                                        $jumlahMasuk = $jumlahMasuk + 1; //hitung jumlah data absen masuk
                                                        $kategori = "Masuk";
                                                        if ($data['absen'] > $data['absen_maks']) {
                                                            $status = "Terlambat";
                                                            $warna = "red";
                                                            $terlambatMasuk = $terlambatMasuk + $selisih;
                                                        } else {
                                                            $status = "Tepat Waktu";
                                                            $warna = "green";
                                                            $tepatMasuk = $tepatMasuk + $selisih;
                                                        }
                                                    } elseif ($data['kategori'] == 2) {
                                                        $jumlahMulai = $jumlahMulai + 1; //hitung jumlah data absen mulai istirahat
                                                        array_push($tanggal2, date('Y-m-d', strtotime($data['absen_maks'])));
                                                        $kategori = "Mulai Istirahat";
                                                        if ($data['absen'] > $data['absen_maks']) {
                                                            $status = "Tepat Waktu";
                                                            $warna = "green";
                                                            $tepatIstirahatMulai = $tepatIstirahatMulai + $selisih;
                                                        } else {
                                                            $status = "Terlalu Cepat";
                                                            $warna = "red";
                                                            $cepatIstirahatMulai = $cepatIstirahatMulai + $selisih;
                                                        }
                                                    } elseif ($data['kategori'] == 3) {
                                                        $jumlahSelesai = $jumlahSelesai + 1; //hitung jumlah data absen selesai istirahat
                                                        array_push($tanggal3, date('Y-m-d', strtotime($data['absen_maks'])));
                                                        $kategori = "Selesai Istirahat";
                                                        if ($data['absen'] > $data['absen_maks']) {
                                                            $status = "Terlalu Cepat";
                                                            $warna = "red";
                                                            $cepatIstirahatSelesai = $cepatIstirahatSelesai + $selisih;
                                                        } else {
                                                            $status = "Tepat Waktu";
                                                            $warna = "green";
                                                            $tepatIstirahatSelesai = $tepatIstirahatSelesai + $selisih;
                                                        }
                                                    } elseif ($data['kategori'] == 4) {
                                                        $jumlahPulang = $jumlahPulang + 1; //hitung jumlah data absen pulang
                                                        array_push($tanggal4, date('Y-m-d', strtotime($data['absen_maks'])));
                                                        $kategori = "Pulang";
                                                        if ($data['absen'] > $data['absen_maks']) {
                                                            $status = "Tepat Waktu";
                                                            $warna = "green";
                                                            $tepatPulang = $tepatPulang + $selisih;
                                                        } else {
                                                            $status = "Terlalu Cepat";
                                                            $warna = "red";
                                                            $cepatPulang = $cepatPulang + $selisih;
                                                        }
                                                    }

                                                    //menghindari program error karena tag tidak terdaftar
                                                    if ($data['nomor_induk'] == "") {
                                                        continue;
                                                    }

                                                    $cabang = getAnyTampil($mysqli, "lokasi", "cabang_gedung", "id", getAnyTampil($mysqli, "cabang_gedung", "pengguna", "nomor_induk", $data['nomor_induk']));
                                                    $zona = getAnyTampil($mysqli, "zona_waktu", "cabang_gedung", "id", getAnyTampil($mysqli, "cabang_gedung", "pengguna", "nomor_induk", $data['nomor_induk']));

                                                    if ($zona == 1) {
                                                        $seconds = "+25200 seconds";
                                                        $zonaWaktu = "WIB";
                                                    } else if ($zona == 2) {
                                                        $seconds = "+28800 seconds";
                                                        $zonaWaktu = "WITA";
                                                    } else {
                                                        $seconds = "+32400 seconds";
                                                        $zonaWaktu = "WIT";
                                                    }

                                                    $startTime = date($data['absen']);
                                                ?>
                                                    <tr>
                                                        <td style="color: <?php echo $warna ?>;"><?php echo date('Y-m-d H:i:s', strtotime($seconds, strtotime($startTime))) . " " . $zonaWaktu ?></td>
                                                        <td><?php echo date('Y-m-d H:i:s', strtotime($seconds, strtotime($data['absen_maks']))) ?></td>
                                                        <td style="color: <?php echo $warna ?>;"><?php echo $selisih ?> Menit</td>
                                                        <td style="color: <?php echo $warna ?>;"><?php echo $status ?></td>
                                                        <td><?php echo $cabang ?></td>
                                                        <td><?php echo $kategori ?></td>
                                                        <td><?php echo $data['idmesin'] ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Absen</th>
                                                    <th>Batas</th>
                                                    <th>Selisih</th>
                                                    <th>Status</th>
                                                    <th>Cabang / Gedung</th>
                                                    <th>Kategori</th>
                                                    <th>ID Mesin</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-sign-in-alt"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Masuk</span>
                                        <span class="info-box-number"><?php echo $terlambatMasuk ?><small> Menit</small>
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-utensils"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Mulai Istirahat</span>
                                        <span class="info-box-number"><?php echo $cepatIstirahatMulai ?><small> Menit</small>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <!-- fix for small devices only -->
                            <div class="clearfix hidden-md-up"></div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-laptop-house"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Selesai Istirahat</span>
                                        <span class="info-box-number"><?php echo $cepatIstirahatSelesai ?><small> Menit</small>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-sign-out-alt"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Pulang</span>
                                        <span class="info-box-number"><?php echo $cepatPulang ?><small> Menit</small>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fa fa-sign-in-alt"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Masuk</span>
                                        <span class="info-box-number"><?php echo $tepatMasuk ?><small> Menit</small>
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-utensils"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Mulai Istirahat</span>
                                        <span class="info-box-number"><?php echo $tepatIstirahatMulai ?><small> Menit</small>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->

                            <!-- fix for small devices only -->
                            <div class="clearfix hidden-md-up"></div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-laptop-house"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Selesai Istirahat</span>
                                        <span class="info-box-number"><?php echo $tepatIstirahatSelesai ?><small> Menit</small>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-sign-out-alt"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Pulang</span>
                                        <span class="info-box-number"><?php echo $tepatPulang ?><small> Menit</small>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <?php

                            //====cek tanggal tidak absen===//
                            //absen masuk
                            for ($i = 0; $i < $jumlahMasuk; $i++) {
                                if (($key = array_search($tanggal1[$i], $tanpaAbsenMasuk)) !== false) {
                                    unset($tanpaAbsenMasuk[$key]);
                                }
                            }
                            $tanpaAbsenMasuk = array_values($tanpaAbsenMasuk); //agar nomor index benar

                            //absen mulai istirahat
                            for ($i = 0; $i < $jumlahMulai; $i++) {
                                if (($key = array_search($tanggal2[$i], $tanpaAbsenMulai)) !== false) {
                                    unset($tanpaAbsenMulai[$key]);
                                }
                                $tanpaAbsenMulai = array_values($tanpaAbsenMulai); //agar nomor index benar
                            }

                            //absen selesai istirahat
                            for ($i = 0; $i < $jumlahSelesai; $i++) {
                                if (($key = array_search($tanggal3[$i], $tanpaAbsenSelesai)) !== false) {
                                    unset($tanpaAbsenSelesai[$key]);
                                }
                                $tanpaAbsenSelesai = array_values($tanpaAbsenSelesai); //agar nomor index benar
                            }

                            //absen pulang
                            for ($i = 0; $i < $jumlahPulang; $i++) {
                                if (($key = array_search($tanggal4[$i], $tanpaAbsenPulang)) !== false) {
                                    unset($tanpaAbsenPulang[$key]);
                                }
                                $tanpaAbsenPulang = array_values($tanpaAbsenPulang); //agar nomor index benar
                            }
                            //====end=====//
                            ?>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="card">
                                    <div class="card-header border-transparent">
                                        <h3 class="card-title">Tanpa Absen Masuk</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table m-0">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($hasilMasuk = 0; $hasilMasuk < count($tanpaAbsenMasuk); $hasilMasuk++) {
                                                        //====Default tanpa absen====//
                                                        $keterangan = "Tidak Absen";
                                                        $warna = "black";
                                                        //====End====//
                                                        //set cuti
                                                        for ($k = 0; $k < count($cuti); $k++) {
                                                            if ($tanpaAbsenMasuk[$hasilMasuk] == $cuti[$k]) {
                                                                $keterangan = "Cuti";
                                                                $warna = "green";
                                                            }
                                                        }

                                                        //set jika hari libur
                                                        if (countRow($mysqli, "libur_khusus", "tanggal", $tanpaAbsenMasuk[$hasilMasuk]) > 0) { //libur khusus
                                                            //$keterangan = getAnyTampil($mysqli, "keterangan", "libur_khusus", "tanggal", $tanpaAbsenPulang[$i]);
                                                            $keterangan = "Libur";
                                                            $warna = "green";
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td style="color: <?php echo $warna ?>;"><?php echo $tanpaAbsenMasuk[$hasilMasuk]; ?></a></td>
                                                            <td style="color: <?php echo $warna ?>;"><?php echo $keterangan; ?></td>
                                                        </tr>
                                                    <?php  } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="card">
                                    <div class="card-header border-transparent">
                                        <h3 class="card-title">Tanpa Absen Mulai Istirahat</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table m-0">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($i = 0; $i < count($tanpaAbsenMulai); $i++) {
                                                        //====Default tanpa absen====//
                                                        $keterangan = "Tidak Absen";
                                                        $warna = "black";
                                                        //====End====//
                                                        //set cuti
                                                        for ($k = 0; $k < count($cuti); $k++) {
                                                            if ($tanpaAbsenMulai[$i] == $cuti[$k]) {
                                                                $keterangan = "Cuti";
                                                                $warna = "green";
                                                            }
                                                        }

                                                        //set jika hari libur
                                                        if (countRow($mysqli, "libur_khusus", "tanggal", $tanpaAbsenMulai[$i]) > 0) { //libur khusus
                                                            //$keterangan = getAnyTampil($mysqli, "keterangan", "libur_khusus", "tanggal", $tanpaAbsenPulang[$i]);
                                                            $keterangan = "Libur";
                                                            $warna = "green";
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td style="color: <?php echo $warna ?>;"><?php echo $tanpaAbsenMulai[$i]; ?></a></td>
                                                            <td style="color: <?php echo $warna ?>;"><?php echo $keterangan; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="card">
                                    <div class="card-header border-transparent">
                                        <h3 class="card-title">Tanpa Absen Selesai Istirahat</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table m-0">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($i = 0; $i < count($tanpaAbsenSelesai); $i++) {
                                                        //====Default tanpa absen====//
                                                        $keterangan = "Tidak Absen";
                                                        $warna = "black";
                                                        //====End====//

                                                        //set cuti
                                                        for ($k = 0; $k < count($cuti); $k++) {
                                                            if ($tanpaAbsenSelesai[$i] == $cuti[$k]) {
                                                                $keterangan = "Cuti";
                                                                $warna = "green";
                                                            }
                                                        }

                                                        //set jika hari libur
                                                        if (countRow($mysqli, "libur_khusus", "tanggal", $tanpaAbsenSelesai[$i]) > 0) { //libur khusus
                                                            //$keterangan = getAnyTampil($mysqli, "keterangan", "libur_khusus", "tanggal", $tanpaAbsenPulang[$i]);
                                                            $keterangan = "Libur";
                                                            $warna = "green";
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td style="color: <?php echo $warna ?>;"><?php echo $tanpaAbsenSelesai[$i]; ?></a></td>
                                                            <td style="color: <?php echo $warna ?>;"><?php echo $keterangan; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="card">
                                    <div class="card-header border-transparent">
                                        <h3 class="card-title">Tanpa Absen Pulang</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table m-0">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($i = 0; $i < count($tanpaAbsenPulang); $i++) {
                                                        //====Default tanpa absen====//
                                                        $keterangan = "Tidak Absen";
                                                        $warna = "black";
                                                        //====End====//

                                                        //set cuti
                                                        for ($k = 0; $k < count($cuti); $k++) {
                                                            if ($tanpaAbsenPulang[$i] == $cuti[$k]) {
                                                                $keterangan = "Cuti";
                                                                $warna = "green";
                                                            }
                                                        }

                                                        //set jika hari libur
                                                        if (countRow($mysqli, "libur_khusus", "tanggal", $tanpaAbsenPulang[$i]) > 0) { //libur khusus
                                                            //$keterangan = getAnyTampil($mysqli, "keterangan", "libur_khusus", "tanggal", $tanpaAbsenPulang[$i]);
                                                            $keterangan = "Libur";
                                                            $warna = "green";
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td style="color: <?php echo $warna ?>;"><?php echo $tanpaAbsenPulang[$i]; ?></a></td>
                                                            <td style="color: <?php echo $warna ?>;"><?php echo $keterangan; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                    <!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <script src="../../plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="../../plugins/jszip/jszip.min.js"></script>
        <script src="../../plugins/pdfmake/pdfmake.min.js"></script>
        <script src="../../plugins/pdfmake/vfs_fonts.js"></script>
        <script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../../dist/js/adminlte.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../../dist/js/demo.js"></script>
        <!-- Page specific script -->
        <script>
            $(function() {
                $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": true,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            })
        </script>
    </body>

    </html>
<?php } ?>