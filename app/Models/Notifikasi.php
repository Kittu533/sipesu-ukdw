<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';
    
    protected $fillable = [
        'id_user_penerima',
        'role_penerima',
        'judul',
        'pesan',
        'type',
        'link',
        'tgl_kirim',
        'is_read',
    ];

    protected $casts = [
        'tgl_kirim' => 'datetime',
        'is_read' => 'boolean',
    ];

    public const TYPE_INFO = 'info';
    public const TYPE_SUCCESS = 'success';
    public const TYPE_WARNING = 'warning';
    public const TYPE_ERROR = 'error';

    /**
     * Relationship: Notifikasi diterima oleh User
     */
    public function userPenerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_penerima', 'id_user');
    }

    /**
     * Scope: untuk user tertentu atau role tertentu
     */
    public function scopeForUserOrRole($query, $userId, $roleId)
    {
        return $query->where(function($q) use ($userId, $roleId) {
            $q->where('id_user_penerima', $userId)
              ->orWhere('role_penerima', $this->getRoleName($roleId));
        });
    }

    /**
     * Get role name from role ID
     */
    public static function getRoleName($roleId)
    {
        return match($roleId) {
            1 => 'mahasiswa',
            2 => 'admin',
            3 => 'dekan',
            4 => 'pejabat',
            default => null,
        };
    }

    /**
     * Scope: belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
