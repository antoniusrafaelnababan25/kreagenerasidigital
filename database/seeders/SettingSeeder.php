<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Site;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $commonSettings = [
            'company_name' => 'KREA Generasi Digital',
            'company_email' => 'hello@kreagenerasidigital.co.id',
            'company_phone' => '+62 812-3456-7890',
            'company_address' => 'Jl. Digital No. 123, Jakarta',
            'whatsapp_number' => '6281234567890',
            'instagram_url' => 'https://instagram.com/kreagenerasidigital',
            'facebook_url' => 'https://facebook.com/kreagenerasidigital',
            'youtube_url' => '',
            'tiktok_url' => '',
            'google_analytics_id' => '',
            'meta_title_default' => 'KREA Generasi Digital',
            'meta_description_default' => 'Solusi digital untuk bisnis Anda: Website, Videografi, Rental Alat Camping, dan Custom Printing.',
        ];

        foreach (Site::all() as $site) {
            foreach ($commonSettings as $key => $value) {
                Setting::updateOrCreate(
                    ['site_id' => $site->id, 'key' => $key],
                    ['value' => $value]
                );
            }
        }

        $this->command->info('✅ Settings seeded untuk ' . Site::count() . ' site.');
    }
}