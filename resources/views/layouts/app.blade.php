<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Absensi Siswa')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #64748b;
        }
        .bg-primary { background-color: var(--primary) !important; }
        .bg-secondary { background-color: var(--secondary) !important; }
        .text-primary { color: var(--primary) !important; }
        .btn-primary { background-color: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background-color: #4338ca; border-color: #4338ca; }
        .sidebar { background-color: #1e293b; min-height: 100vh; }
        .sidebar .nav-link { color: #cbd5e1; padding: 0.75rem 1rem; }
        .sidebar .nav-link:hover { background-color: #334155; color: white; }
        .sidebar .nav-link.active { background-color: var(--primary); color: white; }
        .sidebar .nav-link i { width: 24px; }
        .card-stat { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .card-stat .icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        @auth
        <nav class="sidebar col-md-2 d-none d-md-block">
            <div class="p-3 text-center border-bottom border-secondary">
                @if($config->logo && file_exists(public_path('storage/logos/' . $config->logo)))
                    <img src="{{ asset('storage/logos/' . $config->logo) }}" alt="Logo" class="mb-2" style="height: 50px; width: auto; max-width: 100px;">
                @else
                    <i class="fas fa-school fa-2x text-white mb-2 d-block"></i>
                @endif
                <h5 class="text-white mb-0">{{ $config->nama_sekolah }}</h5>
                <small class="text-muted">Sistem Absensi Siswa</small>
            </div>
            <ul class="nav flex-column mt-2">
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
            <div class="mt-auto p-3 border-top border-secondary">
                <div class="d-flex align-items-center text-white">
                    <i class="fas fa-user-circle fa-2x me-2"></i>
                    <div>
                        <small class="d-block">{{ auth()->user()->nama }}</small>
                        <small class="text-muted">{{ auth()->user()->role }}</small>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light w-100">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
            <div class="p-2 text-center border-top border-secondary">
                <small class="text-muted">Created by <a href="https://github.com/natedekaka" target="_blank" class="text-decoration-none text-secondary fw-semibold">Natedekaka</a></small>
            </div>
        </nav>
        @endauth

        <main class="flex-grow-1 @auth @endauth">
            @auth
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
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
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
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
