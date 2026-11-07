<?php
require_once("etc/config.php");
require_once("etc/function.php");

$nama = "";
if (isset($_POST['tag'])) {
    $tag = trim($_POST['tag'] ?? '');
    $stmt = mysqli_prepare($mysqli, "SELECT nomor_induk, nama FROM pengguna WHERE tag = ? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $tag);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $nama = $row['nama'] . " (" . $row['nomor_induk'] . ")";
        } else {
            $nama = "Tag tidak terdaftar";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Tag | Mencari Name</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition lockscreen">
    <!-- Automatic element centering -->
    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
            <b>Tempelkan</b> Kartu / Tag
        </div>
        <!-- User name -->
        <div class="lockscreen-name"><?php echo e($nama) ?></div>

        

            <!-- lockscreen credentials (contains the form) -->
            <form class="lockscreen-credentials" method="post" action="">
                <div class="input-group">
                    <input type="password" name="tag" class="form-control" placeholder="Tag" required autofocus>

                    <div class="input-group-append">
                        <button type="button" class="btn">
                            <i class="fas fa-arrow-right text-muted"></i>
                        </button>
                    </div>
                </div>
            </form>
            <!-- /.lockscreen credentials -->

        </div>
        <!-- /.lockscreen-item -->
    </div>
    <!-- /.center -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>