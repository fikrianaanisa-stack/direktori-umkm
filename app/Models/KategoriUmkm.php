<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriUmkm extends Model
{
    protected $table = 'kategori_umkm';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    // Satu kategori memiliki banyak UMKM
    public function umkm(): HasMany
    {
        return $this->hasMany(Umkm::class, 'kategori_umkm_id');
    }
}
