<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@kreagenerasidigital.co.id')->first();

        $posts = [
            'company' => [
                [
                    'title' => '5 Alasan Bisnis Anda Wajib Punya Website di 2024',
                    'category' => 'Tips Digital',
                    'excerpt' => 'Website bukan sekadar pelengkap, tapi kebutuhan utama bisnis modern.',
                    'content' => "Di era digital seperti sekarang, website bukan lagi sekadar pelengkap, melainkan kebutuhan utama sebuah bisnis.\n\nBerikut 5 alasan mengapa bisnis Anda wajib punya website:\n\n1. Kredibilitas Bisnis\nWebsite profesional meningkatkan kepercayaan calon pelanggan.\n\n2. Jangkauan Pasar Luas\nDengan website, bisnis Anda bisa diakses 24/7 dari seluruh dunia.\n\n3. Media Marketing Efektif\nWebsite adalah pusat semua aktivitas marketing digital Anda.\n\n4. Kompetisi dengan Kompetitor\nJika kompetitor punya website dan Anda tidak, Anda akan tertinggal.\n\n5. Analisis Data\nDengan Google Analytics, Anda bisa tahu perilaku pengunjung.\n\nJadi, tunggu apa lagi? Hubungi KREA untuk konsultasi gratis!",
                    'tags' => ['KREA', 'Digital', 'Bisnis', 'UMKM', 'Tips'],
                ],
                [
                    'title' => 'Studi Kasus: Transformasi Digital PT Maju Jaya',
                    'category' => 'Studi Kasus',
                    'excerpt' => 'Bagaimana KREA membantu PT Maju Jaya bertransformasi digital.',
                    'content' => "PT Maju Jaya adalah perusahaan manufaktur yang sudah berdiri 20 tahun. Mereka ingin bertransformasi digital tapi bingung mulai dari mana.\n\nKREA membantu mereka dengan:\n\n1. Website company profile modern\n2. Video company profile\n3. Merchandise perusahaan\n\nHasilnya, dalam 6 bulan, leads online mereka naik 300%.\n\n\"KREA sangat profesional dan responsif. Website perusahaan kami jadi jauh lebih modern.\" - Andi Wijaya, CEO PT Maju Jaya.",
                    'tags' => ['KREA', 'Studi Kasus', 'Digital', 'Bisnis'],
                ],
                [
                    'title' => 'KREA Luncurkan Layanan Custom Printing',
                    'category' => 'Berita KREA',
                    'excerpt' => 'KREA resmi meluncurkan layanan custom printing untuk kaos, hoodie, dan merchandise.',
                    'content' => "KREA Generasi Digital dengan bangga meluncurkan layanan baru: Custom Printing.\n\nLayanan ini mencakup:\n- Kaos custom (Cotton Combed 24s, 30s)\n- Hoodie fleece premium\n- Totebag canvas\n- Mug keramik & magic\n- Dan lainnya\n\nDengan teknik printing modern seperti DTF, sublimasi, dan sablon manual, kami siap memenuhi kebutuhan custom printing Anda.\n\nHubungi kami untuk konsultasi gratis!",
                    'tags' => ['KREA', 'Berita', 'Digital'],
                ],
            ],
            'website' => [
                [
                    'title' => 'Panduan Lengkap SEO untuk Pemula di 2024',
                    'category' => 'SEO',
                    'excerpt' => 'Pelajari dasar-dasar SEO agar website Anda muncul di halaman 1 Google.',
                    'content' => "SEO (Search Engine Optimization) adalah seni membuat website Anda muncul di halaman 1 Google.\n\nBerikut panduan lengkap untuk pemula:\n\n1. Riset Keyword\nCari keyword yang relevan dengan bisnis Anda.\n\n2. Optimasi On-Page\n- Title tag yang menarik\n- Meta description yang jelas\n- Heading yang terstruktur (H1, H2, H3)\n\n3. Konten Berkualitas\nBuat konten yang menjawab pertanyaan pengguna.\n\n4. Backlink\nDapatkan link dari website berkualitas.\n\n5. Kecepatan Website\nPastikan website Anda loading cepat.\n\nButuh bantuan SEO? Hubungi KREA!",
                    'tags' => ['SEO', 'Tips', 'Digital'],
                ],
                [
                    'title' => 'Laravel vs WordPress: Mana yang Cocok untuk Bisnis Anda?',
                    'category' => 'Tips Website',
                    'excerpt' => 'Perbandingan lengkap Laravel vs WordPress untuk website bisnis.',
                    'content' => "Bingung mau pilih Laravel atau WordPress? Ini perbandingannya:\n\nLaravel:\n+ Fleksibel, scalable\n+ Cocok untuk aplikasi kompleks\n- Butuh developer\n\nWordPress:\n+ Mudah digunakan\n+ Banyak plugin\n- Kurang fleksibel untuk custom\n\nUntuk bisnis kecil-menengah, WordPress cukup. Untuk startup atau aplikasi kompleks, Laravel lebih baik.\n\nKREA menguasai keduanya. Konsultasi gratis sekarang!",
                    'tags' => ['Laravel', 'WordPress', 'Tips'],
                ],
                [
                    'title' => '10 Tips Membuat Website Cepat dan Ringan',
                    'category' => 'Tutorial',
                    'excerpt' => 'Website lambat = pengunjung kabur. Ini 10 tips membuat website cepat.',
                    'content' => "Kecepatan website sangat berpengaruh pada user experience dan SEO.\n\n10 tips membuat website cepat:\n\n1. Gunakan hosting berkualitas\n2. Optimasi gambar (compress)\n3. Gunakan CDN\n4. Minify CSS & JS\n5. Lazy loading gambar\n6. Cache website\n7. Gunakan font lokal\n8. Kurangi plugin\n9. Gunakan HTTP/2\n10. Rutin audit performa\n\nButuh website cepat? KREA siap membantu!",
                    'tags' => ['Tips', 'Cepat', 'Responsive'],
                ],
            ],
            'rental' => [
                [
                    'title' => 'Panduan Lengkap Camping untuk Pemula',
                    'category' => 'Tips Camping',
                    'excerpt' => 'Pertama kali camping? Ini panduan lengkapnya.',
                    'content' => "Camping pertama kali bisa bikin nervous. Tenang, ini panduannya:\n\n1. Pilih Lokasi yang Aman\nUntuk pemula, pilih camping ground resmi.\n\n2. Alat Wajib\n- Tenda\n- Sleeping bag\n- Matras\n- Headlamp\n- P3K\n\n3. Pakaian\nBawa pakaian hangat & tahan air.\n\n4. Makanan\nBawa makanan instan & air minum.\n\n5. Jangan Lupa\n- Power bank\n- Tisu basah\n- Kantong sampah\n\nSewa alat camping di KREA, harga terjangkau!",
                    'tags' => ['Camping', 'Tips', 'Outdoor'],
                ],
                [
                    'title' => 'Review Tenda Dome 4 Orang: Pilihan Terbaik 2024',
                    'category' => 'Review Alat',
                    'excerpt' => 'Review tenda dome 4 orang yang tersedia di KREA.',
                    'content' => "Tenda dome 4 orang adalah pilihan favorit pendaki.\n\nSpesifikasi:\n- Kapasitas: 4 orang\n- Berat: 3.5 kg\n- Bahan: Polyester 210T\n- Waterproof: 3000mm\n- Frame: Fiberglass\n\nKelebihan:\n+ Mudah dipasang\n+ Ringan\n+ Anti air\n\nKekurangan:\n- Kurang cocok untuk cuaca ekstrem\n\nHarga sewa: Rp 75.000/hari\n\nBooking sekarang di KREA!",
                    'tags' => ['Tenda', 'Review', 'Camping'],
                ],
                [
                    'title' => '5 Destinasi Camping Terbaik di Jawa Barat',
                    'category' => 'Destinasi',
                    'excerpt' => 'Rekomendasi tempat camping di Jawa Barat yang wajib dikunjungi.',
                    'content' => "Jawa Barat punya banyak spot camping keren. Ini 5 rekomendasi:\n\n1. Gunung Gede Pangrango\nPendakian klasik dengan pemandangan luar biasa.\n\n2. Ranca Upas\nCamping ground dengan deer park.\n\n3. Situ Gunung Suspension Bridge\nJembatan gantung terpanjang di Asia Tenggara.\n\n4. Gunung Papandayan\nCamping di kawah dengan view dramatis.\n\n5. Taman Nasional Halimun Salak\nHutan tropis dengan udara segar.\n\nSewa alat camping di KREA sebelum berangkat!",
                    'tags' => ['Camping', 'Destinasi', 'Gunung'],
                ],
            ],
            'video' => [
                [
                    'title' => 'Tips Membuat Video Wedding Sinematik',
                    'category' => 'Tips Videografi',
                    'excerpt' => 'Rahasia membuat video wedding yang sinematik dan berkesan.',
                    'content' => "Video wedding sinematik bukan sekadar rekaman, tapi karya seni.\n\nTips membuatnya:\n\n1. Storytelling\nBuat alur cerita yang menyentuh.\n\n2. Lighting\nManfaatkan golden hour untuk hasil maksimal.\n\n3. Musik\nPilih musik yang tepat untuk membangun emosi.\n\n4. Color Grading\nBeri warna yang konsisten dan sinematik.\n\n5. Drone Shot\nTambahkan aerial shot untuk kesan megah.\n\nButuh videografer wedding? KREA siap membantu!",
                    'tags' => ['Wedding', 'Sinematik', 'Tips'],
                ],
                [
                    'title' => 'Behind The Scene: Shooting Company Profile PT Sejahtera',
                    'category' => 'Behind The Scene',
                    'excerpt' => 'Cerita di balik layar shooting company profile PT Sejahtera.',
                    'content' => "Tim KREA baru saja menyelesaikan shooting company profile PT Sejahtera.\n\nProsesnya:\n\nDay 1: Pre-production meeting\n- Diskusi konsep\n- Survey lokasi\n- Storyboard\n\nDay 2-3: Shooting\n- Interview karyawan\n- B-roll kantor\n- Drone shot\n\nDay 4-7: Editing\n- Rough cut\n- Color grading\n- Sound design\n\nDay 8: Preview ke klien\n- Feedback\n- Revisi minor\n\nDay 10: Final delivery\n\nKlien puas dengan hasilnya!",
                    'tags' => ['Behind The Scene', 'Corporate', 'Video'],
                ],
                [
                    'title' => 'Review Kamera Sony A7 IV untuk Videografi',
                    'category' => 'Review Gear',
                    'excerpt' => 'Review lengkap Sony A7 IV untuk kebutuhan videografi profesional.',
                    'content' => "Sony A7 IV adalah kamera hybrid yang sangat populer untuk videografi.\n\nSpesifikasi:\n- Sensor: 33MP Full Frame\n- Video: 4K 60fps\n- Autofocus: Real-time Eye AF\n- Stabilization: 5-axis IBIS\n\nKelebihan:\n+ Autofocus sangat cepat\n+ Kualitas video luar biasa\n+ Cocok untuk foto & video\n\nKekurangan:\n- Overheating saat 4K 60fps lama\n- Harga cukup mahal\n\nTim KREA menggunakan kamera ini untuk produksi video profesional.",
                    'tags' => ['Kamera', 'Review', 'Videografi'],
                ],
            ],
            'printing' => [
                [
                    'title' => 'Perbedaan Cotton Combed 24s dan 30s',
                    'category' => 'Bahan & Teknik',
                    'excerpt' => 'Bingung pilih cotton combed 24s atau 30s? Ini perbedaannya.',
                    'content' => "Cotton Combed adalah bahan kaos paling populer di Indonesia.\n\nCotton Combed 24s:\n- Lebih tebal\n- Lebih tahan lama\n- Cocok untuk kaos komunitas\n- Sedikit kurang adem\n\nCotton Combed 30s:\n- Lebih tipis\n- Lebih adem\n- Cocok untuk kaos sehari-hari\n- Sedikit kurang tahan lama\n\nRekomendasi:\n- 24s untuk kaos komunitas & event\n- 30s untuk kaos harian\n\nOrder kaos custom di KREA!",
                    'tags' => ['Cotton Combed', 'Bahan', 'Kaos'],
                ],
                [
                    'title' => '5 Inspirasi Desain Kaos Komunitas Keren',
                    'category' => 'Inspirasi Desain',
                    'excerpt' => 'Bingung desain kaos komunitas? Ini 5 inspirasinya.',
                    'content' => "Kaos komunitas bukan sekadar identitas, tapi juga gaya.\n\n5 inspirasi desain:\n\n1. Minimalis dengan Logo Besar\nSimple tapi elegan.\n\n2. Typography Bold\nTeks besar dengan font tebal.\n\n3. Ilustrasi Custom\nGambar ilustrasi yang unik.\n\n4. Vintage Style\nKesan retro dengan warna earthy.\n\n5. Line Art\nGaris-garis simpel tapi berkesan.\n\nKonsultasi desain gratis di KREA!",
                    'tags' => ['Kaos', 'Desain', 'Inspirasi'],
                ],
                [
                    'title' => 'Teknik Sablon DTF vs Sublimasi vs Manual',
                    'category' => 'Bahan & Teknik',
                    'excerpt' => 'Perbandingan 3 teknik sablon populer untuk kaos custom.',
                    'content' => "Ada beberapa teknik sablon untuk kaos custom:\n\n1. DTF (Direct to Film)\n+ Full color\n+ Detail tinggi\n+ Cocok untuk desain kompleks\n- Harga lebih mahal\n\n2. Sublimasi\n+ Warna cerah\n+ Tidak mudah pudar\n- Hanya untuk bahan polyester\n\n3. Sablon Manual\n+ Harga ekonomis\n+ Tahan lama\n- Terbatas warna\n\nRekomendasi:\n- Desain kompleks: DTF\n- Kaos olahraga: Sublimasi\n- Kaos komunitas: Sablon manual\n\nKonsultasi gratis di KREA!",
                    'tags' => ['Sablon', 'DTF', 'Sublimasi'],
                ],
            ],
        ];

        foreach ($posts as $siteKey => $sitePosts) {
            $site = Site::where('key', $siteKey)->first();
            if (! $site) continue;

            foreach ($sitePosts as $index => $post) {
                $category = BlogCategory::where('site_id', $site->id)
                    ->where('name', $post['category'])
                    ->first();

                $blogPost = BlogPost::updateOrCreate(
                    [
                        'site_id' => $site->id,
                        'slug' => Str::slug($post['title']),
                    ],
                    [
                        'site_id' => $site->id,
                        'blog_category_id' => $category?->id,
                        'author_id' => $admin?->id,
                        'slug' => Str::slug($post['title']),
                        'title' => $post['title'],
                        'excerpt' => $post['excerpt'],
                        'content' => $post['content'],
                        'status' => 'published',
                        'published_at' => now()->subDays($index * 3),
                        'reading_time_minutes' => max(1, ceil(str_word_count($post['content']) / 200)),
                        'view_count' => rand(50, 500),
                        'is_featured' => $index === 0,
                        'allow_comments' => true,
                        'meta_title' => $post['title'] . ' | KREA',
                        'meta_description' => $post['excerpt'],
                        'focus_keyword' => strtolower($post['category']),
                        'is_indexable' => true,
                    ]
                );

                // Attach tags
                if (! empty($post['tags'])) {
                    $tagIds = BlogTag::where('site_id', $site->id)
                        ->whereIn('name', $post['tags'])
                        ->pluck('id')
                        ->toArray();

                    $blogPost->tags()->sync($tagIds);
                }
            }
        }

        $this->command->info('✅ Blog posts seeded untuk 5 site.');
    }
}