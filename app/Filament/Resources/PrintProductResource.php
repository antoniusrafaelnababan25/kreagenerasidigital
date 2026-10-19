<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrintProductResource\Pages;
use App\Models\PrintProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PrintProductResource extends Resource
{
    protected static ?string $model = PrintProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationLabel = 'Produk Printing';

    protected static ?string $modelLabel = 'Produk Printing';

    protected static ?string $pluralModelLabel = 'Produk Printing';

    protected static ?string $navigationGroup = '🛒 Katalog';

    protected static ?int $navigationSort = 2;

    // ─── Filter: cuma muncul di tenant printing ───
    public static function shouldRegisterNavigation(): bool
    {
        return filament()->getTenant()?->key === 'printing';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'apparel' => 'Apparel',
                                'merchandise' => 'Merchandise',
                                'packaging' => 'Packaging',
                                'large_format' => 'Large Format',
                            ])
                            ->default('apparel'),

                        Forms\Components\TextInput::make('material')
                            ->label('Bahan')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('print_technique')
                            ->label('Teknik Printing')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->image()
                            ->directory('print/products')
                            ->maxSize(2048),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Harga & Produksi')
                    ->schema([
                        Forms\Components\TextInput::make('base_price')
                            ->label('Harga Dasar')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        Forms\Components\TextInput::make('min_order_qty')
                            ->label('Minimal Order')
                            ->numeric()
                            ->default(1),

                        Forms\Components\TextInput::make('production_days')
                            ->label('Hari Produksi')
                            ->numeric()
                            ->default(3),

                        Forms\Components\TagsInput::make('available_sizes')
                            ->label('Ukuran Tersedia')
                            ->placeholder('Tambah ukuran (S, M, L, XL)')
                            ->separator(','),

                        Forms\Components\TagsInput::make('available_colors')
                            ->label('Warna Tersedia')
                            ->placeholder('Tambah warna (Putih, Hitam)')
                            ->separator(','),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured'),

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
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->square()
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('min_order_qty')
                    ->label('Min Order')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('production_days')
                    ->label('Produksi')
                    ->suffix(' hari'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'apparel' => 'Apparel',
                        'merchandise' => 'Merchandise',
                        'packaging' => 'Packaging',
                        'large_format' => 'Large Format',
                    ]),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
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
            ->defaultSort('is_featured', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrintProducts::route('/'),
            'create' => Pages\CreatePrintProduct::route('/create'),
            'edit' => Pages\EditPrintProduct::route('/{record}/edit'),
        ];
    }
}