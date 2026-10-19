<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebPackageResource\Pages;
use App\Models\WebPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class WebPackageResource extends Resource
{
    protected static ?string $model = WebPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';

    protected static ?string $navigationLabel = 'Paket Website';

    protected static ?string $modelLabel = 'Paket Website';

    protected static ?string $pluralModelLabel = 'Paket Website';

    protected static ?string $navigationGroup = '🎨 Paket Layanan';

    protected static ?int $navigationSort = 1;

    // ─── Filter: cuma muncul di tenant website ───
    public static function shouldRegisterNavigation(): bool
    {
        return filament()->getTenant()?->key === 'website';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Paket')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Paket')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'landing' => 'Landing Page',
                                'company' => 'Company Profile',
                                'ecommerce' => 'E-Commerce',
                                'custom' => 'Custom Web App',
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('features')
                            ->label('Fitur (pisahkan dengan enter)')
                            ->rows(5)
                            ->columnSpanFull()
                            ->helperText('Satu fitur per baris'),

                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Gambar')
                            ->image()
                            ->directory('web-packages')
                            ->maxSize(2048),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Harga & Detail')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        Forms\Components\TextInput::make('compare_price')
                            ->label('Harga Sebelum Diskon')
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('pages_count')
                            ->label('Jumlah Halaman')
                            ->numeric()
                            ->default(1),

                        Forms\Components\TextInput::make('revision_count')
                            ->label('Jumlah Revisi')
                            ->numeric()
                            ->default(1),

                        Forms\Components\TextInput::make('delivery_days')
                            ->label('Hari Pengerjaan')
                            ->numeric()
                            ->default(14),

                        Forms\Components\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Include')
                    ->schema([
                        Forms\Components\Toggle::make('include_domain')->label('Termasuk Domain'),
                        Forms\Components\Toggle::make('include_hosting')->label('Termasuk Hosting'),
                        Forms\Components\Toggle::make('include_maintenance')->label('Termasuk Maintenance'),
                        Forms\Components\TextInput::make('maintenance_months')->label('Bulan Maintenance')->numeric()->default(0),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\Toggle::make('is_featured')->label('Featured'),
                        Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pages_count')
                    ->label('Halaman')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('delivery_days')
                    ->label('Pengerjaan')
                    ->suffix(' hari'),

                Tables\Columns\IconColumn::make('is_featured')->label('Featured')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),

                Tables\Columns\TextColumn::make('order')->label('Urutan')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'landing' => 'Landing Page',
                        'company' => 'Company Profile',
                        'ecommerce' => 'E-Commerce',
                        'custom' => 'Custom Web App',
                    ]),

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
            ->defaultSort('order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebPackages::route('/'),
            'create' => Pages\CreateWebPackage::route('/create'),
            'edit' => Pages\EditWebPackage::route('/{record}/edit'),
        ];
    }
}