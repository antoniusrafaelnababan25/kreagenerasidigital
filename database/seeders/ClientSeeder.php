<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['name' => 'Andi Wijaya', 'email' => 'andi@example.com', 'phone' => '081234567001', 'company' => 'PT Maju Jaya', 'city' => 'Jakarta', 'province' => 'DKI Jakarta', 'status' => 'vip'],
            ['name' => 'Rina Marlina', 'email' => 'rina@example.com', 'phone' => '081234567002', 'company' => 'Toko Bunga Rina', 'city' => 'Bandung', 'province' => 'Jawa Barat', 'status' => 'active'],
            ['name' => 'Dedi Kurniawan', 'email' => 'dedi@example.com', 'phone' => '081234567003', 'company' => 'CV Sukses Abadi', 'city' => 'Surabaya', 'province' => 'Jawa Timur', 'status' => 'vip'],
            ['name' => 'Maya Sari', 'email' => 'maya@example.com', 'phone' => '081234567004', 'company' => 'Maya Fashion', 'city' => 'Yogyakarta', 'province' => 'DI Yogyakarta', 'status' => 'active'],
            ['name' => 'Rudi Hartono', 'email' => 'rudi@example.com', 'phone' => '081234567005', 'company' => 'PT Karya Mandiri', 'city' => 'Semarang', 'province' => 'Jawa Tengah', 'status' => 'active'],
            ['name' => 'Fajar Pratama', 'email' => 'fajar@example.com', 'phone' => '081234567006', 'company' => 'Komunitas Outdoor', 'city' => 'Bogor', 'province' => 'Jawa Barat', 'status' => 'active'],
            ['name' => 'Sinta Dewi', 'email' => 'sinta@example.com', 'phone' => '081234567007', 'company' => null, 'city' => 'Depok', 'province' => 'Jawa Barat', 'status' => 'active'],
            ['name' => 'Agus Setiawan', 'email' => 'agus@example.com', 'phone' => '081234567008', 'company' => 'Mapala UI', 'city' => 'Depok', 'province' => 'Jawa Barat', 'status' => 'active'],
        ];

        foreach ($clients as $client) {
            $user = User::where('email', $client['email'])->first();

            Client::updateOrCreate(
                ['email' => $client['email']],
                [
                    'user_id' => $user?->id,
                    'name' => $client['name'],
                    'email' => $client['email'],
                    'phone' => $client['phone'],
                    'company' => $client['company'],
                    'city' => $client['city'],
                    'province' => $client['province'],
                    'status' => $client['status'],
                ]
            );
        }

        $this->command->info('✅ 8 clients seeded.');
    }
}