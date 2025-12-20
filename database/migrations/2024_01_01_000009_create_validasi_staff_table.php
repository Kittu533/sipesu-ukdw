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
        Schema::create('validasi_staff', function (Blueprint $table) {
            $table->id('id_validasi');
            $table->unsignedBigInteger('id_pengajuan');
            $table->unsignedBigInteger('id_user_staff');
            $table->dateTime('tgl_validasi');
            $table->string('status_validasi', 20);
            $table->text('catatan_staff')->nullable();
            $table->timestamps();

            $table->foreign('id_pengajuan')->references('id_pengajuan')->on('pengajuan_surat')->onDelete('cascade');
            $table->foreign('id_user_staff')->references('id_user')->on('users')->onDelete('restrict');
            $table->index('id_pengajuan');
            $table->index('status_validasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validasi_staff');
    }
};
