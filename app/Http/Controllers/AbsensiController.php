<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Semester;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller
{
    public function index()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $semesters = Semester::orderBy('is_active', 'desc')
            ->orderBy('tahun_ajaran_id', 'desc')
            ->get();

        return view('absensi.index', compact('kelas', 'semesters'));
    }

    public function getSiswa(Request $request)
    {
        $kelasId = $request->get('kelas_id');
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $semesterId = $request->get('semester_id');

        if (!$kelasId || !$semesterId) {
            return response('<div class="alert alert-warning">Pilih kelas dan semester terlebih dahulu!</div>');
        }

        $semester = Semester::find($semesterId);
        if (!$semester) {
            return response('<div class="alert alert-warning">Semester tidak ditemukan!</div>');
        }

        if ($kelasId === 'all') {
            $siswas = Siswa::with('kelas')
                ->where(function($q) {
                    $q->where('status', 'aktif')->orWhereNull('status');
                })
                ->orderBy('kelas_id')
                ->orderBy('nama');
        } else {
            $siswas = Siswa::with('kelas')
                ->where('kelas_id', $kelasId)
                ->where(function($q) {
                    $q->where('status', 'aktif')->orWhereNull('status');
                })
                ->orderBy('nama');
        }

        if ($request->has('search') && $request->search) {
            $siswas->where('nama', 'like', '%' . $request->search . '%');
        }

        $siswas = $siswas->get();

        $existingAbsensi = Absensi::whereIn('siswa_id', $siswas->pluck('id'))
            ->where('tanggal', $tanggal)
            ->pluck('status', 'siswa_id')
            ->toArray();

        $rekapSemester1 = DB::table('absensi as a')
            ->join('semester as s', 'a.semester_id', '=', 's.id')
            ->where('s.semester', 1)
            ->select('a.siswa_id')
            ->selectRaw('SUM(CASE WHEN a.status = "Hadir" THEN 1 ELSE 0 END) as total_hadir')
            ->selectRaw('SUM(CASE WHEN a.status = "Terlambat" THEN 1 ELSE 0 END) as total_terlambat')
            ->selectRaw('SUM(CASE WHEN a.status = "Sakit" THEN 1 ELSE 0 END) as total_sakit')
            ->selectRaw('SUM(CASE WHEN a.status = "Izin" THEN 1 ELSE 0 END) as total_izin')
            ->selectRaw('SUM(CASE WHEN a.status = "Alfa" THEN 1 ELSE 0 END) as total_alfa')
            ->groupBy('a.siswa_id')
            ->get()
            ->keyBy('siswa_id');

        $rekapSemester2 = DB::table('absensi as a')
            ->join('semester as s', 'a.semester_id', '=', 's.id')
            ->where('s.semester', 2)
            ->select('a.siswa_id')
            ->selectRaw('SUM(CASE WHEN a.status = "Hadir" THEN 1 ELSE 0 END) as total_hadir')
            ->selectRaw('SUM(CASE WHEN a.status = "Terlambat" THEN 1 ELSE 0 END) as total_terlambat')
            ->selectRaw('SUM(CASE WHEN a.status = "Sakit" THEN 1 ELSE 0 END) as total_sakit')
            ->selectRaw('SUM(CASE WHEN a.status = "Izin" THEN 1 ELSE 0 END) as total_izin')
            ->selectRaw('SUM(CASE WHEN a.status = "Alfa" THEN 1 ELSE 0 END) as total_alfa')
            ->groupBy('a.siswa_id')
            ->get()
            ->keyBy('siswa_id');

        return view('absensi.partial_siswa', compact(
            'siswas', 'existingAbsensi', 'tanggal', 'semesterId', 'kelasId', 'rekapSemester1', 'rekapSemester2'
        ))->render();
    }

    public function store(Request $request)
    {
        $tanggal = $request->tanggal;
        $semesterId = $request->semester_id;
        $statuses = $request->status ?? [];

        if (!$semesterId) {
            return response()->json(['success' => false, 'message' => 'Semester harus dipilih!'], 400);
        }

        $semester = Semester::find($semesterId);
        if (!$semester) {
            return response()->json(['success' => false, 'message' => 'Semester tidak ditemukan!'], 400);
        }

        if ($tanggal < $semester->tgl_mulai || $tanggal > $semester->tgl_selesai) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal tidak sesuai dengan periode semester!'
            ], 400);
        }

        if (empty($statuses)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data absensi untuk disimpan!'], 400);
        }

        $saved = 0;
        foreach ($statuses as $siswaId => $status) {
            $validStatuses = ['Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat'];
            $status = in_array($status, $validStatuses) ? $status : 'Hadir';

            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                ],
                [
                    'status' => $status,
                    'semester_id' => $semesterId,
                ]
            );
            $saved++;
        }

        return response()->json(['success' => true, 'message' => "Berhasil menyimpan $saved absensi!"]);
    }

    public function barcode()
    {
        $today = Carbon::today()->format('Y-m-d');
        $semesterAktif = Semester::active()->first();

        $statsHariIni = DB::table('absensi')
            ->where('tanggal', $today)
            ->when($semesterAktif, function($q) use ($semesterAktif) {
                $q->where('semester_id', $semesterAktif->id);
            })
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $stats = ['Hadir' => 0, 'Sakit' => 0, 'Izin' => 0, 'Alfa' => 0, 'Terlambat' => 0];
        foreach ($statsHariIni as $s) {
            if (isset($stats[$s->status])) {
                $stats[$s->status] = $s->total;
            }
        }

        $semesters = Semester::orderBy('is_active', 'desc')
            ->orderBy('tahun_ajaran_id', 'desc')
            ->get();

        $riwayat = DB::table('absensi as a')
            ->join('siswa as s', 'a.siswa_id', '=', 's.id')
            ->leftJoin('kelas as k', 's.kelas_id', '=', 'k.id')
            ->where('a.tanggal', $today)
            ->when($semesterAktif, function($q) use ($semesterAktif) {
                $q->where('a.semester_id', $semesterAktif->id);
            })
            ->select('a.tanggal', 'a.status', 's.nama', 'k.nama_kelas')
            ->orderBy('a.id', 'desc')
            ->limit(20)
            ->get();

        return view('absensi.barcode', compact('stats', 'semesters', 'semesterAktif', 'riwayat', 'today'));
    }

    public function cariSiswa(Request $request)
    {
        $barcode = $request->get('barcode');
        $tgl = $request->get('tgl', Carbon::today()->format('Y-m-d'));
        $semesterId = $request->get('semester_id');

        if (!$barcode) {
            return response()->json(['success' => false, 'message' => 'Barcode tidak boleh kosong']);
        }

        $siswa = Siswa::with('kelas')
            ->where(function($q) use ($barcode) {
                $q->where('nis', $barcode)
                  ->orWhere('nisn', $barcode)
                  ->orWhere('barcode', $barcode);
            })
            ->first();

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan']);
        }

        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->where('tanggal', $tgl)
            ->when($semesterId, function($q) use ($semesterId) {
                $q->where('semester_id', $semesterId);
            })
            ->first();

        $sudahAbsen = $absensi ? true : false;
        $statusDisplay = $sudahAbsen ? ($absensi->status ?? '') : '';

        return response()->json([
            'success' => true,
            'siswa' => [
                'id' => $siswa->id,
                'nama' => $siswa->nama,
                'nis' => $siswa->nis,
                'nisn' => $siswa->nisn,
                'kelas_id' => $siswa->kelas_id,
                'kelas_nama' => $siswa->kelas->nama_kelas ?? '',
                'jenis_kelamin' => $siswa->jenis_kelamin,
                'barcode' => $barcode,
            ],
            'sudah_absen' => $sudahAbsen,
            'absensi' => $absensi,
            'status_display' => $statusDisplay,
        ]);
    }

    public function simpanBarcode(Request $request)
    {
        $siswaId = $request->siswa_id;
        $barcode = $request->barcode;
        $status = strtolower($request->status);
        $tgl = $request->tgl;
        $semesterId = $request->semester_id;

        if (!$siswaId || !$barcode || !$status) {
            return response()->json(['success' => false, 'message' => 'Data tidak lengkap']);
        }

        $validStatuses = ['hadir', 'sakit', 'izin', 'alfa'];
        if (!in_array($status, $validStatuses)) {
            $status = 'hadir';
        }

        $statusMap = [
            'hadir' => 'Hadir',
            'sakit' => 'Sakit',
            'izin' => 'Izin',
            'alfa' => 'Alfa',
        ];
        $statusFinal = $statusMap[$status] ?? 'Hadir';

        $existing = Absensi::where('siswa_id', $siswaId)
            ->where('tanggal', $tgl)
            ->when($semesterId, function($q) use ($semesterId) {
                $q->where('semester_id', $semesterId);
            })
            ->first();

        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Siswa sudah melakukan absensi hari ini']);
        }

        Absensi::create([
            'siswa_id' => $siswaId,
            'tanggal' => $tgl,
            'status' => $statusFinal,
            'semester_id' => $semesterId,
        ]);

        return response()->json(['success' => true, 'message' => 'Absensi berhasil disimpan']);
    }
}
