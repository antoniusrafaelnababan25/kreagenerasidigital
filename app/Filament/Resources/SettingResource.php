<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Setting';

    protected static ?string $modelLabel = 'Setting';

    protected static ?string $pluralModelLabel = 'Settings';

    protected static ?string $navigationGroup = '🏢 Multi-Site';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Setting')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Key')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Contoh: company_name, company_email, whatsapp_number'),

                        Forms\Components\Textarea::make('value')
                            ->label('Value')
                            ->rows(3),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
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
            ->defaultSort('key', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}