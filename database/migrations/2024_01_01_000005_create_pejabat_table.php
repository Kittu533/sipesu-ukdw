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
        Schema::create('pejabat', function (Blueprint $table) {
            $table->id('id_pejabat');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_jabatan');
            $table->string('nip', 30)->unique();
            $table->string('tanda_tangan_digital_path', 255)->nullable();
            $table->boolean('is_aktif_ttd')->default(false);
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_jabatan')->references('id_jabatan')->on('jabatan')->onDelete('restrict');
            $table->index('nip');
            $table->index('id_jabatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pejabat');
    }
};
