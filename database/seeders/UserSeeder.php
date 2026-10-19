<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@kreagenerasidigital.co.id'],
            [
                'name' => 'Admin KREA',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Staff
        $staffs = [
            ['name' => 'Budi Santoso', 'email' => 'budi@kreagenerasidigital.co.id'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@kreagenerasidigital.co.id'],
        ];

        foreach ($staffs as $staff) {
            User::updateOrCreate(
                ['email' => $staff['email']],
                [
                    'name' => $staff['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }

        // Klien (untuk testing)
        $clients = [
            ['name' => 'Andi Wijaya', 'email' => 'andi@example.com'],
            ['name' => 'Rina Marlina', 'email' => 'rina@example.com'],
            ['name' => 'Dedi Kurniawan', 'email' => 'dedi@example.com'],
            ['name' => 'Maya Sari', 'email' => 'maya@example.com'],
            ['name' => 'Rudi Hartono', 'email' => 'rudi@example.com'],
        ];

        foreach ($clients as $client) {
            User::updateOrCreate(
                ['email' => $client['email']],
                [
                    'name' => $client['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('✅ 8 users seeded (1 admin, 2 staff, 5 klien).');
    }
}