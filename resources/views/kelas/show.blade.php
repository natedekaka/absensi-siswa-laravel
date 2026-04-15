@extends('layouts.app')

@section('title', 'Detail Kelas - ' . $kelas->nama_kelas)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('kelas.index') }}" class="btn btn-secondary mb-2">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <h4 class="mb-0">{{ $kelas->nama_kelas }}</h4>
        <small class="text-muted">Wali Kelas: {{ $kelas->wali_kelas }}</small>
    </div>
    <div>
        <a href="{{ route('kelas.edit', $kelas->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('absensi.create') }}?kelas_id={{ $kelas->id }}" class="btn btn-primary">
            <i class="fas fa-clipboard-check me-1"></i> Absensi
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Siswa ({{ $siswas->count() }} siswa)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $key => $siswa)
                    <tr>
                        <td>{{ $siswas->firstItem() + $key }}</td>
                        <td>{{ $siswa->nis }}</td>
                        <td>{{ $siswa->nama }}</td>
                        <td>{{ $siswa->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}</td>
                        <td>
                            @if($siswa->status == 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Alumni</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('siswa.show', $siswa->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada siswa di kelas ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $siswas->links() }}
        </div>
    </div>
</div>
@endsection
