@extends('layouts.app')

@section('title', 'Manajemen Kenaikan Kelas')

@push('styles')
<style>
.stat-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}
.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.btn-action {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
}
.step-card {
    border: none;
    border-radius: 20px;
    transition: all 0.3s ease;
    height: 100%;
}
.step-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}
.step-header {
    padding: 1.25rem 1.5rem;
    color: white;
    position: relative;
    border-radius: 20px 20px 0 0;
}
.step-header.step-1 { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); }
.step-header.step-2 { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
.step-header.step-3 { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
.step-header.step-4 { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
.step-number {
    position: absolute;
    top: -12px;
    right: 15px;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    background: white;
    color: #333;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
.step-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    margin: 0 auto 1rem;
}
.step-icon.bg-purple { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
.step-icon.bg-green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.step-icon.bg-yellow { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.step-icon.bg-red { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
.kelas-ref-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
}
.kelas-ref-card .card-header-custom {
    background: linear-gradient(135deg, var(--wa-dark) 0%, #0d6e67 100%);
    color: white;
}
.alumni-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
}
.alumni-card .card-header-custom {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
}
.kelas-badge-ref {
    display: inline-block;
    background: #e0e7ff;
    color: #4338ca;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-wa-dark mb-0">
        <i class="fas fa-graduation-cap me-2"></i>Manajemen Kenaikan Kelas
    </h2>
</div>

@if(session('success'))
    <div class="alert alert-success bg-success text-white border-0 rounded-3 mb-4">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger bg-danger text-white border-0 rounded-3 mb-4">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    </div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="ms-3">
                    <h4 class="mb-0">{{ $siswaX }}</h4>
                    <small class="text-muted">Siswa Kelas 10</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="ms-3">
                    <h4 class="mb-0">{{ $siswaXI }}</h4>
                    <small class="text-muted">Siswa Kelas 11</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="ms-3">
                    <h4 class="mb-0">{{ $siswaXII }}</h4>
                    <small class="text-muted">Siswa Kelas 12</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm p-4">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="ms-3">
                    <h4 class="mb-0">{{ $alumni }}</h4>
                    <small class="text-muted">Alumni</small>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="fw-bold mb-3"><i class="fas fa-tasks me-2"></i>Langkah-Langkah</h4>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="step-card shadow-sm">
            <div class="step-header step-1">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-arrow-up me-2"></i>Kenaikan Tingkat</h5>
                    <span class="step-number">1</span>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Naikkan tingkat siswa (X→XI, XI→XII). Kelas belum berubah.</p>
                <form method="POST" action="{{ route('kenaikan.naik-tingkat') }}">
                    @csrf
                    <div class="mb-3">
                        <select name="tingkat_dari" class="form-select" required onchange="this.form.tingkat_ke.value = parseInt(this.value) + 1">
                            <option value="">Pilih tingkat...</option>
                            <option value="10">Kelas 10 → 11</option>
                            <option value="11">Kelas 11 → 12</option>
                        </select>
                        <input type="hidden" name="tingkat_ke" value="">
                    </div>
                    <button type="submit" class="btn btn-primary btn-action w-100">
                        <i class="fas fa-arrow-up me-2"></i>Proses Kenaikan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="step-card shadow-sm">
            <div class="step-header step-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-random me-2"></i>Redistribusi Kelas</h5>
                    <span class="step-number">2</span>
                </div>
            </div>
            <div class="card-body text-center">
                <div class="step-icon bg-green mb-3">
                    <i class="fas fa-random"></i>
                </div>
                <p class="text-muted mb-3">Pindahkan siswa ke kelas/jurusan baru (IPA/IPS/Bahasa)</p>
                <a href="{{ route('redistribusi.index') }}" class="btn btn-success btn-action w-100">
                    <i class="fas fa-random me-2"></i>Buka Redistribusi
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="step-card shadow-sm">
            <div class="step-header step-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Kelulusan</h5>
                    <span class="step-number">3</span>
                </div>
            </div>
            <div class="card-body text-center">
                <div class="step-icon bg-yellow mb-3">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <p class="text-muted mb-3">Proses kelulusan siswa kelas 12 → alumni</p>
                <a href="{{ route('kelulusan.index') }}" class="btn btn-warning btn-action w-100 text-white" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
                    <i class="fas fa-user-graduate me-2"></i>Buka Kelulusan
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="step-card shadow-sm">
            <div class="step-header step-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-export me-2"></i>Export/Import CSV</h5>
                    <span class="step-number">4</span>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('kenaikan.export') }}" class="mb-3">
                    @csrf
                    <label class="form-label fw-semibold small">Export Siswa</label>
                    <div class="input-group">
                        <select name="tingkat_export" class="form-select" required>
                            <option value="10">Kelas 10</option>
                            <option value="11">Kelas 11</option>
                        </select>
                        <button type="submit" class="btn btn-outline-dark">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </form>
                <hr>
                <form method="POST" action="{{ route('kenaikan.import') }}" enctype="multipart/form-data">
                    @csrf
                    <label class="form-label fw-semibold small">Import ke Kelas Baru</label>
                    <input type="file" name="csv_file" class="form-control mb-2" accept=".csv" required>
                    <small class="text-muted d-block mb-2">Format: NIS;NISN;Nama;Kelas Lama;;ID_Kelas_Baru</small>
                    <button type="submit" class="btn btn-primary btn-action w-100">
                        <i class="fas fa-upload me-2"></i>Import CSV
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="kelas-ref-card shadow-sm">
            <div class="card-header-custom p-3">
                <i class="fas fa-building me-2"></i>Daftar Kelas (Referensi ID)
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 250px;">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">ID</th>
                                <th>Nama Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kelas as $k)
                            <tr>
                                <td class="text-center"><span class="kelas-badge-ref">{{ $k->id }}</span></td>
                                <td>{{ $k->nama_kelas }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="alumni-card shadow-sm">
            <div class="card-header-custom p-3">
                <i class="fas fa-user-graduate me-2"></i>Daftar Alumni
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 250px;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th class="text-center">Tahun Lulus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alumniList as $a)
                            <tr>
                                <td>{{ $a->nis }}</td>
                                <td>{{ $a->nama }}</td>
                                <td class="text-center"><span class="badge bg-primary">{{ $a->tahun_lulus }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada alumni</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
