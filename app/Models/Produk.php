<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'umkm_id',
        'kategori_produk_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'foto',
    ];

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    public function kategoriProduk(): BelongsTo
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_produk_id');
    }

    // Satu produk memiliki banyak pilihan harga
    public function hargaProduk(): HasMany
    {
        return $this->hasMany(HargaProduk::class, 'produk_id');
    }
}