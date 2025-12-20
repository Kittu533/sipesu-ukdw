<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersetujuanPejabat extends Model
{
    use HasFactory;

    protected $table = 'persetujuan_pejabat';
    protected $primaryKey = 'id_persetujuan';
    
    protected $fillable = [
        'id_pengajuan',
        'id_pejabat',
        'tgl_persetujuan',
        'status_persetujuan',
        'alasan_penolakan',
    ];

    protected $casts = [
        'tgl_persetujuan' => 'datetime',
    ];

    /**
     * Relationship: Persetujuan Pejabat untuk Pengajuan Surat
     */
    public function pengajuanSurat(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class, 'id_pengajuan', 'id_pengajuan');
    }

    /**
     * Relationship: Persetujuan Pejabat disetujui oleh Pejabat
     */
    public function pejabat(): BelongsTo
    {
        return $this->belongsTo(Pejabat::class, 'id_pejabat', 'id_pejabat');
    }
}
