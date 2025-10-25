<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_kegiatan');
            $table->string('sub_kegiatan')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->foreignId('pic_id')->constrained('users')->onDelete('restrict');
            $table->string('ketua_pelaksana');
            $table->decimal('pagu_anggaran', 15, 2)->default(0);
            $table->decimal('total_pengajuan', 15, 2)->default(0);
            $table->decimal('total_realisasi', 15, 2)->default(0);
            $table->string('progress')->default('Direncanakan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
