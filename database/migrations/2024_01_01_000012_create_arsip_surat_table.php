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
        Schema::create('arsip_surat', function (Blueprint $table) {
            $table->id('id_arsip');
            $table->unsignedBigInteger('id_pengajuan');
            $table->date('tgl_arsip');
            $table->unsignedBigInteger('arsiparis_user_id');
            $table->timestamps();

            $table->foreign('id_pengajuan')->references('id_pengajuan')->on('pengajuan_surat')->onDelete('cascade');
            $table->foreign('arsiparis_user_id')->references('id_user')->on('users')->onDelete('restrict');
            $table->index('id_pengajuan');
            $table->index('tgl_arsip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_surat');
    }
};
