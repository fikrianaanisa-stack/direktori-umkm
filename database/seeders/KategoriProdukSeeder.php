<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriProdukSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Makanan dan Minuman', 'deskripsi' => 'Makanan, kue, roti, kopi, madu, dan minuman lainnya.'],
            ['nama_kategori' => 'Bahan Pangan', 'deskripsi' => 'Beras, sagu, tepung, gula, dan bahan pangan lainnya.'],
            ['nama_kategori' => 'Sayuran dan Buah-buahan', 'deskripsi' => 'Sayuran segar, buah-buahan, dan hasil pertanian.'],
            ['nama_kategori' => 'Pakaian dan Fashion', 'deskripsi' => 'Pakaian, hijab, tas, dan aksesori fashion.'],
            ['nama_kategori' => 'Kerajinan dan Souvenir', 'deskripsi' => 'Kerajinan tangan, kain tenun, dan cendera mata.'],
            ['nama_kategori' => 'Kecantikan dan Perawatan', 'deskripsi' => 'Kosmetik, sabun, sampo, dan produk perawatan.'],
            ['nama_kategori' => 'Kebutuhan Rumah Tangga', 'deskripsi' => 'Peralatan dapur dan perlengkapan rumah tangga.'],
            ['nama_kategori' => 'Elektronik dan Listrik', 'deskripsi' => 'Lampu, kabel, dan perlengkapan elektronik.'],
            ['nama_kategori' => 'Bahan Bangunan dan Perkakas', 'deskripsi' => 'Semen, cat, paku, dan berbagai perkakas.'],
            ['nama_kategori' => 'Alat Tulis dan Perlengkapan Kantor', 'deskripsi' => 'Buku, pulpen, kertas, dan perlengkapan kantor.'],
            ['nama_kategori' => 'Jasa dan Layanan', 'deskripsi' => 'Laundry, potong rambut, dan layanan jasa lainnya.'],
            ['nama_kategori' => 'Lainnya', 'deskripsi' => 'Produk atau layanan yang belum termasuk kategori lain.'],
        ];

        foreach ($kategori as $item) {
            DB::table('kategori_produk')->updateOrInsert(
                ['nama_kategori' => $item['nama_kategori']],
                [
                    'deskripsi' => $item['deskripsi'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}