@extends('layouts.app')

@section('title', 'Detail Siswa - ' . $siswa->nama)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('siswa.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-user-circle fa-5x text-secondary mb-3"></i>
                <h4>{{ $siswa->nama }}</h4>
                <p class="text-muted">{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                <hr>
                <div class="text-start">
                    <p><strong>NIS:</strong> {{ $siswa->nis }}</p>
                    <p><strong>NISN:</strong> {{ $siswa->nisn }}</p>
                    <p><strong>JK:</strong> {{ $siswa->jenis_kelamin }}</p>
                    <p><strong>Status:</strong> 
                        @if($siswa->status == 'aktif')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Alumni</span>
                        @endif
                    </p>
                    <p><strong>Tingkat:</strong> {{ $siswa->tingkat ? 'Kelas ' . $siswa->tingkat : '-' }}</p>
                    <p><strong>Wali Kelas:</strong> {{ $siswa->kelas->wali_kelas ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Riwayat Absensi</h5>
            </div>
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
                                            <span class="badge bg-warning">{{ $absen->status }}</span>
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
                                <td colspan="2" class="text-center text-muted">Belum ada data absensi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
