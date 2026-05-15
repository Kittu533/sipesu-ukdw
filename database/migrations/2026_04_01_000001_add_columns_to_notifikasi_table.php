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
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->string('type', 20)->default('info')->after('pesan');
            $table->string('link', 255)->nullable()->after('type');
            $table->string('role_penerima', 20)->nullable()->after('id_user_penerima');
            
            $table->dropForeign(['id_user_penerima']);
            $table->foreign('id_user_penerima')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropForeign(['id_user_penerima']);
            $table->dropColumn(['type', 'link', 'role_penerima']);
            
            $table->foreign('id_user_penerima')->references('id_user')->on('users')->onDelete('cascade');
        });
    }
};
