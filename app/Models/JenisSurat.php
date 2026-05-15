<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    use HasFactory;

    public const AKTIF_KULIAH = 'Surat Keterangan Aktif Kuliah';
    public const ALUMNI = 'Surat Keterangan Alumni';
    public const PENGUNDURAN_DIRI = 'Surat Keterangan Pengunduran Diri';
    public const LULUS = 'Surat Keterangan Lulus (Statement Letter)';
    public const CUTI_AKADEMIK = 'Surat Cuti Akademik';

    protected $table = 'jenis_surat';
    protected $primaryKey = 'id_jenis_surat';
    
    protected $fillable = [
        'nama_surat',
        'template_path',
        'pejabat_yg_menandatangani',
        'perlu_validasi_staff',
        'perlu_validasi_dekan',
    ];

    protected $casts = [
        'perlu_validasi_staff' => 'boolean',
        'perlu_validasi_dekan' => 'boolean',
    ];

    /**
     * Relationship: Jenis Surat mendefinisikan banyak Pengajuan Surat
     */
    public function pengajuanSurat(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class, 'id_jenis_surat', 'id_jenis_surat');
    }
    /**
     * Accessor untuk kompatibilitas nama kolom.
     * Mengembalikan 'nama_surat' jika dipanggil sebagai 'nama_jenis_surat'.
     */
    public function getNamaJenisSuratAttribute()
    {
        return $this->attributes['nama_surat'];
    }
}
