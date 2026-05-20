<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['nip' => '198501152010011001'],
            [
                'name' => 'Admin Perpustakaan',
                'email' => 'admin@perpustakaan.test',
                'nim' => null,
                'role' => 'admin',
                'password' => 'admin123',
            ]
        );

        User::updateOrCreate(
            ['nim' => '3312501077'],
            [
                'name' => 'Mahasiswa Demo',
                'email' => 'mahasiswa@perpustakaan.test',
                'nip' => null,
                'role' => 'mahasiswa',
                'password' => '3312501077',
            ]
        );
    }
}
