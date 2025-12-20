<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surat';
    protected $primaryKey = 'id_pengajuan';
    
    protected $fillable = [
        'id_mahasiswa',
        'id_jenis_surat',
        'tgl_pengajuan',
        'status_saat_ini',
        'keterangan_mahasiswa',
        'nomor_surat_resmi',
        'file_surat_content',
        'file_surat_name',
        'file_surat_mime_type',
        'digital_signature_id',
    ];

    protected $casts = [
        'tgl_pengajuan' => 'datetime',
    ];

    /**
     * Relationship: Pengajuan Surat diajukan oleh Mahasiswa
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    /**
     * Relationship: Pengajuan Surat adalah jenis tertentu
     */
    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat', 'id_jenis_surat');
    }

    /**
     * Relationship: Pengajuan Surat memiliki banyak Detail Pengajuan
     */
    public function detailPengajuan(): HasMany
    {
        return $this->hasMany(DetailPengajuan::class, 'id_pengajuan', 'id_pengajuan');
    }

    /**
     * Relationship: Pengajuan Surat melalui Validasi Staff
     */
    public function validasiStaff(): HasMany
    {
        return $this->hasMany(ValidasiStaff::class, 'id_pengajuan', 'id_pengajuan');
    }

    /**
     * Relationship: Pengajuan Surat memerlukan Persetujuan Pejabat
     */
    public function persetujuanPejabat(): HasMany
    {
        return $this->hasMany(PersetujuanPejabat::class, 'id_pengajuan', 'id_pengajuan');
    }

    /**
     * Relationship: Pengajuan Surat merekam Log Status Surat
     */
    public function logStatusSurat(): HasMany
    {
        return $this->hasMany(LogStatusSurat::class, 'id_pengajuan', 'id_pengajuan');
    }

    /**
     * Relationship: Pengajuan Surat mengarsipkan Arsip Surat
     */
    public function arsipSurat(): HasMany
    {
        return $this->hasMany(ArsipSurat::class, 'id_pengajuan', 'id_pengajuan');
    }

    /**
     * Relationship: Pengajuan Surat menggunakan Digital Signature
     */
    public function digitalSignature(): BelongsTo
    {
        return $this->belongsTo(DigitalSignature::class, 'digital_signature_id', 'id');
    }
}
