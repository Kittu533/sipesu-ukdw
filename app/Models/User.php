<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id_user';
    public $remember_token = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_hak_akses',
        'username',
        'password_hash',
        'email',
        'nama_lengkap',
        'status_aktif',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    /**
     * Relationship: User memiliki Hak Akses
     */
    public function hakAkses(): BelongsTo
    {
        return $this->belongsTo(HakAkses::class, 'id_hak_akses', 'id_hak_akses');
    }

    /**
     * Relationship: User memiliki satu Mahasiswa
     */
    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class, 'id_user', 'id_user');
    }

    /**
     * Relationship: User memiliki satu Pejabat
     */
    public function pejabat(): HasOne
    {
        return $this->hasOne(Pejabat::class, 'id_user', 'id_user');
    }

    /**
     * Relationship: User memiliki banyak Log Aktivitas
     */
    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class, 'id_user', 'id_user');
    }

    /**
     * Relationship: User memiliki banyak Validasi Staff
     */
    public function validasiStaff(): HasMany
    {
        return $this->hasMany(ValidasiStaff::class, 'id_user_staff', 'id_user');
    }

    /**
     * Relationship: User memiliki banyak Log Status Surat
     */
    public function logStatusSurat(): HasMany
    {
        return $this->hasMany(LogStatusSurat::class, 'diubah_oleh_user', 'id_user');
    }

    /**
     * Relationship: User memiliki banyak Arsip Surat
     */
    public function arsipSurat(): HasMany
    {
        return $this->hasMany(ArsipSurat::class, 'arsiparis_user_id', 'id_user');
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get the name of the "remember me" token column.
     *
     * @return string|null
     */
    public function getRememberTokenName()
    {
        return null; // Disable remember token
    }

    /**
     * Relationship: User menerima banyak Notifikasi
     */
    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_user_penerima', 'id_user');
    }
}
