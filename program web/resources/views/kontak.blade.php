<!doctype html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hubungi Kami - IoT Smart Home</title>

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
        
        /* Styling Form Card */
        .contact-form-card {
            background-color: #2c3034;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }

        /* Efek Hover Link */
        .social-link { 
            transition: transform 0.2s; 
        }
        .social-link:hover { 
            transform: translateX(5px); 
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
                    <li class="nav-item"><a class="nav-link" href="{{ url('/tentang') }}">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Tampilan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/metode') }}">Metode</a></li>
                    <li class="nav-item"><a class="nav-link active fw-bold text-white" href="{{ url('/kontak') }}">Kontak</a></li>
                    
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
            <h1 class="fw-bold text-white">Hubungi Kami</h1>
            <p class="lead text-secondary">Ada pertanyaan, masukan, atau laporan bug? Tim kami siap membantu Anda.</p>
        </div>
    </section>

    <section class="container py-5">
        <div class="row g-5">
            
            <div class="col-lg-7">
                <div class="contact-form-card h-100">
                    <h4 class="fw-bold text-white mb-4"><i class="fas fa-envelope me-2 text-warning"></i>Kirim Pesan</h4>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST"> 
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label text-secondary small">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control bg-dark text-white border-secondary" id="nama" placeholder="Nama Anda" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label text-secondary small">Alamat Email</label>
                                <input type="email" name="email" class="form-control bg-dark text-white border-secondary" id="email" placeholder="email@contoh.com" required>
                            </div>
                            <div class="col-12">
                                <label for="subjek" class="form-label text-secondary small">Subjek</label>
                                <input type="text" name="subject" class="form-control bg-dark text-white border-secondary" id="subjek" placeholder="Topik Pesan" required>
                            </div>
                            <div class="col-12">
                                <label for="pesan" class="form-label text-secondary small">Pesan Anda</label>
                                <textarea class="form-control bg-dark text-white border-secondary" name="message" id="pesan" rows="5" placeholder="Tulis pesan Anda di sini..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="h-100 d-flex flex-column justify-content-center ps-lg-4">
                    <h3 class="fw-bold text-white mb-4">Informasi Kontak</h3>
                    <p class="text-secondary mb-5">
                        Proyek ini bersifat *Open Source*. Anda dapat berkontribusi, melihat kode sumber, atau menonton dokumentasi lengkap pengembangan alat ini melalui tautan di bawah.
                    </p>
                    
                    <ul class="list-unstyled">
                        <li class="d-flex align-items-start mb-4 social-link">
                            <div class="bg-dark p-3 rounded-circle me-3 border border-secondary">
                                <i class="fab fa-github fa-2x text-white"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-white mb-1">GitHub Repository</h6>
                                <a href="https://github.com/Rei345/smart-monitoring-and-control-home" class="text-decoration-none text-primary small" target="_blank">
                                    Lihat Repository <i class="fas fa-external-link-alt ms-1"></i>
                                </a>
                                <p class="text-secondary small mb-0">Akses kode sumber lengkap (Arduino & Laravel).</p>
                            </div>
                        </li>

                        <li class="d-flex align-items-start mb-4 social-link">
                            <div class="bg-dark p-3 rounded-circle me-3 border border-secondary">
                                <i class="fab fa-youtube fa-2x text-danger"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-white mb-1">YouTube Channel</h6>
                                <a href="https://youtu.be/SZUQcsHr9OU?si=e03SO26reDkNeU-H" class="text-decoration-none text-danger small" target="_blank">
                                    Tonton Video Dokumentasi <i class="fas fa-play-circle ms-1"></i>
                                </a>
                                <p class="text-secondary small mb-0">Tutorial perakitan dan demo alat.</p>
                            </div>
                        </li>

                        <li class="d-flex align-items-start social-link">
                            <div class="bg-dark p-3 rounded-circle me-3 border border-secondary">
                                <i class="fas fa-file-alt fa-2x text-info"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-white mb-1">Dokumen Jurnal</h6>
                                <a href="#" class="text-decoration-none text-info small">
                                    Download PDF (Segera) <i class="fas fa-download ms-1"></i>
                                </a>
                                <p class="text-secondary small mb-0">Laporan Tugas Automation lengkap.</p>
                            </div>
                        </li>
                    </ul>
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