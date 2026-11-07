<?php
require_once("../../etc/config.php");
require_once("../../etc/function.php");
require_once("../../session.php");

if (isset($_POST['ubah'])) {
    $nama = trim($_POST['nama'] ?? '');
    $password_input = trim($_POST['password'] ?? '');

    if ($password_input !== '') {
        $pass = password_hash($password_input, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($mysqli, "UPDATE pengguna SET nama = ?, password = ? WHERE nomor_induk = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $nama, $pass, $_SESSION['nomor_induk']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['nama'] = $nama;
            $successEdit = 1;
        }
    } else {
        $stmt = mysqli_prepare($mysqli, "UPDATE pengguna SET nama = ? WHERE nomor_induk = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $nama, $_SESSION['nomor_induk']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['nama'] = $nama;
            $successEdit = 1;
        }
    }
}

$stmt = mysqli_prepare($mysqli, "SELECT nomor_induk, nama, tag, jabatan_status, cabang_gedung FROM pengguna WHERE nomor_induk = ? LIMIT 1");
$result = null;
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $_SESSION['nomor_induk']);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_row($res);
    mysqli_stmt_close($stmt);
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

<body class="">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Profil Pengguna</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post" action="index.php">
                            <input type="hidden" name="id" value="<?= $result[0] ?>">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nomor Induk</label>
                                    <input type="text" class="form-control" disabled="disabled" value="<?= $result[0] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" value="<?= $result[1] ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" name="ubah" class="btn btn-primary">Ubah</button>
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
        });
    </script>
</body>

</html>