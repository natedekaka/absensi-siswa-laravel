<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $kelasId = $request->get('kelas_id');
        $tglAwal = $request->get('tgl_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglAkhir = $request->get('tgl_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $semesterAktif = Semester::active()->first();
        $taId = $semesterAktif?->tahun_ajaran_id ?? 0;

        $semesterQuery = Semester::query();
        if ($taId > 0) {
            $semesterQuery->where('tahun_ajaran_id', $taId)->whereIn('semester', [1, 2]);
        } else {
            $semesterQuery->whereIn('semester', [1, 2])->orderBy('tahun_ajaran_id', 'desc')->limit(2);
        }
        $semesters = $semesterQuery->get();
        $semesterDates = $semesters->keyBy('semester');

        $statsSmt1 = ['hadir' => 0, 'terlambat' => 0, 'sakit' => 0, 'izin' => 0, 'alfa' => 0, 'total' => 0];
        $statsSmt2 = ['hadir' => 0, 'terlambat' => 0, 'sakit' => 0, 'izin' => 0, 'alfa' => 0, 'total' => 0];
        $siswaSmt1 = collect();
        $siswaSmt2 = collect();
        $totalSiswa = 0;
        $hariSmt1 = 0;
        $hariSmt2 = 0;
        $kehadiranSmt1 = 0;
        $kehadiranSmt2 = 0;
        $smt1Range = null;
        $smt2Range = null;
        $kelas = null;

        if ($kelasId) {
            $kelas = Kelas::find($kelasId);
            $totalSiswa = Siswa::where('kelas_id', $kelasId)
                ->where(function($q) {
                    $q->where('status', 'aktif')->orWhereNull('status');
                })
                ->count();

            $totalHari = (strtotime($tglAkhir) - strtotime($tglAwal)) / (60 * 60 * 24) + 1;

            if (isset($semesterDates[1])) {
                $smt1 = $semesterDates[1];
                $rangeAwal1 = max($tglAwal, $smt1->tgl_mulai);
                $rangeAkhir1 = min($tglAkhir, $smt1->tgl_selesai);
                if ($rangeAwal1 <= $rangeAkhir1) {
                    $smt1Range = [
                        'awal' => $rangeAwal1,
                        'akhir' => $rangeAkhir1,
                        'nama' => $smt1->nama,
                        'id' => $smt1->id
                    ];
                    $hariSmt1 = (strtotime($rangeAkhir1) - strtotime($rangeAwal1)) / (60 * 60 * 24) + 1;

                    $statsSmt1 = DB::select("
                        SELECT 
                            COALESCE(SUM(CASE WHEN a.status = 'Hadir' THEN 1 ELSE 0 END), 0) as hadir,
                            COALESCE(SUM(CASE WHEN a.status = 'Terlambat' THEN 1 ELSE 0 END), 0) as terlambat,
                            COALESCE(SUM(CASE WHEN a.status = 'Sakit' THEN 1 ELSE 0 END), 0) as sakit,
                            COALESCE(SUM(CASE WHEN a.status = 'Izin' THEN 1 ELSE 0 END), 0) as izin,
                            COALESCE(SUM(CASE WHEN a.status = 'Alfa' THEN 1 ELSE 0 END), 0) as alfa,
                            COUNT(*) as total
                        FROM absensi a
                        INNER JOIN siswa s ON a.siswa_id = s.id
                        WHERE s.kelas_id = ? AND a.tanggal BETWEEN ? AND ? AND a.semester_id = ?
                    ", [$kelasId, $rangeAwal1, $rangeAkhir1, $smt1->id]);

                    $statsSmt1 = (array)$statsSmt1[0];

                    $siswaSmt1 = DB::select("
                        SELECT s.id, s.nama, s.jenis_kelamin,
                            COALESCE(SUM(CASE WHEN a.status = 'Hadir' THEN 1 ELSE 0 END), 0) as hadir,
                            COALESCE(SUM(CASE WHEN a.status = 'Terlambat' THEN 1 ELSE 0 END), 0) as terlambat,
                            COALESCE(SUM(CASE WHEN a.status = 'Sakit' THEN 1 ELSE 0 END), 0) as sakit,
                            COALESCE(SUM(CASE WHEN a.status = 'Izin' THEN 1 ELSE 0 END), 0) as izin,
                            COALESCE(SUM(CASE WHEN a.status = 'Alfa' THEN 1 ELSE 0 END), 0) as alfa
                        FROM siswa s
                        LEFT JOIN absensi a ON s.id = a.siswa_id AND a.tanggal BETWEEN ? AND ? AND a.semester_id = ?
                        WHERE s.kelas_id = ? AND (s.status = 'aktif' OR s.status IS NULL)
                        GROUP BY s.id, s.nama, s.jenis_kelamin
                        ORDER BY s.nama ASC
                    ", [$rangeAwal1, $rangeAkhir1, $smt1->id, $kelasId]);

                    $siswaSmt1 = collect($siswaSmt1);
                }
            }

            if (isset($semesterDates[2])) {
                $smt2 = $semesterDates[2];
                $rangeAwal2 = max($tglAwal, $smt2->tgl_mulai);
                $rangeAkhir2 = min($tglAkhir, $smt2->tgl_selesai);
                if ($rangeAwal2 <= $rangeAkhir2) {
                    $smt2Range = [
                        'awal' => $rangeAwal2,
                        'akhir' => $rangeAkhir2,
                        'nama' => $smt2->nama,
                        'id' => $smt2->id
                    ];
                    $hariSmt2 = (strtotime($rangeAkhir2) - strtotime($rangeAwal2)) / (60 * 60 * 24) + 1;

                    $statsSmt2 = DB::select("
                        SELECT 
                            COALESCE(SUM(CASE WHEN a.status = 'Hadir' THEN 1 ELSE 0 END), 0) as hadir,
                            COALESCE(SUM(CASE WHEN a.status = 'Terlambat' THEN 1 ELSE 0 END), 0) as terlambat,
                            COALESCE(SUM(CASE WHEN a.status = 'Sakit' THEN 1 ELSE 0 END), 0) as sakit,
                            COALESCE(SUM(CASE WHEN a.status = 'Izin' THEN 1 ELSE 0 END), 0) as izin,
                            COALESCE(SUM(CASE WHEN a.status = 'Alfa' THEN 1 ELSE 0 END), 0) as alfa,
                            COUNT(*) as total
                        FROM absensi a
                        INNER JOIN siswa s ON a.siswa_id = s.id
                        WHERE s.kelas_id = ? AND a.tanggal BETWEEN ? AND ? AND a.semester_id = ?
                    ", [$kelasId, $rangeAwal2, $rangeAkhir2, $smt2->id]);

                    $statsSmt2 = (array)$statsSmt2[0];

                    $siswaSmt2 = DB::select("
                        SELECT s.id, s.nama, s.jenis_kelamin,
                            COALESCE(SUM(CASE WHEN a.status = 'Hadir' THEN 1 ELSE 0 END), 0) as hadir,
                            COALESCE(SUM(CASE WHEN a.status = 'Terlambat' THEN 1 ELSE 0 END), 0) as terlambat,
                            COALESCE(SUM(CASE WHEN a.status = 'Sakit' THEN 1 ELSE 0 END), 0) as sakit,
                            COALESCE(SUM(CASE WHEN a.status = 'Izin' THEN 1 ELSE 0 END), 0) as izin,
                            COALESCE(SUM(CASE WHEN a.status = 'Alfa' THEN 1 ELSE 0 END), 0) as alfa
                        FROM siswa s
                        LEFT JOIN absensi a ON s.id = a.siswa_id AND a.tanggal BETWEEN ? AND ? AND a.semester_id = ?
                        WHERE s.kelas_id = ? AND (s.status = 'aktif' OR s.status IS NULL)
                        GROUP BY s.id, s.nama, s.jenis_kelamin
                        ORDER BY s.nama ASC
                    ", [$rangeAwal2, $rangeAkhir2, $smt2->id, $kelasId]);

                    $siswaSmt2 = collect($siswaSmt2);
                }
            }

            $totalSeharusnyaSmt1 = $totalSiswa * $hariSmt1;
            $totalSeharusnyaSmt2 = $totalSiswa * $hariSmt2;
            $kehadiranSmt1 = $totalSeharusnyaSmt1 > 0 ? round(($statsSmt1['hadir'] / $totalSeharusnyaSmt1) * 100, 1) : 0;
            $kehadiranSmt2 = $totalSeharusnyaSmt2 > 0 ? round(($statsSmt2['hadir'] / $totalSeharusnyaSmt2) * 100, 1) : 0;
        }

        return view('rekap.index', compact(
            'kelasList', 'kelas', 'kelasId', 'tglAwal', 'tglAkhir',
            'statsSmt1', 'statsSmt2', 'siswaSmt1', 'siswaSmt2',
            'totalSiswa', 'hariSmt1', 'hariSmt2', 'kehadiranSmt1', 'kehadiranSmt2',
            'smt1Range', 'smt2Range', 'semesterAktif'
        ));
    }

    public function export(Request $request)
    {
        $kelasId = $request->get('kelas_id');
        $tglAwal = $request->get('tgl_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tglAkhir = $request->get('tgl_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $type = $request->get('type', 'pdf');

        if (!$kelasId) {
            return back()->with('error', 'Kelas harus dipilih!');
        }

        $kelas = Kelas::find($kelasId);
        if (!$kelas) {
            return back()->with('error', 'Kelas tidak ditemukan!');
        }

        $semesterAktif = Semester::active()->first();
        $taId = $semesterAktif?->tahun_ajaran_id ?? 0;

        $semesterQuery = Semester::query();
        if ($taId > 0) {
            $semesterQuery->where('tahun_ajaran_id', $taId)->whereIn('semester', [1, 2]);
        } else {
            $semesterQuery->whereIn('semester', [1, 2])->orderBy('tahun_ajaran_id', 'desc')->limit(2);
        }
        $semesters = $semesterQuery->get();
        $semesterDates = $semesters->keyBy('semester');

        $siswaSmt1 = collect();
        $siswaSmt2 = collect();
        $hariSmt1 = 0;
        $hariSmt2 = 0;

        if (isset($semesterDates[1])) {
            $smt1 = $semesterDates[1];
            $rangeAwal1 = max($tglAwal, $smt1->tgl_mulai);
            $rangeAkhir1 = min($tglAkhir, $smt1->tgl_selesai);
            if ($rangeAwal1 <= $rangeAkhir1) {
                $hariSmt1 = max(1, (strtotime($rangeAkhir1) - strtotime($rangeAwal1)) / (60 * 60 * 24) + 1);

                $siswaSmt1 = DB::select("
                    SELECT s.id, s.nis, s.nama, s.jenis_kelamin,
                        COALESCE(SUM(CASE WHEN a.status = 'Hadir' THEN 1 ELSE 0 END), 0) as hadir,
                        COALESCE(SUM(CASE WHEN a.status = 'Terlambat' THEN 1 ELSE 0 END), 0) as terlambat,
                        COALESCE(SUM(CASE WHEN a.status = 'Sakit' THEN 1 ELSE 0 END), 0) as sakit,
                        COALESCE(SUM(CASE WHEN a.status = 'Izin' THEN 1 ELSE 0 END), 0) as izin,
                        COALESCE(SUM(CASE WHEN a.status = 'Alfa' THEN 1 ELSE 0 END), 0) as alfa
                    FROM siswa s
                    LEFT JOIN absensi a ON s.id = a.siswa_id AND a.tanggal BETWEEN ? AND ? AND a.semester_id = ?
                    WHERE s.kelas_id = ? AND (s.status = 'aktif' OR s.status IS NULL)
                    GROUP BY s.id, s.nis, s.nama, s.jenis_kelamin
                    ORDER BY s.nama ASC
                ", [$rangeAwal1, $rangeAkhir1, $smt1->id, $kelasId]);
                $siswaSmt1 = collect($siswaSmt1);
            }
        }

        if (isset($semesterDates[2])) {
            $smt2 = $semesterDates[2];
            $rangeAwal2 = max($tglAwal, $smt2->tgl_mulai);
            $rangeAkhir2 = min($tglAkhir, $smt2->tgl_selesai);
            if ($rangeAwal2 <= $rangeAkhir2) {
                $hariSmt2 = max(1, (strtotime($rangeAkhir2) - strtotime($rangeAwal2)) / (60 * 60 * 24) + 1);

                $siswaSmt2 = DB::select("
                    SELECT s.id, s.nis, s.nama, s.jenis_kelamin,
                        COALESCE(SUM(CASE WHEN a.status = 'Hadir' THEN 1 ELSE 0 END), 0) as hadir,
                        COALESCE(SUM(CASE WHEN a.status = 'Terlambat' THEN 1 ELSE 0 END), 0) as terlambat,
                        COALESCE(SUM(CASE WHEN a.status = 'Sakit' THEN 1 ELSE 0 END), 0) as sakit,
                        COALESCE(SUM(CASE WHEN a.status = 'Izin' THEN 1 ELSE 0 END), 0) as izin,
                        COALESCE(SUM(CASE WHEN a.status = 'Alfa' THEN 1 ELSE 0 END), 0) as alfa
                    FROM siswa s
                    LEFT JOIN absensi a ON s.id = a.siswa_id AND a.tanggal BETWEEN ? AND ? AND a.semester_id = ?
                    WHERE s.kelas_id = ? AND (s.status = 'aktif' OR s.status IS NULL)
                    GROUP BY s.id, s.nis, s.nama, s.jenis_kelamin
                    ORDER BY s.nama ASC
                ", [$rangeAwal2, $rangeAkhir2, $smt2->id, $kelasId]);
                $siswaSmt2 = collect($siswaSmt2);
            }
        }

        $dataSmt1 = $siswaSmt1->keyBy('id');
        $dataSmt2 = $siswaSmt2->keyBy('id');

        $allSiswa = Siswa::where('kelas_id', $kelasId)
            ->where(function($q) {
                $q->where('status', 'aktif')->orWhereNull('status');
            })
            ->orderBy('nama')
            ->get();

        if ($type === 'excel' || $type === 'xlsx') {
            $filename = 'rekap_absensi_' . str_replace(' ', '_', $kelas->nama_kelas) . '_' . date('Y-m-d') . '.csv';
            
            $output = "REKAP ABSENSI SISWA - SEMESTER 1 & 2\n";
            $output .= "Kelas: {$kelas->nama_kelas}\n";
            $output .= "Periode: " . date('d/m/Y', strtotime($tglAwal)) . " - " . date('d/m/Y', strtotime($tglAkhir)) . "\n\n";
            
            $output .= "No;NIS;Nama Siswa;JK;Hadir SMT1;Telat SMT1;Sakit SMT1;Izin SMT1;Alfa SMT1;% SMT1;Hadir SMT2;Telat SMT2;Sakit SMT2;Izin SMT2;Alfa SMT2;% SMT2\n";
            
            $no = 1;
            foreach ($allSiswa as $s) {
                $d1 = $dataSmt1->get($s->id, (object)['hadir' => 0, 'terlambat' => 0, 'sakit' => 0, 'izin' => 0, 'alfa' => 0]);
                $d2 = $dataSmt2->get($s->id, (object)['hadir' => 0, 'terlambat' => 0, 'sakit' => 0, 'izin' => 0, 'alfa' => 0]);
                
                $persen1 = $hariSmt1 > 0 ? round(($d1->hadir / $hariSmt1) * 100, 1) : 0;
                $persen2 = $hariSmt2 > 0 ? round(($d2->hadir / $hariSmt2) * 100, 1) : 0;
                $jk = substr($s->jenis_kelamin ?? '', 0, 1);
                
                $output .= "{$no};{$s->nis};{$s->nama};{$jk};{$d1->hadir};{$d1->terlambat};{$d1->sakit};{$d1->izin};{$d1->alfa};{$persen1}%;{$d2->hadir};{$d2->terlambat};{$d2->sakit};{$d2->izin};{$d2->alfa};{$persen2}%\n";
                $no++;
            }
            
            return response($output)
                ->header('Content-Type', 'text/csv; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }

        return view('rekap.export', compact(
            'kelas', 'allSiswa', 'dataSmt1', 'dataSmt2', 
            'hariSmt1', 'hariSmt2', 'tglAwal', 'tglAkhir', 'kelasId'
        ));
    }
}
