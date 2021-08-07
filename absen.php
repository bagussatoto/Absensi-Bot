<?php
require_once("etc/config.php");
$id = 1;
if (isset($_POST['tag'])) {
    $result =  mysqli_fetch_row(mysqli_query($mysqli, "SELECT nomor_induk, nama FROM pengguna where tag = '{$_POST['tag']}'"));
    $nama = $result[1] . " (" . $result[0] . ")";

    $id = $_POST['cabang_gedung'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Tag | Nusabot</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition lockscreen" onload="display_ct();">
    <!-- Automatic element centering -->
    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
            <b id="ct"></b> <?php echo $nama_zona ?>
        </div>
        <!-- User name -->
        <div class="lockscreen-name"><?php echo $nama ?></div>

        <!-- START LOCK SCREEN ITEM -->
        <form method="post" action="">
            <div class="lockscreen-item">
                <!-- lockscreen image -->
                <div class="lockscreen-image">
                    <img src="dist/img/logo-me.png" alt="User Image">
                </div>
                <!-- /.lockscreen-image -->

                <!-- lockscreen credentials (contains the form) -->

                <div class="lockscreen-credentials">
                    <div class="input-group">
                        <input type="password" name="tag" class="form-control" placeholder="Scan Tag Anda" required autofocus>

                        <div class="input-group-append">
                            <button type="button" class="btn">
                                <i class="fas fa-arrow-right text-muted"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- /.lockscreen credentials -->

            </div>
            <div class="text-center">
                <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/deed.id" target="_blank"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a>
            </div>
        </form>
    </div>
    <!-- /.center -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
<script type="text/javascript">
    function display_c() {
        var refresh = 1000; // Refresh rate in milli seconds
        mytime = setTimeout('display_ct()', refresh)
    }

    function display_ct() {
        var x = new Date();
        var weekdays = new Array(7);
        weekdays[0] = "Minggu";
        weekdays[1] = "Senin";
        weekdays[2] = "Selasa";
        weekdays[3] = "Rabu";
        weekdays[4] = "Kamis";
        weekdays[5] = "Jumat";
        weekdays[6] = "Sabtu";

        var x1 = x.getMonth() + 1 + "/" + x.getDate() + "/" + x.getFullYear();
        x1 = weekdays[x.getDay()] + ", " + x1 + "   " + x.getHours() + ":" + x.getMinutes() + ":" + x.getSeconds();
        document.getElementById('ct').innerHTML = x1;
        display_c();
    }
</script>

</html>