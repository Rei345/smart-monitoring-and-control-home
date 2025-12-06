<!doctype html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tentang Proyek - IoT Smart Home</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #212529;
        }
        
        .tech-card {
            background-color: #2c3034;
            border: 1px solid rgba(255,255,255,0.1);
            transition: transform 0.3s, border-color 0.3s;
        }
        .tech-card:hover {
            transform: translateY(-5px);
            border-color: #0d6efd;
        }
        
        .tech-icon-box {
            width: 70px; height: 70px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            font-size: 2rem;
            margin: 0 auto 1rem auto;
        }

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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold text-white" href="{{ url('/tentang') }}">Tentang</a></li>
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

    <section class="py-5 text-center bg-dark border-bottom border-secondary">
        <div class="container">
            <h1 class="fw-bold text-white">Tentang Proyek Ini</h1>
            <p class="lead text-secondary">Latar belakang, tujuan, dan spesifikasi teknis pengembangan sistem.</p>
        </div>
    </section>

    <section class="container py-5">
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6">
                <img src="{{ asset('assets/img/rangkaian.png') }}" class="img-fluid rounded-3 shadow-lg border border-secondary" alt="Foto Alat IoT">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold text-white mb-3">Latar Belakang & Tujuan</h2>
                <p class="text-secondary">
                    Pemantauan dan pengendalian perangkat rumah secara manual seringkali tidak efisien dan memakan waktu. 
                    Di era Internet of Things (IoT), otomatisasi rumah menjadi solusi untuk meningkatkan kenyamanan, keamanan, dan efisiensi energi.
                </p>
                <p class="text-secondary">
                    Proyek ini dikembangkan sebagai Tugas Akhir Mata Kuliah Automation pada program studi Teknik Komputer dengan tujuan utama:
                </p>
                
                <ul class="list-unstyled mt-4">
                    <li class="d-flex mb-3">
                        <i class="fas fa-check-circle text-primary fa-lg me-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold text-white mb-0">Merancang Sistem Terintegrasi</h6>
                            <span class="small text-secondary">Membangun sistem monitoring (Suhu, Kelembapan) dan kontrol (Pintu, Kipas) berbasis web yang responsif.</span>
                        </div>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="fas fa-check-circle text-primary fa-lg me-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold text-white mb-0">Implementasi Protokol IoT</h6>
                            <span class="small text-secondary">Mengimplementasikan komunikasi data *real-time* dan ringan menggunakan protokol MQTT antara ESP32 dan Server.</span>
                        </div>
                    </li>
                    <li class="d-flex">
                        <i class="fas fa-check-circle text-primary fa-lg me-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold text-white mb-0">Manajemen Data & Konfigurasi</h6>
                            <span class="small text-secondary">Menyediakan fitur penyimpanan log historis dan konfigurasi ambang batas dinamis melalui Database MySQL.</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="py-5" style="background-color: #1c1f23;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-white">Teknologi yang Digunakan</h2>
                <p class="text-secondary">Kombinasi perangkat keras dan lunak modern untuk performa maksimal.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card tech-card h-100 p-4 text-center">
                        <div class="tech-icon-box bg-dark border border-secondary text-info">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <h5 class="fw-bold text-white">Hardware</h5>
                        <p class="small text-secondary">ESP32 Devkit V1, DHT11, BMP280, HC-SR04, Servo SG90, Stepper Motor ULN2003.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card tech-card h-100 p-4 text-center">
                        <div class="tech-icon-box bg-dark border border-secondary text-danger">
                            <i class="fab fa-laravel"></i>
                        </div>
                        <h5 class="fw-bold text-white">Backend</h5>
                        <p class="small text-secondary">Laravel 12 sebagai framework utama, menangani Route, Controller, Auth, dan MQTT Listener.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card tech-card h-100 p-4 text-center">
                        <div class="tech-icon-box bg-dark border border-secondary text-warning">
                            <i class="fas fa-database"></i>
                        </div>
                        <h5 class="fw-bold text-white">Data & Protocol</h5>
                        <p class="small text-secondary">MySQL untuk penyimpanan data persisten (Log & Config), dan MQTT (Mosquitto) untuk komunikasi real-time.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card tech-card h-100 p-4 text-center">
                        <div class="tech-icon-box bg-dark border border-secondary text-primary">
                            <i class="fab fa-bootstrap"></i>
                        </div>
                        <h5 class="fw-bold text-white">Frontend</h5>
                        <p class="small text-secondary">Bootstrap 5 untuk UI responsif, Chart.js untuk visualisasi grafik, dan Paho MQTT JS untuk koneksi klien.</p>
                    </div>
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