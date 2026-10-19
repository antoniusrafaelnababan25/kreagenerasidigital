<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationLabel = 'Menu Navigasi';

    protected static ?string $modelLabel = 'Menu';

    protected static ?string $pluralModelLabel = 'Menu Navigasi';

    protected static ?string $navigationGroup = '🏢 Multi-Site';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Menu')
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('Menu Induk (opsional)')
                            ->relationship('parent', 'label')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('label')
                            ->label('Label Menu')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Beranda'),

                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('/'),

                        Forms\Components\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),

                        Forms\Components\Toggle::make('target_blank')
                            ->label('Buka di Tab Baru')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\TextColumn::make('parent.label')
                    ->label('Induk')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('order', 'asc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}