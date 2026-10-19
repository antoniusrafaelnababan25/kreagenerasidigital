<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Proyek';

    protected static ?string $modelLabel = 'Proyek';

    protected static ?string $pluralModelLabel = 'Proyek';

    protected static ?string $navigationGroup = '💰 Keuangan & Project';

    protected static ?int $navigationSort = 4;

    // Project global
    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Proyek')
                    ->schema([
                        Forms\Components\TextInput::make('project_number')
                            ->label('No. Proyek')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Proyek')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('client_id')
                            ->label('Klien')
                            ->relationship('client', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('lead_id')
                            ->label('Lead')
                            ->relationship('lead', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('service_type')
                            ->label('Jenis Layanan')
                            ->options([
                                'website' => 'Website',
                                'video' => 'Video',
                                'printing' => 'Printing',
                                'rental' => 'Rental',
                            ]),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'Berjalan',
                                'review' => 'Review',
                                'completed' => 'Selesai',
                                'on_hold' => 'Ditahan',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\Select::make('priority')
                            ->label('Prioritas')
                            ->options([
                                'low' => 'Rendah',
                                'medium' => 'Sedang',
                                'high' => 'Tinggi',
                                'urgent' => 'Urgent',
                            ])
                            ->default('medium')
                            ->required(),

                        Forms\Components\Select::make('manager_id')
                            ->label('Manager')
                            ->relationship('manager', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Jadwal')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai'),

                        Forms\Components\DatePicker::make('deadline')
                            ->label('Deadline'),

                        Forms\Components\TextInput::make('progress')
                            ->label('Progress (%)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Keuangan')
                    ->schema([
                        Forms\Components\TextInput::make('budget')
                            ->label('Budget')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('actual_cost')
                            ->label('Biaya Aktual')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('billed_amount')
                            ->label('Sudah Ditagih')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Catatan')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project_number')
                    ->label('No. Proyek')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->limit(35),

                Tables\Columns\TextColumn::make('client.name')
                    ->label('Klien')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('service_type')
                    ->label('Layanan')
                    ->badge()
                    ->color('primary')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'in_progress' => 'primary',
                        'review' => 'info',
                        'completed' => 'success',
                        'on_hold' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress')
                    ->suffix('%')
                    ->badge()
                    ->color(fn ($state) => $state >= 100 ? 'success' : ($state >= 50 ? 'info' : 'warning')),

                Tables\Columns\TextColumn::make('budget')
                    ->label('Budget')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('deadline')
                    ->label('Deadline')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($state, $record) => $record->deadline && $record->deadline->isPast() && $record->status !== 'completed' ? 'danger' : null),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'Berjalan',
                        'review' => 'Review',
                        'completed' => 'Selesai',
                        'on_hold' => 'Ditahan',
                        'cancelled' => 'Dibatalkan',
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
                        'urgent' => 'Urgent',
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}