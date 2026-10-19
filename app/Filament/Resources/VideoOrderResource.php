<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoOrderResource\Pages;
use App\Models\VideoOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VideoOrderResource extends Resource
{
    protected static ?string $model = VideoOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationLabel = 'Order Video';

    protected static ?string $modelLabel = 'Order Video';

    protected static ?string $pluralModelLabel = 'Order Video';

    protected static ?string $navigationGroup = '📦 Orders';

    protected static ?int $navigationSort = 3;

    // ─── Filter: cuma muncul di tenant video ───
    public static function shouldRegisterNavigation(): bool
    {
        return filament()->getTenant()?->key === 'video';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Order')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')->label('No. Order')->disabled()->dehydrated(false),
                        Forms\Components\Select::make('client_id')->label('Klien')->relationship('client', 'name')->searchable()->preload(),
                        Forms\Components\Select::make('video_package_id')->label('Paket Video')->relationship('videoPackage', 'name')->searchable()->preload(),
                        Forms\Components\TextInput::make('customer_name')->label('Nama Pelanggan')->required()->maxLength(255),
                        Forms\Components\TextInput::make('customer_email')->label('Email')->email()->maxLength(255),
                        Forms\Components\TextInput::make('customer_phone')->label('Telepon')->tel()->maxLength(20),
                        Forms\Components\TextInput::make('project_title')->label('Judul Proyek')->required()->maxLength(255),
                        Forms\Components\Select::make('video_type')
                            ->label('Jenis Video')
                            ->options([
                                'wedding' => 'Wedding',
                                'company_profile' => 'Company Profile',
                                'event' => 'Event',
                                'sosmed' => 'Konten Sosmed',
                            ]),
                        Forms\Components\Textarea::make('concept')->label('Konsep')->rows(3)->columnSpanFull(),
                        Forms\Components\TextInput::make('location')->label('Lokasi')->maxLength(255),
                        Forms\Components\DatePicker::make('shooting_date')->label('Tanggal Shooting'),
                        Forms\Components\TimePicker::make('shooting_time')->label('Waktu Shooting'),
                        Forms\Components\TextInput::make('duration_minutes')->label('Durasi (menit)')->numeric(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Dikonfirmasi',
                                'pre_production' => 'Pra-Produksi',
                                'shooting' => 'Shooting',
                                'editing' => 'Editing',
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
                Tables\Columns\TextColumn::make('order_number')->label('No. Order')->searchable()->copyable()->badge()->color('primary'),
                Tables\Columns\TextColumn::make('customer_name')->label('Pelanggan')->searchable(),
                Tables\Columns\TextColumn::make('project_title')->label('Proyek')->limit(30),
                Tables\Columns\TextColumn::make('total')->label('Total')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('shooting_date')->label('Shooting')->date('d M Y')->placeholder('-'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'pre_production', 'shooting', 'editing' => 'primary',
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
                        'confirmed' => 'Dikonfirmasi',
                        'pre_production' => 'Pra-Produksi',
                        'shooting' => 'Shooting',
                        'editing' => 'Editing',
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
            'index' => Pages\ListVideoOrders::route('/'),
            'create' => Pages\CreateVideoOrder::route('/create'),
            'edit' => Pages\EditVideoOrder::route('/{record}/edit'),
        ];
    }
}