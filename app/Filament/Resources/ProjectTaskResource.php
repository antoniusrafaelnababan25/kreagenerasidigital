<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectTaskResource\Pages;
use App\Models\ProjectTask;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectTaskResource extends Resource
{
    protected static ?string $model = ProjectTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationLabel = 'Task Proyek';

    protected static ?string $modelLabel = 'Task';

    protected static ?string $pluralModelLabel = 'Task Proyek';

    protected static ?string $navigationGroup = '💰 Keuangan & Project';

    protected static ?int $navigationSort = 5;

    // ProjectTask tidak punya site_id, tapi lewat project
    protected static ?string $tenantOwnershipRelationshipName = 'project';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Task')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label('Proyek')
                            ->relationship('project', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('title')
                            ->label('Judul Task')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('assigned_to')
                            ->label('Ditugaskan Ke')
                            ->relationship('assignedUser', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'todo' => 'Todo',
                                'in_progress' => 'Berjalan',
                                'review' => 'Review',
                                'done' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('todo')
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

                        Forms\Components\DatePicker::make('due_date')
                            ->label('Deadline'),

                        Forms\Components\TextInput::make('estimated_hours')
                            ->label('Estimasi Jam')
                            ->numeric(),

                        Forms\Components\TextInput::make('actual_hours')
                            ->label('Jam Aktual')
                            ->numeric(),

                        Forms\Components\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('project.name')
                    ->label('Proyek')
                    ->searchable()
                    ->limit(25)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('PIC')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'todo' => 'gray',
                        'in_progress' => 'primary',
                        'review' => 'info',
                        'done' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
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

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Deadline')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('estimated_hours')
                    ->label('Estimasi')
                    ->suffix(' jam')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('actual_hours')
                    ->label('Aktual')
                    ->suffix(' jam')
                    ->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'todo' => 'Todo',
                        'in_progress' => 'Berjalan',
                        'review' => 'Review',
                        'done' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),

                Tables\Filters\SelectFilter::make('priority')
                    ->label('Prioritas')
                    ->options([
                        'low' => 'Rendah',
                        'medium' => 'Sedang',
                        'high' => 'Tinggi',
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
            ->defaultSort('order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectTasks::route('/'),
            'create' => Pages\CreateProjectTask::route('/create'),
            'edit' => Pages\EditProjectTask::route('/{record}/edit'),
        ];
    }
}