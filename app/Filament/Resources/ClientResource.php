<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Klien';

    protected static ?string $modelLabel = 'Klien';

    protected static ?string $pluralModelLabel = 'Klien';

    protected static ?string $navigationGroup = '👥 CRM & Marketing';

    protected static ?int $navigationSort = 3;

    // Client global, tidak scoped ke tenant
    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Klien')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(20),

                        Forms\Components\TextInput::make('company')
                            ->label('Perusahaan')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('city')
                            ->label('Kota')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('province')
                            ->label('Provinsi')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('postal_code')
                            ->label('Kode Pos')
                            ->maxLength(10),

                        Forms\Components\FileUpload::make('avatar')
                            ->label('Foto')
                            ->image()
                            ->directory('clients/avatars')
                            ->maxSize(1024),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Tidak Aktif',
                                'vip' => 'VIP',
                            ])
                            ->default('active')
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Foto')
                    ->circular()
                    ->size(40),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('company')
                    ->label('Perusahaan')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('Kota')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'vip' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                        'vip' => 'VIP',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                        'vip' => 'VIP',
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}