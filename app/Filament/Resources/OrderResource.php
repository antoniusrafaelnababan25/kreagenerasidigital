<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Order E-Commerce';

    protected static ?string $modelLabel = 'Order';

    protected static ?string $pluralModelLabel = 'Orders';

    protected static ?string $navigationGroup = '📦 Orders';

    protected static ?int $navigationSort = 1;

    // Order global (e-commerce lintas site)
    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Order')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('No. Order')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('client_id')
                            ->label('Klien')
                            ->relationship('client', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('customer_name')
                            ->label('Nama Pelanggan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('customer_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('customer_phone')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(20),

                        Forms\Components\Textarea::make('shipping_address')
                            ->label('Alamat Pengiriman')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('shipping_city')
                            ->label('Kota')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('shipping_province')
                            ->label('Provinsi')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('shipping_postal_code')
                            ->label('Kode Pos')
                            ->maxLength(10),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Dibayar',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'unpaid' => 'Belum Bayar',
                                'partial' => 'Sebagian',
                                'paid' => 'Lunas',
                                'refunded' => 'Refund',
                            ])
                            ->default('unpaid')
                            ->required(),

                        Forms\Components\TextInput::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->maxLength(255),
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

                        Forms\Components\TextInput::make('tax')
                            ->label('Pajak')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Ongkir')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('total')
                            ->label('Total')
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
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Order')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('Telepon')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'info',
                        'processing' => 'primary',
                        'shipped' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'unpaid' => 'danger',
                        'partial' => 'warning',
                        'paid' => 'success',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Dibayar',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Pembayaran')
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'partial' => 'Sebagian',
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}