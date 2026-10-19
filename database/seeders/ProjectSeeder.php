<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@kreagenerasidigital.co.id')->first();
        $budi = User::where('email', 'budi@kreagenerasidigital.co.id')->first();
        $siti = User::where('email', 'siti@kreagenerasidigital.co.id')->first();

        $websiteSite = Site::where('key', 'website')->first();
        $videoSite = Site::where('key', 'video')->first();
        $printingSite = Site::where('key', 'printing')->first();

        $projects = [
            [
                'name' => 'Website Company Profile PT Maju Jaya',
                'client_email' => 'andi@example.com',
                'site' => $websiteSite,
                'service' => 'website',
                'status' => 'in_progress',
                'priority' => 'high',
                'budget' => 8500000,
                'manager' => $budi,
                'start' => now()->subDays(20),
                'deadline' => now()->addDays(10),
                'progress' => 60,
                'tasks' => [
                    ['title' => 'Riset & Wireframe', 'status' => 'done', 'assigned' => $budi],
                    ['title' => 'Desain UI/UX', 'status' => 'done', 'assigned' => $siti],
                    ['title' => 'Development Frontend', 'status' => 'in_progress', 'assigned' => $budi],
                    ['title' => 'Development Backend', 'status' => 'in_progress', 'assigned' => $admin],
                    ['title' => 'Testing & QA', 'status' => 'todo', 'assigned' => $siti],
                    ['title' => 'Deploy & Launching', 'status' => 'todo', 'assigned' => $budi],
                ],
            ],
            [
                'name' => 'Video Wedding Andi & Sari',
                'client_email' => 'andi@example.com',
                'site' => $videoSite,
                'service' => 'video',
                'status' => 'review',
                'priority' => 'high',
                'budget' => 6500000,
                'manager' => $siti,
                'start' => now()->subDays(30),
                'deadline' => now()->addDays(5),
                'progress' => 80,
                'tasks' => [
                    ['title' => 'Pre-production Meeting', 'status' => 'done', 'assigned' => $siti],
                    ['title' => 'Shooting Day 1', 'status' => 'done', 'assigned' => $siti],
                    ['title' => 'Shooting Day 2', 'status' => 'done', 'assigned' => $siti],
                    ['title' => 'Editing Rough Cut', 'status' => 'done', 'assigned' => $siti],
                    ['title' => 'Color Grading', 'status' => 'in_progress', 'assigned' => $siti],
                    ['title' => 'Final Review', 'status' => 'todo', 'assigned' => $admin],
                ],
            ],
            [
                'name' => 'Kaos Komunitas Motor 50 pcs',
                'client_email' => 'eko@example.com',
                'site' => $printingSite,
                'service' => 'printing',
                'status' => 'completed',
                'priority' => 'medium',
                'budget' => 3750000,
                'manager' => $budi,
                'start' => now()->subDays(15),
                'deadline' => now()->subDays(3),
                'progress' => 100,
                'tasks' => [
                    ['title' => 'Konfirmasi Desain', 'status' => 'done', 'assigned' => $budi],
                    ['title' => 'Approval Klien', 'status' => 'done', 'assigned' => $budi],
                    ['title' => 'Produksi Cetak', 'status' => 'done', 'assigned' => $siti],
                    ['title' => 'Quality Check', 'status' => 'done', 'assigned' => $siti],
                    ['title' => 'Pengiriman', 'status' => 'done', 'assigned' => $budi],
                ],
            ],
            [
                'name' => 'E-Commerce Toko Bunga Rina',
                'client_email' => 'rina@example.com',
                'site' => $websiteSite,
                'service' => 'website',
                'status' => 'pending',
                'priority' => 'medium',
                'budget' => 12000000,
                'manager' => $admin,
                'start' => now()->addDays(3),
                'deadline' => now()->addDays(45),
                'progress' => 0,
                'tasks' => [
                    ['title' => 'Kick-off Meeting', 'status' => 'todo', 'assigned' => $admin],
                    ['title' => 'Riset Kompetitor', 'status' => 'todo', 'assigned' => $budi],
                    ['title' => 'Desain UI/UX', 'status' => 'todo', 'assigned' => $siti],
                    ['title' => 'Development', 'status' => 'todo', 'assigned' => $budi],
                    ['title' => 'Integrasi Payment', 'status' => 'todo', 'assigned' => $admin],
                    ['title' => 'Testing & Launching', 'status' => 'todo', 'assigned' => $admin],
                ],
            ],
            [
                'name' => 'Company Profile Video Startup XYZ',
                'client_email' => 'nadia@example.com',
                'site' => $videoSite,
                'service' => 'video',
                'status' => 'in_progress',
                'priority' => 'high',
                'budget' => 5000000,
                'manager' => $siti,
                'start' => now()->subDays(7),
                'deadline' => now()->addDays(14),
                'progress' => 30,
                'tasks' => [
                    ['title' => 'Konsep & Storyboard', 'status' => 'done', 'assigned' => $siti],
                    ['title' => 'Shooting Hari 1', 'status' => 'in_progress', 'assigned' => $siti],
                    ['title' => 'Shooting Hari 2', 'status' => 'todo', 'assigned' => $siti],
                    ['title' => 'Editing', 'status' => 'todo', 'assigned' => $siti],
                    ['title' => 'Review Klien', 'status' => 'todo', 'assigned' => $admin],
                ],
            ],
        ];

        foreach ($projects as $data) {
            $client = Client::where('email', $data['client_email'])->first();

            $project = Project::updateOrCreate(
                ['project_number' => 'PRJ-' . date('Ymd') . '-' . strtoupper(substr(md5($data['name']), 0, 6))],
                [
                    'site_id' => $data['site']?->id,
                    'client_id' => $client?->id,
                    'name' => $data['name'],
                    'description' => 'Proyek ' . $data['name'],
                    'service_type' => $data['service'],
                    'status' => $data['status'],
                    'priority' => $data['priority'],
                    'start_date' => $data['start'],
                    'deadline' => $data['deadline'],
                    'completed_at' => $data['status'] === 'completed' ? now()->subDays(3) : null,
                    'budget' => $data['budget'],
                    'actual_cost' => $data['budget'] * 0.6,
                    'billed_amount' => $data['status'] === 'completed' ? $data['budget'] : $data['budget'] * 0.5,
                    'progress' => $data['progress'],
                    'manager_id' => $data['manager']?->id,
                    'team_members' => [$budi?->id, $siti?->id],
                ]
            );

            // Buat tasks
            foreach ($data['tasks'] as $index => $task) {
                ProjectTask::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'title' => $task['title'],
                    ],
                    [
                        'project_id' => $project->id,
                        'assigned_to' => $task['assigned']?->id,
                        'title' => $task['title'],
                        'description' => null,
                        'status' => $task['status'],
                        'priority' => $data['priority'],
                        'order' => $index + 1,
                        'due_date' => $data['deadline'],
                        'completed_at' => $task['status'] === 'done' ? now()->subDays(rand(1, 5)) : null,
                        'estimated_hours' => rand(4, 16),
                        'actual_hours' => $task['status'] === 'done' ? rand(4, 20) : null,
                    ]
                );
            }
        }

        $this->command->info('✅ 5 projects + tasks seeded.');
    }
}