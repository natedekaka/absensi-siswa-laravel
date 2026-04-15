@extends('layouts.app')

@section('title', 'Kelulusan Siswa')

@push('styles')
<style>
.kelulusan-page {
    padding: 2rem 0;
}
.kelulusan-card {
    border: none;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}
.kelu-header {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    padding: 2rem;
    color: white;
    text-align: center;
    position: relative;
}
.kelu-icon {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
}
.stat-box {
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
}
.stat-box h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #6366f1;
}
.info-box {
    border-radius: 16px;
    padding: 1.25rem;
    background: #fef3c7;
    border: 1px solid #fcd34d;
}
.btn-kelu {
    border-radius: 14px;
    padding: 0.875rem 1.5rem;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
}
.btn-kelu:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
}
.siswa-item {
    border: 2px solid #e5e7eb;
    border-radius: 14px;
    padding: 0.875rem 1rem;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.siswa-item:hover {
    border-color: #f59e0b;
    background: #fffbeb;
}
.kelas-section {
    margin-bottom: 2rem;
}
.kelas-section h5 {
    color: #d97706;
    font-weight: 600;
    padding: 0.5rem 1rem;
    background: #fef3c7;
    border-radius: 10px;
    display: inline-block;
}
</style>
@endpush

@section('content')
<div class="kelulusan-page">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="kelulusan-card">
                <div class="kelu-header">
                    <div class="kelu-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3>Proses Kelulusan</h3>
                    <p class="mb-0">Tandai siswa kelas 12 sebagai alumni</p>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success bg-success text-white border-0 mb-4" style="border-radius: 12px;">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger bg-danger text-white border-0 mb-4" style="border-radius: 12px;">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="stat-box bg-light">
                                <h2>{{ $countXII }}</h2>
                                <p class="text-muted">Siswa Kelas 12 Aktif</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form method="POST" action="{{ route('kelulusan.proses') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Tahun Lulus</label>
                                    <select name="tahun_lulus" class="form-control" required>
                                        <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                        <option value="{{ date('Y') + 1 }}">{{ date('Y') + 1 }}</option>
                                    </select>
                                </div>

                                <div class="info-box mb-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Perhatian!</strong>
                                    <ul class="mb-0 mt-2 ps-3">
                                        <li>Status jadi <strong>ALUMNI</strong></li>
                                        <li>Tidak di absensi</li>
                                        <li>Di riwayat alumni</li>
                                    </ul>
                                </div>

                                <button type="submit" class="btn btn-kelu w-100 text-white" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
                                    <i class="fas fa-graduation-cap me-2"></i>Proses Kelulusan
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($countXII > 0)
                    <hr>
                    <h5 class="fw-bold mb-3"><i class="fas fa-users me-2"></i>Daftar Siswa Kelas 12</h5>
                    <div class="row g-3" style="max-height: 350px; overflow-y: auto;">
                        @php
                            $currentKelas = '';
                        @endphp
                        @foreach($siswaXII as $siswa)
                            @if($currentKelas != ($siswa->kelas->nama_kelas ?? ''))
                                @php
                                    $currentKelas = $siswa->kelas->nama_kelas ?? '';
                                @endphp
                                <div class="col-12">
                                    <div class="kelas-section">
                                        <h5><i class="fas fa-door-open me-2"></i>{{ $currentKelas }}</h5>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="siswa-item">
                                    <div>
                                        <div class="fw-semibold">{{ $siswa->nama }}</div>
                                        <small class="text-muted">{{ $siswa->nis }}</small>
                                    </div>
                                    <span class="badge bg-warning text-dark">Aktif</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-slash text-muted fa-2x mb-2"></i>
                        <p class="text-muted mb-0">Belum ada siswa kelas 12</p>
                    </div>
                    @endif

                    <div class="text-center mt-4">
                        <a href="{{ route('kenaikan.index') }}" class="btn btn-outline-dark">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
