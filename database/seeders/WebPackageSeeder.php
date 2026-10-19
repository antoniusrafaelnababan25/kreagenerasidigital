<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\WebPackage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WebPackageSeeder extends Seeder
{
    public function run(): void
    {
        $site = Site::where('key', 'website')->first();
        if (! $site) return;

        $packages = [
            [
                'name' => 'Paket Landing Page',
                'category' => 'landing',
                'price' => 2500000,
                'compare' => 3500000,
                'pages' => 1,
                'revision' => 2,
                'delivery' => 7,
                'domain' => true,
                'hosting' => true,
                'maintenance' => false,
                'maintenance_months' => 0,
                'features' => [
                    '1 halaman landing page',
                    'Desain modern & responsive',
                    'Form kontak / WhatsApp',
                    'Google Analytics',
                    'SEO dasar on-page',
                    'Domain .com 1 tahun',
                    'Hosting 1 tahun',
                    '2x revisi',
                    'Garansi 30 hari',
                ],
            ],
            [
                'name' => 'Paket Company Profile Basic',
                'category' => 'company',
                'price' => 5000000,
                'compare' => 6500000,
                'pages' => 5,
                'revision' => 3,
                'delivery' => 14,
                'domain' => true,
                'hosting' => true,
                'maintenance' => true,
                'maintenance_months' => 3,
                'features' => [
                    '5 halaman (Home, About, Services, Portfolio, Contact)',
                    'Desain custom modern',
                    'Responsive mobile-friendly',
                    'Form kontak + WhatsApp',
                    'Google Analytics & Search Console',
                    'SEO on-page lengkap',
                    'Blog / artikel',
                    'Domain .com 1 tahun',
                    'Hosting 1 tahun',
                    'Maintenance 3 bulan',
                    '3x revisi',
                    'Garansi 60 hari',
                ],
            ],
            [
                'name' => 'Paket Company Profile Pro',
                'category' => 'company',
                'price' => 8500000,
                'compare' => 11000000,
                'pages' => 10,
                'revision' => 5,
                'delivery' => 21,
                'domain' => true,
                'hosting' => true,
                'maintenance' => true,
                'maintenance_months' => 6,
                'features' => [
                    '10 halaman lengkap',
                    'Desain custom premium',
                    'Responsive semua device',
                    'Multi-bahasa (ID + EN)',
                    'Blog + kategori + tag',
                    'Portfolio + filter',
                    'Testimoni klien',
                    'Google Analytics & Search Console',
                    'SEO on-page + schema markup',
                    'Domain .com 1 tahun',
                    'Hosting 1 tahun',
                    'Maintenance 6 bulan',
                    '5x revisi',
                    'Garansi 90 hari',
                    'Pelatihan admin panel',
                ],
            ],
            [
                'name' => 'Paket E-Commerce Basic',
                'category' => 'ecommerce',
                'price' => 12000000,
                'compare' => 15000000,
                'pages' => 15,
                'revision' => 3,
                'delivery' => 30,
                'domain' => true,
                'hosting' => true,
                'maintenance' => true,
                'maintenance_months' => 3,
                'features' => [
                    '15 halaman e-commerce',
                    'Katalog produk + kategori',
                    'Keranjang belanja',
                    'Checkout + payment gateway',
                    'Manajemen stok',
                    'Manajemen pesanan',
                    'Halaman admin lengkap',
                    'SEO produk',
                    'Domain .com 1 tahun',
                    'Hosting 1 tahun',
                    'Maintenance 3 bulan',
                    '3x revisi',
                    'Pelatihan admin panel',
                ],
            ],
            [
                'name' => 'Paket E-Commerce Pro',
                'category' => 'ecommerce',
                'price' => 25000000,
                'compare' => 30000000,
                'pages' => 30,
                'revision' => 5,
                'delivery' => 45,
                'domain' => true,
                'hosting' => true,
                'maintenance' => true,
                'maintenance_months' => 6,
                'features' => [
                    '30+ halaman e-commerce',
                    'Multi-vendor / marketplace',
                    'Multi-payment gateway',
                    'Sistem kupon & diskon',
                    'Manajemen stok multi-gudang',
                    'Laporan penjualan lengkap',
                    'Email marketing',
                    'Integrasi kurir (RajaOngkir)',
                    'SEO produk + schema',
                    'Domain .com 1 tahun',
                    'Hosting 1 tahun',
                    'Maintenance 6 bulan',
                    '5x revisi',
                    'Pelatihan admin lengkap',
                    'Support prioritas',
                ],
            ],
            [
                'name' => 'Paket Custom Web App',
                'category' => 'custom',
                'price' => 0,
                'compare' => null,
                'pages' => 0,
                'revision' => 5,
                'delivery' => 60,
                'domain' => false,
                'hosting' => false,
                'maintenance' => true,
                'maintenance_months' => 6,
                'features' => [
                    'Konsultasi kebutuhan',
                    'Analisis & desain sistem',
                    'Pengembangan custom',
                    'Testing & QA',
                    'Deploy ke production',
                    'Dokumentasi lengkap',
                    'Pelatihan tim',
                    'Harga menyesuaikan kompleksitas',
                ],
            ],
        ];

        foreach ($packages as $index => $item) {
            WebPackage::updateOrCreate(
                [
                    'site_id' => $site->id,
                    'slug' => Str::slug($item['name']),
                ],
                [
                    'site_id' => $site->id,
                    'slug' => Str::slug($item['name']),
                    'name' => $item['name'],
                    'category' => $item['category'],
                    'description' => 'Paket ' . $item['name'] . ' untuk kebutuhan website Anda.',
                    'features' => $item['features'],
                    'price' => $item['price'],
                    'compare_price' => $item['compare'],
                    'pages_count' => $item['pages'],
                    'revision_count' => $item['revision'],
                    'delivery_days' => $item['delivery'],
                    'include_domain' => $item['domain'],
                    'include_hosting' => $item['hosting'],
                    'include_maintenance' => $item['maintenance'],
                    'maintenance_months' => $item['maintenance_months'],
                    'is_featured' => $index === 1 || $index === 3,
                    'is_active' => true,
                    'order' => $index + 1,
                ]
            );
        }

        $this->command->info('✅ 6 web packages seeded.');
    }
}