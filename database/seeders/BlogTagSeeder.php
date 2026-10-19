<?php

namespace Database\Seeders;

use App\Models\BlogTag;
use App\Models\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogTagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'company' => ['KREA', 'Digital', 'Bisnis', 'UMKM', 'Startup', 'Teknologi', 'Indonesia', 'Konsultasi', 'Tips', 'Berita'],
            'website' => ['Laravel', 'WordPress', 'SEO', 'Landing Page', 'E-Commerce', 'Hosting', 'Domain', 'Responsive', 'Cepat', 'Aman'],
            'rental' => ['Camping', 'Tenda', 'Sleeping Bag', 'Carrier', 'Gunung', 'Outdoor', 'Hiking', 'Pendakian', 'Alam', 'Petualangan'],
            'video' => ['Wedding', 'Sinematik', 'Drone', 'Editing', 'Color Grading', 'Kamera', 'Lighting', 'Audio', 'Reels', 'YouTube'],
            'printing' => ['Kaos', 'Hoodie', 'Sablon', 'DTF', 'Sublimasi', 'Cotton Combed', 'Fleece', 'Totebag', 'Mug', 'Custom'],
        ];

        foreach ($tags as $siteKey => $siteTags) {
            $site = Site::where('key', $siteKey)->first();
            if (! $site) continue;

            foreach ($siteTags as $tag) {
                BlogTag::updateOrCreate(
                    [
                        'site_id' => $site->id,
                        'slug' => Str::slug($tag),
                    ],
                    [
                        'site_id' => $site->id,
                        'slug' => Str::slug($tag),
                        'name' => $tag,
                    ]
                );
            }
        }

        $this->command->info('✅ Blog tags seeded.');
    }
}