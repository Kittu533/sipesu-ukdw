<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    
    protected $fillable = [
        'id_user',
        'nim',
        'id_prodi',
        'angkatan',
        'ipk_terakhir',
        'status_mahasiswa',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_orang_tua',
        'nip_orang_tua',
        'pangkat_orang_tua',
        'instansi_orang_tua',
    ];

    protected $casts = [
        'ipk_terakhir' => 'decimal:2',
        'tanggal_lahir' => 'date',
    ];

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_TIDAK_AKTIF = 'tidak_aktif';
    public const STATUS_LULUS = 'lulus';
    public const STATUS_UNDUR_DIRI = 'undur_diri';
    public const STATUS_CUTI = 'cuti';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_AKTIF => 'Aktif',
            self::STATUS_TIDAK_AKTIF => 'Tidak Aktif',
            self::STATUS_LULUS => 'Lulus',
            self::STATUS_UNDUR_DIRI => 'Undur Diri',
            self::STATUS_CUTI => 'Cuti',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status_mahasiswa] ?? $this->status_mahasiswa;
    }

    /**
     * Relationship: Mahasiswa terdaftar sebagai User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relationship: Mahasiswa berada di Program Studi
     */
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    /**
     * Relationship: Mahasiswa mengajukan banyak Pengajuan Surat
     */
    public function pengajuanSurat(): HasMany
    {
        return $this->hasMany(PengajuanSurat::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
