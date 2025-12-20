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
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropColumn('file_surat_akhir_path');
            $table->binary('file_surat_content')->nullable()->after('nomor_surat_resmi');
            $table->string('file_surat_name', 255)->nullable()->after('file_surat_content');
            $table->string('file_surat_mime_type', 100)->nullable()->after('file_surat_name');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropColumn(['file_surat_content', 'file_surat_name', 'file_surat_mime_type']);
            $table->string('file_surat_akhir_path', 255)->nullable()->after('nomor_surat_resmi');
        });
    }
};
