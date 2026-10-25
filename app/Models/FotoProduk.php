<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoProduk extends Model
{
    protected $table = 'foto_produk';

    protected $fillable = [
        'produk_id',
        'foto',
        'urutan',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
