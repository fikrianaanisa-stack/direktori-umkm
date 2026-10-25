<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ulasan extends Model
{
    // Nama tabel di database
    protected $table = 'ulasan';

    // Kolom yang boleh diisi melalui model
    protected $fillable = [
        'user_id',
        'umkm_id',
        'produk_id',
        'rating',
        'komentar',
        'status',
        'reviewed_by',
        'reviewed_at',
        'alasan_penolakan',
    ];

    // Tipe data
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'reviewed_at' => 'datetime',
        ];
    }

    // Pengunjung yang menulis ulasan
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // UMKM yang mendapatkan ulasan
    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    // Produk yang mendapatkan ulasan
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Admin yang memoderasi ulasan
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
