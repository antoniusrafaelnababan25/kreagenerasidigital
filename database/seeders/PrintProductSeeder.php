<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PrintProduct;
use App\Models\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PrintProductSeeder extends Seeder
{
    public function run(): void
    {
        $site = Site::where('key', 'printing')->first();
        if (! $site) return;

        $products = [
            ['name' => 'Kaos Cotton Combed 30s', 'category' => 'Kaos', 'type' => 'apparel', 'material' => 'Cotton Combed 30s', 'technique' => 'DTF', 'price' => 65000, 'min_qty' => 1, 'days' => 3, 'sizes' => ['S', 'M', 'L', 'XL', 'XXL'], 'colors' => ['Putih', 'Hitam', 'Navy', 'Merah', 'Abu']],
            ['name' => 'Kaos Cotton Combed 24s', 'category' => 'Kaos', 'type' => 'apparel', 'material' => 'Cotton Combed 24s', 'technique' => 'Sablon Manual', 'price' => 75000, 'min_qty' => 12, 'days' => 5, 'sizes' => ['S', 'M', 'L', 'XL', 'XXL'], 'colors' => ['Putih', 'Hitam', 'Navy', 'Merah']],
            ['name' => 'Kaos Polyester Dry Fit', 'category' => 'Kaos', 'type' => 'apparel', 'material' => 'Polyester', 'technique' => 'Sublimasi', 'price' => 70000, 'min_qty' => 6, 'days' => 4, 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['Putih', 'Hitam', 'Biru']],
            ['name' => 'Hoodie Fleece Premium', 'category' => 'Hoodie', 'type' => 'apparel', 'material' => 'Fleece', 'technique' => 'DTF', 'price' => 185000, 'min_qty' => 1, 'days' => 5, 'sizes' => ['M', 'L', 'XL', 'XXL'], 'colors' => ['Hitam', 'Navy', 'Abu', 'Maroon']],
            ['name' => 'Hoodie Zipper', 'category' => 'Hoodie', 'type' => 'apparel', 'material' => 'Fleece', 'technique' => 'DTF', 'price' => 210000, 'min_qty' => 1, 'days' => 5, 'sizes' => ['M', 'L', 'XL', 'XXL'], 'colors' => ['Hitam', 'Navy', 'Abu']],
            ['name' => 'Totebag Canvas', 'category' => 'Totebag', 'type' => 'merchandise', 'material' => 'Canvas', 'technique' => 'Sablon', 'price' => 45000, 'min_qty' => 12, 'days' => 4, 'sizes' => ['30x40', '35x45'], 'colors' => ['Natural', 'Hitam', 'Navy']],
            ['name' => 'Totebag Spunbond', 'category' => 'Totebag', 'type' => 'merchandise', 'material' => 'Spunbond', 'technique' => 'Sablon', 'price' => 25000, 'min_qty' => 50, 'days' => 3, 'sizes' => ['30x40'], 'colors' => ['Putih', 'Hitam', 'Biru']],
            ['name' => 'Mug Keramik Custom', 'category' => 'Mug', 'type' => 'merchandise', 'material' => 'Keramik', 'technique' => 'Sublimasi', 'price' => 35000, 'min_qty' => 12, 'days' => 3, 'sizes' => ['11oz', '15oz'], 'colors' => ['Putih']],
            ['name' => 'Magic Mug', 'category' => 'Mug', 'type' => 'merchandise', 'material' => 'Keramik', 'technique' => 'Sublimasi', 'price' => 55000, 'min_qty' => 12, 'days' => 3, 'sizes' => ['11oz'], 'colors' => ['Hitam']],
            ['name' => 'Topi Trucker', 'category' => 'Topi', 'type' => 'apparel', 'material' => 'Twill + Mesh', 'technique' => 'Bordir', 'price' => 45000, 'min_qty' => 12, 'days' => 4, 'sizes' => ['All Size'], 'colors' => ['Hitam', 'Navy', 'Putih', 'Merah']],
            ['name' => 'Topi Baseball', 'category' => 'Topi', 'type' => 'apparel', 'material' => 'Twill', 'technique' => 'Bordir', 'price' => 50000, 'min_qty' => 12, 'days' => 4, 'sizes' => ['All Size'], 'colors' => ['Hitam', 'Navy', 'Putih']],
            ['name' => 'Jaket Varsity', 'category' => 'Jaket', 'type' => 'apparel', 'material' => 'Fleece + Polyester', 'technique' => 'Bordir + Sablon', 'price' => 275000, 'min_qty' => 6, 'days' => 7, 'sizes' => ['M', 'L', 'XL', 'XXL'], 'colors' => ['Hitam', 'Navy', 'Maroon']],
        ];

        foreach ($products as $index => $item) {
            $category = Category::where('site_id', $site->id)
                ->where('name', $item['category'])
                ->first();

            PrintProduct::updateOrCreate(
                [
                    'site_id' => $site->id,
                    'slug' => Str::slug($item['name']),
                ],
                [
                    'site_id' => $site->id,
                    'category_id' => $category?->id,
                    'slug' => Str::slug($item['name']),
                    'name' => $item['name'],
                    'description' => 'Custom ' . $item['name'] . ' dengan bahan ' . $item['material'] . '.',
                    'type' => $item['type'],
                    'material' => $item['material'],
                    'print_technique' => $item['technique'],
                    'base_price' => $item['price'],
                    'min_order_qty' => $item['min_qty'],
                    'production_days' => $item['days'],
                    'available_sizes' => $item['sizes'],
                    'available_colors' => $item['colors'],
                    'is_featured' => $index < 3,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('✅ 12 print products seeded.');
    }
}