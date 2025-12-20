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
            'staff' => HakAkses::where('nama_hak_akses', 'staff pelayanan jurusan')->first()->id_hak_akses ?? 3,
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

        // --- 2. STAFF PELAYANAN JURUSAN ---
        $staffUser = User::firstOrCreate(
            ['username' => 'staff_jurusan'],
            [
                'id_hak_akses' => $hakAkses['staff'],
                'nama_lengkap' => 'Staff Pelayanan Informatika',
                'email' => 'staff@sipesu.id',
                'password_hash' => Hash::make('staff123'),
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


        // --- 4. MAHASISWA ---
        $mahasiswaUser = User::firstOrCreate(
            ['username' => '2023001001'],
            [
                'id_hak_akses' => $hakAkses['mahasiswa'],
                'nama_lengkap' => 'Siti Aisyah',
                'email' => 'siti.aisyah@student.id',
                'password_hash' => Hash::make('mahasiswa123'),
                'status_aktif' => true,
            ]
        );
        // Buat data di tabel Mahasiswa
        Mahasiswa::firstOrCreate(
            ['id_user' => $mahasiswaUser->id_user],
            [
                'nim' => '2023001001',
                'id_prodi' => $prodiInformatika,
                'angkatan' => 2023,
                'ipk_terakhir' => 3.85,
            ]
        );

        // --- 5. GENERATE 100 DUMMY MAHASISWA (BERBAGAI PRODI) ---
        $faker = \Faker\Factory::create('id_ID');

        // Counter untuk nomor urut per prodi & angkatan (untuk NIM)
        // Format key: "KodeProdi-Tahun2Digit"
        $counters = [];

        for ($i = 0; $i < 100; $i++) {
            // Pilih Prodi Random dari daftarProdi yang sudah dibuat di atas
            $fakerProdi = $faker->randomElement($daftarProdi);
            $kodeProdi = $fakerProdi['kode'];
            $idProdi = $prodiIds[$kodeProdi];

            // Pilih Angkatan Random (2020 - 2024)
            $angkatan = $faker->numberBetween(2020, 2024);
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
                    'nama_lengkap' => $faker->name,
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
                    'ipk_terakhir' => $faker->randomFloat(2, 2.50, 4.00),
                ]
            );
        }
    }
}