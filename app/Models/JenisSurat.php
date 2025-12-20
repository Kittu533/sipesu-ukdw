<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    use HasFactory;

    protected $table = 'jenis_surat';
    protected $primaryKey = 'id_jenis_surat';
    
    protected $fillable = [
        'nama_surat',
        'template_path',
        'pejabat_yg_menandatangani',
        'perlu_validasi_staff',
    ];

    protected $casts = [
        'perlu_validasi_staff' => 'boolean',
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
