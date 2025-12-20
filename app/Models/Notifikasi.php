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
        'judul',
        'pesan',
        'tgl_kirim',
        'is_read',
    ];

    protected $casts = [
        'tgl_kirim' => 'datetime',
        'is_read' => 'boolean',
    ];

    /**
     * Relationship: Notifikasi diterima oleh User
     */
    public function userPenerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_penerima', 'id_user');
    }
}
