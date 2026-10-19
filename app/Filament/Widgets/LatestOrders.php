<?php

namespace App\Filament\Widgets;

use App\Models\PrintOrder;
use App\Models\RentalBooking;
use App\Models\VideoOrder;
use App\Models\WebOrder;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends BaseWidget
{
    protected static ?string $heading = 'Order Terbaru';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $tenant = filament()->getTenant();
        $tenantKey = $tenant?->key;
        $tenantId = $tenant?->id;

        // Pilih model berdasarkan tenant
        $query = match ($tenantKey) {
            'website' => WebOrder::query(),
            'video' => VideoOrder::query(),
            'printing' => PrintOrder::query(),
            'rental' => RentalBooking::query(),
            default => WebOrder::query(),
        };

        $query->when($tenantId, fn ($q) => $q->where('site_id', $tenantId))
              ->latest()
              ->limit(5);

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Order')
                    ->badge()
                    ->color('primary')
                    ->default(fn ($record) => $record->booking_number ?? '-'),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Pelanggan'),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'completed', 'paid' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->since(),
            ])
            ->paginated(false);
    }
}