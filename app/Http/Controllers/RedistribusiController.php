<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class RedistribusiController extends Controller
{
    public function index()
    {
        $kelasX = Kelas::where(function($q) {
            $q->where('nama_kelas', 'LIKE', 'X-%')
              ->orWhere('nama_kelas', 'LIKE', '10-%');
        })->orderBy('nama_kelas')->get();

        $kelasXI = Kelas::where(function($q) {
            $q->where('nama_kelas', 'LIKE', 'XI-%')
              ->orWhere('nama_kelas', 'LIKE', '11-%');
        })->orderBy('nama_kelas')->get();

        $siswaX = Siswa::with('kelas')
            ->where('status', 'aktif')
            ->whereHas('kelas', function($q) {
                $q->where('nama_kelas', 'LIKE', 'X-%')
                  ->orWhere('nama_kelas', 'LIKE', '10-%');
            })
            ->orderBy('kelas.nama_kelas')
            ->orderBy('nama')
            ->get();

        $siswaByKelas = [];
        foreach ($siswaX as $siswa) {
            $kelasId = $siswa->kelas_id;
            $kelasNama = $siswa->kelas->nama_kelas ?? 'Unknown';
            if (!isset($siswaByKelas[$kelasId])) {
                $siswaByKelas[$kelasId] = [
                    'nama_kelas' => $kelasNama,
                    'siswa' => []
                ];
            }
            $siswaByKelas[$kelasId]['siswa'][] = $siswa;
        }

        return view('kenaikan.redistribusi', compact('kelasX', 'kelasXI', 'siswaByKelas'));
    }

    public function pindahkan(Request $request)
    {
        $siswaIds = $request->siswa_ids ?? [];
        $kelasTujuan = (int)$request->kelas_tujuan;

        if (empty($siswaIds)) {
            return redirect()->route('redistribusi.index')
                ->with('error', 'Pilih setidaknya satu siswa');
        }

        if ($kelasTujuan <= 0) {
            return redirect()->route('redistribusi.index')
                ->with('error', 'Pilih kelas tujuan');
        }

        $updated = 0;
        foreach ($siswaIds as $siswaId) {
            $siswa = Siswa::find($siswaId);
            if ($siswa) {
                $siswa->update(['kelas_id' => $kelasTujuan]);
                $updated++;
            }
        }

        return redirect()->route('redistribusi.index')
            ->with('success', "Berhasil memindahkan $updated siswa ke kelas baru");
    }
}
