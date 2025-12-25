<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fakultas', function (Blueprint $table) {
            $table->id('id_fakultas');
            $table->string('nama_fakultas');
            $table->string('kode_fakultas', 10);
            $table->timestamps();
        });

        // Add fakultas data
        DB::table('fakultas')->insert([
            ['nama_fakultas' => 'Teknologi Informasi', 'kode_fakultas' => 'FTI', 'created_at' => now(), 'updated_at' => now()],
            ['nama_fakultas' => 'Ekonomi dan Bisnis', 'kode_fakultas' => 'FEB', 'created_at' => now(), 'updated_at' => now()],
            ['nama_fakultas' => 'Psikologi', 'kode_fakultas' => 'FP', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Add id_fakultas column to program_studi
        Schema::table('program_studi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_fakultas')->nullable()->after('nama_prodi');
            $table->foreign('id_fakultas')->references('id_fakultas')->on('fakultas');
        });

        // Update existing program_studi with fakultas
        DB::table('program_studi')->where('nama_prodi', 'LIKE', '%Informatika%')->orWhere('nama_prodi', 'LIKE', '%Sistem Informasi%')->update(['id_fakultas' => 1]);
        DB::table('program_studi')->where('nama_prodi', 'LIKE', '%Ekonomi%')->orWhere('nama_prodi', 'LIKE', '%Manajemen%')->update(['id_fakultas' => 2]);
        DB::table('program_studi')->where('nama_prodi', 'LIKE', '%Psikologi%')->update(['id_fakultas' => 3]);
    }

    public function down(): void
    {
        Schema::table('program_studi', function (Blueprint $table) {
            $table->dropForeign(['id_fakultas']);
            $table->dropColumn('id_fakultas');
        });
        
        Schema::dropIfExists('fakultas');
    }
};
