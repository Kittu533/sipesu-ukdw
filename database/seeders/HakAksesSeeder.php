<?php

// File: database/seeders/HakAksesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HakAkses;

class HakAksesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'mahasiswa',
            'admin administrasi akademik',
            'dekan fakultas',
            'pejabat yg berwenang',
        ];

        foreach ($roles as $role) {
            HakAkses::updateOrCreate(
                ['nama_hak_akses' => $role],
                ['nama_hak_akses' => $role]
            );
        }

        HakAkses::where('nama_hak_akses', 'staff pelayanan jurusan')->delete();
    }
}