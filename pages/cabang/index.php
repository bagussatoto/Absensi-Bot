<?php
require_once("../../etc/config.php");
require_once("../../etc/function.php");
require_once("../../session.php");

if (isset($_POST['tambah'])) {
  $zona_waktu = (int)($_POST['zona_waktu'] ?? 1);
  if ($zona_waktu == 1) {
    $seconds = "-25200 seconds";
  } else if ($zona_waktu == 2) {
    $seconds = "-28800 seconds";
  } else {
    $seconds = "-32400 seconds";
  }

  $jam_masuk = date('H:i:s', strtotime($seconds, strtotime($_POST['jam_masuk'] ?? '08:00:00')));
  $istirahat_mulai = date('H:i:s', strtotime($seconds, strtotime($_POST['istirahat_mulai'] ?? '12:00:00')));
  $istirahat_selesai = date('H:i:s', strtotime($seconds, strtotime($_POST['istirahat_selesai'] ?? '13:00:00')));
  $jam_pulang = date('H:i:s', strtotime($seconds, strtotime($_POST['jam_pulang'] ?? '17:00:00')));

  $hari_libur = is_array($_POST['hari_libur'] ?? null) ? $_POST['hari_libur'] : [];
  $pilihan_kerja = implode(',', array_map('intval', $hari_libur));
  $lokasi = trim($_POST['lokasi'] ?? '');

  $stmt = mysqli_prepare($mysqli, "INSERT INTO cabang_gedung(lokasi, jam_masuk, jam_pulang, istirahat_mulai, istirahat_selesai, hari_libur, zona_waktu) VALUES(?, ?, ?, ?, ?, ?, ?)");
  if ($stmt) {
    $zona_str = (string)$zona_waktu;
    mysqli_stmt_bind_param($stmt, "sssssss", $lokasi, $jam_masuk, $jam_pulang, $istirahat_mulai, $istirahat_selesai, $pilihan_kerja, $zona_str);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $successAdd = 1;
  }
}

