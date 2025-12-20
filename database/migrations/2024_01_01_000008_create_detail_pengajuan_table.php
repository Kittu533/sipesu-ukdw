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
        Schema::create('detail_pengajuan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_pengajuan');
            $table->string('kode_field_template', 50);
            $table->string('label_field', 100);
            $table->text('nilai_field');
            $table->dateTime('waktu_dibuat');
            $table->dateTime('waktu_diubah')->nullable();
            $table->timestamps();

            $table->foreign('id_pengajuan')->references('id_pengajuan')->on('pengajuan_surat')->onDelete('cascade');
            $table->index('id_pengajuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengajuan');
    }
};
