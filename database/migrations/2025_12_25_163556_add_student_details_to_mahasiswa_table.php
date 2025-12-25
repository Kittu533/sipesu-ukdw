<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable()->after('nim');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('nama_orang_tua')->nullable()->after('tanggal_lahir');
            $table->string('nip_orang_tua')->nullable()->after('nama_orang_tua');
            $table->string('pangkat_orang_tua')->nullable()->after('nip_orang_tua');
            $table->string('instansi_orang_tua')->nullable()->after('pangkat_orang_tua');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir',
                'tanggal_lahir', 
                'nama_orang_tua',
                'nip_orang_tua',
                'pangkat_orang_tua',
                'instansi_orang_tua'
            ]);
        });
    }
};
