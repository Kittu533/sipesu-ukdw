<?php

// File: database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan Jabatan Seeder dipanggil duluan jika belum ada
        $this->call([
            JabatanSeeder::class,           // Panggil Jabatan (jika ada)
            HakAksesSeeder::class,
            ProgramStudiSeeder::class,
            JenisSuratSeeder::class,
            UserAndRoleDataSeeder::class,   // Ini akan mengisi tabel User, Mahasiswa, dan Pejabat
        ]);
    }
}