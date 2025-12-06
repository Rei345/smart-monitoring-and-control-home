<!doctype html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Metode - IoT Smart Home</title>
    
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
        
        .list-group-item {
            background-color: transparent;
            border: 1px solid rgba(255,255,255,0.1);
            color: #e2e8f0;
            margin-bottom: 10px;
            border-radius: 8px !important;
        }
        
        .hover-text-white:hover { color: #fff !important; transition: 0.3s; }
        .hover-text-primary:hover { color: #0d6efd !important; transition: 0.3s; }
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
                    <li class="nav-item"><a class="nav-link" href="{{ url('/tentang') }}">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Tampilan</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold text-white" href="{{ url('/metode') }}">Metode</a></li>
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
            <h1 class="fw-bold text-white">Metode & Dokumentasi</h1>
            <p class="lead text-secondary">Penjelasan rinci mengenai alur kerja sistem, diagram, dan video dokumentasi pengembangan.</p>
        </div>
    </section>

    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white">Video Dokumentasi Proyek</h2>
            <p class="text-secondary">Berikut adalah video lengkap yang menjelaskan proses perancangan, pembuatan, dan pengujian alat.</p>
        </div>
        
        <div class="ratio ratio-16x9 shadow-lg rounded border border-secondary">
            <iframe src="https://www.youtube.com/embed/SZUQcsHr9OU?si=Pex5cIiFvDDYiELr"
                title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
    </section>

    <section class="py-5" style="background-color: #2c3034;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-white">Diagram Sistem</h2>
                <p class="text-secondary">Gambaran umum alur kerja dari sensor ke pengguna dan sebaliknya.</p>
            </div>
            
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="bg-dark rounded-3 p-2 border border-secondary shadow">
                        <img src="{{ asset('assets/img/blok_diagram.png') }}" class="img-fluid rounded" alt="Blok Diagram Sistem">
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <h3 class="fw-bold text-white mb-4">Alur Kerja Rinci</h3>
                    
                    <div class="list-group list-group-flush">
                        
                        <div class="list-group-item d-flex align-items-start">
                            <div class="me-3 mt-1 text-primary"><i class="fas fa-satellite-dish fa-lg"></i></div>
                            <div>
                                <div class="fw-bold text-white">1. Pengumpulan Data</div>
                                <small class="text-secondary">Sensor DHT11, BMP280, dan Ultrasonik membaca data lingkungan secara real-time. ESP32 mengumpulkan data ini.</small>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-start">
                            <div class="me-3 mt-1 text-success"><i class="fas fa-upload fa-lg"></i></div>
                            <div>
                                <div class="fw-bold text-white">2. Publikasi Data (MQTT)</div>
                                <small class="text-secondary">ESP32 mem-publish data sensor ke topik tertentu di Broker MQTT agar bisa diambil oleh server.</small>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-start">
                            <div class="me-3 mt-1 text-warning"><i class="fas fa-database fa-lg"></i></div>
                            <div>
                                <div class="fw-bold text-white">3. Penyimpanan & Visualisasi</div>
                                <small class="text-secondary">Laravel Worker menyimpan data ke MySQL untuk history, sementara Web Dashboard menampilkan data live via WebSocket.</small>
                            </div>
                        </div>

                        <div class="list-group-item d-flex align-items-start">
                            <div class="me-3 mt-1 text-danger"><i class="fas fa-toggle-on fa-lg"></i></div>
                            <div>
                                <div class="fw-bold text-white">4. Kontrol & Eksekusi</div>
                                <small class="text-secondary">Pengguna mengirim perintah (Buka Pintu/Nyalakan Kipas) dari Web. ESP32 menerima perintah dan menggerakkan Servo/Stepper.</small>
                            </div>
                        </div>
                        
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