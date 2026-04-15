<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 20)->unique();
            $table->string('nisn', 20)->unique();
            $table->string('nama', 100);
            $table->foreignId('kelas_id')->constrained('kelas');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->enum('status', ['aktif', 'alumni'])->default('aktif');
            $table->integer('tingkat')->nullable();
            $table->year('tahun_lulus')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
