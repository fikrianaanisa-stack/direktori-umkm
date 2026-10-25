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

    // Semua ulasan yang diberikan kepada produk
    public function ulasan(): HasMany
    {
        return $this->hasMany(Ulasan::class, 'produk_id');
    }

    // Ulasan produk yang sudah disetujui admin
    public function ulasanDisetujui(): HasMany
    {
        return $this->hasMany(Ulasan::class, 'produk_id')
            ->where('status', 'approved');
    }

    // Satu produk memiliki banyak foto
    public function fotoProduk(): HasMany
    {
        return $this->hasMany(FotoProduk::class, 'produk_id')
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc');
    }
}
