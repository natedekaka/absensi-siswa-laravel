<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\KenaikanController;
use App\Http\Controllers\KelulusanController;
use App\Http\Controllers\RedistribusiController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('siswa', SiswaController::class);
    Route::get('/siswa/import', [SiswaController::class, 'showImport'])->name('siswa.import');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import.process');
    Route::post('/siswa/destroy-batch', [SiswaController::class, 'destroyBatch'])->name('siswa.destroy-batch');
    Route::get('/siswa/template', [SiswaController::class, 'downloadTemplate'])->name('siswa.template');

    Route::resource('kelas', KelasController::class);
    Route::get('/kelas/import', [KelasController::class, 'showImport'])->name('kelas.import');
    Route::post('/kelas/import', [KelasController::class, 'import'])->name('kelas.import.process');
    Route::get('/kelas/template', [KelasController::class, 'downloadTemplate'])->name('kelas.template');

    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/get-siswa', [AbsensiController::class, 'getSiswa'])->name('absensi.get-siswa');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/barcode', [AbsensiController::class, 'barcode'])->name('absensi.barcode');
    Route::get('/absensi/cari-siswa', [AbsensiController::class, 'cariSiswa'])->name('absensi.cari-siswa');
    Route::post('/absensi/simpan-barcode', [AbsensiController::class, 'simpanBarcode'])->name('absensi.simpan-barcode');

    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
    Route::get('/rekap/export', [RekapController::class, 'export'])->name('rekap.export');
    Route::get('/rekap/kelas/{kelasId}', [RekapController::class, 'byKelas'])->name('rekap.by-kelas');
    Route::get('/rekap/siswa/{siswaId}', [RekapController::class, 'bySiswa'])->name('rekap.by-siswa');
    Route::get('/rekap/summary', [RekapController::class, 'summary'])->name('rekap.summary');

    Route::resource('tahun-ajaran', TahunAjaranController::class)->except(['show', 'edit', 'update', 'create']);
    Route::post('/tahun-ajaran/semester', [TahunAjaranController::class, 'storeSemester'])->name('tahun-ajaran.semester.store');
    Route::post('/tahun-ajaran/semester/{id}/activate', [TahunAjaranController::class, 'activateSemester'])->name('tahun-ajaran.semester.activate');
    Route::post('/tahun-ajaran/semester/{id}/destroy', [TahunAjaranController::class, 'destroySemester'])->name('tahun-ajaran.semester.destroy');

    Route::get('/kenaikan', [KenaikanController::class, 'index'])->name('kenaikan.index');
    Route::post('/kenaikan/naik-tingkat', [KenaikanController::class, 'naikTingkat'])->name('kenaikan.naik-tingkat');
    Route::post('/kenaikan/export', [KenaikanController::class, 'exportSiswa'])->name('kenaikan.export');
    Route::post('/kenaikan/import', [KenaikanController::class, 'importKelas'])->name('kenaikan.import');

    Route::get('/kelulusan', [KelulusanController::class, 'index'])->name('kelulusan.index');
    Route::post('/kelulusan/proses', [KelulusanController::class, 'proses'])->name('kelulusan.proses');

    Route::get('/redistribusi', [RedistribusiController::class, 'index'])->name('redistribusi.index');
    Route::post('/redistribusi/pindahkan', [RedistribusiController::class, 'pindahkan'])->name('redistribusi.pindahkan');

    Route::resource('user', UserController::class);

    Route::get('/konfigurasi', [KonfigurasiController::class, 'index'])->name('konfigurasi.index');
    Route::post('/konfigurasi', [KonfigurasiController::class, 'update'])->name('konfigurasi.update');
});
