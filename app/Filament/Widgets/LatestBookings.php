<?php

namespace App\Filament\Widgets;

use App\Models\RentalBooking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestBookings extends BaseWidget
{
    protected static ?string $heading = 'Booking Rental Terbaru';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return filament()->getTenant()?->key === 'rental';
    }

    public function table(Table $table): Table
    {
        $tenant = filament()->getTenant();

        return $table
            ->query(
                RentalBooking::query()
                    ->when($tenant, fn ($q) => $q->where('site_id', $tenant->id))
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('booking_number')
                    ->label('No. Booking')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Penyewa'),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y'),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d M Y'),

                Tables\Columns\TextColumn::make('total_days')
                    ->label('Hari')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'picked_up' => 'primary',
                        'returned' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }
}