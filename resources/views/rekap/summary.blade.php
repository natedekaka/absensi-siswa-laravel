@extends('layouts.app')

@section('title', 'Rekap Summary')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Rekap Per Kelas</h4>
        @if($semester)
            <small class="text-muted">{{ $semester->nama }}</small>
        @endif
    </div>
    <a href="{{ route('rekap.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Kelas</th>
                        <th class="text-center">Wali Kelas</th>
                        <th class="text-center">Jumlah Siswa</th>
                        <th class="text-center">Hadir</th>
                        <th class="text-center">Sakit</th>
                        <th class="text-center">Izin</th>
                        <th class="text-center">Alfa</th>
                        <th class="text-center">Terlambat</th>
                        <th class="text-center">% Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas as $k)
                    <tr>
                        <td>
                            <a href="{{ route('rekap.by-kelas', $k->id) }}">
                                <strong>{{ $k->nama_kelas }}</strong>
                            </a>
                        </td>
                        <td class="text-center">{{ $k->wali_kelas }}</td>
                        <td class="text-center">{{ $k->total_siswa ?? 0 }}</td>
                        <td class="text-center text-success">{{ $stats[$k->id]['hadir'] ?? 0 }}</td>
                        <td class="text-center text-warning">{{ $stats[$k->id]['sakit'] ?? 0 }}</td>
                        <td class="text-center text-info">{{ $stats[$k->id]['izin'] ?? 0 }}</td>
                        <td class="text-center text-danger">{{ $stats[$k->id]['alfa'] ?? 0 }}</td>
                        <td class="text-center text-secondary">{{ $stats[$k->id]['terlambat'] ?? 0 }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $stats[$k->id]['persentase_hadir'] >= 90 ? 'success' : ($stats[$k->id]['persentase_hadir'] >= 70 ? 'warning' : 'danger') }}">
                                {{ $stats[$k->id]['persentase_hadir'] ?? 0 }}%
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">Belum ada data kelas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
