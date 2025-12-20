<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HakAkses extends Model
{
    use HasFactory;

    protected $table = 'hak_akses';
    protected $primaryKey = 'id_hak_akses';
    
    protected $fillable = [
        'nama_hak_akses',
    ];

    /**
     * Relationship: Hak Akses memiliki banyak User
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_hak_akses', 'id_hak_akses');
    }
}
