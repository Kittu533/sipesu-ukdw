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
        Schema::create('persetujuan_pejabat', function (Blueprint $table) {
            $table->id('id_persetujuan');
            $table->unsignedBigInteger('id_pengajuan');
            $table->unsignedBigInteger('id_pejabat');
            $table->dateTime('tgl_persetujuan');
            $table->string('status_persetujuan', 20);
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();

            $table->foreign('id_pengajuan')->references('id_pengajuan')->on('pengajuan_surat')->onDelete('cascade');
            $table->foreign('id_pejabat')->references('id_pejabat')->on('pejabat')->onDelete('restrict');
            $table->index('id_pengajuan');
            $table->index('status_persetujuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persetujuan_pejabat');
    }
};
