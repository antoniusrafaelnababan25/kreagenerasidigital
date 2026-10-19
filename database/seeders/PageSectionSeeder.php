<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\Site;
use Illuminate\Database\Seeder;

class PageSectionSeeder extends Seeder
{
    public function run(): void
    {
        // Section standar untuk halaman 'home' setiap site
        $homeSections = [
            'company' => [
                [
                    'type' => 'hero',
                    'order' => 1,
                    'content' => [
                        'title' => 'KREA Generasi Digital',
                        'subtitle' => 'Solusi Digital Terpadu untuk Bisnis Anda',
                        'description' => 'Kami menyediakan layanan website, videografi, rental alat camping, dan custom printing dengan kualitas terbaik.',
                        'cta_text' => 'Konsultasi Gratis',
                        'cta_url' => '/kontak',
                        'background_type' => 'gradient',
                    ],
                ],
                [
                    'type' => 'services',
                    'order' => 2,
                    'content' => [
                        'title' => 'Layanan Kami',
                        'subtitle' => 'Empat layanan utama untuk kebutuhan digital Anda',
                        'items' => [
                            ['title' => 'Jasa Website', 'desc' => 'Pembuatan website profesional', 'url' => '/layanan/website'],
                            ['title' => 'Rental Alat Camping', 'desc' => 'Sewa alat camping lengkap', 'url' => '/layanan/rental'],
                            ['title' => 'Videografi', 'desc' => 'Produksi video profesional', 'url' => '/layanan/video'],
                            ['title' => 'Custom Printing', 'desc' => 'Cetak custom kaos & merchandise', 'url' => '/layanan/printing'],
                        ],
                    ],
                ],
                [
                    'type' => 'portfolio',
                    'order' => 3,
                    'content' => [
                        'title' => 'Portfolio Terbaru',
                        'subtitle' => 'Karya-karya terbaik kami',
                        'limit' => 6,
                    ],
                ],
                [
                    'type' => 'testimonials',
                    'order' => 4,
                    'content' => [
                        'title' => 'Apa Kata Klien Kami',
                        'limit' => 3,
                    ],
                ],
                [
                    'type' => 'cta',
                    'order' => 5,
                    'content' => [
                        'title' => 'Siap Memulai Proyek Anda?',
                        'description' => 'Hubungi kami sekarang untuk konsultasi gratis.',
                        'cta_text' => 'Hubungi Kami',
                        'cta_url' => '/kontak',
                    ],
                ],
            ],
            'website' => [
                [
                    'type' => 'hero',
                    'order' => 1,
                    'content' => [
                        'title' => 'Jasa Pembuatan Website Profesional',
                        'subtitle' => 'Website Cepat, Modern, dan SEO-Friendly',
                        'description' => 'Dari landing page hingga e-commerce, kami siap membangun website impian Anda.',
                        'cta_text' => 'Lihat Paket',
                        'cta_url' => '/layanan/website/paket',
                    ],
                ],
                [
                    'type' => 'services',
                    'order' => 2,
                    'content' => [
                        'title' => 'Paket Website',
                        'items' => [
                            ['title' => 'Landing Page', 'desc' => 'Cocok untuk promosi produk'],
                            ['title' => 'Company Profile', 'desc' => 'Profil bisnis profesional'],
                            ['title' => 'E-Commerce', 'desc' => 'Toko online lengkap'],
                            ['title' => 'Custom', 'desc' => 'Sesuai kebutuhan Anda'],
                        ],
                    ],
                ],
                [
                    'type' => 'cta',
                    'order' => 3,
                    'content' => [
                        'title' => 'Konsultasi Gratis',
                        'description' => 'Diskusikan kebutuhan website Anda dengan tim kami.',
                        'cta_text' => 'Mulai Sekarang',
                        'cta_url' => '/layanan/website/order',
                    ],
                ],
            ],
            'rental' => [
                [
                    'type' => 'hero',
                    'order' => 1,
                    'content' => [
                        'title' => 'Rental Alat Camping Terlengkap',
                        'subtitle' => 'Sewa Mudah, Harga Terjangkau',
                        'description' => 'Tenda, sleeping bag, carrier, dan alat camping lainnya siap menemani petualangan Anda.',
                        'cta_text' => 'Lihat Katalog',
                        'cta_url' => '/layanan/rental/katalog',
                    ],
                ],
                [
                    'type' => 'services',
                    'order' => 2,
                    'content' => [
                        'title' => 'Kenapa Pilih Kami?',
                        'items' => [
                            ['title' => 'Alat Berkualitas', 'desc' => 'Semua alat terawat dan bersih'],
                            ['title' => 'Harga Terjangkau', 'desc' => 'Mulai dari Rp 25.000/hari'],
                            ['title' => 'Proses Cepat', 'desc' => 'Booking online, ambil kapan saja'],
                            ['title' => 'Deposit Ringan', 'desc' => 'Deposit terjangkau & transparan'],
                        ],
                    ],
                ],
                [
                    'type' => 'cta',
                    'order' => 3,
                    'content' => [
                        'title' => 'Siap Berpetualang?',
                        'description' => 'Booking alat camping sekarang, stok terbatas!',
                        'cta_text' => 'Booking Sekarang',
                        'cta_url' => '/layanan/rental/booking',
                    ],
                ],
            ],
            'video' => [
                [
                    'type' => 'hero',
                    'order' => 1,
                    'content' => [
                        'title' => 'Videografi Profesional',
                        'subtitle' => 'Abadikan Momen Berharga Anda',
                        'description' => 'Wedding, company profile, event, hingga konten sosial media.',
                        'cta_text' => 'Lihat Paket',
                        'cta_url' => '/layanan/video/paket',
                    ],
                ],
                [
                    'type' => 'services',
                    'order' => 2,
                    'content' => [
                        'title' => 'Layanan Video',
                        'items' => [
                            ['title' => 'Wedding', 'desc' => 'Dokumentasi pernikahan sinematik'],
                            ['title' => 'Company Profile', 'desc' => 'Profil perusahaan profesional'],
                            ['title' => 'Event', 'desc' => 'Seminar, konser, gathering'],
                            ['title' => 'Konten Sosmed', 'desc' => 'Reels, TikTok, YouTube'],
                        ],
                    ],
                ],
                [
                    'type' => 'cta',
                    'order' => 3,
                    'content' => [
                        'title' => 'Konsultasi Konsep Video',
                        'description' => 'Diskusikan ide video Anda dengan tim kami.',
                        'cta_text' => 'Hubungi Kami',
                        'cta_url' => '/layanan/video/order',
                    ],
                ],
            ],
            'printing' => [
                [
                    'type' => 'hero',
                    'order' => 1,
                    'content' => [
                        'title' => 'Custom Printing Berkualitas',
                        'subtitle' => 'Kaos, Hoodie, Totebag, Mug, dan Lainnya',
                        'description' => 'Cetak custom dengan bahan premium dan hasil tajam.',
                        'cta_text' => 'Lihat Produk',
                        'cta_url' => '/layanan/printing/produk',
                    ],
                ],
                [
                    'type' => 'services',
                    'order' => 2,
                    'content' => [
                        'title' => 'Produk Kami',
                        'items' => [
                            ['title' => 'Kaos Custom', 'desc' => 'Cotton combed 24s & 30s'],
                            ['title' => 'Hoodie', 'desc' => 'Fleece premium'],
                            ['title' => 'Totebag', 'desc' => 'Canvas tebal'],
                            ['title' => 'Mug', 'desc' => 'Keramik & magic mug'],
                        ],
                    ],
                ],
                [
                    'type' => 'cta',
                    'order' => 3,
                    'content' => [
                        'title' => 'Butuh Custom Printing?',
                        'description' => 'Upload desain Anda, kami cetak dengan kualitas terbaik.',
                        'cta_text' => 'Pesan Sekarang',
                        'cta_url' => '/layanan/printing/order',
                    ],
                ],
            ],
        ];

        foreach ($homeSections as $siteKey => $sections) {
            $site = Site::where('key', $siteKey)->first();
            if (! $site) continue;

            $page = Page::where('site_id', $site->id)->where('slug', 'home')->first();
            if (! $page) continue;

            foreach ($sections as $section) {
                PageSection::updateOrCreate(
                    [
                        'page_id' => $page->id,
                        'type' => $section['type'],
                    ],
                    [
                        'page_id' => $page->id,
                        'type' => $section['type'],
                        'order' => $section['order'],
                        'content' => $section['content'],
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('✅ Page sections seeded untuk 5 homepage.');
    }
}