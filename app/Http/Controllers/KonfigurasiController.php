<?php

namespace App\Http\Controllers;

use App\Models\KonfigurasiSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KonfigurasiController extends Controller
{
    public function index()
    {
        $config = KonfigurasiSekolah::getConfig();
        return view('konfigurasi.index', compact('config'));
    }

    public function update(Request $request)
    {
        $config = KonfigurasiSekolah::first();
        
        $validator = Validator::make($request->all(), [
            'nama_sekolah' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'warna_primer' => 'required|string|max:20',
            'warna_sekunder' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['nama_sekolah', 'warna_primer', 'warna_sekunder']);

        if ($request->hasFile('logo')) {
            $oldLogo = public_path('storage/logos/' . ($config->logo ?? ''));
            if (file_exists($oldLogo)) {
                unlink($oldLogo);
            }
            
            $logo = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('storage/logos'), $filename);
            $data['logo'] = $filename;
        }

        if ($config) {
            $config->update($data);
        } else {
            KonfigurasiSekolah::create($data);
        }

        return redirect()->route('konfigurasi.index')
            ->with('success', 'Konfigurasi berhasil disimpan');
    }
}
