<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamOperasionalUmkm extends Model
{
    // Nama tabel database
    protected $table = 'jam_operasional_umkm';

    // Kolom yang boleh diisi
    protected $fillable = [
        'umkm_id',
        'hari',
        'is_buka',
        'jam_buka',
        'jam_tutup',
    ];

    // Mengubah status buka menjadi boolean
    protected $casts = [
        'is_buka' => 'boolean',
    ];

    // Relasi jadwal dengan UMKM
    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }
}