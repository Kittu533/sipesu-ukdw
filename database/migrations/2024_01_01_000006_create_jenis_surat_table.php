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
        Schema::create('jenis_surat', function (Blueprint $table) {
            $table->id('id_jenis_surat');
            $table->string('nama_surat', 100);
            $table->string('template_path', 255);
            $table->string('pejabat_yg_menandatangani', 50);
            $table->boolean('perlu_validasi_staff')->default(true);
            $table->timestamps();

            $table->index('nama_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_surat');
    }
};
