<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Models\Campaign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Campaign';

    protected static ?string $modelLabel = 'Campaign';

    protected static ?string $pluralModelLabel = 'Campaign';

    protected static ?string $navigationGroup = '👥 CRM & Marketing';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Campaign')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Campaign')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('subject')
                            ->label('Subjek')
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'email' => 'Email',
                                'whatsapp' => 'WhatsApp',
                                'sms' => 'SMS',
                            ])
                            ->default('email')
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'scheduled' => 'Scheduled',
                                'sending' => 'Sending',
                                'sent' => 'Sent',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\RichEditor::make('content')
                            ->label('Konten')
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->label('Jadwal Kirim'),

                        Forms\Components\DateTimePicker::make('sent_at')
                            ->label('Tanggal Kirim'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'draft' => 'gray',
                        'scheduled' => 'warning',
                        'sending' => 'info',
                        'sent' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'draft' => 'Draft',
                        'scheduled' => 'Dijadwalkan',
                        'sending' => 'Mengirim',
                        'sent' => 'Terkirim',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('total_recipients')
                    ->label('Penerima')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('total_opened')
                    ->label('Dibuka')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('sent_at')
                    ->label('Terkirim')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Dijadwalkan',
                        'sending' => 'Mengirim',
                        'sent' => 'Terkirim',
                        'cancelled' => 'Dibatalkan',
                    ]),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'email' => 'Email',
                        'whatsapp' => 'WhatsApp',
                        'sms' => 'SMS',
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
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}