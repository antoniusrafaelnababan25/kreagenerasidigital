<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            'company' => [
                'primary_color' => '#1E40AF',
                'secondary_color' => '#7C3AED',
                'accent_color' => '#F59E0B',
                'font_family' => 'Inter',
                'heading_font' => 'Poppins',
                'hero_gradient' => 'from-blue-900 to-purple-900',
            ],
            'website' => [
                'primary_color' => '#0891B2',
                'secondary_color' => '#2563EB',
                'accent_color' => '#06B6D4',
                'font_family' => 'Inter',
                'heading_font' => 'Inter',
                'hero_gradient' => 'from-cyan-500 to-blue-700',
            ],
            'rental' => [
                'primary_color' => '#15803D',
                'secondary_color' => '#047857',
                'accent_color' => '#84CC16',
                'font_family' => 'Inter',
                'heading_font' => 'Poppins',
                'hero_gradient' => 'from-green-700 to-emerald-900',
            ],
            'video' => [
                'primary_color' => '#F59E0B',
                'secondary_color' => '#DC2626',
                'accent_color' => '#FBBF24',
                'font_family' => 'Inter',
                'heading_font' => 'Playfair Display',
                'hero_gradient' => 'from-gray-900 to-yellow-700',
            ],
            'printing' => [
                'primary_color' => '#EC4899',
                'secondary_color' => '#F97316',
                'accent_color' => '#FBBF24',
                'font_family' => 'Inter',
                'heading_font' => 'Poppins',
                'hero_gradient' => 'from-pink-500 to-orange-500',
            ],
        ];

        foreach ($themes as $siteKey => $themeData) {
            $site = Site::where('key', $siteKey)->first();
            if ($site) {
                Theme::updateOrCreate(
                    ['site_id' => $site->id],
                    array_merge($themeData, ['site_id' => $site->id])
                );
            }
        }

        $this->command->info('✅ 5 themes seeded.');
    }
}