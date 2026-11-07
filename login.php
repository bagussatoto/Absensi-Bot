<?php
require_once("etc/config.php");
require_once("etc/function.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika sudah login, langsung alihkan ke index.php
if (isset($_SESSION['login']) && $_SESSION['login'] === 1) {
    header("Location: index.php");
    exit();
}

$error = "Masukan nomor induk dan password";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $nomor_induk = trim($_POST['nomor_induk'] ?? '');
    $password_input = $_POST['password'] ?? '';

    if ($nomor_induk === '' || $password_input === '') {
        $error = "Nomor Induk dan Password wajib diisi";
    } else {
        // Query menggunakan Prepared Statement untuk mencegah SQL Injection
        $sql = "SELECT nomor_induk, nama, password, aktif FROM pengguna WHERE nomor_induk = ? LIMIT 1";
        $stmt = mysqli_prepare($mysqli, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $nomor_induk);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                // Periksa apakah pengguna berstatus aktif
                if (isset($row['aktif']) && $row['aktif'] == 0) {
                    $error = "Akun Anda telah dinonaktifkan. Hubungi administrator.";
                } else {
                    $hashed_password = $row['password'];
                    $password_valid = false;

                    // 1. Cek kecocokan password_hash (bcrypt / standar modern PHP)
                    if (password_verify($password_input, $hashed_password)) {
                        $password_valid = true;
                    }
                    // 2. Fallback untuk data lawas yang masih MD5 (Backward Compatibility)
                    else if (md5($password_input) === $hashed_password) {
                        $password_valid = true;

                        // Otomatis re-hash password ke algoritma modern yang aman
                        $new_secure_hash = password_hash($password_input, PASSWORD_DEFAULT);
                        $rehash_stmt = mysqli_prepare($mysqli, "UPDATE pengguna SET password = ? WHERE nomor_induk = ?");
                        if ($rehash_stmt) {
                            mysqli_stmt_bind_param($rehash_stmt, "ss", $new_secure_hash, $nomor_induk);
                            mysqli_stmt_execute($rehash_stmt);
                            mysqli_stmt_close($rehash_stmt);
                        }
                    }

                    if ($password_valid) {
                        session_regenerate_id(true); // Cegah Session Fixation
                        $_SESSION['nomor_induk'] = $row['nomor_induk'];
                        $_SESSION['nama'] = $row['nama'];
                        $_SESSION['login'] = 1;

                        header("Location: index.php");
                        exit();
                    } else {
                        $error = "Nomor Induk / Password Salah";
                    }
                }
            } else {
                $error = "Nomor Induk / Password Salah";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Terjadi gangguan sistem. Silakan coba beberapa saat lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Absensi | Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="#" class="h1"><b>Absensi</b></a>
      </div>
      <div class="card-body">
        <p class="login-box-msg"><?php echo e($error); ?></p>

        <form action="" method="post" autocomplete="off">
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="nomor_induk" placeholder="Nomor Induk" value="<?php echo isset($_POST['nomor_induk']) ? e($_POST['nomor_induk']) : ''; ?>" required autofocus>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" name="login" class="btn btn-primary btn-block">Masuk</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
</body>

</html>
