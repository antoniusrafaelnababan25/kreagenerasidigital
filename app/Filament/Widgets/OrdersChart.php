<?php

namespace App\Filament\Widgets;

use App\Models\PrintOrder;
use App\Models\RentalBooking;
use App\Models\VideoOrder;
use App\Models\WebOrder;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Order 6 Bulan Terakhir';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $tenant = filament()->getTenant();
        $tenantKey = $tenant?->key;
        $tenantId = $tenant?->id;

        $months = collect(range(5, 0))->map(function ($month) {
            return now()->subMonths($month)->format('Y-m');
        });

        $labels = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M Y'))->toArray();

        $data = $months->map(function ($month) use ($tenantKey, $tenantId) {
            $start = Carbon::parse($month . '-01')->startOfMonth();
            $end = Carbon::parse($month . '-01')->endOfMonth();

            $total = 0;

            if ($tenantKey === 'website' || $tenantKey === null) {
                $total += WebOrder::when($tenantId, fn ($q) => $q->where('site_id', $tenantId))
                    ->whereBetween('created_at', [$start, $end])->count();
            }

            if ($tenantKey === 'video' || $tenantKey === null) {
                $total += VideoOrder::when($tenantId, fn ($q) => $q->where('site_id', $tenantId))
                    ->whereBetween('created_at', [$start, $end])->count();
            }

            if ($tenantKey === 'printing' || $tenantKey === null) {
                $total += PrintOrder::when($tenantId, fn ($q) => $q->where('site_id', $tenantId))
                    ->whereBetween('created_at', [$start, $end])->count();
            }

            if ($tenantKey === 'rental' || $tenantKey === null) {
                $total += RentalBooking::when($tenantId, fn ($q) => $q->where('site_id', $tenantId))
                    ->whereBetween('created_at', [$start, $end])->count();
            }

            return $total;
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Order',
                    'data' => $data,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.5)',
                    'borderColor' => '#F59E0B',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}