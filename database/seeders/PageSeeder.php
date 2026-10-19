<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Site;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'company' => [
                ['slug' => 'home', 'title' => 'Beranda'],
                ['slug' => 'tentang', 'title' => 'Tentang Kami'],
                ['slug' => 'layanan', 'title' => 'Layanan'],
                ['slug' => 'portfolio', 'title' => 'Portfolio'],
                ['slug' => 'kontak', 'title' => 'Kontak'],
            ],
            'website' => [
                ['slug' => 'home', 'title' => 'Jasa Pembuatan Website'],
                ['slug' => 'paket', 'title' => 'Paket & Harga'],
                ['slug' => 'portfolio', 'title' => 'Portfolio Website'],
                ['slug' => 'order', 'title' => 'Pesan Sekarang'],
                ['slug' => 'faq', 'title' => 'FAQ'],
            ],
            'rental' => [
                ['slug' => 'home', 'title' => 'Rental Alat Camping'],
                ['slug' => 'katalog', 'title' => 'Katalog Alat'],
                ['slug' => 'cara-sewa', 'title' => 'Cara Sewa'],
                ['slug' => 'booking', 'title' => 'Booking'],
                ['slug' => 'faq', 'title' => 'FAQ'],
            ],
            'video' => [
                ['slug' => 'home', 'title' => 'Videografi Profesional'],
                ['slug' => 'paket', 'title' => 'Paket Video'],
                ['slug' => 'portfolio', 'title' => 'Portfolio Video'],
                ['slug' => 'order', 'title' => 'Pesan Sekarang'],
                ['slug' => 'faq', 'title' => 'FAQ'],
            ],
            'printing' => [
                ['slug' => 'home', 'title' => 'Custom Printing'],
                ['slug' => 'produk', 'title' => 'Produk'],
                ['slug' => 'cara-order', 'title' => 'Cara Order'],
                ['slug' => 'order', 'title' => 'Pesan Sekarang'],
                ['slug' => 'faq', 'title' => 'FAQ'],
            ],
        ];

        foreach ($pages as $siteKey => $sitePages) {
            $site = Site::where('key', $siteKey)->first();
            if (! $site) continue;

            foreach ($sitePages as $index => $page) {
                Page::updateOrCreate(
                    ['site_id' => $site->id, 'slug' => $page['slug']],
                    [
                        'site_id' => $site->id,
                        'slug' => $page['slug'],
                        'title' => $page['title'],
                        'meta_title' => $page['title'] . ' | KREA Generasi Digital',
                        'meta_description' => 'Halaman ' . $page['title'] . ' di KREA Generasi Digital.',
                        'is_published' => true,
                    ]
                );
            }
        }

        $this->command->info('✅ Pages seeded untuk 5 site (25 halaman).');
    }
}