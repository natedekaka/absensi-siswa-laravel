<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('cari', '');
        $kelasFilter = $request->get('kelas_id', '');
        $page = $request->get('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $query = Siswa::with('kelas')
            ->where(function($q) {
                $q->where('status', 'aktif')->orWhereNull('status');
            });

        if ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%');
        }

        if ($kelasFilter) {
            $query->where('kelas_id', $kelasFilter);
        }

        $total = $query->count();
        $totalPages = ceil($total / $limit);

        $siswas = $query->orderBy('nama')
            ->skip($offset)
            ->take($limit)
            ->get();

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.index', compact(
            'siswas', 'kelasList', 'keyword', 'kelasFilter', 
            'page', 'limit', 'total', 'totalPages', 'offset'
        ));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('siswa.create', compact('kelas'));
    }

    public function showImport()
    {
        $kelasList = Kelas::orderBy('id')->get();
        return view('siswa.import', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|max:20|unique:siswa,nis',
            'nisn' => 'required|string|max:20|unique:siswa,nisn',
            'nama' => 'required|string|max:100',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ], [
            'nis.unique' => 'NIS sudah digunakan!',
            'nisn.unique' => 'NISN sudah digunakan!',
        ]);

        Siswa::create([
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'kelas_id' => $request->kelas_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'status' => 'aktif',
        ]);

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nis' => 'required|string|max:20|unique:siswa,nis,' . $siswa->id,
            'nisn' => 'required|string|max:20|unique:siswa,nisn,' . $siswa->id,
            'nama' => 'required|string|max:100',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ], [
            'nis.unique' => 'NIS sudah digunakan!',
            'nisn.unique' => 'NISN sudah digunakan!',
        ]);

        $siswa->update([
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'kelas_id' => $request->kelas_id,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Siswa $siswa)
    {
        $nama = $siswa->nama;
        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', "Siswa {$nama} berhasil dihapus!");
    }

    public function destroyBatch(Request $request)
    {
        $deleteType = $request->delete_type;
        $deleted = 0;

        try {
            if ($deleteType === 'selected') {
                $siswaIds = $request->siswa_ids;
                
                if (empty($siswaIds)) {
                    return redirect()->route('siswa.index')
                        ->with('error', 'Tidak ada siswa yang dipilih!');
                }

                $ids = array_filter(array_map('intval', explode(',', $siswaIds)), function($id) {
                    return $id > 0;
                });

                if (empty($ids)) {
                    return redirect()->route('siswa.index')
                        ->with('error', 'Tidak ada siswa yang dipilih!');
                }

                DB::transaction(function () use ($ids, &$deleted) {
                    DB::table('absensi')->whereIn('siswa_id', $ids)->delete();
                    $deleted = DB::table('siswa')->whereIn('id', $ids)->delete();
                });

                return redirect()->route('siswa.index')
                    ->with('success', "Berhasil menghapus {$deleted} siswa!");

            } elseif ($deleteType === 'kelas') {
                $kelasId = $request->kelas_id;

                if (!$kelasId) {
                    return redirect()->route('siswa.index')
                        ->with('error', 'Kelas tidak valid!');
                }

                DB::transaction(function () use ($kelasId, &$deleted) {
                    $deleted = DB::table('siswa')->where('kelas_id', $kelasId)->delete();
                    DB::table('absensi')->whereIn('siswa_id', function($q) use ($kelasId) {
                        $q->select('id')->from('siswa')->where('kelas_id', $kelasId);
                    })->delete();
                });

                return redirect()->route('siswa.index')
                    ->with('success', "Berhasil menghapus {$deleted} siswa di kelas tersebut!");

            } elseif ($deleteType === 'all') {
                DB::transaction(function () use (&$deleted) {
                    $deleted = DB::table('siswa')
                        ->where('status', 'aktif')
                        ->orWhereNull('status')
                        ->delete();
                    DB::table('absensi')->delete();
                });

                return redirect()->route('siswa.index')
                    ->with('success', "Berhasil menghapus semua siswa ({$deleted} siswa)!");

            } else {
                return redirect()->route('siswa.index')
                    ->with('error', 'Jenis penghapusan tidak valid!');
            }
        } catch (\Exception $e) {
            return redirect()->route('siswa.index')
                ->with('error', 'Gagal menghapus siswa: ' . $e->getMessage());
        }
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
        $updated = 0;
        $errors = [];

        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            if (count($data) < 5) continue;

            $nis = trim($data[0] ?? '');
            $nisn = trim($data[1] ?? '');
            $nama = trim($data[2] ?? '');
            $kelasId = (int)trim($data[3] ?? '');
            $jenisKelamin = trim($data[4] ?? '');

            if (!in_array($jenisKelamin, ['Laki-laki', 'Perempuan'])) {
                $errors[] = "Jenis kelamin tidak valid pada NIS $nis";
                continue;
            }

            if (empty($nis) || empty($nisn) || empty($nama) || $kelasId <= 0) {
                $errors[] = "Data tidak valid: $nis";
                continue;
            }

            $existing = Siswa::where('nis', $nis)->first();

            if ($existing) {
                $existing->update([
                    'nisn' => $nisn,
                    'nama' => $nama,
                    'kelas_id' => $kelasId,
                    'jenis_kelamin' => $jenisKelamin,
                ]);
                $updated++;
            } else {
                Siswa::create([
                    'nis' => $nis,
                    'nisn' => $nisn,
                    'nama' => $nama,
                    'kelas_id' => $kelasId,
                    'jenis_kelamin' => $jenisKelamin,
                    'status' => 'aktif',
                ]);
                $imported++;
            }
        }

        fclose($handle);

        $message = '';
        if ($imported > 0) {
            $message = "Berhasil menambahkan {$imported} data siswa";
        }
        if ($updated > 0) {
            $message .= ($message ? ". " : "") . "Berhasil memperbarui {$updated} data";
        }
        if (!empty($errors)) {
            $message .= ($message ? ". " : "") . "Error: " . implode(", ", array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $message .= " dan " . (count($errors) - 5) . " error lainnya";
            }
        }

        return redirect()->route('siswa.index')
            ->with($imported > 0 || $updated > 0 ? 'success' : 'error', $message ?: 'Tidak ada data yang diimport!');
    }

    public function downloadTemplate()
    {
        $content = "nis,nisn,nama,kelas_id,jenis_kelamin\n";
        $content .= "12345,00123456789,Contoh Nama,1,Laki-laki\n";
        $content .= "12346,00123456790,Contoh Nama 2,1,Perempuan\n";

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="template_import_siswa.csv"');
    }
}
