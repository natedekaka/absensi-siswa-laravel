@extends('layouts.app')

@section('title', 'Login - Absensi Siswa')

@push('styles')
<style>
.login-bg {
    background: linear-gradient(135deg, {{ $config->warna_primer }} 0%, {{ $config->warna_sekunder }} 100%);
    min-height: 100vh;
}
</style>
@endpush

@section('content')
<div class="login-bg">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            @if($config->logo && file_exists(public_path('storage/logos/' . $config->logo)))
                                <img src="{{ asset('storage/logos/' . $config->logo) }}" alt="Logo" class="mb-3" style="height: 80px; max-width: 150px; object-fit: contain;">
                            @else
                                <i class="fas fa-school fa-4x text-primary mb-3"></i>
                            @endif
                            <h4 class="fw-bold">{{ $config->nama_sekolah }}</h4>
                            <p class="text-muted">Sistem Absensi Siswa</p>
                        </div>

                        <form method="POST" action="{{ route('login.submit') }}">
                            @csrf

                            @if($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                            @endif

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username') }}" required autofocus>
                                @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-white border-0">
                        <small class="text-muted">Created by <a href="https://github.com/natedekaka" target="_blank" class="text-decoration-none fw-semibold">Natedekaka</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
