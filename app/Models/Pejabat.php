<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pejabat extends Model
{
    use HasFactory;

    protected $table = 'pejabat';
    protected $primaryKey = 'id_pejabat';
    
    protected $fillable = [
        'id_user',
        'id_jabatan',
        'nip',
        'tanda_tangan_digital_path',
        'is_aktif_ttd',
    ];

    protected $casts = [
        'is_aktif_ttd' => 'boolean',
    ];

    /**
     * Relationship: Pejabat ditugaskan sebagai User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relationship: Pejabat memegang Jabatan
     */
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    /**
     * Relationship: Pejabat memberikan banyak Persetujuan
     */
    public function persetujuan(): HasMany
    {
        return $this->hasMany(PersetujuanPejabat::class, 'id_pejabat', 'id_pejabat');
    }
}
