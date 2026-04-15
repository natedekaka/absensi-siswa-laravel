<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KenaikanController extends Controller
{
    public function index()
    {
        $siswaX = Siswa::where('status', 'aktif')
            ->whereHas('kelas', function($q) {
                $q->where('nama_kelas', 'LIKE', 'X-%')
                  ->orWhere('nama_kelas', 'LIKE', '10-%');
            })->count();

        $siswaXI = Siswa::where('status', 'aktif')
            ->whereHas('kelas', function($q) {
                $q->where('nama_kelas', 'LIKE', 'XI-%')
                  ->orWhere('nama_kelas', 'LIKE', '11-%');
            })->count();

        $siswaXII = Siswa::where('status', 'aktif')
            ->whereHas('kelas', function($q) {
                $q->where('nama_kelas', 'LIKE', 'XII-%')
                  ->orWhere('nama_kelas', 'LIKE', '12-%');
            })->count();

        $alumni = Siswa::where('status', 'alumni')->count();

        $kelas = Kelas::orderBy('nama_kelas')->get();

        $alumniList = Siswa::with('kelas')
            ->where('status', 'alumni')
            ->orderBy('tahun_lulus', 'desc')
            ->orderBy('nama', 'asc')
            ->get();

        return view('kenaikan.index', compact('siswaX', 'siswaXI', 'siswaXII', 'alumni', 'kelas', 'alumniList'));
    }

    public function naikTingkat(Request $request)
    {
        $tingkatDari = (int)$request->tingkat_dari;
        $tingkatKe = $tingkatDari + 1;

        $prefixMap = [
            10 => ['X', '10'],
            11 => ['XI', '11'],
        ];

        $prefixAsal = $prefixMap[$tingkatDari][0] ?? '';

        $siswa = Siswa::where('status', 'aktif')
            ->whereHas('kelas', function($q) use ($prefixAsal, $tingkatDari) {
                $q->where('nama_kelas', 'LIKE', $prefixAsal . '-%')
                  ->orWhere('nama_kelas', 'LIKE', $tingkatDari . '-%');
            })
            ->update(['tingkat' => $tingkatKe]);

        $count = Siswa::where('status', 'aktif')
            ->where('tingkat', $tingkatKe)
            ->count();

        if ($count > 0) {
            return redirect()->route('kenaikan.index')
                ->with('success', "Berhasil menaikan $count siswa dari tingkat $tingkatDari ke $tingkatKe");
        }

        return redirect()->route('kenaikan.index')
            ->with('error', 'Tidak ada siswa yang dinaikkan');
    }

    public function exportSiswa(Request $request)
    {
        $tingkat = (int)$request->tingkat_export;

        $prefixMap = [
            10 => ['X', '10'],
            11 => ['XI', '11'],
        ];

        $prefix = $prefixMap[$tingkat][0] ?? '';

        $siswa = Siswa::with('kelas')
            ->where('status', 'aktif')
            ->whereHas('kelas', function($q) use ($prefix, $tingkat) {
                $q->where('nama_kelas', 'LIKE', $prefix . '-%')
                  ->orWhere('nama_kelas', 'LIKE', $tingkat . '-%');
            })
            ->orderBy('kelas.nama_kelas')
            ->orderBy('nama')
            ->get();

        $filename = 'siswa_kelas_' . $tingkat . '_' . date('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($siswa) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['NIS', 'NISN', 'Nama', 'Kelas Lama', 'Nama Kelas Baru', 'ID Kelas Baru'], ';');

            foreach ($siswa as $s) {
                fputcsv($handle, [
                    $s->nis,
                    $s->nisn,
                    $s->nama,
                    $s->kelas->nama_kelas ?? '',
                    '',
                    ''
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importKelas(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file, 'r');
        $rows = [];
        while (($line = fgetcsv($handle, 1000, ';')) !== FALSE) {
            $rows[] = $line;
        }
        fclose($handle);
        array_shift($rows);

        $updated = 0;
        $errors = [];

        foreach ($rows as $row) {
            if (count($row) < 6) continue;

            $nis = trim($row[0]);
            $kelasIdBaru = (int)trim($row[5]);

            if (empty($nis) || $kelasIdBaru <= 0) continue;

            $siswa = Siswa::where('nis', $nis)->where('status', 'aktif')->first();
            if ($siswa) {
                $siswa->update(['kelas_id' => $kelasIdBaru]);
                $updated++;
            }
        }

        if ($updated > 0) {
            return redirect()->route('kenaikan.index')
                ->with('success', "Berhasil mengupdate $updated siswa ke kelas baru");
        }

        return redirect()->route('kenaikan.index')
            ->with('error', 'Tidak ada data yang diupdate');
    }
}
