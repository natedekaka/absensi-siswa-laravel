@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
<span class="navbar-text">
    <i class="fas fa-calendar-day me-1"></i> {{ \Carbon\Carbon::now()->format('d M Y') }}
</span>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4>Dashboard</h4>
        <p class="text-muted">
            @if($semesterAktif)
                {{ $semesterAktif->nama }} - 
                @if($tahunAjaranAktif)
                    {{ $tahunAjaranAktif->nama }}
                @endif
            @else
                Tidak ada semester aktif
            @endif
        </p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-2 col-6 mb-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary text-white me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $stats['total'] }}</h5>
                        <small class="text-muted">Total Absensi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success text-white me-3">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $stats['hadir'] }}</h5>
                        <small class="text-muted">Hadir</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-warning text-white me-3">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $stats['sakit'] }}</h5>
                        <small class="text-muted">Sakit</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-info text-white me-3">
                        <i class="fas fa-file-medical"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $stats['izin'] }}</h5>
                        <small class="text-muted">Izin</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-danger text-white me-3">
                        <i class="fas fa-times"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $stats['alfa'] }}</h5>
                        <small class="text-muted">Alfa</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-3">
        <div class="card card-stat">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="icon bg-secondary text-white me-3">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $stats['terlambat'] }}</h5>
                        <small class="text-muted">Terlambat</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Statistik Absensi</h5>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ request()->fullUrlWithQuery(['period' => 7]) }}" class="btn {{ request('period', 7) == 7 ? 'btn-primary' : 'btn-outline-secondary' }}">7 Hari</a>
                        <a href="{{ request()->fullUrlWithQuery(['period' => 30]) }}" class="btn {{ request('period') == 30 ? 'btn-primary' : 'btn-outline-secondary' }}">30 Hari</a>
                        <a href="{{ request()->fullUrlWithQuery(['period' => 90]) }}" class="btn {{ request('period') == 90 ? 'btn-primary' : 'btn-outline-secondary' }}">90 Hari</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Aktivitas Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentActivity as $activity)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $activity->siswa->nama ?? 'N/A' }}</strong>
                                <br><small class="text-muted">{{ $activity->siswa->kelas->nama_kelas ?? '' }}</small>
                            </div>
                            <div class="text-end">
                                @if($activity->status == 'Hadir')
                                    <span class="badge bg-success">{{ $activity->status }}</span>
                                @elseif($activity->status == 'Sakit')
                                    <span class="badge bg-warning">{{ $activity->status }}</span>
                                @elseif($activity->status == 'Izin')
                                    <span class="badge bg-info">{{ $activity->status }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $activity->status }}</span>
                                @endif
                                <br><small class="text-muted">{{ $activity->tanggal->format('d/m/Y') }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted">
                        Belum ada data absensi
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('attendanceChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [
            {
                label: 'Hadir',
                data: {!! json_encode($chartData['hadirData']) !!},
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Sakit',
                data: {!! json_encode($chartData['sakitData']) !!},
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Izin',
                data: {!! json_encode($chartData['izinData']) !!},
                borderColor: '#0dcaf0',
                backgroundColor: 'rgba(13, 202, 240, 0.1)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Alfa',
                data: {!! json_encode($chartData['alfaData']) !!},
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                tension: 0.3,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
