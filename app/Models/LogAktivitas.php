<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';
    protected $primaryKey = 'id_log';
    
    protected $fillable = [
        'id_user',
        'waktu',
        'tipe_aktivitas',
        'deskripsi',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    /**
     * Relationship: Log Aktivitas dimiliki oleh User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
