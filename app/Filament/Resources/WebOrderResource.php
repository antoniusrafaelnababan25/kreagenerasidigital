<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebOrderResource\Pages;
use App\Models\WebOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebOrderResource extends Resource
{
    protected static ?string $model = WebOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationLabel = 'Order Website';

    protected static ?string $modelLabel = 'Order Website';

    protected static ?string $pluralModelLabel = 'Order Website';

    protected static ?string $navigationGroup = '📦 Orders';

    protected static ?int $navigationSort = 1;

    // ─── Filter: cuma muncul di tenant website ───
    public static function shouldRegisterNavigation(): bool
    {
        return filament()->getTenant()?->key === 'website';
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

                        Forms\Components\TextInput::make('company_name')
                            ->label('Nama Perusahaan')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('business_type')
                            ->label('Jenis Bisnis')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('project_title')
                            ->label('Judul Proyek')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('domain_preference')
                            ->label('Preferensi Domain')
                            ->maxLength(255),

                        Forms\Components\Toggle::make('needs_hosting')->label('Perlu Hosting'),
                        Forms\Components\Toggle::make('needs_content')->label('Perlu Konten'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'brief_confirmed' => 'Brief Dikonfirmasi',
                                'design' => 'Desain',
                                'development' => 'Development',
                                'review' => 'Review',
                                'revision' => 'Revisi',
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

                        Forms\Components\TextInput::make('live_url')
                            ->label('URL Live')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Keuangan')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')->label('Subtotal')->numeric()->prefix('Rp')->default(0),
                        Forms\Components\TextInput::make('discount')->label('Diskon')->numeric()->prefix('Rp')->default(0),
                        Forms\Components\TextInput::make('total')->label('Total')->numeric()->prefix('Rp')->default(0),
                        Forms\Components\TextInput::make('paid_amount')->label('Sudah Dibayar')->numeric()->prefix('Rp')->default(0),
                    ])
                    ->columns(4),
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

                Tables\Columns\TextColumn::make('customer_name')->label('Pelanggan')->searchable(),

                Tables\Columns\TextColumn::make('project_title')->label('Proyek')->limit(30)->searchable(),

                Tables\Columns\TextColumn::make('total')->label('Total')->money('IDR')->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'brief_confirmed' => 'info',
                        'design', 'development' => 'primary',
                        'review' => 'info',
                        'revision' => 'warning',
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

                Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'brief_confirmed' => 'Brief Dikonfirmasi',
                        'design' => 'Desain',
                        'development' => 'Development',
                        'review' => 'Review',
                        'revision' => 'Revisi',
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
            'index' => Pages\ListWebOrders::route('/'),
            'create' => Pages\CreateWebOrder::route('/create'),
            'edit' => Pages\EditWebOrder::route('/{record}/edit'),
        ];
    }
}