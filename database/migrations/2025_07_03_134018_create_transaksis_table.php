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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran']);
            $table->string('keterangan');
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal_transaksi');
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
