<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadActivityResource\Pages;
use App\Models\LeadActivity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeadActivityResource extends Resource
{
    protected static ?string $model = LeadActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Aktivitas Lead';

    protected static ?string $modelLabel = 'Aktivitas Lead';

    protected static ?string $pluralModelLabel = 'Aktivitas Lead';

    protected static ?string $navigationGroup = '👥 CRM & Marketing';

    protected static ?int $navigationSort = 2;

    // LeadActivity tidak punya site_id langsung
    protected static ?string $tenantOwnershipRelationshipName = 'lead';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Aktivitas')
                    ->schema([
                        Forms\Components\Select::make('lead_id')
                            ->label('Lead')
                            ->relationship('lead', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('user_id')
                            ->label('Oleh')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->id()),

                        Forms\Components\Select::make('type')
                            ->label('Tipe Aktivitas')
                            ->options([
                                'call' => 'Telepon',
                                'email' => 'Email',
                                'whatsapp' => 'WhatsApp',
                                'meeting' => 'Meeting',
                                'note' => 'Catatan',
                            ])
                            ->required(),

                        Forms\Components\DateTimePicker::make('activity_at')
                            ->label('Waktu Aktivitas')
                            ->default(now()),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lead.name')
                    ->label('Lead')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'call' => 'info',
                        'email' => 'primary',
                        'whatsapp' => 'success',
                        'meeting' => 'warning',
                        'note' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'call' => 'Telepon',
                        'email' => 'Email',
                        'whatsapp' => 'WhatsApp',
                        'meeting' => 'Meeting',
                        'note' => 'Catatan',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Oleh')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50),

                Tables\Columns\TextColumn::make('activity_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'call' => 'Telepon',
                        'email' => 'Email',
                        'whatsapp' => 'WhatsApp',
                        'meeting' => 'Meeting',
                        'note' => 'Catatan',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('activity_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeadActivities::route('/'),
            'create' => Pages\CreateLeadActivity::route('/create'),
            'edit' => Pages\EditLeadActivity::route('/{record}/edit'),
        ];
    }
}