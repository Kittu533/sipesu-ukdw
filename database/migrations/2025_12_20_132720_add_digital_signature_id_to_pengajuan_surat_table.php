<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->unsignedBigInteger('digital_signature_id')->nullable();
            $table->foreign('digital_signature_id')->references('id')->on('digital_signatures')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropForeign(['digital_signature_id']);
            $table->dropColumn('digital_signature_id');
        });
    }
};
