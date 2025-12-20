<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidasiStaff extends Model
{
    use HasFactory;

    protected $table = 'validasi_staff';
    protected $primaryKey = 'id_validasi';
    
    protected $fillable = [
        'id_pengajuan',
        'id_user_staff',
        'tgl_validasi',
        'status_validasi',
        'catatan_staff',
    ];

    protected $casts = [
        'tgl_validasi' => 'datetime',
    ];

    /**
     * Relationship: Validasi Staff untuk Pengajuan Surat
     */
    public function pengajuanSurat(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class, 'id_pengajuan', 'id_pengajuan');
    }

    /**
     * Relationship: Validasi Staff dilakukan oleh User (Staff)
     */
    public function userStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_staff', 'id_user');
    }
}
