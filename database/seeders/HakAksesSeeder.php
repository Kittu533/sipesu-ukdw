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
            'staff pelayanan jurusan',
            'pejabat yg berwenang',
        ];

        foreach ($roles as $role) {
            // Gunakan updateOrCreate untuk menghindari duplikasi jika seeder dijalankan ulang
            HakAkses::updateOrCreate(
                ['nama_hak_akses' => $role],
                ['nama_hak_akses' => $role]
            );
        }
    }
}