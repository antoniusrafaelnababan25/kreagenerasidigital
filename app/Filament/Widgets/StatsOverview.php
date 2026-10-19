<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $tenant = filament()->getTenant();
        $tenantId = $tenant?->id;

        // Leads
        $totalLeads = Lead::when($tenantId, fn ($q) => $q->where('site_id', $tenantId))->count();
        $newLeads = Lead::when($tenantId, fn ($q) => $q->where('site_id', $tenantId))
            ->where('status', 'new')->count();
        $wonLeads = Lead::when($tenantId, fn ($q) => $q->where('site_id', $tenantId))
            ->where('status', 'won')->count();

        // Clients
        $totalClients = Client::count();
        $vipClients = Client::where('status', 'vip')->count();

        // Invoices
        $totalRevenue = Invoice::where('status', 'paid')->sum('total');
        $pendingInvoices = Invoice::whereIn('status', ['sent', 'viewed', 'partial', 'overdue'])->count();
        $overdueInvoices = Invoice::where('status', 'overdue')->count();

        // Projects
        $activeProjects = Project::whereIn('status', ['pending', 'in_progress', 'review'])->count();

        return [
            Stat::make('Total Leads', $totalLeads)
                ->description("$newLeads baru · $wonLeads won")
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary')
                ->chart($this->getLeadChart($tenantId)),

            Stat::make('Klien', $totalClients)
                ->description("$vipClients VIP")
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description("$pendingInvoices pending")
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Invoice Overdue', $overdueInvoices)
                ->description('Perlu tindakan')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($overdueInvoices > 0 ? 'danger' : 'gray'),

            Stat::make('Proyek Aktif', $activeProjects)
                ->description('Sedang berjalan')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('warning'),
        ];
    }

    protected function getLeadChart(?int $tenantId): array
    {
        $days = collect(range(6, 0))->map(function ($day) use ($tenantId) {
            return Lead::when($tenantId, fn ($q) => $q->where('site_id', $tenantId))
                ->whereDate('created_at', now()->subDays($day))
                ->count();
        })->toArray();

        return $days;
    }
}