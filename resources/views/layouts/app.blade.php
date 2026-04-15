<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Absensi Siswa')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: {{ $config->warna_primer }};
            --secondary: {{ $config->warna_sekunder }};
            --primary-rgb: {{ implode(',', sscanf($config->warna_primer, '#%02x%02x%02x')) }};
            --secondary-rgb: {{ implode(',', sscanf($config->warna_sekunder, '#%02x%02x%02x')) }};
        }
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .bg-primary { background-color: var(--primary) !important; }
        .bg-secondary { background-color: var(--secondary) !important; }
        .text-primary { color: var(--primary) !important; }
        .btn-primary { 
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); 
            border: none; 
        }
        .btn-primary:hover { 
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%); 
            color: white;
        }
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        .btn-outline-primary:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        .btn-secondary-custom {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            border: none;
            color: white;
        }
        .btn-secondary-custom:hover {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .card-header {
            background: white;
            border-bottom: 2px solid #f1f5f9;
            font-weight: 600;
        }
        .sidebar { 
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); 
            min-height: 100vh; 
        }
        .sidebar .nav-link { 
            color: #cbd5e1; 
            padding: 0.75rem 1rem; 
            border-radius: 10px;
            margin: 2px 8px;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover { 
            background-color: rgba(255,255,255,0.1); 
            color: white; 
        }
        .sidebar .nav-link.active { 
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); 
            color: white; 
            box-shadow: 0 4px 15px rgba(var(--primary-rgb), 0.4);
        }
        .sidebar .nav-link i { width: 24px; }
        .sidebar-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 1.5rem;
            border-radius: 0;
        }
        .badge-primary-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.15);
        }
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.15);
        }
        .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .page-link {
            color: var(--primary);
        }
        .page-link:hover {
            color: var(--secondary);
        }
        .table thead th {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            font-weight: 600;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(var(--primary-rgb), 0.05);
        }
        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        .alert-success {
            background-color: #d1fae5;
            border-color: #34d399;
            color: #065f46;
        }
        .alert-danger {
            background-color: #fee2e2;
            border-color: #f87171;
            color: #991b1b;
        }
        .alert-warning {
            background-color: #fef3c7;
            border-color: #fbbf24;
            color: #92400e;
        }
        .text-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .card-stat {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .card-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        .card-stat .icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-wa-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
            color: white;
            box-shadow: 0 4px 15px rgba(var(--primary-rgb), 0.3);
        }
        .btn-wa-primary:hover {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(var(--primary-rgb), 0.4);
        }
        .text-wa-dark { color: var(--primary); }
        .bg-wa-dark { background-color: var(--primary); }
        .bg-wa-green { background-color: var(--secondary); }
        .border-wa { border-color: var(--primary) !important; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        @auth
        <nav class="sidebar col-md-2 d-none d-md-block">
            <div class="sidebar-header text-center border-0">
                @if($config->logo && file_exists(public_path('storage/logos/' . $config->logo)))
                    <img src="{{ asset('storage/logos/' . $config->logo) }}" alt="Logo" class="mb-2" style="height: 50px; width: auto; max-width: 100px;">
                @else
                    <i class="fas fa-school fa-2x text-white mb-2 d-block"></i>
                @endif
                <h5 class="text-white mb-0 fw-bold">{{ $config->nama_sekolah }}</h5>
                <small class="text-white-50">Sistem Absensi Siswa</small>
            </div>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}" href="{{ route('absensi.index') }}">
                        <i class="fas fa-clipboard-check me-2"></i> Absensi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}" href="{{ route('siswa.index') }}">
                        <i class="fas fa-users me-2"></i> Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kelas.*') ? 'active' : '' }}" href="{{ route('kelas.index') }}">
                        <i class="fas fa-school me-2"></i> Kelas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('rekap.*') ? 'active' : '' }}" href="{{ route('rekap.index') }}">
                        <i class="fas fa-chart-bar me-2"></i> Rekap
                    </a>
                </li>
                @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kenaikan.*') || request()->routeIs('kelulusan.*') || request()->routeIs('redistribusi.*') ? 'active' : '' }}" href="{{ route('kenaikan.index') }}">
                        <i class="fas fa-graduation-cap me-2"></i> Kenaikan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tahun-ajaran.*') ? 'active' : '' }}" href="{{ route('tahun-ajaran.index') }}">
                        <i class="fas fa-calendar-alt me-2"></i> Tahun Ajaran
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}" href="{{ route('user.index') }}">
                        <i class="fas fa-user-cog me-2"></i> User
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('konfigurasi.*') ? 'active' : '' }}" href="{{ route('konfigurasi.index') }}">
                        <i class="fas fa-cog me-2"></i> Konfigurasi
                    </a>
                </li>
                @endif
            </ul>
            <div class="mt-auto p-3 border-top border-dark">
                <div class="d-flex align-items-center text-white mb-3">
                    <div class="rounded-circle bg-gradient d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <small class="d-block fw-semibold">{{ auth()->user()->nama }}</small>
                        <small class="text-white-50">{{ ucfirst(auth()->user()->role) }}</small>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light w-100">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
            <div class="p-2 text-center border-top border-dark">
                <small class="text-white-50">Created by <a href="https://github.com/natedekaka" target="_blank" class="text-decoration-none text-white fw-semibold">Natedekaka</a></small>
            </div>
        </nav>
        @endauth

        <main class="flex-grow-1">
            @auth
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-outline-primary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="navbar-text ms-auto">
                        @yield('breadcrumb')
                    </span>
                </div>
            </nav>
            @endauth

            <div class="container-fluid py-4">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
