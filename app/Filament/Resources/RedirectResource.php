<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RedirectResource\Pages;
use App\Models\Redirect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-circle';

    protected static ?string $navigationLabel = 'Redirect';

    protected static ?string $modelLabel = 'Redirect';

    protected static ?string $pluralModelLabel = 'Redirects';

    protected static ?string $navigationGroup = '🏢 Multi-Site';

    protected static ?int $navigationSort = 7;

    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Redirect')
                    ->schema([
                        Forms\Components\TextInput::make('from_url')
                            ->label('Dari URL')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Contoh: /halaman-lama'),

                        Forms\Components\TextInput::make('to_url')
                            ->label('Ke URL')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Contoh: /halaman-baru'),

                        Forms\Components\Select::make('status_code')
                            ->label('Status Code')
                            ->options([
                                301 => '301 - Permanent',
                                302 => '302 - Temporary',
                                307 => '307 - Temporary (Method Preserved)',
                                308 => '308 - Permanent (Method Preserved)',
                            ])
                            ->default(301)
                            ->required(),

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
                Tables\Columns\TextColumn::make('from_url')
                    ->label('Dari')
                    ->badge()
                    ->color('danger')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-right')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('to_url')
                    ->label('Ke')
                    ->badge()
                    ->color('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status_code')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        301, 308 => 'danger',
                        302, 307 => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('hit_count')
                    ->label('Hits')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_hit_at')
                    ->label('Terakhir Hit')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum pernah')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),

                Tables\Filters\SelectFilter::make('status_code')
                    ->label('Status Code')
                    ->options([
                        301 => '301',
                        302 => '302',
                        307 => '307',
                        308 => '308',
                    ]),
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
            ->defaultSort('hit_count', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRedirects::route('/'),
            'create' => Pages\CreateRedirect::route('/create'),
            'edit' => Pages\EditRedirect::route('/{record}/edit'),
        ];
    }
}