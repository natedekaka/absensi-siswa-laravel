@extends('layouts.app')

@section('title', 'Konfigurasi')

@push('styles')
<style>
.logo-preview-container {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    overflow: hidden;
    border: 3px solid #e2e8f0;
}
</style>
@endpush

@push('scripts')
<script>
document.querySelector('input[name="warna_primer"]').addEventListener('input', function() {
    document.getElementById('warnaPrimerValue').value = this.value;
    updatePreview();
});

document.querySelector('input[name="warna_sekunder"]').addEventListener('input', function() {
    document.getElementById('warnaSekunderValue').value = this.value;
    updatePreview();
});

function updatePreview() {
    const primer = document.querySelector('input[name="warna_primer"]').value;
    const sekunder = document.querySelector('input[name="warna_sekunder"]').value;
    document.querySelector('.preview-gradient').style.background = 
        `linear-gradient(135deg, ${primer} 0%, ${sekunder} 100%)`;
}
</script>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h5 class="mb-0"><i class="fas fa-building me-2"></i>Profil Sekolah</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('konfigurasi.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <label class="form-label fw-semibold">Logo Sekolah</label>
                            <div class="logo-preview-container mb-3">
                                @if($config->logo && file_exists(public_path('storage/logos/' . $config->logo)))
                                    <img src="{{ asset('storage/logos/' . $config->logo) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                                @else
                                    <i class="fas fa-school text-secondary" style="font-size: 3rem;"></i>
                                @endif
                            </div>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-2">Max 2MB (JPG, PNG, GIF, WEBP)</small>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Sekolah</label>
                                <input type="text" name="nama_sekolah" class="form-control" 
                                       value="{{ old('nama_sekolah', $config->nama_sekolah) }}" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Warna Primer</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="warna_primer" class="form-control form-control-color" 
                                               value="{{ old('warna_primer', $config->warna_primer) }}" style="width: 60px; height: 45px;">
                                        <input type="text" class="form-control" value="{{ old('warna_primer', $config->warna_primer) }}" 
                                               id="warnaPrimerValue" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Warna Sekunder</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="warna_sekunder" class="form-control form-control-color" 
                                               value="{{ old('warna_sekunder', $config->warna_sekunder) }}" style="width: 60px; height: 45px;">
                                        <input type="text" class="form-control" value="{{ old('warna_sekunder', $config->warna_sekunder) }}" 
                                               id="warnaSekunderValue" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Preview Tampilan</label>
                                <div class="p-3 rounded preview-gradient" style="background: linear-gradient(135deg, {{ $config->warna_primer }} 0%, {{ $config->warna_sekunder }} 100%);">
                                    <div class="d-flex align-items-center gap-3 text-white">
                                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            @if($config->logo && file_exists(public_path('storage/logos/' . $config->logo)))
                                                <img src="{{ asset('storage/logos/' . $config->logo) }}" alt="Logo" style="width: 30px; height: 30px; object-fit: contain;">
                                            @else
                                                <i class="fas fa-school"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $config->nama_sekolah }}</div>
                                            <small>Sistem Absensi Siswa</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
