<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            'company' => [
                ['name' => 'Andi Wijaya', 'position' => 'CEO', 'company' => 'PT Maju Jaya', 'content' => 'KREA sangat profesional dan responsif. Website perusahaan kami jadi jauh lebih modern.', 'rating' => 5],
                ['name' => 'Rina Marlina', 'position' => 'Owner', 'company' => 'Toko Bunga Rina', 'content' => 'Pelayanan cepat, hasil memuaskan. Recommended!', 'rating' => 5],
                ['name' => 'Dedi Kurniawan', 'position' => 'Marketing Manager', 'company' => 'CV Sukses Abadi', 'content' => 'Tim KREA memahami kebutuhan kami dengan baik. Hasilnya melebihi ekspektasi.', 'rating' => 5],
            ],
            'website' => [
                ['name' => 'Budi Santoso', 'position' => 'Founder', 'company' => 'StartupKita', 'content' => 'Website kami sekarang loading cepat dan tampilannya modern. Traffic naik 3x lipat!', 'rating' => 5],
                ['name' => 'Maya Sari', 'position' => 'Owner', 'company' => 'Maya Fashion', 'content' => 'E-commerce yang dibuat KREA sangat membantu penjualan online kami.', 'rating' => 5],
                ['name' => 'Rudi Hartono', 'position' => 'Direktur', 'company' => 'PT Karya Mandiri', 'content' => 'Prosesnya transparan, komunikasi lancar, hasil memuaskan.', 'rating' => 5],
            ],
            'rental' => [
                ['name' => 'Fajar Pratama', 'position' => 'Ketua', 'company' => 'Komunitas Outdoor', 'content' => 'Alat campingnya lengkap dan berkualitas. Harga juga bersaing!', 'rating' => 5],
                ['name' => 'Sinta Dewi', 'position' => 'Mahasiswa', 'company' => 'Universitas Indonesia', 'content' => 'Sewa tenda untuk camping bareng teman-teman, prosesnya mudah dan cepat.', 'rating' => 4],
                ['name' => 'Agus Setiawan', 'position' => 'Pendaki', 'company' => 'Mapala', 'content' => 'Carrier dan sleeping bag-nya nyaman dipakai. Recommended untuk pendaki!', 'rating' => 5],
            ],
            'video' => [
                ['name' => 'Andi & Sari', 'position' => 'Pengantin', 'company' => '-', 'content' => 'Video wedding kami sangat indah dan sinematik. Terima kasih KREA!', 'rating' => 5],
                ['name' => 'PT Sejahtera', 'position' => 'HRD', 'company' => 'PT Sejahtera', 'content' => 'Company profile video kami sangat profesional. Membantu branding perusahaan.', 'rating' => 5],
                ['name' => 'Event Organizer XYZ', 'position' => 'Manager', 'company' => 'EO XYZ', 'content' => 'Dokumentasi event kami rapi dan cepat. Kerja sama yang menyenangkan.', 'rating' => 5],
            ],
            'printing' => [
                ['name' => 'Komunitas Motor', 'position' => 'Ketua', 'company' => 'MC Indonesia', 'content' => 'Kaos komunitas kami hasilnya bagus, sablon rapi dan tahan lama.', 'rating' => 5],
                ['name' => 'Sarah Amelia', 'position' => 'Owner', 'company' => 'Sarah Store', 'content' => 'Totebag custom dengan desain sendiri, hasilnya memuaskan!', 'rating' => 5],
                ['name' => 'Startup XYZ', 'position' => 'HRD', 'company' => 'Startup XYZ', 'content' => 'Hoodie perusahaan kami jadi favorit karyawan. Kualitas premium!', 'rating' => 5],
            ],
        ];

        foreach ($testimonials as $siteKey => $items) {
            $site = Site::where('key', $siteKey)->first();
            if (! $site) continue;

            foreach ($items as $item) {
                Testimonial::updateOrCreate(
                    [
                        'site_id' => $site->id,
                        'name' => $item['name'],
                    ],
                    [
                        'site_id' => $site->id,
                        'name' => $item['name'],
                        'position' => $item['position'],
                        'company' => $item['company'],
                        'content' => $item['content'],
                        'rating' => $item['rating'],
                        'service_type' => $siteKey,
                        'is_published' => true,
                        'is_featured' => $item['rating'] === 5,
                    ]
                );
            }
        }

        $this->command->info('✅ Testimonials seeded untuk 5 site.');
    }
}