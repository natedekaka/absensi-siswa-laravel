<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semester', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->integer('semester');
            $table->string('nama', 50);
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->boolean('is_active')->default(false);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semester');
    }
};
