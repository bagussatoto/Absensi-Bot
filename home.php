<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Absensi Bot - Sistem Absensi IoT Pintar & Modern</title>

  <!-- Google Font: Inter / Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Bootstrap 4 / AdminLTE Style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <style>
    :root {
      --primary: #2563eb;
      --primary-dark: #1d4ed8;
      --secondary: #0ea5e9;
      --dark: #0f172a;
      --light-bg: #f8fafc;
    }
    
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #334155;
      background-color: #ffffff;
      overflow-x: hidden;
    }

    /* Navbar */
    .landing-nav {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid #e2e8f0;
      transition: all 0.3s ease;
    }
    .landing-nav .navbar-brand {
      font-weight: 800;
      font-size: 1.4rem;
      color: var(--dark);
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .landing-nav .navbar-brand i {
      color: var(--primary);
    }
    .nav-link {
      font-weight: 600;
      color: #475569 !important;
      transition: color 0.2s;
    }
    .nav-link:hover {
      color: var(--primary) !important;
    }

    /* Hero Section */
    .hero-section {
      padding: 120px 0 80px;
      background: radial-gradient(circle at 80% 20%, rgba(37, 99, 235, 0.08) 0%, transparent 50%),
                  radial-gradient(circle at 20% 80%, rgba(14, 165, 233, 0.08) 0%, transparent 50%);
    }
    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 6px 16px;
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      color: var(--primary);
      border-radius: 9999px;
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 24px;
    }
    .hero-title {
      font-size: 3.2rem;
      font-weight: 800;
      color: var(--dark);
      line-height: 1.2;
      margin-bottom: 20px;
    }
    .hero-title span {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .hero-desc {
      font-size: 1.15rem;
      color: #64748b;
      line-height: 1.7;
      margin-bottom: 35px;
    }

    /* Action Buttons */
    .btn-custom-primary {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: #fff;
      font-weight: 600;
      padding: 12px 28px;
      border-radius: 10px;
      border: none;
      box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
      transition: all 0.3s ease;
    }
    .btn-custom-primary:hover {
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 14px 25px rgba(37, 99, 235, 0.3);
    }
    .btn-custom-outline {
      border: 2px solid #cbd5e1;
      color: #334155;
      font-weight: 600;
      padding: 10px 24px;
      border-radius: 10px;
      transition: all 0.3s ease;
    }
    .btn-custom-outline:hover {
      background: #f1f5f9;
      color: var(--dark);
    }

    /* Feature Cards */
    .feature-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      padding: 32px 24px;
      transition: all 0.3s ease;
      height: 100%;
    }
    .feature-card:hover {
      border-color: #bfdbfe;
      box-shadow: 0 20px 30px rgba(15, 23, 42, 0.05);
      transform: translateY(-5px);
    }
    .feature-icon {
      width: 56px;
      height: 56px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      margin-bottom: 20px;
    }
    .icon-blue { background: #eff6ff; color: #2563eb; }
    .icon-emerald { background: #ecfdf5; color: #059669; }
    .icon-purple { background: #faf5ff; color: #9333ea; }
    .icon-amber { background: #fffbeb; color: #d97706; }

    /* Mockup Display */
    .mockup-wrapper {
      position: relative;
      border-radius: 18px;
      box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
      border: 6px solid #1e293b;
      overflow: hidden;
      background: #0f172a;
    }
    .mockup-header {
      background: #1e293b;
      padding: 10px 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .mockup-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
    }
    .dot-red { background: #ef4444; }
    .dot-yellow { background: #f59e0b; }
    .dot-green { background: #10b981; }

    /* Stats Section */
    .stats-section {
      background: #0f172a;
      color: #fff;
      padding: 60px 0;
    }
    .stat-number {
      font-size: 2.5rem;
      font-weight: 800;
      color: #38bdf8;
    }

    /* Footer */
    footer {
      background: #ffffff;
      border-top: 1px solid #e2e8f0;
      padding: 40px 0;
      color: #64748b;
    }

    @media (max-width: 768px) {
      .hero-title { font-size: 2.2rem; }
      .hero-section { padding: 90px 0 50px; }
    }
  </style>
</head>

<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top landing-nav">
    <div class="container">
      <a class="navbar-brand" href="home.php">
        <i class="fas fa-fingerprint"></i> Absensi<span>Bot</span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item"><a class="nav-link" href="#fitur">Fitur Unggulan</a></li>
          <li class="nav-item"><a class="nav-link" href="#arsitektur">Cara Kerja IoT</a></li>
          <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
          <li class="nav-item"><a class="nav-link" href="scan.php">Cek Kartu / Scan</a></li>
        </ul>
        <div class="d-flex align-items-center">
          <a href="login.php" class="btn btn-custom-primary">
            <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sistem
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-5 mb-lg-0">
          <div class="hero-badge">
            <i class="fas fa-bolt"></i> Versi 1.0.0 Resmi Dirilis
          </div>
          <h1 class="hero-title">
            Solusi Absensi Realtime Berbasis <span>IoT & RFID</span>
          </h1>
          <p class="hero-desc">
            Sistem absensi cerdas tanpa instalasi rumit. Terhubung langsung dengan mikrokontroler RFID, mendukung kalkulasi keterlambatan otomatis, multi cabang/gedung, dan pelaporan presisi.
          </p>
          <div class="d-flex flex-wrap gap-3">
            <a href="login.php" class="btn btn-custom-primary mr-3 mb-2">
              <i class="fas fa-desktop mr-2"></i> Dashboard Admin
            </a>
            <a href="scan.php" class="btn btn-custom-outline mb-2">
              <i class="fas fa-id-card mr-2"></i> Simulator Tap Tag
            </a>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="mockup-wrapper">
            <div class="mockup-header">
              <div class="mockup-dot dot-red"></div>
              <div class="mockup-dot dot-yellow"></div>
              <div class="mockup-dot dot-green"></div>
              <span class="text-xs text-muted ml-2">Absensi Bot - Dashboard Preview</span>
            </div>
            <div class="p-4 text-white">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 font-weight-bold"><i class="fas fa-chart-line mr-2 text-info"></i> Realtime IoT Feed</h5>
                <span class="badge badge-success px-2 py-1"><i class="fas fa-circle text-xs mr-1"></i> Live</span>
              </div>
              <div class="bg-dark p-3 rounded mb-2 border border-secondary">
                <small class="text-muted d-block">ID Mesin: ESP32-LOBBY-01</small>
                <div class="d-flex justify-content-between mt-1">
                  <span><strong>123</strong> - Admin CS</span>
                  <span class="text-success font-weight-bold">Tepat Waktu (Masuk)</span>
                </div>
              </div>
              <div class="bg-dark p-3 rounded border border-secondary">
                <small class="text-muted d-block">ID Mesin: ESP8266-LT2</small>
                <div class="d-flex justify-content-between mt-1">
                  <span><strong>104</strong> - Karyawan Staff</span>
                  <span class="text-warning font-weight-bold">Mulai Istirahat</span>
                </div>
              </div>
              <div class="mt-3 text-center">
                <small class="text-secondary"><i class="fas fa-shield-alt mr-1"></i> Terenkripsi & Dilindungi Anti-SQL Injection</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Counter -->
  <section class="stats-section">
    <div class="container">
      <div class="row text-center">
        <div class="col-6 col-md-3 mb-4 mb-md-0">
          <div class="stat-number">100%</div>
          <p class="text-muted mb-0">Open Source & Bebas Lisensi</p>
        </div>
        <div class="col-6 col-md-3 mb-4 mb-md-0">
          <div class="stat-number">4 Mode</div>
          <p class="text-muted mb-0">Masuk, Istirahat, Kembali, Pulang</p>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-number">3 Zona</div>
          <p class="text-muted mb-0">Dukungan WIB, WITA, & WIT</p>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-number">PHP 8+</div>
          <p class="text-muted mb-0">Arsitektur Modern & Aman</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="fitur" class="py-5" style="background-color: #f8fafc;">
    <div class="container py-4">
      <div class="text-center max-w-xl mx-auto mb-5">
        <h6 class="text-primary font-weight-bold text-uppercase">Fitur Utama</h6>
        <h2 class="font-weight-bold text-dark">Didesain untuk Efisiensi & Kecepatan</h2>
        <p class="text-muted">Kelola absensi karyawan pabrik, kantor, maupun institusi dengan mudah dan fleksibel.</p>
      </div>

      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon icon-blue">
              <i class="fas fa-microchip"></i>
            </div>
            <h5 class="font-weight-bold">Integrasi Mesin IoT</h5>
            <p class="text-muted">Mendukung mesin RFID (ESP8266 / ESP32) dengan respon JSON instan serta validasi MAC Address mesin.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon icon-emerald">
              <i class="fas fa-shield-alt"></i>
            </div>
            <h5 class="font-weight-bold">Keamanan Terstandar</h5>
            <p class="text-muted">Dilengkapi Prepared Statements pencegah SQL Injection, password hashing bcrypt modern, dan proteksi XSS.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon icon-purple">
              <i class="fas fa-building"></i>
            </div>
            <h5 class="font-weight-bold">Multi Cabang & Shift</h5>
            <p class="text-muted">Pengaturan jam operasional dan hari libur spesifik untuk berbagai lokasi gedung atau kantor cabang.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon icon-amber">
              <i class="fas fa-clock"></i>
            </div>
            <h5 class="font-weight-bold">Kalkulasi Keterlambatan</h5>
            <p class="text-muted">Perhitungan durasi keterlambatan otomatis saat jam masuk kerja, waktu mulai istirahat, hingga kepulangan.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon icon-blue">
              <i class="fas fa-calendar-check"></i>
            </div>
            <h5 class="font-weight-bold">Manajemen Cuti & Libur</h5>
            <p class="text-muted">Pencatatan cuti khusus karyawan serta penetapan tanggal libur nasional secara terpusat.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon icon-emerald">
              <i class="fas fa-mobile-alt"></i>
            </div>
            <h5 class="font-weight-bold">Responsif di Semua Perangkat</h5>
            <p class="text-muted">Antarmuka AdminLTE yang nyaman diakses melalui laptop, tablet, maupun layar smartphone.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- IoT Architecture Section -->
  <section id="arsitektur" class="py-5 bg-white">
    <div class="container py-4">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <h6 class="text-primary font-weight-bold text-uppercase">Arsitektur Sistem</h6>
          <h2 class="font-weight-bold text-dark mb-4">Bagaimana Mesin IoT Terhubung?</h2>
          <div class="d-flex mb-3">
            <div class="mr-3 font-weight-bold text-primary" style="font-size: 1.5rem;">1.</div>
            <div>
              <h6 class="font-weight-bold mb-1">Tap Kartu RFID</h6>
              <p class="text-muted">Karyawan menempelkan kartu / tag RFID ke modul RC522 pada alat absensi.</p>
            </div>
          </div>
          <div class="d-flex mb-3">
            <div class="mr-3 font-weight-bold text-primary" style="font-size: 1.5rem;">2.</div>
            <div>
              <h6 class="font-weight-bold mb-1">Pengiriman HTTP GET / JSON</h6>
              <p class="text-muted">Mikrokontroler mengirimkan ID Tag dan ID Mesin ke endpoint <code>machine.php</code>.</p>
            </div>
          </div>
          <div class="d-flex">
            <div class="mr-3 font-weight-bold text-primary" style="font-size: 1.5rem;">3.</div>
            <div>
              <h6 class="font-weight-bold mb-1">Pencatatan Database & Verifikasi</h6>
              <p class="text-muted">Server mencatat waktu UTC, mengecek batas waktu shift cabang, dan mengembalikan respon sukses.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6 text-center">
          <div class="p-4 bg-light rounded-lg border">
            <i class="fas fa-network-wired fa-5x text-primary mb-3"></i>
            <h5 class="font-weight-bold">Alur Komunikasi Sederhana & Cepat</h5>
            <p class="text-muted mb-0">Dirancang struktural agar ramah bagi yang baru mempelajari integrasi Web dan Internet of Things (IoT).</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="py-5" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
    <div class="container text-center text-white py-4">
      <h2 class="font-weight-bold mb-3">Siap Menggunakan Absensi Bot?</h2>
      <p class="lead mb-4">Masuk ke sistem kontrol untuk mengelola pengguna, data cabang, dan riwayat absensi.</p>
      <a href="login.php" class="btn btn-light btn-lg px-5 font-weight-bold text-primary shadow">
        Buka Dashboard Sekarang
      </a>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container text-center">
      <p class="font-weight-bold mb-1">Absensi Bot &copy; <?php echo date('Y'); ?></p>
      <small class="text-muted">Dikembangkan dengan semangat Open Source untuk kemajuan teknologi Indonesia.</small>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
