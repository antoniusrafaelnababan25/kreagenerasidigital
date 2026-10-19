<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Testimoni';

    protected static ?string $modelLabel = 'Testimoni';

    protected static ?string $pluralModelLabel = 'Testimoni';

    protected static ?string $navigationGroup = '📝 Konten';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Testimoni')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('position')
                            ->label('Jabatan')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('company')
                            ->label('Perusahaan')
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('avatar')
                            ->label('Foto')
                            ->image()
                            ->directory('testimonials/avatars')
                            ->maxSize(1024),

                        Forms\Components\Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '⭐ 1',
                                2 => '⭐⭐ 2',
                                3 => '⭐⭐⭐ 3',
                                4 => '⭐⭐⭐⭐ 4',
                                5 => '⭐⭐⭐⭐⭐ 5',
                            ])
                            ->default(5)
                            ->required(),

                        Forms\Components\Select::make('service_type')
                            ->label('Jenis Layanan')
                            ->options([
                                'website' => 'Website',
                                'video' => 'Video',
                                'printing' => 'Printing',
                                'rental' => 'Rental',
                                'multi' => 'Multi-Service',
                            ]),

                        Forms\Components\Textarea::make('content')
                            ->label('Isi Testimoni')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('project_reference')
                            ->label('Referensi Proyek')
                            ->maxLength(255),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured'),

                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),

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
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Foto')
                    ->circular()
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('company')
                    ->label('Perusahaan')
                    ->placeholder('-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state)),

                Tables\Columns\TextColumn::make('service_type')
                    ->label('Layanan')
                    ->badge()
                    ->color('primary')
                    ->placeholder('-'),

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
                        'multi' => 'Multi-Service',
                    ]),

                Tables\Filters\SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '1 Bintang',
                        2 => '2 Bintang',
                        3 => '3 Bintang',
                        4 => '4 Bintang',
                        5 => '5 Bintang',
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}