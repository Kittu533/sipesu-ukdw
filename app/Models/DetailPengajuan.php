<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengajuan extends Model
{
    use HasFactory;

    protected $table = 'detail_pengajuan';
    protected $primaryKey = 'id_detail';
    
    protected $fillable = [
        'id_pengajuan',
        'kode_field_template',
        'label_field',
        'nilai_field',
        'waktu_dibuat',
        'waktu_diubah',
    ];

    protected $casts = [
        'waktu_dibuat' => 'datetime',
        'waktu_diubah' => 'datetime',
    ];

    /**
     * Relationship: Detail Pengajuan milik Pengajuan Surat
     */
    public function pengajuanSurat(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class, 'id_pengajuan', 'id_pengajuan');
    }
}
