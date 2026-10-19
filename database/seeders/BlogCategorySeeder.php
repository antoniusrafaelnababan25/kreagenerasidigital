<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'company' => [
                ['name' => 'Berita KREA', 'description' => 'Update terbaru seputar KREA Generasi Digital'],
                ['name' => 'Tips Digital', 'description' => 'Tips dan trik seputar dunia digital'],
                ['name' => 'Studi Kasus', 'description' => 'Cerita sukses klien-klien kami'],
                ['name' => 'Teknologi', 'description' => 'Perkembangan teknologi terbaru'],
            ],
            'website' => [
                ['name' => 'Tips Website', 'description' => 'Tips seputar website dan SEO'],
                ['name' => 'Tutorial', 'description' => 'Tutorial teknis seputar web development'],
                ['name' => 'SEO', 'description' => 'Optimasi mesin pencari'],
                ['name' => 'E-Commerce', 'description' => 'Tips jualan online'],
            ],
            'rental' => [
                ['name' => 'Tips Camping', 'description' => 'Tips dan trik camping'],
                ['name' => 'Review Alat', 'description' => 'Review alat camping'],
                ['name' => 'Destinasi', 'description' => 'Rekomendasi tempat camping'],
            ],
            'video' => [
                ['name' => 'Tips Videografi', 'description' => 'Tips seputar videografi'],
                ['name' => 'Behind The Scene', 'description' => 'Dokumentasi proses produksi'],
                ['name' => 'Review Gear', 'description' => 'Review alat videografi'],
            ],
            'printing' => [
                ['name' => 'Tips Printing', 'description' => 'Tips seputar custom printing'],
                ['name' => 'Inspirasi Desain', 'description' => 'Inspirasi desain kaos, hoodie, dll'],
                ['name' => 'Bahan & Teknik', 'description' => 'Info bahan dan teknik printing'],
            ],
        ];

        foreach ($categories as $siteKey => $siteCategories) {
            $site = Site::where('key', $siteKey)->first();
            if (! $site) continue;

            foreach ($siteCategories as $index => $cat) {
                BlogCategory::updateOrCreate(
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

        $this->command->info('✅ Blog categories seeded.');
    }
}