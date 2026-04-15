@extends('layouts.app')

@section('title', 'Rekap Absensi')

@push('styles')
<style>
.stat-card {
    border-radius: 12px;
    overflow: hidden;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.stat-header {
    padding: 1rem;
    color: white;
    font-weight: 600;
}
.stat-body {
    padding: 1rem;
}
.stat-item {
    text-align: center;
}
.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
}
.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
}
.smt1-header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
.smt2-header { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
.table-rekap th, .table-rekap td {
    vertical-align: middle;
    font-size: 0.85rem;
}
.table-rekap thead th {
    background: var(--primary);
    color: white;
    border: none;
}
.percent-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 600;
}
.percent-high { background: #d4edda; color: #155724; }
.percent-mid { background: #fff3cd; color: #856404; }
.percent-low { background: #f8d7da; color: #721c24; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-chart-bar me-2"></i>Rekap Absensi
    </h2>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Pilih Kelas</label>
                <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Awal</label>
                <input type="date" name="tgl_awal" class="form-control" value="{{ $tglAwal }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="tgl_akhir" class="form-control" value="{{ $tglAkhir }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

@if(!$kelasId)
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Silakan pilih kelas untuk melihat rekap absensi.
    </div>
@else

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="stat-header smt1-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Semester 1</span>
                    <span class="badge bg-light text-dark">{{ $kehadiranSmt1 }}% Kehadiran</span>
                </div>
            </div>
            <div class="stat-body">
                <p class="text-muted mb-3">{{ $hariSmt1 }} hari belajar</p>
                <div class="row text-center">
                    <div class="col-3 stat-item">
                        <div class="stat-number text-success">{{ $statsSmt1['hadir'] }}</div>
                        <div class="stat-label">Hadir</div>
                    </div>
                    <div class="col-3 stat-item">
                        <div class="stat-number text-warning">{{ $statsSmt1['terlambat'] }}</div>
                        <div class="stat-label">Telat</div>
                    </div>
                    <div class="col-3 stat-item">
                        <div class="stat-number text-secondary">{{ $statsSmt1['sakit'] }}</div>
                        <div class="stat-label">Sakit</div>
                    </div>
                    <div class="col-3 stat-item">
                        <div class="stat-number text-danger">{{ $statsSmt1['alfa'] }}</div>
                        <div class="stat-label">Alfa</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="stat-header smt2-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Semester 2</span>
                    <span class="badge bg-light text-dark">{{ $kehadiranSmt2 }}% Kehadiran</span>
                </div>
            </div>
            <div class="stat-body">
                <p class="text-muted mb-3">{{ $hariSmt2 }} hari belajar</p>
                <div class="row text-center">
                    <div class="col-3 stat-item">
                        <div class="stat-number text-success">{{ $statsSmt2['hadir'] }}</div>
                        <div class="stat-label">Hadir</div>
                    </div>
                    <div class="col-3 stat-item">
                        <div class="stat-number text-warning">{{ $statsSmt2['terlambat'] }}</div>
                        <div class="stat-label">Telat</div>
                    </div>
                    <div class="col-3 stat-item">
                        <div class="stat-number text-secondary">{{ $statsSmt2['sakit'] }}</div>
                        <div class="stat-label">Sakit</div>
                    </div>
                    <div class="col-3 stat-item">
                        <div class="stat-number text-danger">{{ $statsSmt2['alfa'] }}</div>
                        <div class="stat-label">Alfa</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <strong>Semester 1</strong>
                @if($smt1Range)
                    <span class="badge bg-light text-dark ms-2">{{ $smt1Range['nama'] }}</span>
                @endif
            </div>
            <div class="table-responsive" style="max-height: 400px;">
                <table class="table table-rekap table-hover table-sm mb-0">
                    <thead class="sticky-top">
                        <tr>
                            <th>#</th>
                            <th>Siswa</th>
                            <th>H</th>
                            <th>T</th>
                            <th>S</th>
                            <th>I</th>
                            <th>A</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($siswaSmt1 as $row)
                            @php
                                $persen = $hariSmt1 > 0 ? round(($row->hadir / $hariSmt1) * 100, 1) : 0;
                                $percentClass = $persen >= 80 ? 'percent-high' : ($persen >= 60 ? 'percent-mid' : 'percent-low');
                            @endphp
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->hadir }}</td>
                                <td>{{ $row->terlambat }}</td>
                                <td>{{ $row->sakit }}</td>
                                <td>{{ $row->izin }}</td>
                                <td>{{ $row->alfa }}</td>
                                <td><span class="percent-badge {{ $percentClass }}">{{ $persen }}%</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong>Semester 2</strong>
                @if($smt2Range)
                    <span class="badge bg-light text-dark ms-2">{{ $smt2Range['nama'] }}</span>
                @endif
            </div>
            <div class="table-responsive" style="max-height: 400px;">
                <table class="table table-rekap table-hover table-sm mb-0">
                    <thead class="sticky-top">
                        <tr>
                            <th>#</th>
                            <th>Siswa</th>
                            <th>H</th>
                            <th>T</th>
                            <th>S</th>
                            <th>I</th>
                            <th>A</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($siswaSmt2 as $row)
                            @php
                                $persen = $hariSmt2 > 0 ? round(($row->hadir / $hariSmt2) * 100, 1) : 0;
                                $percentClass = $persen >= 80 ? 'percent-high' : ($persen >= 60 ? 'percent-mid' : 'percent-low');
                            @endphp
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->hadir }}</td>
                                <td>{{ $row->terlambat }}</td>
                                <td>{{ $row->sakit }}</td>
                                <td>{{ $row->izin }}</td>
                                <td>{{ $row->alfa }}</td>
                                <td><span class="percent-badge {{ $percentClass }}">{{ $persen }}%</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body text-center">
        <a href="{{ route('rekap.export', ['kelas_id' => $kelasId, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'type' => 'excel']) }}" class="btn btn-success me-2">
            <i class="fas fa-file-excel me-2"></i>Export Excel
        </a>
        <a href="{{ route('rekap.export', ['kelas_id' => $kelasId, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'type' => 'pdf']) }}" class="btn btn-primary">
            <i class="fas fa-print me-2"></i>Cetak PDF
        </a>
    </div>
</div>

@endif
@endsection
