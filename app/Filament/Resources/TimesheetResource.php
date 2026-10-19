<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimesheetResource\Pages;
use App\Models\Timesheet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TimesheetResource extends Resource
{
    protected static ?string $model = Timesheet::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Timesheet';

    protected static ?string $modelLabel = 'Timesheet';

    protected static ?string $pluralModelLabel = 'Timesheet';

    protected static ?string $navigationGroup = '💰 Keuangan & Project';

    protected static ?int $navigationSort = 6;

    // Timesheet lewat project
    protected static ?string $tenantOwnershipRelationshipName = 'project';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Timesheet')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->id()),

                        Forms\Components\Select::make('project_id')
                            ->label('Proyek')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('project_task_id')
                            ->label('Task')
                            ->relationship('task', 'title')
                            ->searchable()
                            ->preload(),

                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal')
                            ->required()
                            ->default(now()),

                        Forms\Components\TextInput::make('hours')
                            ->label('Jam')
                            ->numeric()
                            ->required()
                            ->default(1),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_billable')
                            ->label('Billable')
                            ->default(true),

                        Forms\Components\TextInput::make('hourly_rate')
                            ->label('Rate per Jam')
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\Toggle::make('is_approved')
                            ->label('Approved'),

                        Forms\Components\Select::make('approved_by')
                            ->label('Approved By')
                            ->relationship('approvedBy', 'name')
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('project.name')
                    ->label('Proyek')
                    ->limit(25)
                    ->placeholder('-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('task.title')
                    ->label('Task')
                    ->limit(25)
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('hours')
                    ->label('Jam')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_billable')
                    ->label('Billable')
                    ->boolean(),

                Tables\Columns\TextColumn::make('hourly_rate')
                    ->label('Rate')
                    ->money('IDR')
                    ->placeholder('-'),

                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name'),

                Tables\Filters\SelectFilter::make('project_id')
                    ->label('Proyek')
                    ->relationship('project', 'name'),

                Tables\Filters\TernaryFilter::make('is_billable')->label('Billable'),
                Tables\Filters\TernaryFilter::make('is_approved')->label('Approved'),

                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->where('date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->where('date', '<=', $data['until']));
                    }),
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
            ->defaultSort('date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimesheets::route('/'),
            'create' => Pages\CreateTimesheet::route('/create'),
            'edit' => Pages\EditTimesheet::route('/{record}/edit'),
        ];
    }
}