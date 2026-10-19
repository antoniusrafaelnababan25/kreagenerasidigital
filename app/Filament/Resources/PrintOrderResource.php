<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrintOrderResource\Pages;
use App\Models\PrintOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PrintOrderResource extends Resource
{
    protected static ?string $model = PrintOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-printer';

    protected static ?string $navigationLabel = 'Order Printing';

    protected static ?string $modelLabel = 'Order Printing';

    protected static ?string $pluralModelLabel = 'Order Printing';

    protected static ?string $navigationGroup = '📦 Orders';

    protected static ?int $navigationSort = 2;

    // ─── Filter: cuma muncul di tenant printing ───
    public static function shouldRegisterNavigation(): bool
    {
        return filament()->getTenant()?->key === 'printing';
    }

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

                        Forms\Components\Select::make('print_product_id')
                            ->label('Produk')
                            ->relationship('printProduct', 'name')
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

                        Forms\Components\Textarea::make('customer_address')
                            ->label('Alamat')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('size')
                            ->label('Ukuran')
                            ->maxLength(50),

                        Forms\Components\TextInput::make('color')
                            ->label('Warna')
                            ->maxLength(50),

                        Forms\Components\TextInput::make('material')
                            ->label('Bahan')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('print_technique')
                            ->label('Teknik Printing')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('unit_price')
                            ->label('Harga Satuan')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        Forms\Components\Select::make('shipping_method')
                            ->label('Metode Pengiriman')
                            ->options([
                                'pickup' => 'Ambil Sendiri',
                                'delivery' => 'Dikirim',
                            ])
                            ->default('pickup'),

                        Forms\Components\TextInput::make('tracking_number')
                            ->label('No. Resi')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Disetujui',
                                'in_production' => 'Produksi',
                                'ready' => 'Siap',
                                'shipped' => 'Dikirim',
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
                            ])
                            ->default('unpaid')
                            ->required(),
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

                        Forms\Components\TextInput::make('paid_amount')
                            ->label('Sudah Dibayar')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\Textarea::make('design_notes')
                            ->label('Catatan Desain')
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

                Tables\Columns\TextColumn::make('printProduct.name')
                    ->label('Produk')
                    ->limit(25)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Qty')
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
                        'approved' => 'info',
                        'in_production' => 'primary',
                        'ready' => 'primary',
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
                        'dp' => 'warning',
                        'paid' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'in_production' => 'Produksi',
                        'ready' => 'Siap',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Pembayaran')
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'dp' => 'DP',
                        'paid' => 'Lunas',
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
            'index' => Pages\ListPrintOrders::route('/'),
            'create' => Pages\CreatePrintOrder::route('/create'),
            'edit' => Pages\EditPrintOrder::route('/{record}/edit'),
        ];
    }
}