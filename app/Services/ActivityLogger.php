<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Mencatat aktivitas tambah, ubah, dan hapus data.
     */
    public static function log(
        string $module,
        string $action,
        ?Model $subject = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): void {
        $user = Auth::user();

        // Hanya mencatat aktivitas admin yang login
        if (!$user || $user->role !== 'admin') {
            return;
        }

        // Kolom sensitif tidak disimpan dalam riwayat
        $sensitiveFields = [
            'password',
            'remember_token',
            'token',
            'secret',
        ];

        $oldValues = $oldValues === null
            ? null
            : array_diff_key($oldValues, array_flip($sensitiveFields));

        $newValues = $newValues === null
            ? null
            : array_diff_key($newValues, array_flip($sensitiveFields));

        ActivityLog::create([
            'user_id' => $user->id,
            'nama_pengguna' => $user->name,
            'module' => $module,
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->getKey(),
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}
