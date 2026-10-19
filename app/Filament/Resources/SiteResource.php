<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteResource\Pages;
use App\Models\Site;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationLabel = 'Site';

    protected static ?string $modelLabel = 'Site';

    protected static ?string $pluralModelLabel = 'Sites';

    protected static ?string $navigationGroup = '🏢 Multi-Site';

    protected static ?int $navigationSort = 1;

    // Site = tenant, tidak scoped ke tenant
    protected static bool $isScopedToTenant = false;

    // ─── Filter: cuma muncul di tenant company ───
    public static function shouldRegisterNavigation(): bool
    {
        return filament()->getTenant()?->key === 'company';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Site')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Key')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Identifier unik. Contoh: company, website, rental, video, printing'),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Site')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('path')
                            ->label('Path')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Path URL. Contoh: /, layanan/website, layanan/rental'),

                        Forms\Components\TextInput::make('domain')
                            ->label('Domain')
                            ->maxLength(255)
                            ->helperText('Kosongkan jika pakai path-based routing'),

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
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),

                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('path')
                    ->label('Path')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\TextColumn::make('domain')
                    ->label('Domain')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('pages_count')
                    ->label('Halaman')
                    ->counts('pages')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('id', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }
}