<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ $config->nama_sekolah }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, {{ $config->warna_primer }} 0%, {{ $config->warna_sekunder }} 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .login-wrapper {
            position: relative;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .login-header {
            background: linear-gradient(135deg, {{ $config->warna_primer }} 0%, {{ $config->warna_sekunder }} 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            animation: pulse 3s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        .logo-container {
            position: relative;
            z-index: 1;
        }
        .logo-img {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            object-fit: cover;
            background: white;
            padding: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .logo-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .school-name {
            position: relative;
            z-index: 1;
            color: white;
            font-size: 1.25rem;
            font-weight: 700;
            margin-top: 16px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .login-body {
            padding: 35px 30px;
        }
        .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            margin-bottom: 8px;
        }
        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: {{ $config->warna_primer }};
            box-shadow: 0 0 0 4px {{ $config->warna_primer }}20;
        }
        .input-group-text {
            background: transparent;
            border: 2px solid #e5e7eb;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #6b7280;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }
        .btn-login {
            background: linear-gradient(135deg, {{ $config->warna_primer }} 0%, {{ $config->warna_sekunder }} 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px {{ $config->warna_primer }}40;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px {{ $config->warna_primer }}50;
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .alert {
            border-radius: 12px;
            border: none;
            padding: 14px 16px;
        }
        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
        }
        .footer-text {
            text-align: center;
            padding: 20px;
            color: #9ca3af;
            font-size: 0.8rem;
        }
        .footer-text a {
            color: {{ $config->warna_primer }};
            text-decoration: none;
            font-weight: 600;
        }
        .footer-text a:hover {
            text-decoration: underline;
        }
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: -1;
        }
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            animation: float 6s ease-in-out infinite;
        }
        .shape:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }
        .shape:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 50%;
            right: -50px;
            animation-delay: -2s;
        }
        .shape:nth-child(3) {
            width: 150px;
            height: 150px;
            bottom: -50px;
            left: 30%;
            animation-delay: -4s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    @if($config->logo && file_exists(public_path('storage/logos/' . $config->logo)))
                        <img src="{{ asset('storage/logos/' . $config->logo) }}" alt="Logo" class="logo-img">
                    @else
                        <div class="logo-icon">
                            <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                        </div>
                    @endif
                    <h4 class="school-name">{{ $config->nama_sekolah }}</h4>
                </div>
            </div>

            <div class="login-body">
                <div class="text-center mb-4">
                    <h5 class="fw-bold text-dark mb-2">Selamat Datang</h5>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-clipboard-check me-1"></i>
                        Sistem Informasi Absensi Siswa
                    </p>
                    <p class="text-muted small">
                        <i class="fas fa-chart-bar me-1"></i>
                        Monitoring & Rekapitulasi Kehadiran
                    </p>
                </div>

                @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                   id="username" name="username" value="{{ old('username') }}" 
                                   placeholder="Masukkan username" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" 
                                   placeholder="Masukkan password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login w-100">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk
                    </button>
                </form>
            </div>

            <div class="footer-text">
                Created by <a href="https://github.com/natedekaka" target="_blank">Natedekaka</a>
            </div>
        </div>
    </div>
</body>
</html>
