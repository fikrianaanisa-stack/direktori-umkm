<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UmkmSeeder extends Seeder
{
    public function run(): void
    {
        $umkm = [
            [
                'kategori_umkm_id' => 1,
                'wilayah_id' => 1,
                'nama_umkm' => 'Madu Hutan Romadu',
                'deskripsi' => 'Usaha penjualan madu hutan alami.',
                'alamat' => 'BTN Bukit Lateri Indah, Blok BIII No. 12 A1, Kelurahan Lateri, Kecamatan Baguala, Kota Ambon, Maluku.',
                'telepon' => '085243582306',
                'whatsapp' => '085243582306',
                'instagram' => 'https://www.instagram.com/romaduku/',
                'facebook' => 'ROMADU',
                'tiktok' => null,
                'website' => null,
                'jam_operasional' => null,
                'tahun_berdiri' => 2016,
                'logo' => null,
                'foto_usaha' => null,
            ],
            [
                'kategori_umkm_id' => 3,
                'wilayah_id' => 1,
                'nama_umkm' => 'Bastell Ambon',
                'deskripsi' => 'Usaha kerajinan dan souvenir khas Maluku.',
                'alamat' => 'Jl. Wolter Monginsidi, Kelurahan Lateri, Kecamatan Baguala, Kota Ambon, Maluku.',
                'telepon' => '085244882066',
                'whatsapp' => '085244882066',
                'instagram' => 'https://www.instagram.com/bastellambon/',
                'facebook' => 'Bastellambon',
                'tiktok' => 'bastellambon',
                'website' => null,
                'jam_operasional' => 'Setiap hari 08.00-19.00 WIT',
                'tahun_berdiri' => 2018,
                'logo' => null,
                'foto_usaha' => null,
            ],
            [
                'kategori_umkm_id' => 2,
                'wilayah_id' => 2,
                'nama_umkm' => 'Mamimu Gallery Hijab',
                'deskripsi' => 'Usaha fashion yang menjual hijab serta pakaian wanita dan anak-anak secara eceran maupun grosir.',
                'alamat' => 'BTN Puskopad, Lorong Mebel, Waiheru, Kecamatan Baguala, Kota Ambon, Maluku.',
                'telepon' => '085233041397',
                'whatsapp' => '082264257123',
                'instagram' => null,
                'facebook' => 'Mamimu Galleryhijab Ambonwaiheru',
                'tiktok' => null,
                'website' => null,
                'jam_operasional' => '08.00-16.00 WIT',
                'tahun_berdiri' => 2024,
                'logo' => null,
                'foto_usaha' => null,
            ],
            [
                'kategori_umkm_id' => 4,
                'wilayah_id' => 2,
                'nama_umkm' => 'Sahabat Laundry',
                'deskripsi' => 'Jasa laundry pakaian kiloan dan satuan, perlengkapan tidur, sepatu, helm, serta laundry express satu hari.',
                'alamat' => 'Lorong Mayat, Waiheru Dalam, Desa Waiheru, Kecamatan Baguala, Kota Ambon, Maluku.',
                'telepon' => '082198490102',
                'whatsapp' => '082198490102',
                'instagram' => null,
                'facebook' => null,
                'tiktok' => null,
                'website' => null,
                'jam_operasional' => 'Buka setiap hari',
                'tahun_berdiri' => 2022,
                'logo' => null,
                'foto_usaha' => null,
            ],
        ];

        foreach ($umkm as $data) {
            $nama = $data['nama_umkm'];

            DB::table('umkm')->updateOrInsert(
                ['nama_umkm' => $nama],
                array_merge($data, [
                    'updated_at' => now(),
                ])
            );
        }
    }
}