if (isset($_POST['ubah'])) {
  $zona_waktu = (int)($_POST['zona_waktu'] ?? 1);
  if ($zona_waktu == 1) {
    $seconds = "-25200 seconds";
  } else if ($zona_waktu == 2) {
    $seconds = "-28800 seconds";
  } else {
    $seconds = "-32400 seconds";
  }

  $jam_masuk = date('H:i:s', strtotime($seconds, strtotime($_POST['jam_masuk'] ?? '08:00:00')));
  $istirahat_mulai = date('H:i:s', strtotime($seconds, strtotime($_POST['istirahat_mulai'] ?? '12:00:00')));
  $istirahat_selesai = date('H:i:s', strtotime($seconds, strtotime($_POST['istirahat_selesai'] ?? '13:00:00')));
  $jam_pulang = date('H:i:s', strtotime($seconds, strtotime($_POST['jam_pulang'] ?? '17:00:00')));

  $hari_libur = is_array($_POST['hari_libur'] ?? null) ? $_POST['hari_libur'] : [];
  $pilihan_kerja = implode(',', array_map('intval', $hari_libur));
  $lokasi = trim($_POST['lokasi'] ?? '');
  $id = (int)($_POST['id'] ?? 0);

  $stmt = mysqli_prepare($mysqli, "UPDATE cabang_gedung SET lokasi = ?, jam_masuk = ?, jam_pulang = ?, istirahat_mulai = ?, istirahat_selesai = ?, hari_libur = ?, zona_waktu = ? WHERE id = ?");
  if ($stmt) {
    $zona_str = (string)$zona_waktu;
    mysqli_stmt_bind_param($stmt, "sssssssi", $lokasi, $jam_masuk, $jam_pulang, $istirahat_mulai, $istirahat_selesai, $pilihan_kerja, $zona_str, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $successEdit = 1;
  }
}

if (isset($_GET['id'])) {
  $id_target = (int)$_GET['id'];
  $nama = getAnyTampil($mysqli, 'lokasi', 'cabang_gedung', 'id', $id_target);
  $aktif = getAnyTampil($mysqli, 'aktif', 'cabang_gedung', 'id', $id_target);
  if ($aktif == 1) {
    $aktif = 0;
    $aktifText = "non-aktif";
  } else {
    $aktif = 1;
    $aktifText = "aktif";
  }
  $stmt = mysqli_prepare($mysqli, "UPDATE cabang_gedung SET aktif = ? WHERE id = ?");
  if ($stmt) {
    $aktif_str = (string)$aktif;
    mysqli_stmt_bind_param($stmt, "si", $aktif_str, $id_target);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $successDelete = 1;
  }
}

$result = mysqli_query($mysqli, "SELECT * FROM cabang_gedung");
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
              <h1>Cabang / Gedung</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
        <?php if ($successAdd == 1) { ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
            <b><?php echo $_POST['lokasi'] ?></b> sudah ditambahkan.
          </div>
        <?php } else if ($successEdit == 1) { ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
            <b><?php echo $_POST['lokasi_lama'] ?></b> sudah berhasil diubah.
          </div>
        <?php } else if ($successDelete == 1) { ?>
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-exclamation"></i> Berhasil!</h5>
            Status <b><?php echo $nama ?></b> menjadi <b><?php echo $aktifText ?></b>.
          </div>
        <?php } ?>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Lokasi</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Mulai Istirahat</th>
                        <th>Selesai Istirahat</th>
                        <th>Hari Libur</th>
                        <th>Zona Waktu</th>
                        <th>Aktif</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php while ($data = mysqli_fetch_array($result)) {
                        if ($data['aktif'] == 1) {
                          $aktif = "Aktif";
                        } else {
                          $aktif = "Non-aktif";
                        }

                        if ($data['id'] == 0) {
                          continue;
                        }

                        if ($data['zona_waktu'] == 1) {
                          $zona = "WIB";
                          $seconds = "+25200 seconds";
                        } elseif ($data['zona_waktu'] == 2) {
                          $zona = "WITA";
                          $seconds = "+28800 seconds";
                        } elseif ($data['zona_waktu'] == 3) {
                          $zona = "WIT";
                          $seconds = "+32400 seconds";
                        }

                        $jam_masuk = date('H:i:s', strtotime($seconds, strtotime(date($data['jam_masuk']))));
                        $istirahat_mulai = date('H:i:s', strtotime($seconds, strtotime(date($data['istirahat_mulai']))));
                        $istirahat_selesai = date('H:i:s', strtotime($seconds, strtotime(date($data['istirahat_selesai']))));
                        $jam_pulang = date('H:i:s', strtotime($seconds, strtotime(date($data['jam_pulang']))));
                      ?>
                        <tr>
                          <td><?php echo $data['lokasi'] ?></td>
                          <td><?php echo $jam_masuk ?></td>
                          <td><?php echo $jam_pulang ?></td>
                          <td><?php echo $istirahat_mulai ?></td>
                          <td><?php echo $istirahat_selesai ?></td>
                          <td><?php echo $data['hari_libur'] ?></td>
                          <td><?php echo $zona ?></td>
                          <td><?php echo $aktif ?></td>
                          <td><a href="edit.php?id=<?= $data['id'] ?>"><i class="fas fa-edit"></i></a> | <a href="index.php?id=<?= $data['id'] ?>"><i class="fas fa-minus-circle"></i></a></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Lokasi</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Mulai Istirahat</th>
                        <th>Selesai Istirahat</th>
                        <th>Hari Libur</th>
                        <th>Zona Waktu</th>
                        <th>Aktif</th>
                        <th>Aksi</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>

              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Data Baru</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="post" action="index.php">
                  <div class="card-body">
                    <div class="form-group">
                      <label>Lokasi</label>
                      <input type="text" name="lokasi" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label>Jam Masuk</label>
                      <input type="time" name="jam_masuk" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label>Jam Pulang</label>
                      <input type="time" name="jam_pulang" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label>Mulai Istirahat</label>
                      <input type="time" name="istirahat_mulai" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label>Selesai Istirahat</label>
                      <input type="time" name="istirahat_selesai" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label>Hari Libur</label>
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input custom-control-input-danger" type="checkbox" id="minggu" value="0" name="hari_libur[]">
                        <label for="minggu" class="custom-control-label">Minggu</label>
                      </div>
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input custom-control-input-danger" type="checkbox" id="senin" value="1" name="hari_libur[]">
                        <label for="senin" class="custom-control-label">Senin</label>
                      </div>
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input custom-control-input-danger" type="checkbox" id="selasa" value="2" name="hari_libur[]">
                        <label for="selasa" class="custom-control-label">Selasa</label>
                      </div>
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input custom-control-input-danger" type="checkbox" id="rabu" value="3" name="hari_libur[]">
                        <label for="rabu" class="custom-control-label">Rabu</label>
                      </div>
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input custom-control-input-danger" type="checkbox" id="kamis" value="4" name="hari_libur[]">
                        <label for="kamis" class="custom-control-label">Kamis</label>
                      </div>
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input custom-control-input-danger" type="checkbox" id="jumat" value="5" name="hari_libur[]">
                        <label for="jumat" class="custom-control-label">Jumat</label>
                      </div>
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input custom-control-input-danger" type="checkbox" id="sabtu" value="6" name="hari_libur[]">
                        <label for="sabtu" class="custom-control-label">Sabtu</label>
                      </div>
                    </div>
                    <div class="form-group">
                      <label>Zona Waktu</label>
                      <select class="custom-select" name="zona_waktu">
                        <option value="1">WIB</option>
                        <option value="2">WITA</option>
                        <option value="3">WIT</option>
                      </select>
                    </div>
                  </div>
                  <!-- /.card-body -->

                  <div class="card-footer">
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
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