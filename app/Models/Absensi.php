<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    public $timestamps = false;

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'status',
        'semester_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    public function scopeByKelas($query, $kelasId)
    {
        return $query->whereHas('siswa', function ($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId);
        });
    }

    public static function getStatusOptions(): array
    {
        return ['Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat'];
    }
}
