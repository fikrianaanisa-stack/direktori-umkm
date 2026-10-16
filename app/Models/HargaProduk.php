<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HargaProduk extends Model
{
    protected $table = 'harga_produk';

    protected $fillable = [
        'produk_id',
        'harga_min',
        'harga_max',
        'jumlah_satuan',
        'satuan',
    ];

    protected $casts = [
        'harga_min' => 'decimal:2',
        'harga_max' => 'decimal:2',
        'jumlah_satuan' => 'decimal:2',
    ];

    // Relasi harga dengan produk
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}