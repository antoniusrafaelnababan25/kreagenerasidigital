<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Site;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            'company' => [
                ['label' => 'Beranda', 'url' => '/', 'order' => 1],
                ['label' => 'Portfolio', 'url' => '/portfolio', 'order' => 2],
                ['label' => 'Blog', 'url' => '/blog', 'order' => 3],
                ['label' => 'Website', 'url' => '/layanan/website', 'order' => 4],
                ['label' => 'Rental', 'url' => '/layanan/rental', 'order' => 5],
                ['label' => 'Video', 'url' => '/layanan/video', 'order' => 6],
                ['label' => 'Printing', 'url' => '/layanan/printing', 'order' => 7],
                ['label' => 'Kontak', 'url' => '/kontak', 'order' => 8],
            ],
            'website' => [
                ['label' => 'Beranda', 'url' => '/layanan/website', 'order' => 1],
                ['label' => 'Paket', 'url' => '/layanan/website/paket', 'order' => 2],
                ['label' => 'Portfolio', 'url' => '/layanan/website/portfolio', 'order' => 3],
                ['label' => 'Order', 'url' => '/layanan/website/order', 'order' => 4],
            ],
            'rental' => [
                ['label' => 'Beranda', 'url' => '/layanan/rental', 'order' => 1],
                ['label' => 'Katalog', 'url' => '/layanan/rental/katalog', 'order' => 2],
                ['label' => 'Booking', 'url' => '/layanan/rental/booking', 'order' => 3],
            ],
            'video' => [
                ['label' => 'Beranda', 'url' => '/layanan/video', 'order' => 1],
                ['label' => 'Paket', 'url' => '/layanan/video/paket', 'order' => 2],
                ['label' => 'Portfolio', 'url' => '/layanan/video/portfolio', 'order' => 3],
                ['label' => 'Order', 'url' => '/layanan/video/order', 'order' => 4],
            ],
            'printing' => [
                ['label' => 'Beranda', 'url' => '/layanan/printing', 'order' => 1],
                ['label' => 'Produk', 'url' => '/layanan/printing/produk', 'order' => 2],
                ['label' => 'Order', 'url' => '/layanan/printing/order', 'order' => 3],
            ],
        ];

        foreach ($menus as $siteKey => $siteMenus) {
            $site = Site::where('key', $siteKey)->first();
            if (! $site) continue;

            foreach ($siteMenus as $menu) {
                Menu::updateOrCreate(
                    ['site_id' => $site->id, 'label' => $menu['label']],
                    array_merge($menu, ['site_id' => $site->id, 'is_active' => true])
                );
            }
        }

        $this->command->info('✅ Menus seeded untuk 5 site.');
    }
}