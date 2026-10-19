<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalItemResource\Pages;
use App\Models\RentalItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class RentalItemResource extends Resource
{
    protected static ?string $model = RentalItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Alat Rental';

    protected static ?string $modelLabel = 'Alat Rental';

    protected static ?string $pluralModelLabel = 'Alat Rental';

    protected static ?string $navigationGroup = '🛒 Katalog';

    protected static ?int $navigationSort = 1;

    // ─── Filter: cuma muncul di tenant rental ───
    public static function shouldRegisterNavigation(): bool
    {
        return filament()->getTenant()?->key === 'rental';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Alat')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Alat')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('brand')
                            ->label('Merek')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('specification')
                            ->label('Spesifikasi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->image()
                            ->directory('rental/items')
                            ->maxSize(2048),

                        Forms\Components\TextInput::make('weight')
                            ->label('Berat (gram)')
                            ->numeric(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Harga & Stok')
                    ->schema([
                        Forms\Components\TextInput::make('price_per_day')
                            ->label('Harga per Hari')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        Forms\Components\TextInput::make('price_per_week')
                            ->label('Harga per Minggu')
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('deposit')
                            ->label('Deposit')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('stock_total')
                            ->label('Total Stok')
                            ->numeric()
                            ->default(0),

                        Forms\Components\TextInput::make('stock_available')
                            ->label('Stok Tersedia')
                            ->numeric()
                            ->default(0),

                        Forms\Components\TextInput::make('stock_maintenance')
                            ->label('Stok Maintenance')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Select::make('condition')
                            ->label('Kondisi')
                            ->options([
                                'good' => 'Baik',
                                'fair' => 'Cukup',
                                'damaged' => 'Rusak',
                            ])
                            ->default('good'),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'available' => 'Tersedia',
                                'rented' => 'Disewa',
                                'maintenance' => 'Maintenance',
                                'retired' => 'Pensiun',
                            ])
                            ->default('available'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->square()
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('brand')
                    ->label('Merek')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('price_per_day')
                    ->label('Harga/Hari')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock_available')
                    ->label('Tersedia')
                    ->badge()
                    ->color(fn ($state) => $state > 5 ? 'success' : ($state > 0 ? 'warning' : 'danger')),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'available' => 'success',
                        'rented' => 'info',
                        'maintenance' => 'warning',
                        'retired' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'available' => 'Tersedia',
                        'rented' => 'Disewa',
                        'maintenance' => 'Maintenance',
                        'retired' => 'Pensiun',
                        default => $state,
                    }),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'rented' => 'Disewa',
                        'maintenance' => 'Maintenance',
                        'retired' => 'Pensiun',
                    ]),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
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
            ->defaultSort('is_featured', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentalItems::route('/'),
            'create' => Pages\CreateRentalItem::route('/create'),
            'edit' => Pages\EditRentalItem::route('/{record}/edit'),
        ];
    }
}