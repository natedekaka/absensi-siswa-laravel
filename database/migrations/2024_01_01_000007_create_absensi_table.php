<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa');
            $table->date('tanggal');
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat']);
            $table->foreignId('semester_id')->nullable()->constrained('semester');
            
            $table->index('tanggal');
            $table->index('semester_id');
            $table->index(['tanggal', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
