<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Menambahkan kolom pada tabel kategori_produk
    public function up(): void
    {
        Schema::table('kategori_produk', function (Blueprint $table) {
            $table->string('nama_kategori', 100);
            $table->text('deskripsi')->nullable();
        });
    }

    // Membatalkan penambahan kolom
    public function down(): void
    {
        Schema::table('kategori_produk', function (Blueprint $table) {
            $table->dropColumn(['nama_kategori', 'deskripsi']);
        });
    }
};