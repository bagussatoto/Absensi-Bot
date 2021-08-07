<?php
require_once("../../etc/config.php");
require_once("../../etc/function.php");

$result =  mysqli_fetch_row(mysqli_query($mysqli, "SELECT * FROM cabang_gedung where id = '{$_GET['id']}'"));

if ($result[7] == 1) {
    $zona = "WIB";
    $seconds = "+25200 seconds";
} elseif ($result[7] == 2) {
    $zona = "WITA";
    $seconds = "+28800 seconds";
} elseif ($result[7] == 3) {
    $zona = "WIT";
    $seconds = "+32400 seconds";
}

$jam_masuk = date('H:i:s', strtotime($seconds, strtotime(date($result[2]))));
$istirahat_mulai = date('H:i:s', strtotime($seconds, strtotime(date($result[4]))));
$istirahat_selesai = date('H:i:s', strtotime($seconds, strtotime(date($result[5]))));
$jam_pulang = date('H:i:s', strtotime($seconds, strtotime(date($result[3]))));
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
                            <a href="../cabang" class="btn btn-app">
                                <i class="fas fa-arrow-left"></i> Batal
                            </a>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Ubah Cabang / Gedung</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post" action="index.php">
                                    <input type="hidden" name="id" value="<?= $result[0] ?>">
                                    <input type="hidden" name="lokasi_lama" value="<?= $result[1] ?>">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Lokasi</label>
                                            <input type="text" class="form-control" name="lokasi" value="<?= $result[1] ?>" autofocus required>
                                        </div>
                                        <div class="form-group">
                                            <label>Jam Masuk</label>
                                            <input type="time" class="form-control" name="jam_masuk" value="<?= $jam_masuk ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Jam Pulang</label>
                                            <input type="time" class="form-control" name="jam_pulang" value="<?= $jam_pulang ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Mulai Istirahat</label>
                                            <input type="time" class="form-control" name="istirahat_mulai" value="<?= $istirahat_mulai ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Selesai Istirahat</label>
                                            <input type="time" class="form-control" name="istirahat_selesai" value="<?= $istirahat_selesai ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Hari Libur</label>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input custom-control-input-danger" type="checkbox" id="minggu" value="0" name="hari_libur[]" <?php if (strstr($result[6], '0')) echo "checked"; ?>>
                                                <label for="minggu" class="custom-control-label">Minggu</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input custom-control-input-danger" type="checkbox" id="senin" value="1" name="hari_libur[]" <?php if (strstr($result[6], '1')) echo "checked"; ?>>
                                                <label for="senin" class="custom-control-label">Senin</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input custom-control-input-danger" type="checkbox" id="selasa" value="2" name="hari_libur[]" <?php if (strstr($result[6], '2')) echo "checked"; ?>>
                                                <label for="selasa" class="custom-control-label">Selasa</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input custom-control-input-danger" type="checkbox" id="rabu" value="3" name="hari_libur[]" <?php if (strstr($result[6], '3')) echo "checked"; ?>>
                                                <label for="rabu" class="custom-control-label">Rabu</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input custom-control-input-danger" type="checkbox" id="kamis" value="4" name="hari_libur[]" <?php if (strstr($result[6], '4')) echo "checked"; ?>>
                                                <label for="kamis" class="custom-control-label">Kamis</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input custom-control-input-danger" type="checkbox" id="jumat" value="5" name="hari_libur[]" <?php if (strstr($result[6], '5')) echo "checked"; ?>>
                                                <label for="jumat" class="custom-control-label">Jumat</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input custom-control-input-danger" type="checkbox" id="sabtu" value="6" name="hari_libur[]" <?php if (strstr($result[6], '6')) echo "checked"; ?>>
                                                <label for="sabtu" class="custom-control-label">Sabtu</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Zona Waktu</label>
                                            <select class="custom-select" name="zona_waktu">
                                                <option value="<?php echo $result[7] ?>"><?php echo $zona ?></option>
                                                <?php if ($result[7] == 2 || $result[7] == 3) echo "<option value='1'>WIB</option>" ?>
                                                <?php if ($result[7] == 1 || $result[7] == 3) echo "<option value='2'>WITA</option>" ?>
                                                <?php if ($result[7] == 2 || $result[7] == 1) echo "<option value='3'>WIT</option>" ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" name="ubah" class="btn btn-warning">Ubah</button>
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
        });
    </script>
</body>

</html>