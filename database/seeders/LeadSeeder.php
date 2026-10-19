<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@kreagenerasidigital.co.id')->first();
        $websiteSite = Site::where('key', 'website')->first();
        $rentalSite = Site::where('key', 'rental')->first();
        $videoSite = Site::where('key', 'video')->first();
        $printingSite = Site::where('key', 'printing')->first();

        $leads = [
            ['name' => 'Hendra Gunawan', 'email' => 'hendra@example.com', 'phone' => '081234568001', 'company' => 'PT Hendra Jaya', 'service' => 'website', 'site' => $websiteSite, 'source' => 'website', 'status' => 'new', 'priority' => 'high', 'value' => 8500000, 'message' => 'Butuh website company profile untuk perusahaan kami.'],
            ['name' => 'Lina Marlina', 'email' => 'lina@example.com', 'phone' => '081234568002', 'company' => 'Lina Catering', 'service' => 'website', 'site' => $websiteSite, 'source' => 'instagram', 'status' => 'contacted', 'priority' => 'medium', 'value' => 2500000, 'message' => 'Mau bikin landing page untuk catering.'],
            ['name' => 'Bambang Sutrisno', 'email' => 'bambang@example.com', 'phone' => '081234568003', 'company' => 'Komunitas Trail', 'service' => 'rental', 'site' => $rentalSite, 'source' => 'whatsapp', 'status' => 'qualified', 'priority' => 'high', 'value' => 1500000, 'message' => 'Sewa 20 tenda untuk event komunitas.'],
            ['name' => 'Citra Kirana', 'email' => 'citra@example.com', 'phone' => '081234568004', 'company' => null, 'service' => 'rental', 'site' => $rentalSite, 'source' => 'website', 'status' => 'new', 'priority' => 'low', 'value' => 300000, 'message' => 'Sewa alat camping untuk 4 orang.'],
            ['name' => 'Rizky Pratama', 'email' => 'rizky@example.com', 'phone' => '081234568005', 'company' => 'Wedding Organizer', 'service' => 'video', 'site' => $videoSite, 'source' => 'referral', 'status' => 'proposal', 'priority' => 'high', 'value' => 6500000, 'message' => 'Butuh videografer wedding untuk klien kami.'],
            ['name' => 'Nadia Safira', 'email' => 'nadia@example.com', 'phone' => '081234568006', 'company' => 'Startup XYZ', 'service' => 'video', 'site' => $videoSite, 'source' => 'website', 'status' => 'contacted', 'priority' => 'medium', 'value' => 5000000, 'message' => 'Company profile video untuk startup kami.'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko@example.com', 'phone' => '081234568007', 'company' => 'Komunitas Motor', 'service' => 'printing', 'site' => $printingSite, 'source' => 'instagram', 'status' => 'qualified', 'priority' => 'high', 'value' => 3750000, 'message' => 'Order 50 kaos komunitas.'],
            ['name' => 'Putri Amelia', 'email' => 'putri@example.com', 'phone' => '081234568008', 'company' => 'SMA Negeri 1', 'service' => 'printing', 'site' => $printingSite, 'source' => 'whatsapp', 'status' => 'new', 'priority' => 'medium', 'value' => 2500000, 'message' => 'Order 100 totebag untuk event sekolah.'],
            ['name' => 'Yudi Setiawan', 'email' => 'yudi@example.com', 'phone' => '081234568009', 'company' => 'PT Yudi Sejahtera', 'service' => 'website', 'site' => $websiteSite, 'source' => 'website', 'status' => 'won', 'priority' => 'high', 'value' => 12000000, 'message' => 'E-commerce untuk toko online kami.'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'phone' => '081234568010', 'company' => null, 'service' => 'rental', 'site' => $rentalSite, 'source' => 'website', 'status' => 'lost', 'priority' => 'low', 'value' => 200000, 'message' => 'Tanya sewa tenda, tapi batal.'],
        ];

        foreach ($leads as $index => $lead) {
            $createdLead = Lead::updateOrCreate(
                ['email' => $lead['email']],
                [
                    'site_id' => $lead['site']?->id,
                    'name' => $lead['name'],
                    'email' => $lead['email'],
                    'phone' => $lead['phone'],
                    'company' => $lead['company'],
                    'service_type' => $lead['service'],
                    'message' => $lead['message'],
                    'source' => $lead['source'],
                    'status' => $lead['status'],
                    'priority' => $lead['priority'],
                    'estimated_value' => $lead['value'],
                    'assigned_to' => $admin?->id,
                    'contacted_at' => in_array($lead['status'], ['contacted', 'qualified', 'proposal', 'won', 'lost'])
                        ? now()->subDays(rand(1, 10))
                        : null,
                    'converted_at' => $lead['status'] === 'won' ? now()->subDays(2) : null,
                ]
            );

            // Tambah activity
            if (in_array($lead['status'], ['contacted', 'qualified', 'proposal', 'won'])) {
                LeadActivity::create([
                    'lead_id' => $createdLead->id,
                    'user_id' => $admin?->id,
                    'type' => 'whatsapp',
                    'description' => 'Follow-up via WhatsApp, respon positif.',
                    'activity_at' => now()->subDays(rand(1, 5)),
                ]);
            }

            if (in_array($lead['status'], ['qualified', 'proposal', 'won'])) {
                LeadActivity::create([
                    'lead_id' => $createdLead->id,
                    'user_id' => $admin?->id,
                    'type' => 'meeting',
                    'description' => 'Meeting online, diskusi detail proyek.',
                    'activity_at' => now()->subDays(rand(1, 3)),
                ]);
            }
        }

        $this->command->info('✅ 10 leads + activities seeded.');
    }
}