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
        // Update kode_prodi to 2 digits
        Schema::table('program_studi', function (Blueprint $table) {
            $table->string('kode_prodi', 2)->change();
        });

        // Delete or truncate mahasiswa with nim > 8 characters before changing column
        \Illuminate\Support\Facades\DB::table('mahasiswa')
            ->whereRaw('LENGTH(nim) > 8')
            ->delete();

        // Also reset nim for all existing records to a valid format (for dev only)
        // This assumes they need to be re-imported with correct format
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->string('nim', 8)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_studi', function (Blueprint $table) {
            $table->string('kode_prodi', 10)->change();
        });

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->string('nim', 20)->change();
        });
    }
};
