<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiSekolah extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_sekolah';

    protected $fillable = [
        'nama_sekolah',
        'logo',
        'warna_primer',
        'warna_sekunder',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public static function getConfig()
    {
        return self::first() ?? new self([
            'nama_sekolah' => 'SMA Negeri',
            'warna_primer' => '#4f46e5',
            'warna_sekunder' => '#64748b',
        ]);
    }
}
