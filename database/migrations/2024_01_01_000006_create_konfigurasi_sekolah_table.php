<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konfigurasi_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah', 255)->default('SMA Negeri');
            $table->string('logo', 255)->nullable();
            $table->string('warna_primer', 20)->default('#4f46e5');
            $table->string('warna_sekunder', 20)->default('#64748b');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_sekolah');
    }
};
