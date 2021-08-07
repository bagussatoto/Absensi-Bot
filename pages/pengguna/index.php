<?php
require_once("../../etc/config.php");
require_once("../../etc/function.php");

if (isset($_POST['tambah'])) {
  $pass = md5($_POST['nomor_induk']);
  $result = mysqli_query($mysqli, "INSERT INTO pengguna(nomor_induk,nama,tag,jabatan_status,cabang_gedung,password) VALUES('{$_POST['nomor_induk']}','{$_POST['nama']}','{$_POST['tag']}','{$_POST['jabatan_status']}','{$_POST['cabang_gedung']}','$pass')");
  $successAdd = 1;
}

if (isset($_POST['ubah'])) {
  $result = mysqli_query($mysqli, "UPDATE pengguna SET nomor_induk='{$_POST['nomor_induk']}', nama='{$_POST['nama']}',tag='{$_POST['tag']}',jabatan_status='{$_POST['jabatan_status']}',cabang_gedung='{$_POST['cabang_gedung']}' WHERE nomor_induk='{$_POST['nomor_induk_lama']}'");
  $successEdit = 1;
}

if (isset($_GET['id'])) {
  $nama = getAnyTampil($mysqli, 'nama', 'pengguna', 'nomor_induk', $_GET['id']);
  $aktif = getAnyTampil($mysqli, 'aktif', 'pengguna', 'nomor_induk', $_GET['id']);
  if ($aktif == 1) {
    $aktif = 0;
    $aktifText = "non-aktif";
  } else {
    $aktif = 1;
    $aktifText = "aktif";
  }
  $result = mysqli_query($mysqli, "UPDATE pengguna SET aktif='$aktif' WHERE nomor_induk='{$_GET['id']}'");
  $successDelete = 1;
}

$result = mysqli_query($mysqli, "SELECT * FROM pengguna");
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
              <h1>Pengguna</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
        <?php if ($successAdd == 1) { ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
            <b><?php echo $_POST['nama'] ?></b> sudah ditambahkan sebagai pengguna baru.
          </div>
        <?php } else if ($successEdit == 1) { ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
            <b><?php echo $_POST['nama_lama'] ?></b> sudah berhasil diubah.
          </div>
        <?php } else if ($successDelete == 1) { ?>
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-exclamation"></i> Berhasil!</h5>
            Status <b><?php echo $nama ?></b> menjadi pengguna <b><?php echo $aktifText ?></b>.
          </div>
        <?php } ?>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <a href="../../scan.php" target="_blank" type="button" class="btn btn-block btn-outline-primary">Cek Pengguna (Scan Tag / Kartu)</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Nomor Induk</th>
                        <th>Nama</th>
                        <th>Jabatan / Status</th>
                        <th>Cabang / Gedung</th>
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

                        if ($data['nomor_induk'] == 0) {
                          continue;
                        }
                      ?>
                        <tr>
                          <td><?php echo $data['nomor_induk'] ?></td>
                          <td><a href="../absensi/pengguna.php?nomor_induk=<?php echo $data['nomor_induk'] ?>"><?php echo $data['nama'] ?></a></td>
                          <td><?php echo getAnyTampil($mysqli, "jabatan_status", "jabatan_status", "id", $data['jabatan_status']) ?></td>
                          <td><?php echo getAnyTampil($mysqli, "lokasi", "cabang_gedung", "id", $data['cabang_gedung']) ?></td>
                          <td><?php echo $aktif ?></td>
                          <td><a href="edit.php?id=<?= $data['nomor_induk'] ?>"><i class="fas fa-edit"></i></a> | <a href="index.php?id=<?= $data['nomor_induk'] ?>"><i class="fas fa-minus-circle"></i></a></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Nomor Induk</th>
                        <th>Nama</th>
                        <th>Jabatan / Status</th>
                        <th>Cabang / Gedung</th>
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
                      <label>Nomor Induk</label>
                      <input type="text" class="form-control" name="nomor_induk" placeholder="Nomor Induk / Identitas Pengguna" autofocus required>
                    </div>
                    <div class="form-group">
                      <label>Nama</label>
                      <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="form-group">
                      <label>Jabatan / Status</label>
                      <?php comboBoxSelect($mysqli, 'jabatan_status', 'id', 'jabatan_status', 'jabatan_status', 1) ?>
                    </div>
                    <div class="form-group">
                      <label>Cabang / Gedung</label>
                      <?php comboBoxSelect($mysqli, 'cabang_gedung', 'id', 'lokasi', 'cabang_gedung', 0) ?>
                    </div>
                    <div class="form-group">
                      <label>Tag</label>
                      <input type="text" name="tag" placeholder="Scan Tag RFID" class="form-control" required>
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