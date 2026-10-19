<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Site;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            'company' => [
                ['question' => 'Apa saja layanan KREA Generasi Digital?', 'answer' => 'Kami menyediakan 4 layanan utama: Jasa Pembuatan Website, Rental Alat Camping, Videografi & Editing, dan Custom Printing.', 'order' => 1],
                ['question' => 'Bagaimana cara menghubungi KREA?', 'answer' => 'Anda bisa menghubungi kami via WhatsApp, email, atau mengisi formulir kontak di website kami.', 'order' => 2],
                ['question' => 'Apakah KREA melayani seluruh Indonesia?', 'answer' => 'Ya, untuk layanan website dan video kami melayani seluruh Indonesia secara online. Untuk rental dan printing, tersedia untuk area tertentu.', 'order' => 3],
            ],
            'website' => [
                ['question' => 'Berapa lama proses pembuatan website?', 'answer' => 'Landing page 3-7 hari, company profile 7-14 hari, e-commerce 14-30 hari, tergantung kompleksitas.', 'order' => 1],
                ['question' => 'Apakah termasuk domain dan hosting?', 'answer' => 'Tergantung paket. Paket tertentu sudah termasuk domain & hosting 1 tahun pertama.', 'order' => 2],
                ['question' => 'Berapa kali revisi yang diberikan?', 'answer' => 'Setiap paket memiliki jumlah revisi berbeda. Umumnya 2-5 kali revisi.', 'order' => 3],
                ['question' => 'Apakah website bisa saya kelola sendiri?', 'answer' => 'Ya, kami menyediakan admin panel agar Anda bisa update konten sendiri.', 'order' => 4],
                ['question' => 'Bagaimana sistem pembayarannya?', 'answer' => 'DP 50% di awal, 50% saat website selesai dan disetujui.', 'order' => 5],
            ],
            'rental' => [
                ['question' => 'Bagaimana cara menyewa alat camping?', 'answer' => 'Pilih alat di katalog, tentukan tanggal, isi form booking, dan lakukan pembayaran DP.', 'order' => 1],
                ['question' => 'Apakah perlu deposit?', 'answer' => 'Ya, deposit diperlukan sebagai jaminan. Deposit akan dikembalikan saat alat kembali dalam kondisi baik.', 'order' => 2],
                ['question' => 'Apakah bisa antar-jemput?', 'answer' => 'Ya, tersedia layanan antar-jemput dengan biaya tambahan sesuai jarak.', 'order' => 3],
                ['question' => 'Bagaimana jika alat rusak atau hilang?', 'answer' => 'Penyewa bertanggung jawab mengganti sesuai nilai alat atau biaya perbaikan.', 'order' => 4],
                ['question' => 'Berapa lama minimal sewa?', 'answer' => 'Minimal sewa 1 hari (24 jam).', 'order' => 5],
            ],
            'video' => [
                ['question' => 'Berapa lama proses editing video?', 'answer' => 'Tergantung kompleksitas. Umumnya 7-14 hari kerja setelah shooting.', 'order' => 1],
                ['question' => 'Apakah termasuk shooting dan editing?', 'answer' => 'Ya, semua paket sudah termasuk shooting dan editing.', 'order' => 2],
                ['question' => 'Berapa crew yang akan bertugas?', 'answer' => 'Tergantung paket. Paket basic 1-2 crew, paket premium bisa 4-6 crew.', 'order' => 3],
                ['question' => 'Apakah bisa request konsep khusus?', 'answer' => 'Tentu! Kami akan diskusikan konsep sesuai keinginan Anda.', 'order' => 4],
            ],
            'printing' => [
                ['question' => 'Berapa minimal order?', 'answer' => 'Minimal order 1 pcs untuk beberapa produk, namun ada juga yang minimal 12 pcs untuk harga lebih murah.', 'order' => 1],
                ['question' => 'Berapa lama proses produksi?', 'answer' => 'Umumnya 3-7 hari kerja tergantung jumlah dan tingkat kesulitan desain.', 'order' => 2],
                ['question' => 'Format file desain apa yang diterima?', 'answer' => 'Kami menerima format PNG, JPG, PDF, AI, dan PSD. Resolusi minimal 300 DPI.', 'order' => 3],
                ['question' => 'Apakah bisa minta sample dulu?', 'answer' => 'Ya, untuk order besar kami bisa buatkan sample terlebih dahulu (biaya terpisah).', 'order' => 4],
                ['question' => 'Bahan apa saja yang tersedia?', 'answer' => 'Cotton Combed 24s, 30s, Polyester, Fleece, Canvas, dan masih banyak lagi.', 'order' => 5],
            ],
        ];

        foreach ($faqs as $siteKey => $siteFaqs) {
            $site = Site::where('key', $siteKey)->first();
            if (! $site) continue;

            foreach ($siteFaqs as $faq) {
                Faq::updateOrCreate(
                    [
                        'site_id' => $site->id,
                        'question' => $faq['question'],
                    ],
                    [
                        'site_id' => $site->id,
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'category' => 'general',
                        'service_type' => $siteKey,
                        'order' => $faq['order'],
                        'is_published' => true,
                    ]
                );
            }
        }

        $this->command->info('✅ FAQs seeded untuk 5 site.');
    }
}