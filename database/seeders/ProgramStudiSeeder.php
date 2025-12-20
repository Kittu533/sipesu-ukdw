<?php

// File: database/seeders/ProgramStudiSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramStudi;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $prodis = [
            ['kode_prodi' => '01', 'nama_prodi' => 'Teologi', 'id_jurusan' => 1],
            ['kode_prodi' => '11', 'nama_prodi' => 'Manajemen', 'id_jurusan' => 2],
            ['kode_prodi' => '12', 'nama_prodi' => 'Akuntansi', 'id_jurusan' => 2],
            ['kode_prodi' => '13', 'nama_prodi' => 'Magister Manajemen', 'id_jurusan' => 2],
            ['kode_prodi' => '31', 'nama_prodi' => 'Biologi', 'id_jurusan' => 3],
            ['kode_prodi' => '41', 'nama_prodi' => 'Kedokteran', 'id_jurusan' => 4],
            ['kode_prodi' => '61', 'nama_prodi' => 'Arsitektur', 'id_jurusan' => 5],
            ['kode_prodi' => '62', 'nama_prodi' => 'Desain Produk', 'id_jurusan' => 5],
            ['kode_prodi' => '71', 'nama_prodi' => 'Informatika', 'id_jurusan' => 6],
            ['kode_prodi' => '72', 'nama_prodi' => 'Sistem Informasi', 'id_jurusan' => 6],
            ['kode_prodi' => '81', 'nama_prodi' => 'Pendidikan Bahasa Inggris', 'id_jurusan' => 7],
            ['kode_prodi' => '50', 'nama_prodi' => 'Magister Filsafat Keilahian', 'id_jurusan' => 8],
            ['kode_prodi' => '57', 'nama_prodi' => 'Dr Teologi (S3)', 'id_jurusan' => 1],
        ];

        foreach ($prodis as $prodi) {
            ProgramStudi::updateOrCreate(
                ['kode_prodi' => $prodi['kode_prodi']],
                $prodi
            );
        }
    }
}