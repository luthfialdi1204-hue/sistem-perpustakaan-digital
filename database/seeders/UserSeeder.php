<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['nip' => 198501152010011001],
            [
                'nama_pengguna' => 'Admin Perpustakaan',
                'email' => 'admin@perpustakaan.test',
                'nim' => 0,
                'role_user' => User::ROLE_ADMIN,
                'kata_sandi' => 'admin123',
            ]
        );

        User::updateOrCreate(
            ['nim' => 3312501077],
            [
                'nama_pengguna' => 'Mahasiswa Demo',
                'email' => 'mahasiswa@perpustakaan.test',
                'nip' => 0,
                'role_user' => User::ROLE_MAHASISWA,
                'kata_sandi' => '3312501077',
            ]
        );
    }
}
