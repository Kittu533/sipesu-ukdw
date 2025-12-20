<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digital_signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name')->comment('Contoh: TTD Basah atau QR Verifikasi');
            $table->enum('type', ['png', 'qrcode']);
            $table->string('path')->comment('Lokasi penyimpanan file di storage');
            $table->text('qr_text')->nullable()->comment('Data yang dienkripsi atau link verifikasi');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('user_id', 'fk_user_signature')->references('id_user')->on('users')->onDelete('cascade');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_signatures');
    }
};
