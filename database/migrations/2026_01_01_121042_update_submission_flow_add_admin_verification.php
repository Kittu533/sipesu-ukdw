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
        // Update existing submissions with old status to new flow
        DB::table('pengajuan_surat')
            ->where('status_saat_ini', 'Menunggu Verifikasi')
            ->update(['status_saat_ini' => 'Menunggu Verifikasi Admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to old flow
        DB::table('pengajuan_surat')
            ->where('status_saat_ini', 'Menunggu Verifikasi Admin')
            ->update(['status_saat_ini' => 'Menunggu Verifikasi']);
    }
};
