<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestLeads extends BaseWidget
{
    protected static ?string $heading = 'Lead Terbaru';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $tenant = filament()->getTenant();

        return $table
            ->query(
                Lead::query()
                    ->when($tenant, fn ($q) => $q->where('site_id', $tenant->id))
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->copyable(),

                Tables\Columns\TextColumn::make('service_type')
                    ->label('Layanan')
                    ->badge()
                    ->color('primary')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'new' => 'info',
                        'contacted' => 'warning',
                        'qualified' => 'primary',
                        'proposal' => 'success',
                        'won' => 'success',
                        'lost' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('estimated_value')
                    ->label('Estimasi')
                    ->money('IDR')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Masuk')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Lead $record) => route('filament.admin.resources.leads.edit', [
                        'tenant' => filament()->getTenant()->key,
                        'record' => $record->id,
                    ]))
                    ->icon('heroicon-o-eye'),
            ])
            ->paginated(false);
    }
}