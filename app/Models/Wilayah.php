<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';

    protected $fillable = [
        'kecamatan',
        'kelurahan_desa',
        'jenis_wilayah',
    ];

    public function umkm()
    {
        return $this->hasMany(Umkm::class, 'wilayah_id');
    }
}
