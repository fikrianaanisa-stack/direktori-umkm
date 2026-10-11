<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriUmkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori_umkm')->insert([
            [
                'nama_kategori' => 'Makanan & Minuman',
                'deskripsi' => 'Usaha yang bergerak di bidang makanan dan minuman.',
            ],
            [
                'nama_kategori' => 'Fashion',
                'deskripsi' => 'Usaha yang bergerak di bidang pakaian dan produk fashion.',
            ],
            [
                'nama_kategori' => 'Kerajinan & Souvenir',
                'deskripsi' => 'Usaha yang menghasilkan atau menjual produk kerajinan dan souvenir.',
            ],
            [
                'nama_kategori' => 'Jasa',
                'deskripsi' => 'Usaha yang menyediakan berbagai layanan atau jasa.',
            ],
            [
                'nama_kategori' => 'Perdagangan',
                'deskripsi' => 'Usaha yang bergerak dalam kegiatan penjualan berbagai jenis barang kepada konsumen.',
            ],
        ]);
    }
}
