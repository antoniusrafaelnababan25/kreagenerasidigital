<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationLabel = 'FAQ';

    protected static ?string $modelLabel = 'FAQ';

    protected static ?string $pluralModelLabel = 'FAQ';

    protected static ?string $navigationGroup = '🎨 Paket Layanan';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FAQ')
                    ->schema([
                        Forms\Components\TextInput::make('question')
                            ->label('Pertanyaan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('answer')
                            ->label('Jawaban')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('service_type')
                            ->label('Jenis Layanan')
                            ->options([
                                'website' => 'Website',
                                'video' => 'Video',
                                'printing' => 'Printing',
                                'rental' => 'Rental',
                            ]),

                        Forms\Components\TextInput::make('category')
                            ->label('Kategori')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured'),

                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('Pertanyaan')
                    ->searchable()
                    ->sortable()
                    ->limit(60),

                Tables\Columns\TextColumn::make('service_type')
                    ->label('Layanan')
                    ->badge()
                    ->color('primary')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                Tables\Columns\TextColumn::make('order')->label('Urutan')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('service_type')
                    ->label('Layanan')
                    ->options([
                        'website' => 'Website',
                        'video' => 'Video',
                        'printing' => 'Printing',
                        'rental' => 'Rental',
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}