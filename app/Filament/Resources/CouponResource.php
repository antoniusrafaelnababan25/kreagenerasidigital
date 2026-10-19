<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Kupon';

    protected static ?string $modelLabel = 'Kupon';

    protected static ?string $pluralModelLabel = 'Kupon';

    protected static ?string $navigationGroup = '💰 Keuangan & Project';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kupon')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Kupon')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Contoh: DISKON10, NEWYEAR2026'),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Kupon')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(2)
                            ->columnSpreadFull(),

                        Forms\Components\Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'fixed' => 'Nominal Tetap',
                                'percentage' => 'Persentase',
                            ])
                            ->default('fixed')
                            ->required(),

                        Forms\Components\TextInput::make('value')
                            ->label('Nilai')
                            ->numeric()
                            ->required()
                            ->helperText('Untuk persentase, isi angka saja (contoh: 10 untuk 10%)'),

                        Forms\Components\TextInput::make('min_purchase')
                            ->label('Minimal Pembelian')
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('max_discount')
                            ->label('Maksimal Diskon')
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('usage_limit')
                            ->label('Batas Penggunaan')
                            ->numeric(),

                        Forms\Components\TextInput::make('usage_limit_per_user')
                            ->label('Batas per User')
                            ->numeric(),

                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Mulai Berlaku'),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Berakhir'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => $state === 'percentage' ? 'Persentase' : 'Nominal'),

                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->formatStateUsing(fn ($state, $record) => $record->type === 'percentage' ? $state . '%' : 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Dipakai')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state, $record) => $record->usage_limit ? $state . '/' . $record->usage_limit : $state),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Berakhir')
                    ->dateTime('d M Y')
                    ->placeholder('Tidak ada')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'fixed' => 'Nominal Tetap',
                        'percentage' => 'Persentase',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}