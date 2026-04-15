<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::with('semesters')
            ->orderBy('nama', 'desc')
            ->get();
        return view('tahun-ajaran.index', compact('tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:tahun_ajaran,nama',
        ], [
            'nama.unique' => 'Tahun ajaran sudah ada!',
        ]);

        TahunAjaran::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan!');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $ta = TahunAjaran::findOrFail($id);

        $cek = Semester::where('tahun_ajaran_id', $id)->count();
        if ($cek > 0) {
            return redirect()->route('tahun-ajaran.index')
                ->with('error', 'Hapus terlebih dahulu semester dalam tahun ajaran ini!');
        }

        $nama = $ta->nama;
        $ta->delete();

        return redirect()->route('tahun-ajaran.index')
            ->with('success', "Tahun ajaran {$nama} berhasil dihapus!");
    }

    public function storeSemester(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'semester' => 'required|integer|in:1,2',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
        ], [
            'tgl_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
        ]);

        $exists = Semester::where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->where('semester', $request->semester)
            ->first();

        if ($exists) {
            return redirect()->route('tahun-ajaran.index')
                ->with('error', 'Semester tersebut sudah ada!');
        }

        Semester::create([
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'semester' => $request->semester,
            'nama' => "Semester " . $request->semester,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
        ]);

        return redirect()->route('tahun-ajaran.index')
            ->with('success', 'Semester berhasil ditambahkan!');
    }

    public function activateSemester(Request $request)
    {
        $id = $request->id;
        $semester = Semester::findOrFail($id);

        Semester::where('is_active', 1)->update(['is_active' => 0]);
        $semester->update(['is_active' => 1]);

        TahunAjaran::where('is_active', 1)->update(['is_active' => 0]);
        TahunAjaran::where('id', $semester->tahun_ajaran_id)->update(['is_active' => 1]);

        return redirect()->route('tahun-ajaran.index')
            ->with('success', 'Semester berhasil diaktifkan!');
    }

    public function destroySemester(Request $request)
    {
        $id = $request->id;
        $semester = Semester::findOrFail($id);
        $nama = $semester->nama;
        $semester->delete();

        return redirect()->route('tahun-ajaran.index')
            ->with('success', "Semester {$nama} berhasil dihapus!");
    }
}
