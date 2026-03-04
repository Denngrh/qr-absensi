<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Sample Mahasiswa
        \App\Models\Mahasiswa::create([
            'nim' => '12345678',
            'nama' => 'John Doe',
            'jurusan' => 'Teknik Informatika',
            'semester' => '5',
            'email' => 'john@example.com',
            'no_hp' => '08123456789',
        ]);

        \App\Models\Mahasiswa::create([
            'nim' => '87654321',
            'nama' => 'Jane Smith',
            'jurusan' => 'Sistem Informasi',
            'semester' => '3',
            'email' => 'jane@example.com',
            'no_hp' => '08198765432',
        ]);

        // Sample Panitia
        \App\Models\Panitia::create([
            'nip' => '198001012020011001',
            'nama' => 'Dr. Ahmad Wijaya',
            'jabatan' => 'Ketua Panitia',
            'email' => 'ahmad@example.com',
            'no_hp' => '08111222333',
        ]);

        \App\Models\Panitia::create([
            'nip' => '198502022020022002',
            'nama' => 'Sarah Kusuma',
            'jabatan' => 'Sekretaris',
            'email' => 'sarah@example.com',
            'no_hp' => '08222333444',
        ]);
    }
}

