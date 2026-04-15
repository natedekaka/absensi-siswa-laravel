@extends('layouts.app')

@section('title', 'Input Absensi')

@section('content')
<style>
    .table-absensi {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .table-absensi thead {
        background: var(--primary);
        color: white;
    }
    .table-absensi th, .table-absensi td {
        vertical-align: middle;
        text-align: center;
        padding: 0.5rem;
        font-size: 0.85rem;
    }
    .table-absensi td:first-child { text-align: center; }
    .table-absensi td:nth-child(3) { text-align: left; }
    .table-absensi tbody tr:hover { background: #f8f9fa; }
    .col-hadir, .col-status { width: 50px; min-width: 50px; }
    .col-rekap { min-width: 160px; white-space: nowrap; }
    .rekap-badge {
        font-size: 0.65rem;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 2px 4px;
        display: inline-flex;
        gap: 2px;
    }
    .rekap-badge span {
        padding: 1px 3px;
        border-radius: 3px;
    }
    .rekap-h { background: #d4edda; color: #155724; font-weight: bold; }
    .rekap-t { background: #fff3cd; color: #856404; font-weight: bold; }
    .rekap-s { background: #e2e3e5; color: #383d41; font-weight: bold; }
    .rekap-i { background: #d1ecf1; color: #0c5460; font-weight: bold; }
    .rekap-a { background: #f8d7da; color: #721c24; font-weight: bold; }
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.75rem;
        color: white;
    }
    .status-hadir { background: #28a745; }
    .status-terlambat { background: #ffb142; }
    .status-sakit { background: #778ca3; }
    .status-izin { background: #2ed573; }
    .status-alfa { background: #ff5252; }
    .status-kosong { background: #aaa; }
    .attendance-radio input {
        width: 18px;
        height: 18px;
        accent-color: #28a745;
        cursor: pointer;
    }
    .search-box {
        position: relative;
    }
    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    .search-box input {
        padding-left: 35px;
    }
</style>

<div class="d-flex align-items-center mb-4 flex-wrap gap-2">
    <h2 class="fw-bold mb-0">
        <i class="fas fa-clipboard-check me-2"></i>Input Absensi Harian
    </h2>
</div>

<form id="form-absensi">
    @csrf
    <input type="hidden" name="kelas_id" id="kelas_id">
    <input type="hidden" name="semester_id" id="semester_id">

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-3 h-100">
                <label class="form-label fw-semibold mb-2">
                    <i class="fas fa-calendar-alt me-2"></i>Tanggal
                </label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" 
                       value="{{ date('Y-m-d') }}" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 h-100">
                <label class="form-label fw-semibold mb-2">
                    <i class="fas fa-graduation-cap me-2"></i>Semester
                </label>
                <select id="semester" name="semester_id" class="form-select" required>
                    <option value="">Pilih Semester</option>
                    @foreach($semesters as $s)
                    <option value="{{ $s->id }}" {{ $s->is_active ? 'selected' : '' }}>{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 h-100">
                <label class="form-label fw-semibold mb-2">
                    <i class="fas fa-door-open me-2"></i>Kelas
                </label>
                <select id="kelas" class="form-select" required>
                    <option value="">Pilih Kelas</option>
                    <option value="all">Semua Kelas</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row mb-4" id="searchContainer" style="display: none;">
        <div class="col-md-6">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="search_nama" class="form-control" placeholder="Cari nama siswa...">
            </div>
        </div>
    </div>

    <div id="tombolSimpanAtas" class="mb-4" style="display: none;">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Simpan Absensi
        </button>
    </div>

    <div id="siswa-container" class="mb-4"></div>

    <div id="tombolSimpanBawah" class="text-center" style="display: none;">
        <button type="submit" class="btn btn-primary btn-lg px-5">
            <i class="fas fa-save me-2"></i>Simpan Semua Absensi
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.getElementById('form-absensi').addEventListener('submit', function(e) {
    e.preventDefault();
    simpanAbsensi();
    return false;
});

function toggleElements(show) {
    const display = show ? 'block' : 'none';
    document.getElementById('tombolSimpanAtas').style.display = display;
    document.getElementById('tombolSimpanBawah').style.display = display;
    document.getElementById('searchContainer').style.display = display;
}

document.getElementById('semester').addEventListener('change', function() {
    document.getElementById('semester_id').value = this.value;
    loadSiswa();
});

document.getElementById('kelas').addEventListener('change', function() {
    const kelasId = this.value;
    document.getElementById('kelas_id').value = kelasId;
    if (kelasId) {
        toggleElements(true);
        loadSiswa();
    } else {
        toggleElements(false);
        document.getElementById('siswa-container').innerHTML = '';
    }
});

document.getElementById('tanggal').addEventListener('change', loadSiswa);
document.getElementById('search_nama').addEventListener('input', loadSiswa);

function loadSiswa() {
    const kelasId = document.getElementById('kelas').value;
    const tanggal = document.getElementById('tanggal').value;
    const semesterId = document.getElementById('semester').value;
    const search = document.getElementById('search_nama').value;

    if (kelasId && semesterId) {
        let url = '{{ route("absensi.get-siswa") }}?kelas_id=' + encodeURIComponent(kelasId) + 
                  '&tanggal=' + encodeURIComponent(tanggal) + 
                  '&semester_id=' + encodeURIComponent(semesterId);
        if (search) url += '&search=' + encodeURIComponent(search);

        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById('siswa-container').innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('siswa-container').innerHTML = '<div class="alert alert-danger">Gagal memuat data siswa.</div>';
            });
    } else if (kelasId) {
        document.getElementById('siswa-container').innerHTML = '<div class="alert alert-warning">Pilih semester terlebih dahulu!</div>';
    }
}

function simpanAbsensi() {
    const submitBtn = document.querySelector('#tombolSimpanBawah button');
    const originalText = submitBtn.innerHTML;
    
    const semesterId = document.getElementById('semester').value;
    const tanggal = document.getElementById('tanggal').value;
    
    const statuses = {};
    const radioButtons = document.querySelectorAll('input[type="radio"]:checked');
    for (let i = 0; i < radioButtons.length; i++) {
        const radio = radioButtons[i];
        if (radio.name.indexOf('status[') === 0) {
            const match = radio.name.match(/status\[(\d+)\]/);
            if (match) {
                statuses[match[1]] = radio.value;
            }
        }
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    
    fetch('{{ route("absensi.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            tanggal: tanggal,
            semester_id: semesterId,
            status: statuses
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            loadSiswa();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan!');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}
</script>
@endpush
