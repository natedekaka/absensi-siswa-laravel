@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    :root {
        --primary: {{ $config->warna_primer }};
        --secondary: {{ $config->warna_sekunder }};
    }
    .stat-card-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border: none;
        border-radius: 16px;
        color: white;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card-primary:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }
    .stat-card-white {
        background: white;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card-white:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .chart-card {
        background: white;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .chart-header {
        border-bottom: 2px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
    }
    .activity-card {
        background: white;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .badge-hadir { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); }
    .badge-sakit { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .badge-izin { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
    .badge-alfa { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border: none;
        color: white;
    }
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Dashboard</h4>
                <p class="text-muted mb-0">
                    @if($semesterAktif)
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ $semesterAktif->nama }} - 
                        @if($tahunAjaranAktif)
                            {{ $tahunAjaranAktif->nama }}
                        @endif
                    @else
                        <span class="badge bg-warning text-dark">Tidak ada semester aktif</span>
                    @endif
                </p>
            </div>
            <div>
                <span class="badge btn-primary-custom px-3 py-2">
                    <i class="fas fa-calendar-day me-1"></i>
                    {{ \Carbon\Carbon::now()->format('d M Y') }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="card stat-card-primary">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-white bg-opacity-25 me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">{{ $stats['total'] }}</h4>
                        <small class="opacity-75">Total Absensi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="card stat-card-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-success bg-opacity-10 text-success me-3">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-success">{{ $stats['hadir'] }}</h4>
                        <small class="text-muted">Hadir</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="card stat-card-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-warning bg-opacity-10 text-warning me-3">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-warning">{{ $stats['sakit'] }}</h4>
                        <small class="text-muted">Sakit</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="card stat-card-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                        <i class="fas fa-file-medical"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-primary">{{ $stats['izin'] }}</h4>
                        <small class="text-muted">Izin</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="card stat-card-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-danger bg-opacity-10 text-danger me-3">
                        <i class="fas fa-times"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-danger">{{ $stats['alfa'] }}</h4>
                        <small class="text-muted">Alfa</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6 mb-3">
        <div class="card stat-card-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <div class="icon-circle bg-secondary bg-opacity-10 text-secondary me-3">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-secondary">{{ $stats['terlambat'] }}</h4>
                        <small class="text-muted">Terlambat</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card chart-card">
            <div class="chart-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Statistik Absensi
                    </h5>
                    <div class="btn-group btn-group-sm shadow-sm">
                        <a href="{{ request()->fullUrlWithQuery(['period' => 7]) }}" class="btn {{ request('period', 7) == 7 ? 'btn-primary-custom' : 'btn-outline-secondary' }}">7 Hari</a>
                        <a href="{{ request()->fullUrlWithQuery(['period' => 30]) }}" class="btn {{ request('period') == 30 ? 'btn-primary-custom' : 'btn-outline-secondary' }}">30 Hari</a>
                        <a href="{{ request()->fullUrlWithQuery(['period' => 90]) }}" class="btn {{ request('period') == 90 ? 'btn-primary-custom' : 'btn-outline-secondary' }}">90 Hari</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card activity-card">
            <div class="chart-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                    @forelse($recentActivity as $activity)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="text-dark">{{ $activity->siswa->nama ?? 'N/A' }}</strong>
                                <br><small class="text-muted">{{ $activity->siswa->kelas->nama_kelas ?? '' }}</small>
                            </div>
                            <div class="text-end">
                                @if($activity->status == 'Hadir')
                                    <span class="badge badge-hadir">{{ $activity->status }}</span>
                                @elseif($activity->status == 'Sakit')
                                    <span class="badge badge-sakit">{{ $activity->status }}</span>
                                @elseif($activity->status == 'Izin')
                                    <span class="badge badge-izin">{{ $activity->status }}</span>
                                @else
                                    <span class="badge badge-alfa">{{ $activity->status }}</span>
                                @endif
                                <br><small class="text-muted">{{ $activity->tanggal->format('d/m/Y') }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-5">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada data absensi</p>
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

const primaryColor = '{{ $config->warna_primer }}';
const secondaryColor = '{{ $config->warna_sekunder }}';

new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [
            {
                label: 'Hadir',
                data: {!! json_encode($chartData['hadirData']) !!},
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            },
            {
                label: 'Sakit',
                data: {!! json_encode($chartData['sakitData']) !!},
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            },
            {
                label: 'Izin',
                data: {!! json_encode($chartData['izinData']) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            },
            {
                label: 'Alfa',
                data: {!! json_encode($chartData['alfaData']) !!},
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 20
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: true,
                    color: 'rgba(0,0,0,0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        },
        interaction: {
            intersect: false,
            mode: 'index'
        }
    }
});
</script>
@endpush
