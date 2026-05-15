# ERD Revisi - Sistem Pengajuan Surat

```mermaid
erDiagram
    HAK_AKSES ||--o{ USERS : "dimiliki oleh"
    USERS ||--o| MAHASISWA : "profil mahasiswa"
    USERS ||--o| PEJABAT : "profil pejabat"
    USERS ||--o{ LOG_AKTIVITAS : "melakukan"
    USERS ||--o{ VALIDASI_STAFF : "memvalidasi"
    USERS ||--o{ LOG_STATUS_SURAT : "mengubah status"
    USERS ||--o{ ARSIP_SURAT : "mengarsipkan"
    USERS ||--o{ NOTIFIKASI : "menerima"
    USERS ||--o{ DIGITAL_SIGNATURES : "memiliki"

    FAKULTAS ||--o{ PROGRAM_STUDI : "memiliki"
    PROGRAM_STUDI ||--o{ MAHASISWA : "menaungi"

    JABATAN ||--o{ PEJABAT : "diisi oleh"
    PEJABAT ||--o{ PERSETUJUAN_PEJABAT : "memberi persetujuan"

    MAHASISWA ||--o{ PENGAJUAN_SURAT : "mengajukan"
    JENIS_SURAT ||--o{ PENGAJUAN_SURAT : "jenis pengajuan"
    DIGITAL_SIGNATURES ||--o{ PENGAJUAN_SURAT : "digunakan pada"

    PENGAJUAN_SURAT ||--o{ DETAIL_PENGAJUAN : "memiliki detail"
    PENGAJUAN_SURAT ||--o{ VALIDASI_STAFF : "divalidasi"
    PENGAJUAN_SURAT ||--o{ PERSETUJUAN_PEJABAT : "disetujui"
    PENGAJUAN_SURAT ||--o{ LOG_STATUS_SURAT : "riwayat status"
    PENGAJUAN_SURAT ||--o{ ARSIP_SURAT : "diarsipkan"

    HAK_AKSES {
        bigint id_hak_akses PK
        varchar nama_hak_akses UK
        timestamp created_at
        timestamp updated_at
    }

    USERS {
        bigint id_user PK
        bigint id_hak_akses FK
        varchar username UK
        varchar password_hash
        varchar email UK
        varchar nama_lengkap
        boolean status_aktif
        json digital_signatures
        timestamp created_at
        timestamp updated_at
    }

    FAKULTAS {
        bigint id_fakultas PK
        varchar nama_fakultas
        varchar kode_fakultas
        timestamp created_at
        timestamp updated_at
    }

    PROGRAM_STUDI {
        bigint id_prodi PK
        varchar kode_prodi UK
        varchar nama_prodi
        bigint id_fakultas FK
        timestamp created_at
        timestamp updated_at
    }

    MAHASISWA {
        bigint id_mahasiswa PK
        bigint id_user FK
        varchar nim UK
        varchar tempat_lahir
        date tanggal_lahir
        varchar nama_orang_tua
        varchar nip_orang_tua
        varchar pangkat_orang_tua
        varchar instansi_orang_tua
        bigint id_prodi FK
        year angkatan
        decimal ipk_terakhir
        varchar status_mahasiswa
        timestamp created_at
        timestamp updated_at
    }

    JABATAN {
        bigint id_jabatan PK
        varchar nama_jabatan UK
        varchar kode_jabatan UK
        timestamp created_at
        timestamp updated_at
    }

    PEJABAT {
        bigint id_pejabat PK
        bigint id_user FK
        bigint id_jabatan FK
        varchar nip UK
        varchar tanda_tangan_digital_path
        boolean is_aktif_ttd
        timestamp created_at
        timestamp updated_at
    }

    JENIS_SURAT {
        bigint id_jenis_surat PK
        varchar nama_surat
        varchar template_path
        varchar pejabat_yg_menandatangani
        boolean perlu_validasi_staff
        boolean perlu_validasi_dekan
        timestamp created_at
        timestamp updated_at
    }

    PENGAJUAN_SURAT {
        bigint id_pengajuan PK
        bigint id_mahasiswa FK
        bigint id_jenis_surat FK
        bigint digital_signature_id FK
        datetime tgl_pengajuan
        varchar status_saat_ini
        text keterangan_mahasiswa
        varchar nomor_surat_resmi
        longblob file_surat_content
        varchar file_surat_name
        varchar file_surat_mime_type
        timestamp created_at
        timestamp updated_at
    }

    DETAIL_PENGAJUAN {
        bigint id_detail PK
        bigint id_pengajuan FK
        varchar kode_field_template
        varchar label_field
        text nilai_field
        datetime waktu_dibuat
        datetime waktu_diubah
        timestamp created_at
        timestamp updated_at
    }

    VALIDASI_STAFF {
        bigint id_validasi PK
        bigint id_pengajuan FK
        bigint id_user_staff FK
        datetime tgl_validasi
        varchar status_validasi
        text catatan_staff
        timestamp created_at
        timestamp updated_at
    }

    PERSETUJUAN_PEJABAT {
        bigint id_persetujuan PK
        bigint id_pengajuan FK
        bigint id_pejabat FK
        datetime tgl_persetujuan
        varchar status_persetujuan
        text alasan_penolakan
        timestamp created_at
        timestamp updated_at
    }

    LOG_STATUS_SURAT {
        bigint id_log_status PK
        bigint id_pengajuan FK
        datetime tgl_perubahan
        varchar status_lama
        varchar status_baru
        text keterangan
        bigint diubah_oleh_user FK
        timestamp created_at
        timestamp updated_at
    }

    ARSIP_SURAT {
        bigint id_arsip PK
        bigint id_pengajuan FK
        date tgl_arsip
        bigint arsiparis_user_id FK
        timestamp created_at
        timestamp updated_at
    }

    NOTIFIKASI {
        bigint id_notifikasi PK
        bigint id_user_penerima FK
        varchar role_penerima
        varchar judul
        text pesan
        varchar type
        varchar link
        datetime tgl_kirim
        boolean is_read
        timestamp created_at
        timestamp updated_at
    }

    LOG_AKTIVITAS {
        bigint id_log PK
        bigint id_user FK
        datetime waktu
        varchar tipe_aktivitas
        text deskripsi
        timestamp created_at
        timestamp updated_at
    }

    DIGITAL_SIGNATURES {
        bigint id PK
        bigint user_id FK
        varchar name
        enum type
        varchar path
        text qr_text
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }
```

## Catatan Revisi

- Tabel `jabatan` yang dobel di ERD lama dipisahkan menjadi `pejabat` dan `jabatan`.
- Atribut `username`, `password`, `email`, `nama_lengkap`, dan `status_aktif` dipindahkan ke `users`, bukan `notifikasi`.
- `log_aktivitas` direlasikan ke `users`, bukan langsung ke `pengajuan_surat`.
- Ditambahkan tabel `fakultas` karena `program_studi` sekarang punya `id_fakultas`.
- Ditambahkan tabel `digital_signatures` dan relasinya ke `users` serta `pengajuan_surat`.
- Kolom file final pada `pengajuan_surat` dirapikan sesuai migration terbaru: `file_surat_content`, `file_surat_name`, dan `file_surat_mime_type`.
- `jenis_surat` ditambahkan kolom `perlu_validasi_dekan`.
- `log_status_surat` ditambahkan `status_lama` dan `keterangan`.
