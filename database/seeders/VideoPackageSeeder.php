<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\VideoPackage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VideoPackageSeeder extends Seeder
{
    public function run(): void
    {
        $site = Site::where('key', 'video')->first();
        if (! $site) return;

        $packages = [
            [
                'name' => 'Paket Wedding Silver',
                'category' => 'wedding',
                'price' => 3500000,
                'compare' => 4500000,
                'duration' => '1 hari shooting',
                'crew' => 2,
                'output' => 'MP4 Full HD',
                'revision' => 1,
                'delivery' => 14,
                'features' => "1 kamera profesional\nHighlight video 3-5 menit\nVideo full ceremony 30-60 menit\n1x revisi\nMusik copyright free\nFile via Google Drive",
            ],
            [
                'name' => 'Paket Wedding Gold',
                'category' => 'wedding',
                'price' => 6500000,
                'compare' => 8000000,
                'duration' => '2 hari shooting',
                'crew' => 4,
                'output' => 'MP4 4K',
                'revision' => 3,
                'delivery' => 21,
                'features' => "2 kamera profesional\nDrone aerial shot\nHighlight video 5-7 menit\nVideo full ceremony\nSame day edit\n3x revisi\nMusik custom\nFlashdisk eksklusif",
            ],
            [
                'name' => 'Paket Wedding Platinum',
                'category' => 'wedding',
                'price' => 12000000,
                'compare' => null,
                'duration' => '3 hari shooting',
                'crew' => 6,
                'output' => 'MP4 4K Cinematic',
                'revision' => 5,
                'delivery' => 30,
                'features' => "3 kamera sinematik\nDrone 4K\nPre-wedding video\nHighlight 7-10 menit\nVideo full ceremony\nSame day edit\n5x revisi\nColor grading profesional\nAlbum foto eksklusif\nFlashdisk premium",
            ],
            [
                'name' => 'Paket Company Profile Basic',
                'category' => 'corporate',
                'price' => 5000000,
                'compare' => 6500000,
                'duration' => '1 hari shooting',
                'crew' => 2,
                'output' => 'MP4 Full HD',
                'revision' => 2,
                'delivery' => 10,
                'features' => "1 kamera profesional\nVideo 3-5 menit\nInterview karyawan\nB-roll kantor\n2x revisi\nMusik copyright free",
            ],
            [
                'name' => 'Paket Company Profile Premium',
                'category' => 'corporate',
                'price' => 9500000,
                'compare' => 12000000,
                'duration' => '2 hari shooting',
                'crew' => 4,
                'output' => 'MP4 4K',
                'revision' => 3,
                'delivery' => 14,
                'features' => "2 kamera profesional\nDrone aerial\nVideo 5-7 menit\nInterview multiple\nMotion graphic\n3x revisi\nSubtitle Indonesia & English",
            ],
            [
                'name' => 'Paket Event Documentation',
                'category' => 'event',
                'price' => 3000000,
                'compare' => null,
                'duration' => '1 hari event',
                'crew' => 2,
                'output' => 'MP4 Full HD',
                'revision' => 2,
                'delivery' => 7,
                'features' => "1 kamera profesional\nHighlight event 3-5 menit\nFull documentation\n2x revisi",
            ],
            [
                'name' => 'Paket Konten Sosmed Basic',
                'category' => 'content',
                'price' => 2500000,
                'compare' => 3000000,
                'duration' => '1 hari shooting',
                'crew' => 1,
                'output' => 'MP4 Vertical 9:16',
                'revision' => 2,
                'delivery' => 7,
                'features' => "10 konten Reels/TikTok\nDurasi 15-60 detik\nEditing profesional\nCaption & hashtag\n2x revisi",
            ],
            [
                'name' => 'Paket Konten Sosmed Pro',
                'category' => 'content',
                'price' => 5000000,
                'compare' => 6500000,
                'duration' => '2 hari shooting',
                'crew' => 2,
                'output' => 'MP4 Vertical + Horizontal',
                'revision' => 3,
                'delivery' => 14,
                'features' => "25 konten Reels/TikTok\n5 konten YouTube\nThumbnail design\nCaption & hashtag\n3x revisi\nKonsultasi konten",
            ],
        ];

        foreach ($packages as $index => $item) {
            VideoPackage::updateOrCreate(
                [
                    'site_id' => $site->id,
                    'slug' => Str::slug($item['name']),
                ],
                [
                    'site_id' => $site->id,
                    'slug' => Str::slug($item['name']),
                    'name' => $item['name'],
                    'category' => $item['category'],
                    'description' => 'Paket ' . $item['name'] . ' dengan kualitas profesional.',
                    'features' => $item['features'],
                    'price' => $item['price'],
                    'compare_price' => $item['compare'],
                    'duration' => $item['duration'],
                    'crew_count' => $item['crew'],
                    'output_format' => $item['output'],
                    'revision_count' => $item['revision'],
                    'delivery_days' => $item['delivery'],
                    'is_featured' => $index === 0 || $index === 3,
                    'is_active' => true,
                    'order' => $index + 1,
                ]
            );
        }

        $this->command->info('✅ 8 video packages seeded.');
    }
}