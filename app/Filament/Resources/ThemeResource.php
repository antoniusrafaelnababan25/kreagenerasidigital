<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThemeResource\Pages;
use App\Models\Theme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ThemeResource extends Resource
{
    protected static ?string $model = Theme::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationLabel = 'Theme';

    protected static ?string $modelLabel = 'Theme';

    protected static ?string $pluralModelLabel = 'Themes';

    protected static ?string $navigationGroup = '🏢 Multi-Site';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Warna')
                    ->schema([
                        Forms\Components\ColorPicker::make('primary_color')
                            ->label('Warna Primary')
                            ->required()
                            ->default('#3B82F6'),

                        Forms\Components\ColorPicker::make('secondary_color')
                            ->label('Warna Secondary')
                            ->required()
                            ->default('#8B5CF6'),

                        Forms\Components\ColorPicker::make('accent_color')
                            ->label('Warna Accent')
                            ->required()
                            ->default('#F59E0B'),

                        Forms\Components\ColorPicker::make('background_color')
                            ->label('Warna Background')
                            ->required()
                            ->default('#FFFFFF'),

                        Forms\Components\ColorPicker::make('text_color')
                            ->label('Warna Text')
                            ->required()
                            ->default('#111827'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Font')
                    ->schema([
                        Forms\Components\Select::make('font_family')
                            ->label('Font Body')
                            ->options([
                                'Inter' => 'Inter',
                                'Poppins' => 'Poppins',
                                'Roboto' => 'Roboto',
                                'Open Sans' => 'Open Sans',
                                'Lato' => 'Lato',
                                'Nunito' => 'Nunito',
                            ])
                            ->default('Inter')
                            ->required(),

                        Forms\Components\Select::make('heading_font')
                            ->label('Font Heading')
                            ->options([
                                'Inter' => 'Inter',
                                'Poppins' => 'Poppins',
                                'Playfair Display' => 'Playfair Display',
                                'Montserrat' => 'Montserrat',
                                'Raleway' => 'Raleway',
                            ])
                            ->default('Poppins')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Lainnya')
                    ->schema([
                        Forms\Components\TextInput::make('hero_gradient')
                            ->label('Hero Gradient (Tailwind class)')
                            ->maxLength(255)
                            ->helperText('Contoh: from-blue-900 to-purple-900'),

                        Forms\Components\FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->directory('themes/logos')
                            ->maxSize(2048),

                        Forms\Components\FileUpload::make('favicon_path')
                            ->label('Favicon')
                            ->image()
                            ->directory('themes/favicons')
                            ->maxSize(512),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('site.name')
                    ->label('Site')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ColorColumn::make('primary_color')
                    ->label('Primary'),

                Tables\Columns\ColorColumn::make('secondary_color')
                    ->label('Secondary'),

                Tables\Columns\ColorColumn::make('accent_color')
                    ->label('Accent'),

                Tables\Columns\TextColumn::make('font_family')
                    ->label('Font Body'),

                Tables\Columns\TextColumn::make('heading_font')
                    ->label('Font Heading'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListThemes::route('/'),
            'create' => Pages\CreateTheme::route('/create'),
            'edit' => Pages\EditTheme::route('/{record}/edit'),
        ];
    }
}