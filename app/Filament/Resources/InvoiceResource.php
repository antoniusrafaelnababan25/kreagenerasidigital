<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static ?string $navigationLabel = 'Invoice';

    protected static ?string $modelLabel = 'Invoice';

    protected static ?string $pluralModelLabel = 'Invoice';

    protected static ?string $navigationGroup = '💰 Keuangan & Project';

    protected static ?int $navigationSort = 1;

    // Invoice global
    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Invoice')
                    ->schema([
                        Forms\Components\TextInput::make('invoice_number')
                            ->label('No. Invoice')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('client_id')
                            ->label('Klien')
                            ->relationship('client', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('project_id')
                            ->label('Proyek')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('title')
                            ->label('Judul Invoice')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('issue_date')
                            ->label('Tanggal Terbit')
                            ->required()
                            ->default(now()),

                        Forms\Components\DatePicker::make('due_date')
                            ->label('Jatuh Tempo')
                            ->required()
                            ->default(now()->addDays(14)),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Terkirim',
                                'viewed' => 'Dilihat',
                                'partial' => 'Dibayar Sebagian',
                                'paid' => 'Lunas',
                                'overdue' => 'Jatuh Tempo',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(2)
                            ->columnSpanFull(),
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

                        Forms\Components\TextInput::make('remaining')
                            ->label('Sisa')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Catatan & Syarat')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(2),

                        Forms\Components\Textarea::make('terms')
                            ->label('Syarat & Ketentuan')
                            ->rows(2),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('No. Invoice')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('client.name')
                    ->label('Klien')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('paid_amount')
                    ->label('Dibayar')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'info',
                        'viewed' => 'info',
                        'partial' => 'warning',
                        'paid' => 'success',
                        'overdue' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('issue_date')
                    ->label('Terbit')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Terkirim',
                        'viewed' => 'Dilihat',
                        'partial' => 'Dibayar Sebagian',
                        'paid' => 'Lunas',
                        'overdue' => 'Jatuh Tempo',
                        'cancelled' => 'Dibatalkan',
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}