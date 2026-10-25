<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel ulasan pengunjung.
     */
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();

            // Pengunjung yang memberikan ulasan
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Objek ulasan: UMKM atau produk
            $table->foreignId('umkm_id')
                ->nullable()
                ->constrained('umkm')
                ->cascadeOnDelete();

            $table->foreignId('produk_id')
                ->nullable()
                ->constrained('produk')
                ->cascadeOnDelete();

            // Rating dan komentar pengunjung
            $table->unsignedTinyInteger('rating');
            $table->text('komentar');

            // Status moderasi ulasan
            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            // Admin yang memeriksa ulasan
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            // Alasan jika ulasan ditolak
            $table->text('alasan_penolakan')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Menghapus tabel ulasan.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
