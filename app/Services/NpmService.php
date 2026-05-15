<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\ProgramStudi;

class NpmService
{
    public const KODE_PRODI = [
        '01' => 'Teologi',
        '11' => 'Manajemen',
        '12' => 'Akuntansi',
        '13' => 'Magister Manajemen (S2)',
        '31' => 'Biologi',
        '41' => 'Kedokteran',
        '61' => 'Arsitektur',
        '62' => 'Desain Produk',
        '71' => 'Informatika',
        '72' => 'Sistem Informasi',
        '81' => 'Pend. Bahasa Inggris',
        '50' => 'Magister Filsafat Keilahian (S2)',
        '57' => 'Dr. Teologi (S3)',
    ];

    /**
     * Generate NPM untuk mahasiswa baru
     * Format: [Kode Prodi 2 digit][Tahun Masuk 2 digit][No. Urut 4 digit]
     * Contoh: 71250001 (Informatika, 2025, urutan 0001)
     */
    public function generateNpm(int $prodiId, int $tahunMasuk): string
    {
        $prodi = ProgramStudi::find($prodiId);
        if (!$prodi) {
            throw new \Exception('Program Studi tidak ditemukan.');
        }

        $kodeProdi = str_pad($prodi->kode_prodi, 2, '0', STR_PAD_LEFT);
        $tahunMasuk = substr(str_pad($tahunMasuk, 2, '0', STR_PAD_LEFT), -2);

        $prefix = $kodeProdi . $tahunMasuk;

        $lastSequence = $this->getLastSequence($prodiId, $tahunMasuk);
        $newSequence = $lastSequence + 1;

        return $prefix . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get last sequence number for prodi + tahun
     */
    private function getLastSequence(int $prodiId, string $tahunMasuk): int
    {
        $prefix = '%' . $tahunMasuk;

        $lastMahasiswa = Mahasiswa::where('id_prodi', $prodiId)
            ->where('nim', 'LIKE', $prefix)
            ->orderBy('nim', 'desc')
            ->first();

        if (!$lastMahasiswa) {
            return 0;
        }

        $sequence = substr($lastMahasiswa->nim, -4);
        return (int) $sequence;
    }

    /**
     * Validate NPM format
     */
    public function validateNpm(string $npm): bool
    {
        if (strlen($npm) !== 8) {
            return false;
        }

        $kodeProdi = substr($npm, 0, 2);
        $tahunMasuk = substr($npm, 2, 2);

        return isset(self::KODE_PRODI[$kodeProdi]);
    }

    /**
     * Extract parts from NPM
     */
    public function parseNpm(string $npm): array
    {
        return [
            'kode_prodi' => substr($npm, 0, 2),
            'tahun_masuk' => substr($npm, 2, 2),
            'sequence' => substr($npm, 4, 4),
        ];
    }
}
