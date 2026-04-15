@if($siswas->count() > 0)
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
    .col-hadir, .col-status { width: 45px; min-width: 45px; }
    .col-rekap { min-width: 150px; white-space: nowrap; }
    .rekap-badge {
        font-size: 0.6rem;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 2px 4px;
        display: inline-flex;
        gap: 1px;
    }
    .rekap-badge span {
        padding: 1px 2px;
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
</style>

<table class="table table-absensi table-hover">
    <thead>
        <tr>
            @if($kelasId === 'all')
            <th>Kelas</th>
            @endif
            <th>No</th>
            <th>Nama Siswa</th>
            <th class="col-hadir">H</th>
            <th class="col-status">T</th>
            <th class="col-status">S</th>
            <th class="col-status">I</th>
            <th class="col-status">A</th>
            <th class="col-rekap">Smt 1</th>
            <th class="col-rekap">Smt 2</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach($siswas as $row)
        @php
            $statusSebelumnya = $existingAbsensi[$row->id] ?? '';
            $hadirChecked = $statusSebelumnya === '' ? 'checked' : '';
            $statusClass = strtolower($statusSebelumnya) ?: 'kosong';
            $rekap1 = $rekapSemester1[$row->id] ?? null;
            $rekap2 = $rekapSemester2[$row->id] ?? null;
        @endphp
        <tr>
            @if($kelasId === 'all')
            <td class="fw-bold text-secondary">{{ $row->kelas->nama_kelas ?? '-' }}</td>
            @endif
            <td>{{ $no++ }}</td>
            <td class="text-start">
                <strong>{{ $row->nama }}</strong>
                <input type="hidden" name="siswa_id[]" value="{{ $row->id }}">
            </td>
            <td class="col-hadir">
                <input type="radio" name="status[{{ $row->id }}]" value="Hadir" {{ $statusSebelumnya == 'Hadir' ? 'checked' : $hadirChecked }}>
            </td>
            <td class="col-status">
                <input type="radio" name="status[{{ $row->id }}]" value="Terlambat" {{ $statusSebelumnya == 'Terlambat' ? 'checked' : '' }}>
            </td>
            <td class="col-status">
                <input type="radio" name="status[{{ $row->id }}]" value="Sakit" {{ $statusSebelumnya == 'Sakit' ? 'checked' : '' }}>
            </td>
            <td class="col-status">
                <input type="radio" name="status[{{ $row->id }}]" value="Izin" {{ $statusSebelumnya == 'Izin' ? 'checked' : '' }}>
            </td>
            <td class="col-status">
                <input type="radio" name="status[{{ $row->id }}]" value="Alfa" {{ $statusSebelumnya == 'Alfa' ? 'checked' : '' }}>
            </td>
            <td class="col-rekap">
                <span class="rekap-badge">
                    <span class="rekap-h">H:{{ (int)($rekap1->total_hadir ?? 0) }}</span>
                    <span class="rekap-t">T:{{ (int)($rekap1->total_terlambat ?? 0) }}</span>
                    <span class="rekap-s">S:{{ (int)($rekap1->total_sakit ?? 0) }}</span>
                    <span class="rekap-i">I:{{ (int)($rekap1->total_izin ?? 0) }}</span>
                    <span class="rekap-a">A:{{ (int)($rekap1->total_alfa ?? 0) }}</span>
                </span>
            </td>
            <td class="col-rekap">
                <span class="rekap-badge">
                    <span class="rekap-h">H:{{ (int)($rekap2->total_hadir ?? 0) }}</span>
                    <span class="rekap-t">T:{{ (int)($rekap2->total_terlambat ?? 0) }}</span>
                    <span class="rekap-s">S:{{ (int)($rekap2->total_sakit ?? 0) }}</span>
                    <span class="rekap-i">I:{{ (int)($rekap2->total_izin ?? 0) }}</span>
                    <span class="rekap-a">A:{{ (int)($rekap2->total_alfa ?? 0) }}</span>
                </span>
            </td>
            <td>
                @if($statusSebelumnya)
                    <span class="status-badge status-{{ $statusClass }}">{{ $statusSebelumnya }}</span>
                @else
                    <span class="status-badge status-kosong">-</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="alert alert-info text-center py-4">
    <i class="fas fa-user-slash fa-2x d-block mb-2"></i>
    <strong>Tidak ada siswa ditemukan</strong>
</div>
@endif
