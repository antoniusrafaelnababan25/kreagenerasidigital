<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $site = Site::where('key', 'printing')->first();
        if (! $site) return;

        $products = [
            ['name' => 'Kaos Cotton Combed 30s', 'category' => 'Kaos', 'price' => 65000, 'compare' => 85000, 'sku' => 'KCS-30S', 'desc' => 'Kaos polos cotton combed 30s, adem dan nyaman.'],
            ['name' => 'Kaos Cotton Combed 24s', 'category' => 'Kaos', 'price' => 75000, 'compare' => 95000, 'sku' => 'KCS-24S', 'desc' => 'Kaos cotton combed 24s, lebih tebal dan tahan lama.'],
            ['name' => 'Kaos Polyester Dry Fit', 'category' => 'Kaos', 'price' => 70000, 'compare' => null, 'sku' => 'KCS-POLY', 'desc' => 'Kaos olahraga dry fit, cepat kering.'],
            ['name' => 'Hoodie Fleece Premium', 'category' => 'Hoodie', 'price' => 185000, 'compare' => 225000, 'sku' => 'HD-FLC', 'desc' => 'Hoodie fleece premium, hangat dan nyaman.'],
            ['name' => 'Hoodie Zipper', 'category' => 'Hoodie', 'price' => 210000, 'compare' => null, 'sku' => 'HD-ZIP', 'desc' => 'Hoodie dengan zipper, cocok untuk casual.'],
            ['name' => 'Totebag Canvas', 'category' => 'Totebag', 'price' => 45000, 'compare' => null, 'sku' => 'TB-CNV', 'desc' => 'Totebag canvas tebal, kuat dan stylish.'],
            ['name' => 'Totebag Spunbond', 'category' => 'Totebag', 'price' => 25000, 'compare' => null, 'sku' => 'TB-SPN', 'desc' => 'Totebag spunbond, ekonomis untuk souvenir.'],
            ['name' => 'Mug Keramik Custom', 'category' => 'Mug', 'price' => 35000, 'compare' => 45000, 'sku' => 'MG-KRM', 'desc' => 'Mug keramik custom, cocok untuk souvenir.'],
            ['name' => 'Magic Mug', 'category' => 'Mug', 'price' => 55000, 'compare' => null, 'sku' => 'MG-MGC', 'desc' => 'Magic mug berubah warna saat diisi air panas.'],
            ['name' => 'Topi Trucker', 'category' => 'Topi', 'price' => 45000, 'compare' => null, 'sku' => 'TP-TRK', 'desc' => 'Topi trucker dengan jaring, cocok untuk komunitas.'],
            ['name' => 'Topi Baseball', 'category' => 'Topi', 'price' => 50000, 'compare' => null, 'sku' => 'TP-BSB', 'desc' => 'Topi baseball polos, bisa custom logo.'],
            ['name' => 'Jaket Varsity', 'category' => 'Jaket', 'price' => 275000, 'compare' => 325000, 'sku' => 'JK-VRS', 'desc' => 'Jaket varsity premium, cocok untuk komunitas.'],
        ];

        foreach ($products as $index => $item) {
            $category = Category::where('site_id', $site->id)
                ->where('name', $item['category'])
                ->first();

            Product::updateOrCreate(
                [
                    'site_id' => $site->id,
                    'slug' => Str::slug($item['name']),
                ],
                [
                    'site_id' => $site->id,
                    'category_id' => $category?->id,
                    'slug' => Str::slug($item['name']),
                    'name' => $item['name'],
                    'description' => $item['desc'],
                    'short_description' => $item['desc'],
                    'price' => $item['price'],
                    'compare_price' => $item['compare'],
                    'cost_price' => $item['price'] * 0.6,
                    'stock' => rand(50, 200),
                    'sku' => $item['sku'],
                    'weight' => 250,
                    'unit' => 'pcs',
                    'status' => 'published',
                    'is_featured' => $index < 3,
                    'track_stock' => true,
                ]
            );
        }

        $this->command->info('✅ 12 products seeded untuk site printing.');
    }
}