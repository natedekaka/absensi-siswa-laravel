<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nisn',
        'nama',
        'kelas_id',
        'jenis_kelamin',
        'status',
        'tingkat',
        'tahun_lulus',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeAlumni($query)
    {
        return $query->where('status', 'alumni');
    }

    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    public function scopeByTingkat($query, $tingkat)
    {
        return $query->where('tingkat', $tingkat);
    }
}
