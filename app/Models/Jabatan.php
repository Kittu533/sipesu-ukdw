<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';
    protected $primaryKey = 'id_jabatan';
    
    protected $fillable = [
        'nama_jabatan',
        'kode_jabatan',
    ];

    /**
     * Relationship: Jabatan memegang banyak Pejabat
     */
    public function pejabat(): HasMany
    {
        return $this->hasMany(Pejabat::class, 'id_jabatan', 'id_jabatan');
    }
}
