<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_surat', function (Blueprint $table) {
            $table->boolean('perlu_validasi_dekan')->default(true)->after('perlu_validasi_staff');
        });

        DB::table('jenis_surat')->update(['perlu_validasi_dekan' => true]);

        DB::table('jenis_surat')
            ->whereIn('nama_surat', ['Surat Keterangan Aktif Kuliah', 'Surat Keterangan Alumni', 'Surat Keterangan Lulus (Statement Letter)'])
            ->update(['perlu_validasi_dekan' => false]);
    }

    public function down(): void
    {
        Schema::table('jenis_surat', function (Blueprint $table) {
            $table->dropColumn('perlu_validasi_dekan');
        });
    }
};