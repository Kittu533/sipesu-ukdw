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
        Schema::create('log_status_surat', function (Blueprint $table) {
            $table->id('id_log_status');
            $table->unsignedBigInteger('id_pengajuan');
            $table->dateTime('tgl_perubahan');
            $table->string('status_baru', 50);
            $table->unsignedBigInteger('diubah_oleh_user');
            $table->timestamps();

            $table->foreign('id_pengajuan')->references('id_pengajuan')->on('pengajuan_surat')->onDelete('cascade');
            $table->foreign('diubah_oleh_user')->references('id_user')->on('users')->onDelete('restrict');
            $table->index('id_pengajuan');
            $table->index('tgl_perubahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_status_surat');
    }
};
