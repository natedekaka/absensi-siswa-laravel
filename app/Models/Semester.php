<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semester';
    
    public $timestamps = false;

    protected $fillable = [
        'tahun_ajaran_id',
        'semester',
        'nama',
        'tgl_mulai',
        'tgl_selesai',
        'is_active',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'tgl_mulai' => 'date',
            'tgl_selesai' => 'date',
            'created_at' => 'datetime',
        ];
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'semester_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
