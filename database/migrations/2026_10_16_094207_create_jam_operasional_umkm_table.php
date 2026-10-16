<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Membuat tabel jam operasional UMKM
    public function up(): void
    {
        Schema::create('jam_operasional_umkm', function (Blueprint $table) {
            $table->id();

            $table->foreignId('umkm_id')
                ->constrained('umkm')
                ->cascadeOnDelete();

            $table->string('hari', 10);
            $table->boolean('is_buka')->default(false);
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();

            $table->timestamps();

            // Mencegah jadwal ganda untuk hari yang sama
            $table->unique(['umkm_id', 'hari']);
        });
    }

    // Menghapus tabel jika migration dibatalkan
    public function down(): void
    {
        Schema::dropIfExists('jam_operasional_umkm');
    }
};