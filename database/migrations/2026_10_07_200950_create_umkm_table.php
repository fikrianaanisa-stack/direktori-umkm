<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('umkm', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('kategori_umkm_id')
                ->constrained('kategori_umkm');

            $table->foreignId('wilayah_id')
                ->constrained('wilayah');

            // Data utama UMKM
            $table->string('nama_umkm', 150);
            $table->text('deskripsi')->nullable();
            $table->text('alamat');

            // Kontak
            $table->string('telepon', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();

            // Media sosial
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('website')->nullable();

            // Informasi usaha
            $table->string('jam_operasional', 100)->nullable();
            $table->year('tahun_berdiri')->nullable();

            // Gambar
            $table->string('logo')->nullable();
            $table->string('foto_usaha')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkm');
    }
};
