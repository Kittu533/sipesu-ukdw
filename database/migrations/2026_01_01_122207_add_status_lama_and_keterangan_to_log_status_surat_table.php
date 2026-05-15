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
        Schema::table('log_status_surat', function (Blueprint $table) {
            $table->string('status_lama', 50)->nullable()->after('tgl_perubahan');
            $table->text('keterangan')->nullable()->after('status_baru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_status_surat', function (Blueprint $table) {
            $table->dropColumn(['status_lama', 'keterangan']);
        });
    }
};
