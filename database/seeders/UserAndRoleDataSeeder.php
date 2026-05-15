<?php

// File: database/seeders/UserAndRoleDataSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\HakAkses;
use App\Models\Mahasiswa;
use App\Models\Pejabat;
use App\Models\ProgramStudi;
use App\Models\Jabatan;

class UserAndRoleDataSeeder extends Seeder
{
    public function run(): void
    {
        // --- 0. SETUP PRODI (Updated per request) ---
        $daftarProdi = [
            ['kode' => '71', 'nama' => 'Informatika'],
            ['kode' => '72', 'nama' => 'Sistem Informasi'],
            ['kode' => '61', 'nama' => 'Arsitektur'],
            ['kode' => '62', 'nama' => 'Desain Produk'],
        ];

        // Pastikan Prodi ada dan simpan ID-nya untuk referensi
        $prodiIds = [];
        foreach ($daftarProdi as $p) {
            $prodiObj = ProgramStudi::firstOrCreate(
                ['kode_prodi' => $p['kode']],
                ['nama_prodi' => $p['nama']]
            );
            $prodiIds[$p['kode']] = $prodiObj->id_prodi;
        }

        // Pastikan Jabatan sudah ada untuk Pejabat (jika belum, buat dummy)
        $kaprodiJabatan = Jabatan::firstOrCreate(['nama_jabatan' => 'Kepala Program Studi', 'kode_jabatan' => 'KPS']);
        
        // 1. Ambil ID Hak Akses
        $hakAkses = [
            'mahasiswa' => HakAkses::where('nama_hak_akses', 'mahasiswa')->first()->id_hak_akses ?? 1,
            'admin' => HakAkses::where('nama_hak_akses', 'admin administrasi akademik')->first()->id_hak_akses ?? 2,
            'dekan' => HakAkses::where('nama_hak_akses', 'dekan fakultas')->first()->id_hak_akses ?? 3,
            'pejabat' => HakAkses::where('nama_hak_akses', 'pejabat yg berwenang')->first()->id_hak_akses ?? 4,
        ];
        
        // Ambil ID Program Studi
        $prodiInformatika = ProgramStudi::where('kode_prodi', '71')->first()->id_prodi ?? 1;

        // --- 1. ADMIN ADMINISTRASI AKADEMIK ---
        $adminUser = User::firstOrCreate(
            ['username' => 'admin_akademik'],
            [
                'id_hak_akses' => $hakAkses['admin'],
                'nama_lengkap' => 'Admin Akademik SIPESU',
                'email' => 'admin@sipesu.id',
                'password_hash' => Hash::make('admin123'),
                'status_aktif' => true,
            ]
        );

        // --- 2. DEKAN FAKULTAS ---
        $dekanUser = User::firstOrCreate(
            ['username' => 'dekan_fakultas'],
            [
                'id_hak_akses' => $hakAkses['dekan'],
                'nama_lengkap' => 'Dekan Fakultas Informatika',
                'email' => 'dekan@fakultasi.id',
                'password_hash' => Hash::make('dekan123'),
                'status_aktif' => true,
            ]
        );

        // --- 3. PEJABAT BERWENANG (KAPRODI) ---
        $pejabatUser = User::firstOrCreate(
            ['username' => 'kaprodi_if'],
            [
                'id_hak_akses' => $hakAkses['pejabat'],
                'nama_lengkap' => 'Dr. Budi Santoso, M.Kom.',
                'email' => 'kaprodi.if@sipesu.id',
                'password_hash' => Hash::make('pejabat123'),
                'status_aktif' => true,
            ]
        );
        // Buat data di tabel Pejabat
        Pejabat::firstOrCreate(
            ['id_user' => $pejabatUser->id_user],
            [
                'id_jabatan' => $kaprodiJabatan->id_jabatan,
                'nip' => '198001012005011001',
                'tanda_tangan_digital_path' => null,
                'is_aktif_ttd' => true,
            ]
        );


        // --- 4. MAHASISWA (NIM baru format 8 digit: KodeProdi+Tahun+Urut) ---
        // Format: 71230001 = Informatika(71) + 2023(23) + 0001
        $mahasiswaUser = User::firstOrCreate(
            ['username' => '71230001'],
            [
                'id_hak_akses' => $hakAkses['mahasiswa'],
                'nama_lengkap' => 'Siti Aisyah',
                'email' => 'siti.aisyah@student.id',
                'password_hash' => Hash::make('71230001'),
                'status_aktif' => true,
            ]
        );
        // Buat data di tabel Mahasiswa
        Mahasiswa::firstOrCreate(
            ['id_user' => $mahasiswaUser->id_user],
            [
                'nim' => '71230001',
                'id_prodi' => $prodiInformatika,
                'angkatan' => 2023,
                'ipk_terakhir' => 3.85,
                'status_mahasiswa' => 'aktif',
            ]
        );

        // --- 5. GENERATE 100 DUMMY MAHASISWA (BERBAGAI PRODI) ---
        $namaDepan = ['Ahmad', 'Budi', 'Citra', 'Dewi', 'Eka', 'Fajar', 'Gita', 'Hana', 'Indra', 'Joko', 'Kartika', 'Lina', 'Maya', 'Nanda', 'Putri', 'Rama', 'Sari', 'Tono', 'Vina', 'Yoga'];
        $namaBelakang = ['Pratama', 'Santoso', 'Wijaya', 'Lestari', 'Permana', 'Saputra', 'Wulandari', 'Nugroho', 'Kusuma', 'Hidayat'];
        $prodiIndex = array_values($daftarProdi);

        // Counter untuk nomor urut per prodi & angkatan (untuk NIM)
        // Format key: "KodeProdi-Tahun2Digit"
        $counters = [];

        for ($i = 0; $i < 100; $i++) {
            // Pilih Prodi dari daftarProdi yang sudah dibuat di atas
            $fakerProdi = $prodiIndex[$i % count($prodiIndex)];
            $kodeProdi = $fakerProdi['kode'];
            $idProdi = $prodiIds[$kodeProdi];

            // Sebar angkatan 2020 - 2024
            $angkatan = 2020 + ($i % 5);
            $tahunDuaDigit = substr((string)$angkatan, -2); 

            // Hitung nomor urut
            $key = $kodeProdi . $tahunDuaDigit;
            if (!isset($counters[$key])) {
                $counters[$key] = 0;
            }
            $counters[$key]++;
            $seq = $counters[$key];

            // Format NIM: KodeProdi(2) + Tahun(2) + Urut(4) -> Contoh: 71230001
            $nim = sprintf("%s%s%04d", $kodeProdi, $tahunDuaDigit, $seq);

            // Buat User (Password = NIM)
            $mhsDummy = User::firstOrCreate(
                ['username' => $nim],
                [
                    'id_hak_akses' => $hakAkses['mahasiswa'],
                    'nama_lengkap' => $namaDepan[$i % count($namaDepan)] . ' ' . $namaBelakang[$i % count($namaBelakang)],
                    'email' => $nim . '@students.ukdw.ac.id',
                    'password_hash' => Hash::make($nim),
                    'status_aktif' => true,
                ]
            );

            // Buat Data Mahasiswa
            Mahasiswa::firstOrCreate(
                ['id_user' => $mhsDummy->id_user],
                [
                    'nim' => $nim,
                    'id_prodi' => $idProdi,
                    'angkatan' => $angkatan,
                    'ipk_terakhir' => round(2.50 + (($i % 151) / 100), 2),
                    'status_mahasiswa' => 'aktif',
                ]
            );
        }
    }
}
