<!doctype html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beranda - IoT Smart Home</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #212529;
            color: #e2e8f0;
        }
        
        /* Hero Section */
        .hero-section { 
            background-color: #212529; 
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        /* Kartu Fitur */
        .feature-card { 
            transition: transform 0.3s; 
            border: 1px solid rgba(255,255,255,0.1); 
            background: #2c3034;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }
        .feature-card:hover { 
            transform: translateY(-5px); 
            border-color: #0d6efd; 
        }
        
        .icon-box { 
            width: 60px; 
            height: 60px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            border-radius: 50%; 
            font-size: 1.5rem; 
        }
        
        /* Foto Tim */
        .team-img {
            width: 120px; 
            height: 120px; 
            object-fit: cover; 
            border: 3px solid #495057; 
            padding: 3px;
            transition: all 0.3s;
        }
        .team-img:hover {
            border-color: #0d6efd;
            transform: scale(1.05);
        }

        /* Efek Hover Link Footer */
        .hover-text-white:hover { 
            color: #fff !important; 
            transition: 0.3s; 
        }

        .hover-text-primary:hover { 
            color: #0d6efd !important; 
            transition: 0.3s; 
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top bg-dark shadow-sm border-bottom border-secondary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fas fa-network-wired me-2 text-primary"></i> IoT Smart Home
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active fw-bold text-white" href="{{ url('/') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/tentang') }}">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Tampilan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/metode') }}">Metode</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/kontak') }}">Kontak</a></li>
                    @auth
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0 dropdown">
                            <a class="btn btn-outline-primary btn-sm rounded-pill px-4 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-2"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end bg-dark border-secondary">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger small">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section py-5 text-white">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge bg-primary bg-opacity-25 text-primary border border-primary mb-3 px-3">Tugas Automation 2025</span>
                    <h1 class="display-4 fw-bold mb-3">Smart Monitoring & Control System</h1>
                    <p class="lead text-white-50 mb-4">
                        Sistem IoT cerdas berbasis <strong>ESP32</strong> dan <strong>Laravel</strong>. Pantau kondisi ruangan secara <em>real-time</em> dan kendalikan perangkat dari mana saja melalui protokol MQTT yang cepat dan aman.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4"><i class="fas fa-tachometer-alt me-2"></i>Buka Dashboard</a>
                        <a href="{{ url('/metode') }}" class="btn btn-outline-light btn-lg px-4">Lihat Dokumentasi</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="{{ asset('assets/img/automation_2103800 (1).png') }}" alt="IoT Illustration" class="img-fluid w-75 drop-shadow">
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" style="background-color: #1c1f23;">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-primary fw-bold text-uppercase">Arsitektur Sistem</h6>
                <h2 class="fw-bold text-white">Bagaimana Sistem Bekerja?</h2>
                <p class="text-secondary">Menggunakan pendekatan <em>Hybrid</em> untuk kecepatan dan keandalan data.</p>
            </div>
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="card feature-card h-100 p-4">
                        <div class="card-body">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3"><i class="fas fa-microchip"></i></div>
                            <h4 class="fw-bold text-white">1. Akuisisi Data</h4>
                            <p class="text-secondary small">Sensor membaca data lingkungan lalu ESP32 mengirimkannya via WiFi menggunakan protokol ringan MQTT.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 p-4">
                        <div class="card-body">
                            <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3"><i class="fas fa-network-wired"></i></div>
                            <h4 class="fw-bold text-white">2. Transmisi & Log</h4>
                            <p class="text-secondary small">Data dikirim <em>real-time</em> ke Dashboard, sekaligus disimpan ke Database MySQL oleh Laravel Worker sebagai riwayat.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 p-4">
                        <div class="card-body">
                            <div class="icon-box bg-warning bg-opacity-10 text-warning mx-auto mb-3"><i class="fas fa-laptop-code"></i></div>
                            <h4 class="fw-bold text-white">3. Kontrol Cerdas</h4>
                            <p class="text-secondary small">User memantau grafik, mengubah ambang batas (Config), dan mengatur jadwal operasional secara dinamis dari Web.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" style="background-color: #212529;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-white">Fitur Unggulan</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card feature-card h-100 text-center p-3">
                        <div class="card-body">
                            <i class="fas fa-chart-line fa-3x text-danger mb-3 mt-2"></i>
                            <h5 class="fw-bold text-white">Real-time Monitor</h5>
                            <p class="text-secondary small">Visualisasi data sensor (Suhu, Kelembapan, Tekanan) secara langsung tanpa refresh.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card feature-card h-100 text-center p-3">
                        <div class="card-body">
                            <i class="fas fa-gamepad fa-3x text-info mb-3 mt-2"></i>
                            <h5 class="fw-bold text-white">Remote Control</h5>
                            <p class="text-secondary small">Kendalikan Pintu (Servo) dan Kipas (Stepper) beserta kecepatannya dari jarak jauh.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card feature-card h-100 text-center p-3">
                        <div class="card-body">
                            <i class="fas fa-sliders-h fa-3x text-warning mb-3 mt-2"></i>
                            <h5 class="fw-bold text-white">Dynamic Config</h5>
                            <p class="text-secondary small">Atur ambang batas suhu dan jarak pemicu otomatis langsung melalui website.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card feature-card h-100 text-center p-3">
                        <div class="card-body">
                            <i class="fas fa-clock fa-3x text-success mb-3 mt-2"></i>
                            <h5 class="fw-bold text-white">Smart Schedule</h5>
                            <p class="text-secondary small">Penjadwalan otomatis waktu operasional pintu yang tersinkronisasi dengan NTP.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="tim" class="py-5" style="background-color: #2c3034;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-white">Tim Peneliti</h2>
                <p class="text-secondary">Pengembang di balik proyek ini.</p>
            </div>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-4 justify-content-center">
                <div class="col text-center">
                    <img src="{{ asset('assets/img/reinhard.jpg') }}" class="img-fluid rounded-circle mb-3 team-img shadow" alt="Reinhard">
                    <h6 class="fw-bold text-white mb-1">Reinhard Marcelino Sitompul</h6>
                    <p class="text-primary small">Programmer</p>
                </div>
                <div class="col text-center">
                    <img src="{{ asset('assets/img/cindy.jpg') }}" class="img-fluid rounded-circle mb-3 team-img shadow" alt="Cindy">
                    <h6 class="fw-bold text-white mb-1">Cindy Chalizah Hsb</h6>
                    <p class="text-primary small">Designer</p>
                </div>
                <div class="col text-center">
                    <img src="{{ asset('assets/img/napit.jpg') }}" class="img-fluid rounded-circle mb-3 team-img shadow" alt="Napit">
                    <h6 class="fw-bold text-white mb-1">Napit Saputra Simanjuntak</h6>
                    <p class="text-primary small">Hardware Engineer</p>
                </div>
                <div class="col text-center">
                    <img src="{{ asset('assets/img/reva.jpg') }}" class="img-fluid rounded-circle mb-3 team-img shadow" alt="Reva">
                    <h6 class="fw-bold text-white mb-1">Reva Amalia Putri</h6>
                    <p class="text-primary small">System Analyst</p>
                </div>
                <div class="col text-center">
                    <img src="{{ asset('assets/img/rafli.jpg') }}" class="img-fluid rounded-circle mb-3 team-img shadow" alt="Rafli">
                    <h6 class="fw-bold text-white mb-1">Rafli Marcopolo Siahaan</h6>
                    <p class="text-primary small">Quality Assurance</p>
                </div>
            </div>
        </div>
    </section>

    <footer id="kontak" class="py-5 text-white mt-auto" style="background-color: #15171a; border-top: 1px solid #343a40;">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-network-wired me-2"></i> IoT Smart Home</h5>
                    <p class="text-secondary small mb-4">Solusi pemantauan dan kontrol rumah cerdas yang terintegrasi, aman, dan mudah digunakan untuk kebutuhan akademis maupun praktis.</p>
                    <div class="d-flex gap-3">
                        <a href="https://github.com/Rei345/smart-monitoring-and-control-home" class="text-secondary hover-text-white fs-5"><i class="fab fa-github"></i></a>
                        <a href="#" class="text-secondary hover-text-white fs-5"><i class="fab fa-instagram"></i></a>
                        <a href="https://youtu.be/SZUQcsHr9OU?si=e03SO26reDkNeU-H" class="text-secondary hover-text-white fs-5"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold text-white mb-3">Navigasi</h6>
                    <ul class="list-unstyled text-secondary small d-grid gap-2">
                        <li><a href="{{ url('/') }}" class="text-decoration-none text-secondary hover-text-primary">Beranda</a></li>
                        <li><a href="{{ url('/tentang') }}" class="text-decoration-none text-secondary hover-text-primary">Tentang Kami</a></li>
                        <li><a href="{{ route('dashboard') }}" class="text-decoration-none text-secondary hover-text-primary">Dashboard</a></li>
                        <li><a href="{{ url('/metode') }}" class="text-decoration-none text-secondary hover-text-primary">Metodologi</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-white mb-3">Hubungi Kami</h6>
                    <ul class="list-unstyled text-secondary small d-grid gap-2">
                        <li class="d-flex align-items-start"><i class="fas fa-envelope me-2 mt-1 text-primary"></i><span>admin</span></li>
                        <li class="d-flex align-items-start"><i class="fas fa-map-marker-alt me-2 mt-1 text-primary"></i><span>Politeknik Negeri Medan,<br>Sumatera Utara, Indonesia</span></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-white mb-3">Powered By</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-dark border border-secondary text-secondary">Laravel</span>
                        <span class="badge bg-dark border border-secondary text-secondary">MySQL</span>
                        <span class="badge bg-dark border border-secondary text-secondary">MQTT</span>
                        <span class="badge bg-dark border border-secondary text-secondary">ESP32</span>
                        <span class="badge bg-dark border border-secondary text-secondary">Bootstrap 5</span>
                    </div>
                </div>
            </div>
            <hr class="border-secondary opacity-25 my-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <small class="text-secondary">&copy; 2025 IoT Smart Home Project. All Rights Reserved.</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>