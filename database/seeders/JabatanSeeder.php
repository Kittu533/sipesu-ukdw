<?php

// File: database/seeders/JabatanSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            ['nama_jabatan' => 'Kepala Program Studi', 'kode_jabatan' => 'KPS'],
            ['nama_jabatan' => 'Wakil Rektor Bidang Akademik', 'kode_jabatan' => 'WRA'],
            ['nama_jabatan' => 'Dekan Fakultas', 'kode_jabatan' => 'DEKAN'],
            ['nama_jabatan' => 'Kepala Biro Administrasi Akademik', 'kode_jabatan' => 'KABA'],
            ['nama_jabatan' => 'Sekretaris Program Studi', 'kode_jabatan' => 'SEKPRODI'],
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::updateOrCreate(
                ['kode_jabatan' => $jabatan['kode_jabatan']],
                $jabatan
            );
        }
    }
}