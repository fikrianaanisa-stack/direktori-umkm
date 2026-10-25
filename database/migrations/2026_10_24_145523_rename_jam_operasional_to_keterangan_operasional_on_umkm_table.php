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
        Schema::table('umkm', function (Blueprint $table) {
            $table->renameColumn('jam_operasional', 'keterangan_operasional');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('umkm', function (Blueprint $table) {
            $table->renameColumn('keterangan_operasional', 'jam_operasional');
        });
    }
};