<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSurat = [
            [
                'nama_surat' => 'Surat Keterangan Aktif Kuliah',
                'template_path' => 'pdf.active-student-certificate',
                'pejabat_yg_menandatangani' => 'Kepala Biro Administrasi Akademik',
                'perlu_validasi_staff' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_surat' => 'Surat Keterangan Alumni',
                'template_path' => 'pdf.alumni-certificate',
                'pejabat_yg_menandatangani' => 'Kepala Biro Administrasi Akademik',
                'perlu_validasi_staff' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_surat' => 'Surat Keterangan Pengunduran Diri',
                'template_path' => 'pdf.withdrawal-certificate',
                'pejabat_yg_menandatangani' => 'Kepala Biro Administrasi Akademik',
                'perlu_validasi_staff' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_surat' => 'Surat Keterangan Lulus (Statement Letter)',
                'template_path' => 'pdf.statement-letter',
                'pejabat_yg_menandatangani' => 'Kepala Biro Administrasi Akademik',
                'perlu_validasi_staff' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('jenis_surat')->insert($jenisSurat);
    }
}
