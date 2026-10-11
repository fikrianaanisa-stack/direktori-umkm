<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
