<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use App\Models\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $portfolios = [
            'website' => [
                ['title' => 'Website Company Profile PT Maju Jaya', 'client_name' => 'PT Maju Jaya', 'service_type' => 'website', 'year' => '2024', 'short' => 'Company profile modern dengan CMS custom.', 'tags' => ['company-profile', 'laravel']],
                ['title' => 'E-Commerce Toko Bunga Rina', 'client_name' => 'Toko Bunga Rina', 'service_type' => 'website', 'year' => '2024', 'short' => 'Toko online dengan payment gateway Midtrans.', 'tags' => ['ecommerce', 'midtrans']],
                ['title' => 'Landing Page Event Musik', 'client_name' => 'EO XYZ', 'service_type' => 'website', 'year' => '2023', 'short' => 'Landing page event dengan tiket online.', 'tags' => ['landing-page', 'event']],
                ['title' => 'Website Restoran Sederhana', 'client_name' => 'Restoran Sederhana', 'service_type' => 'website', 'year' => '2023', 'short' => 'Website restoran dengan menu digital.', 'tags' => ['restoran', 'menu-digital']],
            ],
            'video' => [
                ['title' => 'Wedding Andi & Sari', 'client_name' => 'Andi & Sari', 'service_type' => 'video', 'year' => '2024', 'short' => 'Video wedding sinematik dengan drone.', 'tags' => ['wedding', 'sinematik']],
                ['title' => 'Company Profile PT Sejahtera', 'client_name' => 'PT Sejahtera', 'service_type' => 'video', 'year' => '2024', 'short' => 'Video profil perusahaan 5 menit.', 'tags' => ['company-profile', 'corporate']],
                ['title' => 'Dokumentasi Event Gathering', 'client_name' => 'Startup XYZ', 'service_type' => 'video', 'year' => '2023', 'short' => 'Dokumentasi gathering karyawan.', 'tags' => ['event', 'gathering']],
                ['title' => 'Konten Sosmed Brand Fashion', 'client_name' => 'Maya Fashion', 'service_type' => 'video', 'year' => '2023', 'short' => 'Paket 20 konten reels untuk Instagram.', 'tags' => ['sosmed', 'reels']],
            ],
            'printing' => [
                ['title' => 'Kaos Komunitas Motor', 'client_name' => 'MC Indonesia', 'service_type' => 'printing', 'year' => '2024', 'short' => '300 pcs kaos sablon manual.', 'tags' => ['kaos', 'sablon']],
                ['title' => 'Hoodie Startup XYZ', 'client_name' => 'Startup XYZ', 'service_type' => 'printing', 'year' => '2024', 'short' => '100 pcs hoodie fleece premium.', 'tags' => ['hoodie', 'fleece']],
                ['title' => 'Totebag Seminar Nasional', 'client_name' => 'Universitas ABC', 'service_type' => 'printing', 'year' => '2023', 'short' => '500 pcs totebag canvas untuk seminar.', 'tags' => ['totebag', 'seminar']],
                ['title' => 'Mug Custom Perusahaan', 'client_name' => 'PT Karya Mandiri', 'service_type' => 'printing', 'year' => '2023', 'short' => '200 pcs mug keramik custom logo.', 'tags' => ['mug', 'souvenir']],
            ],
            'rental' => [
                ['title' => 'Trip Gunung Gede', 'client_name' => 'Mapala UI', 'service_type' => 'rental', 'year' => '2024', 'short' => 'Sewa 20 set alat camping untuk trip 3 hari.', 'tags' => ['camping', 'gunung']],
                ['title' => 'Outbound Perusahaan', 'client_name' => 'PT Sejahtera', 'service_type' => 'rental', 'year' => '2024', 'short' => 'Sewa alat outbound untuk 50 peserta.', 'tags' => ['outbound', 'team-building']],
                ['title' => 'Camping Keluarga', 'client_name' => 'Keluarga Wijaya', 'service_type' => 'rental', 'year' => '2023', 'short' => 'Paket camping keluarga 4 orang.', 'tags' => ['camping', 'keluarga']],
            ],
            'company' => [
                ['title' => 'Kolaborasi KREA x PT Maju Jaya', 'client_name' => 'PT Maju Jaya', 'service_type' => 'multi', 'year' => '2024', 'short' => 'Website + video company profile + merchandise.', 'tags' => ['bundle', 'multi-service']],
                ['title' => 'Paket Lengkap Startup XYZ', 'client_name' => 'Startup XYZ', 'service_type' => 'multi', 'year' => '2024', 'short' => 'Website, video, dan printing untuk launching startup.', 'tags' => ['bundle', 'startup']],
            ],
        ];

        foreach ($portfolios as $siteKey => $items) {
            $site = Site::where('key', $siteKey)->first();
            if (! $site) continue;

            foreach ($items as $index => $item) {
                $slug = Str::slug($item['title']);

                Portfolio::updateOrCreate(
                    [
                        'site_id' => $site->id,
                        'slug' => $slug,
                    ],
                    [
                        'site_id' => $site->id,
                        'slug' => $slug,
                        'title' => $item['title'],
                        'client_name' => $item['client_name'],
                        'short_description' => $item['short'],
                        'description' => $item['short'] . "\n\nProyek ini dikerjakan oleh tim KREA Generasi Digital dengan fokus pada kualitas dan kepuasan klien.",
                        'service_type' => $item['service_type'],
                        'project_year' => $item['year'],
                        'tags' => $item['tags'],
                        'meta_title' => $item['title'] . ' | KREA',
                        'meta_description' => $item['short'],
                        'is_featured' => $index < 2,
                        'is_published' => true,
                        'order' => $index + 1,
                    ]
                );
            }
        }

        $this->command->info('✅ Portfolios seeded untuk 5 site.');
    }
}