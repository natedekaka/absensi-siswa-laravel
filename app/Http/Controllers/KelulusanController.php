<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelulusanController extends Controller
{
    public function index()
    {
        $siswaXII = Siswa::with('kelas')
            ->where('status', 'aktif')
            ->whereHas('kelas', function($q) {
                $q->where('nama_kelas', 'LIKE', 'XII-%')
                  ->orWhere('nama_kelas', 'LIKE', '12-%');
            })
            ->orderBy('kelas.nama_kelas')
            ->orderBy('nama')
            ->get();

        $countXII = $siswaXII->count();

        return view('kenaikan.kelulusan', compact('siswaXII', 'countXII'));
    }

    public function proses(Request $request)
    {
        $tahunLulus = (int)$request->tahun_lulus;

        $siswaXII = Siswa::where('status', 'aktif')
            ->whereHas('kelas', function($q) {
                $q->where('nama_kelas', 'LIKE', 'XII-%')
                  ->orWhere('nama_kelas', 'LIKE', '12-%');
            })
            ->get();

        $count = $siswaXII->count();

        if ($count > 0) {
            foreach ($siswaXII as $siswa) {
                $siswa->update([
                    'status' => 'alumni',
                    'tingkat' => null,
                    'tahun_lulus' => $tahunLulus,
                ]);
            }

            return redirect()->route('kelulusan.index')
                ->with('success', "Berhasil meluluskan $count siswa kelas 12");
        }

        return redirect()->route('kelulusan.index')
            ->with('error', 'Tidak ada siswa kelas 12 yang diluluskan');
    }
}
