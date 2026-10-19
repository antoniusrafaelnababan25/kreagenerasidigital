<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Leads';

    protected static ?string $modelLabel = 'Lead';

    protected static ?string $pluralModelLabel = 'Leads';

    protected static ?string $navigationGroup = '👥 CRM & Marketing';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Lead')
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

                        Forms\Components\Select::make('service_type')
                            ->label('Layanan Diminati')
                            ->options([
                                'website' => 'Website',
                                'video' => 'Video',
                                'printing' => 'Printing',
                                'rental' => 'Rental',
                            ]),

                        Forms\Components\Select::make('source')
                            ->label('Sumber')
                            ->options([
                                'website' => 'Website',
                                'whatsapp' => 'WhatsApp',
                                'instagram' => 'Instagram',
                                'facebook' => 'Facebook',
                                'referral' => 'Referral',
                                'other' => 'Lainnya',
                            ]),

                        Forms\Components\Textarea::make('message')
                            ->label('Pesan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status & Prioritas')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'new' => 'Baru',
                                'contacted' => 'Dihubungi',
                                'qualified' => 'Qualified',
                                'proposal' => 'Proposal',
                                'won' => 'Won',
                                'lost' => 'Lost',
                            ])
                            ->default('new')
                            ->required(),

                        Forms\Components\Select::make('priority')
                            ->label('Prioritas')
                            ->options([
                                'low' => 'Rendah',
                                'medium' => 'Sedang',
                                'high' => 'Tinggi',
                            ])
                            ->default('medium')
                            ->required(),

                        Forms\Components\TextInput::make('estimated_value')
                            ->label('Estimasi Nilai')
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\Select::make('assigned_to')
                            ->label('Ditugaskan Ke')
                            ->relationship('assignedUser', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\DateTimePicker::make('contacted_at')
                            ->label('Tanggal Dihubungi'),

                        Forms\Components\DateTimePicker::make('converted_at')
                            ->label('Tanggal Convert'),
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

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('service_type')
                    ->label('Layanan')
                    ->badge()
                    ->color('primary')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'new' => 'info',
                        'contacted' => 'warning',
                        'qualified' => 'primary',
                        'proposal' => 'success',
                        'won' => 'success',
                        'lost' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'new' => 'Baru',
                        'contacted' => 'Dihubungi',
                        'qualified' => 'Qualified',
                        'proposal' => 'Proposal',
                        'won' => 'Won',
                        'lost' => 'Lost',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'low' => 'gray',
                        'medium' => 'warning',
                        'high' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('estimated_value')
                    ->label('Estimasi')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('PIC')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'Baru',
                        'contacted' => 'Dihubungi',
                        'qualified' => 'Qualified',
                        'proposal' => 'Proposal',
                        'won' => 'Won',
                        'lost' => 'Lost',
                    ]),

                Tables\Filters\SelectFilter::make('service_type')
                    ->label('Layanan')
                    ->options([
                        'website' => 'Website',
                        'video' => 'Video',
                        'printing' => 'Printing',
                        'rental' => 'Rental',
                    ]),

                Tables\Filters\SelectFilter::make('priority')
                    ->label('Prioritas')
                    ->options([
                        'low' => 'Rendah',
                        'medium' => 'Sedang',
                        'high' => 'Tinggi',
                    ]),

                Tables\Filters\SelectFilter::make('source')
                    ->label('Sumber')
                    ->options([
                        'website' => 'Website',
                        'whatsapp' => 'WhatsApp',
                        'instagram' => 'Instagram',
                        'facebook' => 'Facebook',
                        'referral' => 'Referral',
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
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}