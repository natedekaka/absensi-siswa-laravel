@extends('layouts.app')

@section('title', 'Tahun Ajaran & Semester')

@push('styles')
<style>
.tahun-card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
}
.tahun-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
}
.tahun-card.active-ta {
    border: 2px solid var(--wa-green);
}
.tahun-header {
    background: linear-gradient(135deg, var(--wa-dark) 0%, #0d6e67 100%);
    padding: 1.5rem;
    color: white;
    position: relative;
}
.tahun-header.active {
    background: linear-gradient(135deg, var(--wa-green) 0%, #1ebe57 100%);
}
.tahun-header .badge-status {
    position: absolute;
    top: 1rem;
    right: 1rem;
}
.semester-item {
    border-left: 3px solid #ccc;
    padding: 0.75rem 1rem;
    margin: 0.5rem 0;
    background: #f8f9fa;
    border-radius: 0 10px 10px 0;
    transition: all 0.2s;
    position: relative;
}
.semester-item:hover {
    background: var(--wa-light);
}
.semester-item.active {
    border-left: 4px solid var(--wa-green);
    background: linear-gradient(90deg, rgba(37,211,102,0.15) 0%, #f8f9fa 100%);
    box-shadow: 0 2px 8px rgba(37,211,102,0.2);
}
.semester-item.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--wa-green);
    border-radius: 10px 0 0 10px;
}
.semester-badge-active {
    background: linear-gradient(135deg, var(--wa-green) 0%, #1ebe57 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    box-shadow: 0 2px 4px rgba(37,211,102,0.3);
}
.semester-badge-inactive {
    background: #e0e0e0;
    color: #666;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
}
.btn-semester {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.add-card {
    border: 2px dashed #ccc;
    border-radius: 20px;
    background: transparent;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
}
.add-card:hover {
    border-color: var(--wa-dark);
    background: rgba(18, 140, 126, 0.05);
}
.modal-header-gradient {
    background: linear-gradient(135deg, var(--wa-dark) 0%, #0d6e67 100%);
    color: white;
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-wa-dark mb-0">
        <i class="fas fa-calendar-alt me-2"></i>Tahun Ajaran & Semester
    </h2>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row g-4">
    @forelse($tahunAjaran as $ta)
    <div class="col-md-6 col-lg-4">
        <div class="card tahun-card shadow-sm h-100 {{ $ta->is_active ? 'active-ta' : '' }}">
            <div class="tahun-header {{ $ta->is_active ? 'active' : '' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1 fw-bold">
                            <i class="fas fa-school me-2"></i>{{ $ta->nama }}
                        </h5>
                        <small class="opacity-75">{{ $ta->semesters->count() }} Semester</small>
                    </div>
                    @if($ta->is_active)
                    <span class="badge bg-light text-success badge-status">
                        <i class="fas fa-check-circle me-1"></i>Aktif
                    </span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($ta->semesters->count() > 0)
                    @foreach($ta->semesters->sortBy('semester') as $sm)
                    <div class="semester-item d-flex justify-content-between align-items-center {{ $sm->is_active ? 'active' : '' }}">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2">
                                @if($sm->is_active)
                                    <span class="semester-badge-active">
                                        <i class="fas fa-star"></i> AKTIF
                                    </span>
                                @endif
                                <div class="fw-semibold {{ $sm->is_active ? 'text-success' : '' }}">
                                    <i class="fas fa-book me-1 {{ $sm->is_active ? 'text-success' : 'text-muted' }}"></i>
                                    {{ $sm->nama }}
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($sm->tgl_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($sm->tgl_selesai)->format('d M Y') }}
                            </small>
                        </div>
                        <div class="d-flex gap-1 ms-2">
                            @if($sm->is_active)
                                <span class="text-success" title="Semester Aktif">
                                    <i class="fas fa-check-circle fa-lg"></i>
                                </span>
                            @else
                                <form method="POST" class="d-inline" action="{{ route('tahun-ajaran.semester.activate', $sm->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success btn-semester" title="Aktifkan">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>
                            @endif
                            <form method="POST" class="d-inline" action="{{ route('tahun-ajaran.semester.destroy', $sm->id) }}" onsubmit="return confirm('Yakin hapus semester ini?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger btn-semester" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-folder-open fa-2x mb-2"></i>
                        <p class="mb-0 small">Belum ada semester</p>
                    </div>
                @endif
                
                <button class="btn btn-outline-primary btn-sm w-100 mt-3" data-bs-toggle="modal" data-bs-target="#modalSemester{{ $ta->id }}">
                    <i class="fas fa-plus me-1"></i> Tambah Semester
                </button>
            </div>
            <div class="card-footer bg-transparent d-flex justify-content-between">
                <small class="text-muted">Dibuat: {{ $ta->created_at ? \Carbon\Carbon::parse($ta->created_at)->format('d/m/Y') : date('d/m/Y') }}</small>
                <form method="POST" action="{{ route('tahun-ajaran.destroy', $ta->id) }}" onsubmit="return confirm('Yakin hapus tahun ajaran {{ $ta->nama }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSemester{{ $ta->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-gradient">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Tambah Semester
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('tahun-ajaran.semester.store') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="tahun_ajaran_id" value="{{ $ta->id }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="1">Semester 1 (Ganjil)</option>
                                <option value="2">Semester 2 (Genap)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" name="tgl_mulai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Selesai</label>
                            <input type="date" name="tgl_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-wa-primary">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center">
        <div class="card p-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <p class="text-muted">Belum ada tahun ajaran</p>
        </div>
    </div>
    @endforelse

    <div class="col-md-6 col-lg-4">
        <button class="add-card w-100 h-100" data-bs-toggle="modal" data-bs-target="#modalTahunAjaran">
            <div class="text-center p-4">
                <i class="fas fa-plus-circle fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">Tambah Tahun Ajaran</h6>
            </div>
        </button>
    </div>
</div>

<div class="modal fade" id="modalTahunAjaran" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-gradient">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Tambah Tahun Ajaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('tahun-ajaran.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Tahun Ajaran</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-calendar"></i></span>
                            <input type="text" name="nama" class="form-control" placeholder="2025/2026" required>
                        </div>
                        <small class="text-muted">Contoh: 2025/2026</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-wa-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
