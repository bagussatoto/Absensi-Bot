<?php
require_once("../../etc/config.php");
require_once("../../etc/function.php");

if (isset($_POST['tambah'])) {
  $result = mysqli_query($mysqli, "INSERT INTO cuti(nomor_induk,tanggal) VALUES('{$_POST['nomor_induk']}','{$_POST['tanggal']}')");
  $successAdd = 1;
}

if (isset($_POST['ubah'])) {
  $result = mysqli_query($mysqli, "UPDATE cuti SET nomor_induk='{$_POST['nomor_induk']}',tanggal='{$_POST['tanggal']}' WHERE id='{$_POST['id']}'");
  $successEdit = 1;
}

if (isset($_GET['id'])) {
  $nama = getAnyTampil($mysqli, 'nomor_induk', 'cuti', 'id', $_GET['id']);
  $tanggal = getAnyTampil($mysqli, 'tanggal', 'cuti', 'id', $_GET['id']);

  $result = mysqli_query($mysqli, "DELETE FROM cuti WHERE id='{$_GET['id']}'");
  $successDelete = 1;
}

$result = mysqli_query($mysqli, "SELECT * FROM cuti limit 500");
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
              <h1>Cuti</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
        <?php if ($successAdd == 1) { ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
            <b><?php echo getAnyTampil($mysqli, 'nama', 'pengguna', 'nomor_induk', $_POST['nomor_induk']) ?></b> sudah ditambahkan ke daftar cuti.
          </div>
        <?php } else if ($successEdit == 1) { ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
            Nomor Induk <b><?php echo $_POST['nomor_induk_lama'] ?></b> sudah berhasil diubah.
          </div>
        <?php } else if ($successDelete == 1) { ?>
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-exclamation"></i> Berhasil!</h5>
            Nomor induk <b><?php echo $nama ?></b> telah membatalkan cuti tanggal <b><?php echo $tanggal ?></b>.
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
                        <th>Nomor Induk</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
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
                      ?>
                        <tr>
                          <td><?php echo $data['nomor_induk'] ?></td>
                          <td><?php echo getAnyTampil($mysqli, 'nama', 'pengguna', 'nomor_induk', $data['nomor_induk']) ?></td>
                          <td><?php echo $data['tanggal'] ?></td>
                          <td><a href="edit.php?id=<?= $data['id'] ?>"><i class="fas fa-edit"></i></a> | <a href="index.php?id=<?= $data['id'] ?>"><i class="fas fa-minus-circle"></i></a></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Nomor Induk</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
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
                      <input type="text" name="nomor_induk" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label>tanggal</label>
                      <input type="date" name="tanggal" class="form-control" required>
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