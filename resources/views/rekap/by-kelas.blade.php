@extends('layouts.app')

@section('title', 'Rekap ' . $kelas->nama_kelas)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Rekap {{ $kelas->nama_kelas }}</h4>
        <small class="text-muted">
            Wali Kelas: {{ $kelas->wali_kelas }} | 
            @if($semester) {{ $semester->nama }} @endif
        </small>
    </div>
    <a href="{{ route('rekap.summary') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th class="text-center">Total</th>
                        <th class="text-center text-success">Hadir</th>
                        <th class="text-center text-warning">Sakit</th>
                        <th class="text-center text-info">Izin</th>
                        <th class="text-center text-danger">Alfa</th>
                        <th class="text-center text-secondary">Terlambat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $key => $siswa)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $siswa->nis }}</td>
                        <td>{{ $siswa->nama }}</td>
                        <td class="text-center">{{ $stats[$siswa->id]['total'] }}</td>
                        <td class="text-center text-success">{{ $stats[$siswa->id]['hadir'] }}</td>
                        <td class="text-center text-warning">{{ $stats[$siswa->id]['sakit'] }}</td>
                        <td class="text-center text-info">{{ $stats[$siswa->id]['izin'] }}</td>
                        <td class="text-center text-danger">{{ $stats[$siswa->id]['alfa'] }}</td>
                        <td class="text-center text-secondary">{{ $stats[$siswa->id]['terlambat'] }}</td>
                        <td>
                            <a href="{{ route('rekap.by-siswa', $siswa->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Belum ada siswa</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
