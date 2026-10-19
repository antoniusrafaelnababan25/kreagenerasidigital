<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\RentalItem;
use App\Models\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RentalItemSeeder extends Seeder
{
    public function run(): void
    {
        $site = Site::where('key', 'rental')->first();
        if (! $site) return;

        $items = [
            ['name' => 'Tenda Dome 4 Orang', 'category' => 'Tenda', 'price_day' => 75000, 'price_week' => 450000, 'deposit' => 200000, 'stock' => 15, 'brand' => 'Naturehike', 'spec' => 'Kapasitas 4 orang, waterproof 3000mm, fiberglass frame.'],
            ['name' => 'Tenda Dome 2 Orang', 'category' => 'Tenda', 'price_day' => 50000, 'price_week' => 300000, 'deposit' => 150000, 'stock' => 20, 'brand' => 'Consina', 'spec' => 'Kapasitas 2 orang, ringan, mudah dipasang.'],
            ['name' => 'Tenda Camp 6 Orang', 'category' => 'Tenda', 'price_day' => 125000, 'price_week' => 750000, 'deposit' => 300000, 'stock' => 8, 'brand' => 'Eiger', 'spec' => 'Kapasitas 6 orang, untuk camping keluarga.'],
            ['name' => 'Sleeping Bag Polar', 'category' => 'Sleeping Bag', 'price_day' => 25000, 'price_week' => 150000, 'deposit' => 50000, 'stock' => 30, 'brand' => 'Consina', 'spec' => 'Bahan polar, hangat, cocok untuk suhu 10-15°C.'],
            ['name' => 'Sleeping Bag Mummy', 'category' => 'Sleeping Bag', 'price_day' => 35000, 'price_week' => 210000, 'deposit' => 75000, 'stock' => 20, 'brand' => 'Naturehike', 'spec' => 'Model mummy, cocok untuk suhu 0-10°C.'],
            ['name' => 'Carrier 60L', 'category' => 'Carrier', 'price_day' => 50000, 'price_week' => 300000, 'deposit' => 150000, 'stock' => 12, 'brand' => 'Eiger', 'spec' => 'Kapasitas 60L, cocok untuk pendakian 3-5 hari.'],
            ['name' => 'Carrier 45L', 'category' => 'Carrier', 'price_day' => 40000, 'price_week' => 240000, 'deposit' => 100000, 'stock' => 15, 'brand' => 'Consina', 'spec' => 'Kapasitas 45L, untuk pendakian 2-3 hari.'],
            ['name' => 'Matras Foil', 'category' => 'Matras', 'price_day' => 15000, 'price_week' => 90000, 'deposit' => 30000, 'stock' => 40, 'brand' => 'Generic', 'spec' => 'Matras foil, ringan, anti air.'],
            ['name' => 'Matras Angin', 'category' => 'Matras', 'price_day' => 25000, 'price_week' => 150000, 'deposit' => 50000, 'stock' => 25, 'brand' => 'Naturehike', 'spec' => 'Matras angin, tebal 5cm, nyaman.'],
            ['name' => 'Kompor Portable', 'category' => 'Alat Masak', 'price_day' => 20000, 'price_week' => 120000, 'deposit' => 50000, 'stock' => 20, 'brand' => 'Kovea', 'spec' => 'Kompor gas portable, ringan.'],
            ['name' => 'Nesting Set', 'category' => 'Alat Masak', 'price_day' => 25000, 'price_week' => 150000, 'deposit' => 75000, 'stock' => 15, 'brand' => 'Generic', 'spec' => 'Set alat masak camping, 4-6 orang.'],
            ['name' => 'Headlamp LED', 'category' => 'Headlamp', 'price_day' => 15000, 'price_week' => 90000, 'deposit' => 30000, 'stock' => 30, 'brand' => 'Naturehike', 'spec' => 'Lampu kepala LED, 3 mode, tahan air.'],
            ['name' => 'Lampu Tenda', 'category' => 'Headlamp', 'price_day' => 10000, 'price_week' => 60000, 'deposit' => 20000, 'stock' => 35, 'brand' => 'Generic', 'spec' => 'Lampu tenda gantung, LED hemat energi.'],
        ];

        foreach ($items as $index => $item) {
            $category = Category::where('site_id', $site->id)
                ->where('name', $item['category'])
                ->first();

            RentalItem::updateOrCreate(
                [
                    'site_id' => $site->id,
                    'slug' => Str::slug($item['name']),
                ],
                [
                    'site_id' => $site->id,
                    'category_id' => $category?->id,
                    'slug' => Str::slug($item['name']),
                    'name' => $item['name'],
                    'brand' => $item['brand'],
                    'description' => $item['spec'],
                    'specification' => $item['spec'],
                    'price_per_day' => $item['price_day'],
                    'price_per_week' => $item['price_week'],
                    'deposit' => $item['deposit'],
                    'stock_total' => $item['stock'],
                    'stock_available' => $item['stock'],
                    'stock_maintenance' => 0,
                    'condition' => 'good',
                    'status' => 'available',
                    'is_featured' => $index < 3,
                    'is_active' => true,
                    'weight' => 1000,
                ]
            );
        }

        $this->command->info('✅ 13 rental items seeded.');
    }
}