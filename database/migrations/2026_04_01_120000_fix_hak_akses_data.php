<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $staff = DB::table('hak_akses')->where('nama_hak_akses', 'staff pelayanan jurusan')->first();
        $dekanOther = DB::table('hak_akses')->where('nama_hak_akses', 'dekan fakultas')->where('id_hak_akses', '!=', 3)->first();

        if ($dekanOther) {
            DB::table('hak_akses')->where('id_hak_akses', $dekanOther->id_hak_akses)->update(['nama_hak_akses' => 'dekan_fakultas_temp']);
        }

        if ($staff) {
            DB::table('hak_akses')->where('id_hak_akses', $staff->id_hak_akses)->update(['nama_hak_akses' => 'staff_temp']);
        }

        DB::table('hak_akses')->updateOrInsert(
            ['id_hak_akses' => 1],
            ['nama_hak_akses' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('hak_akses')->updateOrInsert(
            ['id_hak_akses' => 2],
            ['nama_hak_akses' => 'admin administrasi akademik', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('hak_akses')->updateOrInsert(
            ['id_hak_akses' => 3],
            ['nama_hak_akses' => 'dekan fakultas', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('hak_akses')->updateOrInsert(
            ['id_hak_akses' => 4],
            ['nama_hak_akses' => 'pejabat yg berwenang', 'created_at' => now(), 'updated_at' => now()]
        );

        if ($staff) {
            DB::table('users')->where('id_hak_akses', $staff->id_hak_akses)->update(['id_hak_akses' => 3]);
            DB::table('hak_akses')->where('id_hak_akses', $staff->id_hak_akses)->delete();
        }

        if ($dekanOther) {
            DB::table('users')->where('id_hak_akses', $dekanOther->id_hak_akses)->update(['id_hak_akses' => 3]);
            DB::table('hak_akses')->where('id_hak_akses', $dekanOther->id_hak_akses)->delete();
        }

        DB::table('hak_akses')->whereNotIn('id_hak_akses', [1, 2, 3, 4])->delete();
    }

    public function down(): void
    {
    }
};