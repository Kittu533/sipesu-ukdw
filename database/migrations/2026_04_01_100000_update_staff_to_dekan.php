<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $dekanId = DB::table('hak_akses')->where('nama_hak_akses', 'dekan fakultas')->value('id_hak_akses');
        $staffId = DB::table('hak_akses')->where('nama_hak_akses', 'staff pelayanan jurusan')->value('id_hak_akses');
        
        if ($dekanId && $staffId) {
            DB::table('users')
                ->where('id_hak_akses', $staffId)
                ->update(['id_hak_akses' => $dekanId]);
        }
    }

    public function down(): void
    {
    }
};