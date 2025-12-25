<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus data yang ada terlebih dahulu
        DB::table('pengajuan_surat')->update(['file_surat_content' => null]);
        
        // Ubah kolom menjadi longblob
        DB::statement('ALTER TABLE pengajuan_surat MODIFY file_surat_content LONGBLOB NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE pengajuan_surat MODIFY file_surat_content TEXT NULL');
    }
};
