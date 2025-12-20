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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->unsignedBigInteger('id_user_penerima');
            $table->string('judul', 100);
            $table->text('pesan');
            $table->dateTime('tgl_kirim');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('id_user_penerima')->references('id_user')->on('users')->onDelete('cascade');
            $table->index('id_user_penerima');
            $table->index('is_read');
            $table->index('tgl_kirim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
