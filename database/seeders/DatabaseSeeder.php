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
        // Sample Users for each role
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Guru Mengajar',
                'email' => 'guru@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'guru_mengajar',
            ],
            [
                'name' => 'Wali Kelas',
                'email' => 'walikelas@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'wali_kelas',
            ],
            [
                'name' => 'Guru Piket',
                'email' => 'gurupiket@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'guru_piket',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
