<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeoMetaResource\Pages;
use App\Models\SeoMeta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SeoMetaResource extends Resource
{
    protected static ?string $model = SeoMeta::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'SEO Meta';

    protected static ?string $modelLabel = 'SEO Meta';

    protected static ?string $pluralModelLabel = 'SEO Meta';

    protected static ?string $navigationGroup = '🏢 Multi-Site';

    protected static ?int $navigationSort = 6;

    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('SEO Meta')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60)
                            ->helperText('Maksimal 60 karakter'),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('Maksimal 160 karakter'),

                        Forms\Components\TextInput::make('canonical_url')
                            ->label('Canonical URL')
                            ->url()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('focus_keyword')
                            ->label('Focus Keyword')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Open Graph')
                    ->schema([
                        Forms\Components\TextInput::make('og_title')
                            ->label('OG Title')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('og_description')
                            ->label('OG Description')
                            ->rows(2),

                        Forms\Components\FileUpload::make('og_image')
                            ->label('OG Image')
                            ->image()
                            ->directory('seo/og-images')
                            ->maxSize(2048),

                        Forms\Components\Select::make('twitter_card')
                            ->label('Twitter Card')
                            ->options([
                                'summary' => 'Summary',
                                'summary_large_image' => 'Summary Large Image',
                            ])
                            ->default('summary_large_image'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Indexing')
                    ->schema([
                        Forms\Components\Toggle::make('is_indexable')
                            ->label('Indexable')
                            ->default(true),

                        Forms\Components\Toggle::make('is_followable')
                            ->label('Followable')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('seoable_type')
                    ->label('Tipe')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => class_basename($state)),

                Tables\Columns\TextColumn::make('seoable_id')
                    ->label('ID Entitas'),

                Tables\Columns\TextColumn::make('meta_title')
                    ->label('Meta Title')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('focus_keyword')
                    ->label('Focus Keyword')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\IconColumn::make('is_indexable')
                    ->label('Index')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_indexable')
                    ->label('Indexable'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeoMetas::route('/'),
            'create' => Pages\CreateSeoMeta::route('/create'),
            'edit' => Pages\EditSeoMeta::route('/{record}/edit'),
        ];
    }
}