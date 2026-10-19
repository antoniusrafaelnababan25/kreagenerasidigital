<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'rental' => [
                ['name' => 'Tenda', 'description' => 'Berbagai jenis tenda camping'],
                ['name' => 'Sleeping Bag', 'description' => 'Sleeping bag berkualitas'],
                ['name' => 'Carrier', 'description' => 'Tas carrier untuk pendakian'],
                ['name' => 'Matras', 'description' => 'Matras camping'],
                ['name' => 'Alat Masak', 'description' => 'Kompor, nesting, dll'],
                ['name' => 'Headlamp', 'description' => 'Lampu kepala untuk camping'],
            ],
            'printing' => [
                ['name' => 'Kaos', 'description' => 'Kaos custom berbagai bahan'],
                ['name' => 'Hoodie', 'description' => 'Hoodie & sweater custom'],
                ['name' => 'Totebag', 'description' => 'Totebag canvas'],
                ['name' => 'Mug', 'description' => 'Mug keramik & magic'],
                ['name' => 'Topi', 'description' => 'Topi custom'],
                ['name' => 'Jaket', 'description' => 'Jaket custom'],
            ],
            'website' => [
                ['name' => 'Landing Page', 'description' => 'Website 1 halaman'],
                ['name' => 'Company Profile', 'description' => 'Website profil perusahaan'],
                ['name' => 'E-Commerce', 'description' => 'Toko online'],
                ['name' => 'Custom Web App', 'description' => 'Aplikasi web custom'],
            ],
            'video' => [
                ['name' => 'Wedding', 'description' => 'Video pernikahan'],
                ['name' => 'Corporate', 'description' => 'Video perusahaan'],
                ['name' => 'Event', 'description' => 'Dokumentasi event'],
                ['name' => 'Content Creator', 'description' => 'Konten sosmed'],
            ],
            'company' => [
                ['name' => 'Portfolio', 'description' => 'Karya KREA'],
                ['name' => 'Blog', 'description' => 'Artikel & berita'],
            ],
        ];

        foreach ($categories as $siteKey => $siteCategories) {
            $site = Site::where('key', $siteKey)->first();
            if (! $site) continue;

            foreach ($siteCategories as $index => $cat) {
                Category::updateOrCreate(
                    [
                        'site_id' => $site->id,
                        'slug' => Str::slug($cat['name']),
                    ],
                    [
                        'site_id' => $site->id,
                        'slug' => Str::slug($cat['name']),
                        'name' => $cat['name'],
                        'description' => $cat['description'],
                        'order' => $index + 1,
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('✅ Categories seeded.');
    }
}