# 📚 Dokumentasi Sistem Database OLTP - Aplikasi Pengajuan Surat

## 📋 Daftar Isi
1. [Ringkasan Skema](#ringkasan-skema)
2. [Struktur Tabel](#struktur-tabel)
3. [Model Eloquent](#model-eloquent)
4. [Relationship Antar Model](#relationship-antar-model)
5. [Contoh Penggunaan](#contoh-penggunaan)
6. [Menjalankan Migration](#menjalankan-migration)

---

## Ringkasan Skema

Sistem database OLTP ini dirancang untuk mendukung aplikasi **Pengajuan Surat Keterangan** dengan fitur:
- ✅ Manajemen Pengguna dan Keamanan (dengan Hak Akses)
- ✅ Data Akademik Master (Mahasiswa, Prodi, Pejabat)
- ✅ Manajemen Pengajuan Surat dengan Template
- ✅ Workflow Validasi dan Persetujuan
- ✅ Tanda Tangan Digital Pejabat
- ✅ Logging dan Audit Trail
- ✅ Notifikasi Sistem

---

## Struktur Tabel

### I. Manajemen Pengguna dan Keamanan

#### `hak_akses`
Mendefinisikan peran/role pengguna dalam sistem.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_hak_akses (PK) | INT | Kunci Utama |
| nama_hak_akses | VARCHAR(50) | Contoh: 'Mahasiswa', 'Admin', 'Staff', 'Pejabat Berwenang' |

#### `users`
Tabel utama untuk autentikasi dan identitas pengguna.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_user (PK) | BIGINT | Kunci Utama |
| id_hak_akses (FK) | BIGINT | Merujuk ke `hak_akses` |
| username | VARCHAR(50) | Unique - untuk login |
| password_hash | VARCHAR(255) | Hash password terenkripsi |
| email | VARCHAR(100) | Unique - email resmi |
| nama_lengkap | VARCHAR(100) | Nama lengkap pengguna |
| status_aktif | BOOLEAN | Status akun aktif/tidak aktif |

#### `log_aktivitas`
Mencatat setiap aktivitas pengguna untuk audit trail.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_log (PK) | BIGINT | Kunci Utama |
| id_user (FK) | BIGINT | Merujuk ke `users` |
| waktu | DATETIME | Waktu aktivitas terjadi |
| tipe_aktivitas | VARCHAR(50) | UPDATE, DELETE, LOGIN, CREATE, dll |
| deskripsi | TEXT | Deskripsi rinci aktivitas |

---

### II. Data Akademik Master

#### `program_studi`
Master data Program Studi/Jurusan.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_prodi (PK) | BIGINT | Kunci Utama |
| kode_prodi | VARCHAR(10) | Unique - Contoh: TI, SI |
| nama_prodi | VARCHAR(100) | Nama lengkap prodi |
| id_jurusan | BIGINT | Referensi ke jurusan (opsional) |

#### `mahasiswa`
Data mahasiswa yang mengajukan surat.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_mahasiswa (PK) | BIGINT | Kunci Utama |
| id_user (FK) | BIGINT | Merujuk ke `users` (1-to-1) |
| nim | VARCHAR(20) | Unique - Nomor Induk Mahasiswa |
| id_prodi (FK) | BIGINT | Merujuk ke `program_studi` |
| angkatan | YEAR | Tahun masuk |
| ipk_terakhir | DECIMAL(3,2) | IPK terkini (nullable) |

#### `jabatan`
Master data Jabatan Struktural Pejabat.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_jabatan (PK) | BIGINT | Kunci Utama |
| nama_jabatan | VARCHAR(100) | Unique - Contoh: 'Kepala Jurusan' |
| kode_jabatan | VARCHAR(10) | Unique - Kode singkatan |

#### `pejabat`
Data Pejabat yang berwenang menandatangani surat.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_pejabat (PK) | BIGINT | Kunci Utama |
| id_user (FK) | BIGINT | Merujuk ke `users` (1-to-1) |
| id_jabatan (FK) | BIGINT | Merujuk ke `jabatan` |
| nip | VARCHAR(30) | Unique - Nomor Induk Pegawai |
| tanda_tangan_digital_path | VARCHAR(255) | Path ke file gambar TTD |
| is_aktif_ttd | BOOLEAN | Status TTD aktif digunakan |

---

### III. Manajemen Pengajuan Surat

#### `jenis_surat`
Mendefinisikan jenis-jenis surat yang bisa diajukan.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_jenis_surat (PK) | BIGINT | Kunci Utama |
| nama_surat | VARCHAR(100) | Contoh: 'Surat Keterangan Aktif Kuliah' |
| template_path | VARCHAR(255) | Path file template (.docx) |
| pejabat_yg_menandatangani | VARCHAR(50) | Jabatan yang berwenang |
| perlu_validasi_staff | BOOLEAN | Butuh validasi Staff dulu? |

#### `pengajuan_surat`
Tabel fakta utama - setiap pengajuan surat dari Mahasiswa.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_pengajuan (PK) | BIGINT | Kunci Utama |
| id_mahasiswa (FK) | BIGINT | Merujuk ke `mahasiswa` |
| id_jenis_surat (FK) | BIGINT | Merujuk ke `jenis_surat` |
| tgl_pengajuan | DATETIME | Tanggal pengajuan |
| status_saat_ini | VARCHAR(50) | Status terakhir surat |
| keterangan_mahasiswa | TEXT | Keterangan dari mahasiswa |
| nomor_surat_resmi | VARCHAR(100) | Nomor surat resmi (setelah setuju) |
| file_surat_akhir_path | VARCHAR(255) | Path surat final (dengan TTD) |

#### `detail_pengajuan`
Menyimpan inputan spesifik untuk setiap field template surat.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_detail (PK) | BIGINT | Kunci Utama |
| id_pengajuan (FK) | BIGINT | Merujuk ke `pengajuan_surat` |
| kode_field_template | VARCHAR(50) | Placeholder di template (contoh: {{NAMA_ORANG_TUA}}) |
| label_field | VARCHAR(100) | Label UI (contoh: "Nama Orang Tua") |
| nilai_field | TEXT | Nilai yang diinputkan mahasiswa |
| waktu_dibuat | DATETIME | Audit timestamp |
| waktu_diubah | DATETIME | Audit timestamp (nullable) |

---

### IV. Workflow (Validasi dan Persetujuan)

#### `validasi_staff`
Mencatat validasi oleh Staff Pelayanan Jurusan.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_validasi (PK) | BIGINT | Kunci Utama |
| id_pengajuan (FK) | BIGINT | Merujuk ke `pengajuan_surat` |
| id_user_staff (FK) | BIGINT | Staff yang memvalidasi |
| tgl_validasi | DATETIME | Waktu validasi |
| status_validasi | VARCHAR(20) | 'Diteruskan' atau 'Tolak' |
| catatan_staff | TEXT | Catatan dari staff |

#### `persetujuan_pejabat`
Mencatat persetujuan/penolakan oleh Pejabat Berwenang.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_persetujuan (PK) | BIGINT | Kunci Utama |
| id_pengajuan (FK) | BIGINT | Merujuk ke `pengajuan_surat` |
| id_pejabat (FK) | BIGINT | Pejabat yang menyetujui |
| tgl_persetujuan | DATETIME | Waktu persetujuan |
| status_persetujuan | VARCHAR(20) | 'Setuju' atau 'Tolak' |
| alasan_penolakan | TEXT | Alasan penolakan (nullable) |

#### `log_status_surat`
Riwayat perubahan status surat untuk audit dan tracking.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_log_status (PK) | BIGINT | Kunci Utama |
| id_pengajuan (FK) | BIGINT | Merujuk ke `pengajuan_surat` |
| tgl_perubahan | DATETIME | Waktu perubahan |
| status_baru | VARCHAR(50) | Status baru (contoh: 'Divalidasi', 'TTD') |
| diubah_oleh_user (FK) | BIGINT | Siapa yang mengubah |

#### `arsip_surat`
Menyimpan data arsip surat yang sudah selesai.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_arsip (PK) | BIGINT | Kunci Utama |
| id_pengajuan (FK) | BIGINT | Merujuk ke `pengajuan_surat` |
| tgl_arsip | DATE | Tanggal diarsipkan |
| arsiparis_user_id (FK) | BIGINT | Admin/Staff yang mengarsipkan |

---

### V. Komunikasi

#### `notifikasi`
Notifikasi/pesan sistem untuk pengguna.

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id_notifikasi (PK) | BIGINT | Kunci Utama |
| id_user_penerima (FK) | BIGINT | Penerima notifikasi |
| judul | VARCHAR(100) | Judul notifikasi |
| pesan | TEXT | Isi pesan |
| tgl_kirim | DATETIME | Waktu pengiriman |
| is_read | BOOLEAN | Status sudah dibaca |

---

## Model Eloquent

Semua model telah dibuat di `app/Models/` dengan relationship yang tepat:

- `User` - Model utama untuk autentikasi
- `HakAkses` - Model untuk role/permission
- `LogAktivitas` - Model untuk audit trail
- `ProgramStudi` - Master data prodi
- `Mahasiswa` - Data mahasiswa
- `Jabatan` - Master data jabatan
- `Pejabat` - Data pejabat berwenang
- `JenisSurat` - Master data jenis surat
- `PengajuanSurat` - Tabel utama pengajuan
- `DetailPengajuan` - Detail inputan pengajuan
- `ValidasiStaff` - Validasi staff
- `PersetujuanPejabat` - Persetujuan pejabat
- `LogStatusSurat` - Riwayat status
- `ArsipSurat` - Arsip surat
- `Notifikasi` - Notifikasi sistem

---

## Relationship Antar Model

### USERS (Pusat)
```
User
├── 1 → HakAkses (BelongsTo)
├── 1 ↔ Mahasiswa (HasOne)
├── 1 ↔ Pejabat (HasOne)
├── ∞ → LogAktivitas (HasMany)
├── ∞ → ValidasiStaff (HasMany) [sebagai staff]
├── ∞ → LogStatusSurat (HasMany) [sebagai pengubah]
├── ∞ → ArsipSurat (HasMany) [sebagai pengarsip]
└── ∞ → Notifikasi (HasMany)
```

### MAHASISWA
```
Mahasiswa
├── 1 ← User (BelongsTo)
├── 1 ← ProgramStudi (BelongsTo)
└── ∞ → PengajuanSurat (HasMany)
```

### PEJABAT
```
Pejabat
├── 1 ← User (BelongsTo)
├── 1 ← Jabatan (BelongsTo)
└── ∞ → PersetujuanPejabat (HasMany)
```

### PENGAJUAN_SURAT (Pusat)
```
PengajuanSurat
├── 1 ← Mahasiswa (BelongsTo)
├── 1 ← JenisSurat (BelongsTo)
├── ∞ → DetailPengajuan (HasMany)
├── ∞ → ValidasiStaff (HasMany)
├── ∞ → PersetujuanPejabat (HasMany)
├── ∞ → LogStatusSurat (HasMany)
└── ∞ → ArsipSurat (HasMany)
```

---

## Contoh Penggunaan

### 1. Mengambil Data Mahasiswa dengan Semua Pengajuannya

```php
use App\Models\Mahasiswa;

$mahasiswa = Mahasiswa::with([
    'user',
    'programStudi',
    'pengajuanSurat' => function($q) {
        $q->with(['jenisSurat', 'detailPengajuan']);
    }
])->find($id);

// Akses data
echo $mahasiswa->user->nama_lengkap;
echo $mahasiswa->programStudi->nama_prodi;
foreach($mahasiswa->pengajuanSurat as $pengajuan) {
    echo $pengajuan->jenisSurat->nama_surat;
}
```

### 2. Mengambil Pengajuan Surat dengan Workflow-nya

```php
use App\Models\PengajuanSurat;

$pengajuan = PengajuanSurat::with([
    'mahasiswa.user',
    'jenisSurat',
    'detailPengajuan',
    'validasiStaff.userStaff',
    'persetujuanPejabat.pejabat.user',
    'logStatusSurat.userPengubah',
    'arsipSurat'
])->find($id);

// Cek status validasi
$validasi = $pengajuan->validasiStaff->first();
if($validasi && $validasi->status_validasi === 'Diteruskan') {
    echo "Sudah divalidasi staff";
}

// Cek status persetujuan
$persetujuan = $pengajuan->persetujuanPejabat->first();
if($persetujuan && $persetujuan->status_persetujuan === 'Setuju') {
    echo "Sudah disetujui pejabat";
}
```

### 3. Membuat Pengajuan Surat Baru dengan Detail

```php
use App\Models\PengajuanSurat;
use App\Models\DetailPengajuan;
use Carbon\Carbon;

$pengajuan = PengajuanSurat::create([
    'id_mahasiswa' => 1,
    'id_jenis_surat' => 1,
    'tgl_pengajuan' => Carbon::now(),
    'status_saat_ini' => 'Menunggu Validasi',
    'keterangan_mahasiswa' => 'Perlu surat untuk visa',
]);

// Tambahkan detail pengajuan
DetailPengajuan::create([
    'id_pengajuan' => $pengajuan->id_pengajuan,
    'kode_field_template' => '{{TUJUAN}}',
    'label_field' => 'Tujuan Pengajuan',
    'nilai_field' => 'Mengurus visa',
    'waktu_dibuat' => Carbon::now(),
]);
```

### 4. Mencatat Validasi Staff

```php
use App\Models\ValidasiStaff;
use Carbon\Carbon;

ValidasiStaff::create([
    'id_pengajuan' => 1,
    'id_user_staff' => 2, // User ID staff
    'tgl_validasi' => Carbon::now(),
    'status_validasi' => 'Diteruskan',
    'catatan_staff' => 'Data lengkap, lanjut ke pejabat',
]);
```

### 5. Mencatat Persetujuan Pejabat

```php
use App\Models\PersetujuanPejabat;
use Carbon\Carbon;

PersetujuanPejabat::create([
    'id_pengajuan' => 1,
    'id_pejabat' => 1,
    'tgl_persetujuan' => Carbon::now(),
    'status_persetujuan' => 'Setuju',
    'nomor_surat_resmi' => 'KJ-001/2024', // Update juga di pengajuan_surat
]);
```

### 6. Mencari Surat yang Menunggu Persetujuan Pejabat Tertentu

```php
use App\Models\PengajuanSurat;

$suratMenunggu = PengajuanSurat::where('status_saat_ini', 'Menunggu Persetujuan Pejabat')
    ->with(['mahasiswa.user', 'jenisSurat'])
    ->whereNotIn('id_pengajuan', function($q) {
        $q->selectRaw('id_pengajuan')
          ->from('persetujuan_pejabat');
    })
    ->get();
```

### 7. Mencatat Log Status Surat

```php
use App\Models\LogStatusSurat;
use Carbon\Carbon;

LogStatusSurat::create([
    'id_pengajuan' => 1,
    'tgl_perubahan' => Carbon::now(),
    'status_baru' => 'Divalidasi Staff',
    'diubah_oleh_user' => 2, // User yang mengubah status
]);
```

### 8. Mengirim Notifikasi

```php
use App\Models\Notifikasi;
use Carbon\Carbon;

Notifikasi::create([
    'id_user_penerima' => 1,
    'judul' => 'Surat Disetujui',
    'pesan' => 'Pengajuan surat Anda telah disetujui. Silakan ambil di ruang dekanat.',
    'tgl_kirim' => Carbon::now(),
    'is_read' => false,
]);
```

---

## Menjalankan Migration

### Cara 1: Migration Semua Sekaligus

```bash
php artisan migrate
```

### Cara 2: Migration dengan Step (Melihat Progress)

```bash
php artisan migrate --step
```

### Cara 3: Rollback (Batalkan Migration)

```bash
# Rollback semua
php artisan migrate:reset

# Rollback dan jalankan lagi
php artisan migrate:refresh

# Rollback step terakhir
php artisan migrate:rollback --step=1
```

### Cara 4: Seed Database (Optional - setelah buat seeder)

```bash
php artisan db:seed
```

---

## Catatan Penting

1. **Foreign Keys**: Semua tabel sudah memiliki foreign key yang benar dengan ON DELETE yang sesuai.
   - `CASCADE` untuk data yang bergantung (detail)
   - `RESTRICT` untuk data master (tidak bisa dihapus jika masih digunakan)

2. **Indexing**: Index sudah ditambahkan di kolom-kolom yang sering diquery (FK, status, tanggal)

3. **Timestamps**: Setiap model sudah memiliki `created_at` dan `updated_at` secara otomatis

4. **Primary Key Custom**: Semua tabel menggunakan primary key custom sesuai naming convention OLTP

5. **Password Field**: Di User model, field `password_hash` tidak otomatis di-hash. Untuk hashing gunakan:
   ```php
   $user->password_hash = Hash::make($password);
   ```

---

## Troubleshooting

### Error: "SQLSTATE[HY000]: General error: 1005 Can't create table"
Pastikan urutan migration benar, terutama FK harus ke tabel yang sudah ada.

### Error: "Specified key was too long"
Kurangi panjang VARCHAR di kolom yang dijadikan unique key.

### Error: "Unknown column in foreign key"
Pastikan primary key di tabel referenced sama dengan tipe data foreign key.

---

Selesai! Sistem database OLTP Anda sudah siap digunakan dengan model dan relationship yang lengkap.
