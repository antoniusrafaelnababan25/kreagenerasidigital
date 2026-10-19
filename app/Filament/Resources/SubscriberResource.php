<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriberResource\Pages;
use App\Models\Subscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubscriberResource extends Resource
{
    protected static ?string $model = Subscriber::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Subscriber';

    protected static ?string $modelLabel = 'Subscriber';

    protected static ?string $pluralModelLabel = 'Subscriber';

    protected static ?string $navigationGroup = '👥 CRM & Marketing';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Subscriber')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->maxLength(255),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Aktif',
                                'unsubscribed' => 'Berhenti',
                                'bounced' => 'Bounced',
                            ])
                            ->default('active')
                            ->required(),

                        Forms\Components\TextInput::make('source')
                            ->label('Sumber')
                            ->maxLength(255),

                        Forms\Components\DateTimePicker::make('subscribed_at')
                            ->label('Tanggal Subscribe')
                            ->default(now()),

                        Forms\Components\DateTimePicker::make('unsubscribed_at')
                            ->label('Tanggal Unsubscribe'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'active' => 'success',
                        'unsubscribed' => 'gray',
                        'bounced' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'active' => 'Aktif',
                        'unsubscribed' => 'Berhenti',
                        'bounced' => 'Bounced',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('source')
                    ->label('Sumber')
                    ->badge()
                    ->color('gray')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('subscribed_at')
                    ->label('Subscribe')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'unsubscribed' => 'Berhenti',
                        'bounced' => 'Bounced',
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
            ->defaultSort('subscribed_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscribers::route('/'),
            'create' => Pages\CreateSubscriber::route('/create'),
            'edit' => Pages\EditSubscriber::route('/{record}/edit'),
        ];
    }
}