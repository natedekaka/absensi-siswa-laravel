<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function index()
    {
        $kelass = DB::table('kelas as k')
            ->leftJoin('siswa as s', 'k.id', '=', 's.kelas_id')
            ->select('k.id', 'k.nama_kelas', 'k.wali_kelas', DB::raw('COUNT(s.id) as total_siswa'))
            ->groupBy('k.id', 'k.nama_kelas', 'k.wali_kelas')
            ->orderBy('k.nama_kelas')
            ->get();

        return view('kelas.index', compact('kelass'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas',
            'wali_kelas' => 'nullable|string|max:100',
        ], [
            'nama_kelas.unique' => "Kelas '{$request->nama_kelas}' sudah ada!",
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas' => $request->wali_kelas,
        ]);

        return redirect()->route('kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit(Kelas $kelas)
    {
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas,' . $kelas->id,
            'wali_kelas' => 'nullable|string|max:100',
        ], [
            'nama_kelas.unique' => "Kelas '{$request->nama_kelas}' sudah ada!",
        ]);

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas' => $request->wali_kelas,
        ]);

        return redirect()->route('kelas.index')
            ->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy(Kelas $kelas)
    {
        $hasSiswa = $kelas->siswas()->exists();

        if ($hasSiswa) {
            return redirect()->route('kelas.index')
                ->with('error', 'Kelas tidak bisa dihapus karena masih memiliki siswa.');
        }

        $nama = $kelas->nama_kelas;
        $kelas->delete();

        return redirect()->route('kelas.index')
            ->with('success', "Kelas {$nama} berhasil dihapus!");
    }

    public function showImport()
    {
        return view('kelas.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        fgetcsv($handle);

        $imported = 0;
        $errors = [];

        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            if (count($data) < 2) continue;

            $namaKelas = trim($data[0] ?? '');
            $waliKelas = trim($data[1] ?? '');

            if (empty($namaKelas)) {
                continue;
            }

            $existing = Kelas::where('nama_kelas', $namaKelas)->first();

            if ($existing) {
                $errors[] = "Kelas $namaKelas sudah ada";
                continue;
            }

            $kelas = Kelas::create([
                'nama_kelas' => $namaKelas,
                'wali_kelas' => $waliKelas,
            ]);

            if ($kelas) {
                $imported++;
            } else {
                $errors[] = "Error inserting $namaKelas";
            }
        }

        fclose($handle);

        $message = '';
        if ($imported > 0) {
            $message = "Berhasil mengimport {$imported} data kelas";
        }
        if (!empty($errors)) {
            $message .= ($message ? ". " : "") . "Error: " . implode(", ", array_slice($errors, 0, 5));
        }

        return redirect()->route('kelas.index')
            ->with($imported > 0 ? 'success' : 'error', $message ?: 'Tidak ada data yang diimport!');
    }

    public function downloadTemplate()
    {
        $content = "nama_kelas,wali_kelas\n";
        $content .= "X IPA 1,Budi Santoso\n";
        $content .= "X IPA 2,Ani Wijaya\n";
        $content .= "X IPS 1,Dedi Kurniawan\n";

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="template_import_kelas.csv"');
    }
}
