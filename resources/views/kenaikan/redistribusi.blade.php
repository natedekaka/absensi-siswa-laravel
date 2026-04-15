@extends('layouts.app')

@section('title', 'Redistribusi Kelas')

@push('styles')
<style>
.kelas-section {
    background: white;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.kelas-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    padding: 1rem 1.5rem;
    font-weight: 600;
}
.siswa-list {
    padding: 1rem;
}
.siswa-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    background: #f8f9fa;
    transition: all 0.2s;
}
.siswa-item:hover {
    background: #e9ecef;
}
.siswa-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin-right: 1rem;
    accent-color: #4f46e5;
}
.siswa-nama {
    font-weight: 600;
    color: #333;
}
.siswa-nis {
    font-size: 0.85rem;
    color: #666;
}
.siswa-jk {
    font-size: 0.8rem;
    padding: 2px 8px;
    border-radius: 4px;
    background: #e9ecef;
}
</style>
@endpush

@push('scripts')
<script>
function selectAll() {
    document.querySelectorAll('input[name="siswa_ids[]"]').forEach(cb => cb.checked = true);
}
function deselectAll() {
    document.querySelectorAll('input[name="siswa_ids[]"]').forEach(cb => cb.checked = false);
}
</script>
@endpush

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('kenaikan.index') }}" class="btn btn-outline-secondary me-3">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h2 class="fw-bold text-wa-dark mb-0">
        <i class="fas fa-random me-2"></i>Redistribusi Kelas 10 → 11
    </h2>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mb-4">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        @if(!empty($siswaByKelas))
            <form method="POST" id="redistribusiForm">
                @csrf
                <input type="hidden" name="action" value="pindahkan">
                
                @foreach($siswaByKelas as $kelasId => $data)
                <div class="kelas-section">
                    <div class="kelas-header d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-door-open me-2"></i>{{ $data['nama_kelas'] }}
                        </span>
                        <span class="badge bg-white text-primary">{{ count($data['siswa']) }} siswa</span>
                    </div>
                    <div class="siswa-list">
                        @foreach($data['siswa'] as $siswa)
                        <div class="siswa-item">
                            <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}" id="siswa_{{ $siswa->id }}">
                            <label for="siswa_{{ $siswa->id }}" class="d-flex align-items-center w-100">
                                <div class="flex-grow-1">
                                    <div class="siswa-nama">{{ $siswa->nama }}</div>
                                    <div class="siswa-nis">NIS: {{ $siswa->nis }}</div>
                                </div>
                                <span class="siswa-jk">
                                    {{ $siswa->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}
                                </span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </form>
        @else
            <div class="alert alert-info">Tidak ada siswa kelas 10</div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm sticky-top" style="top: 100px;">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fas fa-paper-plane me-2"></i>Pindahkan Siswa
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('redistribusi.pindahkan') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kelas Tujuan (Kelas 11)</label>
                        <select name="kelas_tujuan" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasXI as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Aksi</label>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAll()">
                                <i class="fas fa-check-square me-1"></i>Pilih Semua
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deselectAll()">
                                <i class="fas fa-square me-1"></i>Batal Pilih
                            </button>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-random me-2"></i>Pindahkan
                    </button>
                    
                    <a href="{{ route('kenaikan.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </form>
            </div>
        </div>
        
        <div class="card shadow-sm mt-3">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                <i class="fas fa-lightbulb me-2"></i>Cara Menggunakan
            </div>
            <div class="card-body">
                <ol class="mb-0" style="padding-left: 1.2rem; font-size: 0.9rem;">
                    <li class="mb-2">Ceklis siswa yang ingin dipindahkan</li>
                    <li class="mb-2">Pilih kelas tujuan (XI-IPA, XI-IPS, dll)</li>
                    <li class="mb-2">Klik tombol "Pindahkan"</li>
                    <li class="mb-2">Ulangi untuk kelompok siswa berikutnya</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
