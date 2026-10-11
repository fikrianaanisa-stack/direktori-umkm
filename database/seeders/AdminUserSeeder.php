<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        
        $admins = [
            [
                'name' => 'Fikriana Anisa',
                'email' => 'fikrianaanisa@gmail.com',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Aeni Salsabila',
                'email' => 'farisprayoga61@gmail.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Ananta Rachim Algoro',
                'email' => 'algororachim@gmail.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Erna Amelia',
                'email' => 'ernaamelia157@gmail.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Naufal Arif Rahmatulloh',
                'email' => 'arifnaufal0899@gmail.com',
                'role' => 'admin',
            ],
        ];
        
        foreach ($admins as $admin) {
            $email = $admin['email']
                ?? Str::slug($admin['name'], '.') . '@example.com';
            // Jangan membuat akun ganda
            if (User::where('email', $email)->exists()) {
                continue;
            }

            // Membuat password awal secara acak
            $password = Str::random(20);

            // Membuat akun pengguna
            User::create([
                'name' => $admin['name'],
                'email' => $email,
                'role' => $admin['role'],
                'password' => $password,
            ]);

            // Menyimpan informasi login sementara
            $path = storage_path('app/private/admin-passwords.txt');

            File::ensureDirectoryExists(dirname($path));

            File::append(
                $path,
                "Nama: {$admin['name']}\n" .
                "Email: {$email}\n" .
                "Password: {$password}\n\n"
            );
        }

    }
}
