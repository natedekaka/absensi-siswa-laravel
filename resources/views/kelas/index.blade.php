@extends('layouts.app')

@section('title', 'Data Kelas')

@push('styles')
<style>
.class-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}
.class-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}
.class-card-header {
    background: linear-gradient(135deg, var(--primary) 0%, #6c5ce7 100%);
    padding: 1.25rem;
    position: relative;
}
.class-card-header .kelas-icon {
    position: absolute;
    right: -10px;
    bottom: -10px;
    font-size: 4rem;
    opacity: 0.15;
    color: white;
}
.class-card-body {
    padding: 1.25rem;
}
.kelas-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 500;
}
.badge-wali {
    background: rgba(79, 70, 229, 0.1);
    color: var(--primary);
}
.badge-siswa {
    background: rgba(37, 211, 102, 0.1);
    color: #1ebe57;
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="fw-bold mb-0">
        <i class="fas fa-door-open me-2"></i>Data Kelas
    </h2>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('kelas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i><span class="d-none d-md-inline">Tambah</span>
        </a>
        <a href="{{ route('kelas.import') }}" class="btn btn-success">
            <i class="fas fa-file-import me-1"></i><span class="d-none d-md-inline">Import</span>
        </a>
    </div>
</div>

@if($kelass->count() > 0)
<div class="row g-4">
    @foreach($kelass as $kelas)
    <div class="col-md-6 col-lg-4">
        <div class="card class-card h-100 shadow-sm">
            <div class="class-card-header text-white position-relative">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ $kelas->nama_kelas }}</h5>
                        <small class="opacity-75">Kelas</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light text-dark rounded-circle" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item py-2" href="{{ route('kelas.edit', $kelas->id) }}">
                                <i class="fas fa-edit me-2 text-warning"></i>Edit
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item py-2 text-danger" href="{{ route('kelas.destroy', $kelas->id) }}" 
                                   onclick="return confirm('Yakin hapus kelas {{ $kelas->nama_kelas }}?')">
                                    <i class="fas fa-trash me-2"></i>Hapus
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <i class="fas fa-school kelas-icon"></i>
            </div>
            <div class="class-card-body d-flex flex-column">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="kelas-badge badge-wali">
                        <i class="fas fa-user-tie"></i>
                        {{ $kelas->wali_kelas ?? 'Belum ada' }}
                    </span>
                    <span class="kelas-badge badge-siswa">
                        <i class="fas fa-users"></i>
                        {{ $kelas->total_siswa }} Siswa
                    </span>
                </div>
                <div class="mt-auto d-flex gap-2">
                    <a href="{{ route('siswa.index') }}?kelas_id={{ $kelas->id }}" class="btn btn-outline-dark flex-fill">
                        <i class="fas fa-users me-1"></i> Lihat Siswa
                    </a>
                    <a href="{{ route('absensi.index') }}?kelas_id={{ $kelas->id }}" class="btn btn-success flex-fill">
                        <i class="fas fa-clipboard-check me-1"></i> Absensi
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card p-5 text-center">
    <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
    <p class="text-muted mb-0">Belum ada data kelas</p>
    <a href="{{ route('kelas.create') }}" class="btn btn-primary mt-3">
        <i class="fas fa-plus me-2"></i>Tambah Kelas
    </a>
</div>
@endif
@endsection
