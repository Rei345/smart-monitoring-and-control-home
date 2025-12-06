<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - IoT Smart Home</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #212529; 
            color: #e2e8f0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background-color: #343a40; 
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5); 
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
        }

        .form-control {
            background-color: #212529; 
            border: 1px solid #495057;
            color: #fff;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            background-color: #212529;
            border-color: #0d6efd; 
            color: #fff;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25);
        }

        .input-group-text {
            background-color: #2b3035;
            border: 1px solid #495057;
            color: #adb5bd;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .back-link {
            text-decoration: none;
            color: #adb5bd;
            font-size: 0.875rem;
            transition: color 0.2s;
        }
        
        .back-link:hover {
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="login-card">
        
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-25 text-primary rounded-circle mb-3" style="width: 70px; height: 70px;">
                <i class="fas fa-network-wired fa-2x"></i>
            </div>
            <h3 class="fw-bold text-white mb-1">Selamat Datang</h3>
            <p class="text-secondary small">Silakan login untuk mengakses dashboard</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger small py-2">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf 
            <div class="mb-3">
                <label for="email" class="form-label small text-secondary fw-bold">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" id="email" placeholder="admin@iot.com" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label small text-secondary fw-bold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" id="password" placeholder="••••••••" required>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 small">
                <div class="form-check">
                    <input class="form-check-input bg-dark border-secondary" type="checkbox" id="remember">
                    <label class="form-check-label text-secondary" for="remember">
                        Ingat Saya
                    </label>
                </div>
                <a href="#" class="text-primary text-decoration-none">Lupa Password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill mb-3">
                Masuk Dashboard <i class="fas fa-sign-in-alt ms-2"></i>
            </button>
        </form>

        <div class="text-center border-top border-secondary border-opacity-25 pt-3 mt-4">
            <a href="{{ url('/') }}" class="back-link">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>