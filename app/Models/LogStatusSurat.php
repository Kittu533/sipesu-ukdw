<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogStatusSurat extends Model
{
    use HasFactory;

    protected $table = 'log_status_surat';
    protected $primaryKey = 'id_log_status';
    
    protected $fillable = [
        'id_pengajuan',
        'tgl_perubahan',
        'status_lama',
        'status_baru',
        'diubah_oleh_user',
        'keterangan',
    ];

    protected $casts = [
        'tgl_perubahan' => 'datetime',
    ];

    /**
     * Relationship: Log Status Surat merekam Pengajuan Surat
     */
    public function pengajuanSurat(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class, 'id_pengajuan', 'id_pengajuan');
    }

    /**
     * Relationship: Log Status Surat diubah oleh User
     */
    public function userPengubah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diubah_oleh_user', 'id_user');
    }
}
