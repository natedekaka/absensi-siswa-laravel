@extends('layouts.app')

@section('title', 'Rekap ' . $siswa->nama)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Riwayat Absensi</h4>
        <small class="text-muted">{{ $siswa->nama }} | {{ $siswa->kelas->nama_kelas ?? '-' }}</small>
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-2 col-6">
        <div class="card card-stat">
            <div class="card-body text-center">
                <h4 class="text-primary">{{ $summary['total'] }}</h4>
                <small class="text-muted">Total</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card card-stat">
            <div class="card-body text-center">
                <h4 class="text-success">{{ $summary['hadir'] }}</h4>
                <small class="text-muted">Hadir</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card card-stat">
            <div class="card-body text-center">
                <h4 class="text-warning">{{ $summary['sakit'] }}</h4>
                <small class="text-muted">Sakit</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card card-stat">
            <div class="card-body text-center">
                <h4 class="text-info">{{ $summary['izin'] }}</h4>
                <small class="text-muted">Izin</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card card-stat">
            <div class="card-body text-center">
                <h4 class="text-danger">{{ $summary['alfa'] }}</h4>
                <small class="text-muted">Alfa</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="card card-stat">
            <div class="card-body text-center">
                <h4 class="text-secondary">{{ $summary['terlambat'] }}</h4>
                <small class="text-muted">Terlambat</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $absen)
                    <tr>
                        <td>{{ $absen->tanggal->format('d/m/Y') }}</td>
                        <td>
                            @switch($absen->status)
                                @case('Hadir')
                                    <span class="badge bg-success">{{ $absen->status }}</span>
                                    @break
                                @case('Sakit')
                                    <span class="badge bg-warning text-dark">{{ $absen->status }}</span>
                                    @break
                                @case('Izin')
                                    <span class="badge bg-info">{{ $absen->status }}</span>
                                    @break
                                @case('Terlambat')
                                    <span class="badge bg-secondary">{{ $absen->status }}</span>
                                    @break
                                @default
                                    <span class="badge bg-danger">{{ $absen->status }}</span>
                            @endswitch
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $absensis->links() }}
        </div>
    </div>
</div>
@endsection
