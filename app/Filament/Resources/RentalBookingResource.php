<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalBookingResource\Pages;
use App\Models\RentalBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RentalBookingResource extends Resource
{
    protected static ?string $model = RentalBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Booking Rental';

    protected static ?string $modelLabel = 'Booking Rental';

    protected static ?string $pluralModelLabel = 'Booking Rental';

    protected static ?string $navigationGroup = '📦 Orders';

    protected static ?int $navigationSort = 1;

    // ─── Filter: cuma muncul di tenant rental ───
    public static function shouldRegisterNavigation(): bool
    {
        return filament()->getTenant()?->key === 'rental';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Booking')
                    ->schema([
                        Forms\Components\TextInput::make('booking_number')
                            ->label('No. Booking')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('client_id')
                            ->label('Klien')
                            ->relationship('client', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('customer_name')
                            ->label('Nama Penyewa')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('customer_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('customer_phone')
                            ->label('Telepon')
                            ->tel()
                            ->required()
                            ->maxLength(20),

                        Forms\Components\TextInput::make('customer_id_number')
                            ->label('No. KTP/SIM')
                            ->maxLength(50),

                        Forms\Components\Textarea::make('customer_address')
                            ->label('Alamat')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Periode Sewa')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required(),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->required(),

                        Forms\Components\TextInput::make('total_days')
                            ->label('Total Hari')
                            ->numeric()
                            ->default(1),

                        Forms\Components\Select::make('pickup_method')
                            ->label('Metode Pengambilan')
                            ->options([
                                'self_pickup' => 'Ambil Sendiri',
                                'delivery' => 'Diantar',
                            ])
                            ->default('self_pickup'),

                        Forms\Components\Textarea::make('pickup_address')
                            ->label('Alamat Pengambilan')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Dikonfirmasi',
                                'picked_up' => 'Diambil',
                                'returned' => 'Dikembalikan',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\Select::make('payment_status')
                            ->label('Pembayaran')
                            ->options([
                                'unpaid' => 'Belum Bayar',
                                'dp' => 'DP',
                                'paid' => 'Lunas',
                                'refunded' => 'Refund',
                            ])
                            ->default('unpaid')
                            ->required(),

                        Forms\Components\DateTimePicker::make('picked_up_at')
                            ->label('Waktu Diambil'),

                        Forms\Components\DateTimePicker::make('returned_at')
                            ->label('Waktu Dikembalikan'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Keuangan')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('discount')
                            ->label('Diskon')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('deposit')
                            ->label('Deposit')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('late_fee')
                            ->label('Denda Keterlambatan')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('damage_fee')
                            ->label('Denda Kerusakan')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('paid_amount')
                            ->label('Sudah Dibayar')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('refund_amount')
                            ->label('Refund')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_number')
                    ->label('No. Booking')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Penyewa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('Telepon')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_days')
                    ->label('Hari')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'picked_up' => 'primary',
                        'returned' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'unpaid' => 'danger',
                        'dp' => 'warning',
                        'paid' => 'success',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Dikonfirmasi',
                        'picked_up' => 'Diambil',
                        'returned' => 'Dikembalikan',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Pembayaran')
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'dp' => 'DP',
                        'paid' => 'Lunas',
                        'refunded' => 'Refund',
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
            'index' => Pages\ListRentalBookings::route('/'),
            'create' => Pages\CreateRentalBooking::route('/create'),
            'edit' => Pages\EditRentalBooking::route('/{record}/edit'),
        ];
    }
}