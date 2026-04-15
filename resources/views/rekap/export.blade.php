<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi - {{ $kelas->nama_kelas }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin-bottom: 5px; }
        .header p { font-size: 12px; color: #666; margin: 0; }
        table { font-size: 10px; width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 4px; text-align: center; }
        th { background-color: #128C7E; color: white; }
        .smt-header { background-color: #25D366; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn-print { position: fixed; top: 20px; right: 20px; }
    </style>
</head>
<body>
    <div class="no-print btn-print">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak / Save as PDF
        </button>
        <a href="{{ route('rekap.export', ['kelas_id' => $kelasId, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'type' => 'excel']) }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <a href="{{ route('rekap.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div style="margin: 20px;">
        <div class="header">
            <h1>LAPORAN REKAP ABSENSI SISWA</h1>
            <p><strong>Kelas: {{ $kelas->nama_kelas }}</strong> | Wali Kelas: {{ $kelas->wali_kelas ?? '-' }}</p>
            <p>Periode: {{ date('d/m/Y', strtotime($tglAwal)) }} - {{ date('d/m/Y', strtotime($tglAkhir)) }}</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">No</th>
                    <th rowspan="2" style="vertical-align: middle;">NIS</th>
                    <th rowspan="2" style="vertical-align: middle;">Nama Siswa</th>
                    <th rowspan="2" style="vertical-align: middle;">JK</th>
                    <th colspan="5" class="smt-header">SEMESTER 1 ({{ $hariSmt1 > 0 ? $hariSmt1 : 0 }} hari)</th>
                    <th colspan="5" class="smt-header">SEMESTER 2 ({{ $hariSmt2 > 0 ? $hariSmt2 : 0 }} hari)</th>
                </tr>
                <tr>
                    <th>H</th><th>T</th><th>S</th><th>I</th><th>A</th><th>%</th>
                    <th>H</th><th>T</th><th>S</th><th>I</th><th>A</th><th>%</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($allSiswa as $siswa)
                    @php
                        $d1 = $dataSmt1->get($siswa->id, (object)['hadir' => 0, 'terlambat' => 0, 'sakit' => 0, 'izin' => 0, 'alfa' => 0]);
                        $d2 = $dataSmt2->get($siswa->id, (object)['hadir' => 0, 'terlambat' => 0, 'sakit' => 0, 'izin' => 0, 'alfa' => 0]);
                        
                        $persen1 = $hariSmt1 > 0 ? round(($d1->hadir / $hariSmt1) * 100, 1) : 0;
                        $persen2 = $hariSmt2 > 0 ? round(($d2->hadir / $hariSmt2) * 100, 1) : 0;
                        $jk = substr($siswa->jenis_kelamin ?? '', 0, 1);
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $siswa->nis }}</td>
                        <td style="text-align: left;">{{ $siswa->nama }}</td>
                        <td>{{ $jk }}</td>
                        <td>{{ $d1->hadir }}</td>
                        <td>{{ $d1->terlambat }}</td>
                        <td>{{ $d1->sakit }}</td>
                        <td>{{ $d1->izin }}</td>
                        <td>{{ $d1->alfa }}</td>
                        <td><strong>{{ $persen1 }}%</strong></td>
                        <td>{{ $d2->hadir }}</td>
                        <td>{{ $d2->terlambat }}</td>
                        <td>{{ $d2->sakit }}</td>
                        <td>{{ $d2->izin }}</td>
                        <td>{{ $d2->alfa }}</td>
                        <td><strong>{{ $persen2 }}%</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="margin-top: 20px; text-align: right; font-size: 11px; color: #666;">
            Dicetak: {{ date('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>
