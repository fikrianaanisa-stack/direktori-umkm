<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harga_produk', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel produk
            $table->foreignId('produk_id')
                ->constrained('produk')
                ->cascadeOnDelete();

            // Harga tetap atau rentang harga
            $table->decimal('harga_min', 12, 2)->nullable();
            $table->decimal('harga_max', 12, 2)->nullable();

            // Jumlah dan satuan produk
            $table->decimal('jumlah_satuan', 10, 2)->default(1);
            $table->string('satuan', 50)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harga_produk');
    }
};