<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipSurat extends Model
{
    use HasFactory;

    protected $table = 'arsip_surat';
    protected $primaryKey = 'id_arsip';
    
    protected $fillable = [
        'id_pengajuan',
        'tgl_arsip',
        'arsiparis_user_id',
    ];

    protected $casts = [
        'tgl_arsip' => 'date',
    ];

    /**
     * Relationship: Arsip Surat mengarsipkan Pengajuan Surat
     */
    public function pengajuanSurat(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class, 'id_pengajuan', 'id_pengajuan');
    }

    /**
     * Relationship: Arsip Surat diarsipkan oleh User (Admin/Dekan)
     */
    public function userPengarsip(): BelongsTo
    {
        return $this->belongsTo(User::class, 'arsiparis_user_id', 'id_user');
    }
}
