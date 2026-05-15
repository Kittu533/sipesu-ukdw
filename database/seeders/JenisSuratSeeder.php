<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisSurat;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSurat = [
            [
                'nama_surat' => JenisSurat::AKTIF_KULIAH,
                'template_path' => 'pdf.active-student-certificate',
                'pejabat_yg_menandatangani' => 'Kepala Biro Administrasi Akademik',
                'perlu_validasi_staff' => true,
                'perlu_validasi_dekan' => false,
            ],
            [
                'nama_surat' => JenisSurat::ALUMNI,
                'template_path' => 'pdf.alumni-certificate',
                'pejabat_yg_menandatangani' => 'Kepala Biro Administrasi Akademik',
                'perlu_validasi_staff' => true,
                'perlu_validasi_dekan' => false,
            ],
            [
                'nama_surat' => JenisSurat::PENGUNDURAN_DIRI,
                'template_path' => 'pdf.withdrawal-certificate',
                'pejabat_yg_menandatangani' => 'Kepala Biro Administrasi Akademik',
                'perlu_validasi_staff' => true,
                'perlu_validasi_dekan' => true,
            ],
            [
                'nama_surat' => JenisSurat::LULUS,
                'template_path' => 'pdf.statement-letter',
                'pejabat_yg_menandatangani' => 'Kepala Biro Administrasi Akademik',
                'perlu_validasi_staff' => true,
                'perlu_validasi_dekan' => false,
            ],
            [
                'nama_surat' => JenisSurat::CUTI_AKADEMIK,
                'template_path' => 'pdf.academic-leave-certificate',
                'pejabat_yg_menandatangani' => 'Kepala Biro Administrasi Akademik',
                'perlu_validasi_staff' => true,
                'perlu_validasi_dekan' => true,
            ],
        ];

        foreach ($jenisSurat as $surat) {
            JenisSurat::updateOrCreate(
                ['nama_surat' => $surat['nama_surat']],
                $surat
            );
        }
    }
}
