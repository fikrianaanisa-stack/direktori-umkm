<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Umkm extends Model
{
    protected $table = 'umkm';

    protected $fillable = [
        'kategori_umkm_id',
        'wilayah_id',
        'nama_umkm',
        'deskripsi',
        'alamat',
        'latitude',
        'longitude',
        'google_maps_url',
        'telepon',
        'whatsapp',
        'instagram',
        'facebook',
        'tiktok',
        'website',
        'jam_operasional',
        'tahun_berdiri',
        'logo',
        'foto_usaha',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'tahun_berdiri' => 'integer',
    ];

    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class, 'umkm_id');
    }

    // Relasi UMKM dengan jadwal operasional per hari
    public function jamOperasionalHarian()
    {
        return $this->hasMany(JamOperasionalUmkm::class, 'umkm_id');
    }
}
