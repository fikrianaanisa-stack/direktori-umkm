<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        $wilayah = [
            // Kecamatan Baguala
            ['Baguala', 'Lateri', 'Kelurahan'],
            ['Baguala', 'Waiheru', 'Desa'],
            ['Baguala', 'Passo', 'Negeri'],
            ['Baguala', 'Halong', 'Negeri'],
            ['Baguala', 'Nania', 'Desa'],
            ['Baguala', 'Negeri Lama', 'Desa'],
            ['Baguala', 'Latta', 'Desa'],

            // Kecamatan Sirimau
            ['Sirimau', 'Batu Merah', 'Negeri'],
            ['Sirimau', 'Rijali', 'Kelurahan'],
            ['Sirimau', 'Honipopu', 'Kelurahan'],
            ['Sirimau', 'Hative Kecil', 'Negeri'],
            ['Sirimau', 'Waihoka', 'Kelurahan'],
            ['Sirimau', 'Amantelu', 'Kelurahan'],
            ['Sirimau', 'Karang Panjang', 'Kelurahan'],
            ['Sirimau', 'Batu Meja', 'Kelurahan'],
            ['Sirimau', 'Batu Gajah', 'Kelurahan'],
            ['Sirimau', 'Ahusen', 'Kelurahan'],
            ['Sirimau', 'Uritetu', 'Kelurahan'],
            ['Sirimau', 'Pandan Kasturi', 'Kelurahan'],
            ['Sirimau', 'Soya', 'Negeri'],
            ['Sirimau', 'Galala', 'Desa'],

            // Kecamatan Nusaniwe
            ['Nusaniwe', 'Wainitu', 'Kelurahan'],
            ['Nusaniwe', 'Mangga Dua', 'Kelurahan'],
            ['Nusaniwe', 'Silale', 'Kelurahan'],
            ['Nusaniwe', 'Urimessing', 'Kelurahan'],
            ['Nusaniwe', 'Benteng', 'Kelurahan'],
            ['Nusaniwe', 'Kudamati', 'Kelurahan'],
            ['Nusaniwe', 'Waihaong', 'Kelurahan'],
            ['Nusaniwe', 'Nusaniwe', 'Kelurahan'],
            ['Nusaniwe', 'Amahusu', 'Negeri'],
            ['Nusaniwe', 'Nusaniwe', 'Negeri'],
            ['Nusaniwe', 'Urimessing', 'Negeri'],
            ['Nusaniwe', 'Latuhalat', 'Negeri'],
            ['Nusaniwe', 'Negeri Seilale', 'Negeri'],

            // Kecamatan Leitimur Selatan
            ['Leitimur Selatan', 'Rutong', 'Negeri'],
            ['Leitimur Selatan', 'Hutumuri', 'Negeri'],
            ['Leitimur Selatan', 'Ema', 'Negeri'],
            ['Leitimur Selatan', 'Hukurila', 'Negeri'],
            ['Leitimur Selatan', 'Hatalai', 'Negeri'],
            ['Leitimur Selatan', 'Naku', 'Negeri'],
            ['Leitimur Selatan', 'Kilang', 'Negeri'],
            ['Leitimur Selatan', 'Leahari', 'Negeri'],

            // Kecamatan Teluk Ambon
            ['Teluk Ambon', 'Tihu', 'Kelurahan'],
            ['Teluk Ambon', 'Laha', 'Negeri'],
            ['Teluk Ambon', 'Tawiri', 'Negeri'],
            ['Teluk Ambon', 'Hative Besar', 'Negeri'],
            ['Teluk Ambon', 'Rumah Tiga', 'Negeri'],
            ['Teluk Ambon', 'Wayame', 'Desa'],
            ['Teluk Ambon', 'Poka', 'Desa'],
            ['Teluk Ambon', 'Hunuth/Durian Patah', 'Desa'],
        ];

        foreach ($wilayah as [$kecamatan, $nama, $jenis]) {
            $query = DB::table('wilayah')
                ->where('kecamatan', $kecamatan)
                ->where('kelurahan_desa', $nama);

            $existing = (clone $query)
                ->where('jenis_wilayah', $jenis)
                ->first();

            if ($existing) {
                continue;
            }

            // Gunakan kembali ID data lama yang belum memiliki jenis.
            $legacy = (clone $query)
                ->whereNull('jenis_wilayah')
                ->first();

            if ($legacy) {
                DB::table('wilayah')
                    ->where('id', $legacy->id)
                    ->update([
                        'jenis_wilayah' => $jenis,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('wilayah')->insert([
                    'kecamatan' => $kecamatan,
                    'kelurahan_desa' => $nama,
                    'jenis_wilayah' => $jenis,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}