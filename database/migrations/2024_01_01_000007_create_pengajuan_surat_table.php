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
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_jenis_surat');
            $table->dateTime('tgl_pengajuan');
            $table->string('status_saat_ini', 50);
            $table->text('keterangan_mahasiswa')->nullable();
            $table->string('nomor_surat_resmi', 100)->nullable();
            $table->string('file_surat_akhir_path', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_jenis_surat')->references('id_jenis_surat')->on('jenis_surat')->onDelete('restrict');
            $table->index('id_mahasiswa');
            $table->index('status_saat_ini');
            $table->index('tgl_pengajuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};
