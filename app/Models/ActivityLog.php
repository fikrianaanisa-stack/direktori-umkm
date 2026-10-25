<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    // Riwayat hanya menggunakan created_at
    public $timestamps = false;

    // Kolom yang dapat diisi melalui model
    protected $fillable = [
        'user_id',
        'nama_pengguna',
        'module',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'old_values',
        'new_values',
    ];

    // Konversi data JSON menjadi array
    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    // Relasi aktivitas dengan pengguna
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
