<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $sites = [
            ['key' => 'company', 'name' => 'KREA Company Profile', 'path' => '/', 'is_active' => true],
            ['key' => 'website', 'name' => 'KREA Jasa Website', 'path' => 'layanan/website', 'is_active' => true],
            ['key' => 'rental', 'name' => 'KREA Rental Alat Camping', 'path' => 'layanan/rental', 'is_active' => true],
            ['key' => 'video', 'name' => 'KREA Videografi', 'path' => 'layanan/video', 'is_active' => true],
            ['key' => 'printing', 'name' => 'KREA Custom Printing', 'path' => 'layanan/printing', 'is_active' => true],
        ];

        foreach ($sites as $site) {
            Site::updateOrCreate(['key' => $site['key']], $site);
        }

        $this->command->info('✅ 5 sites seeded.');
    }
}