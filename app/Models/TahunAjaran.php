<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';
    
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'is_active',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class, 'tahun_ajaran_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
