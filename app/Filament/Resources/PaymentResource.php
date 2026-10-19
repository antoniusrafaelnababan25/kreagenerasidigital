<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Pembayaran';

    protected static ?string $modelLabel = 'Pembayaran';

    protected static ?string $pluralModelLabel = 'Pembayaran';

    protected static ?string $navigationGroup = '💰 Keuangan & Project';

    protected static ?int $navigationSort = 2;

    // Payment polymorphic, tidak scoped ke tenant
    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pembayaran')
                    ->schema([
                        Forms\Components\TextInput::make('payment_number')
                            ->label('No. Pembayaran')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('amount')
                            ->label('Jumlah')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        Forms\Components\Select::make('method')
                            ->label('Metode')
                            ->options([
                                'transfer' => 'Transfer Bank',
                                'cash' => 'Tunai',
                                'midtrans' => 'Midtrans',
                                'xendit' => 'Xendit',
                                'other' => 'Lainnya',
                            ]),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'verified' => 'Terverifikasi',
                                'failed' => 'Gagal',
                                'refunded' => 'Refund',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\TextInput::make('reference')
                            ->label('Referensi')
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('proof_path')
                            ->label('Bukti Pembayaran')
                            ->image()
                            ->directory('payments/proofs')
                            ->maxSize(2048),

                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Waktu Bayar'),

                        Forms\Components\Select::make('verified_by')
                            ->label('Diverifikasi Oleh')
                            ->relationship('verifiedBy', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_number')
                    ->label('No. Pembayaran')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('payable_type')
                    ->label('Tipe')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => class_basename($state)),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('method')
                    ->label('Metode')
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'verified' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Waktu Bayar')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('verifiedBy.name')
                    ->label('Verifikator')
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'verified' => 'Terverifikasi',
                        'failed' => 'Gagal',
                        'refunded' => 'Refund',
                    ]),

                Tables\Filters\SelectFilter::make('method')
                    ->label('Metode')
                    ->options([
                        'transfer' => 'Transfer Bank',
                        'cash' => 'Tunai',
                        'midtrans' => 'Midtrans',
                        'xendit' => 'Xendit',
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}