<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // ─── Fondasi ───
            UserSeeder::class,
            SiteSeeder::class,
            ThemeSeeder::class,
            SettingSeeder::class,
            PageSeeder::class,

            // ─── Konten Statis ───
            PageSectionSeeder::class,
            FaqSeeder::class,
            TestimonialSeeder::class,
            PortfolioSeeder::class,

            // ─── Blog ───
            BlogCategorySeeder::class,
            BlogTagSeeder::class,
            BlogPostSeeder::class,

            // ─── Katalog ───
            CategorySeeder::class,
            ProductSeeder::class,
            RentalItemSeeder::class,
            PrintProductSeeder::class,

            // ─── Layanan ───
            VideoPackageSeeder::class,
            WebPackageSeeder::class,

            // ─── CRM & Transaksi ───
            ClientSeeder::class,
            LeadSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}