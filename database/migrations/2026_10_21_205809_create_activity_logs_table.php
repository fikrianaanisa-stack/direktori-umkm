<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Identitas admin yang melakukan aktivitas
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('nama_pengguna', 255);

            // Informasi aktivitas
            $table->string('module', 50);
            $table->string('action', 30);
            $table->string('subject_type', 100)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->text('description')->nullable();

            // Data sebelum dan sesudah perubahan
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Waktu aktivitas
            $table->timestamp('created_at')->useCurrent();

            // Index untuk mempercepat pencarian riwayat
            $table->index(['module', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
