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
        if (Schema::hasColumn('mahasiswa', 'ipk_terakhir')) {
            return;
        }

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->decimal('ipk_terakhir', 3, 2)->nullable()->after('status_mahasiswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('mahasiswa', 'ipk_terakhir')) {
            return;
        }

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn('ipk_terakhir');
        });
    }
};
