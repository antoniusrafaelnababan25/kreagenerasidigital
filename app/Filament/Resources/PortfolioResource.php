<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PortfolioResource\Pages;
use App\Models\Portfolio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PortfolioResource extends Resource
{
    protected static ?string $model = Portfolio::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Portfolio';

    protected static ?string $modelLabel = 'Portfolio';

    protected static ?string $pluralModelLabel = 'Portfolio';

    protected static ?string $navigationGroup = '📝 Konten';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Portfolio')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('client_name')
                            ->label('Nama Klien')
                            ->maxLength(255),

                        Forms\Components\Select::make('service_type')
                            ->label('Jenis Layanan')
                            ->options([
                                'website' => 'Website',
                                'video' => 'Video',
                                'printing' => 'Printing',
                                'rental' => 'Rental',
                                'multi' => 'Multi-Service',
                            ]),

                        Forms\Components\TextInput::make('project_year')
                            ->label('Tahun Proyek')
                            ->maxLength(4),

                        Forms\Components\Textarea::make('short_description')
                            ->label('Deskripsi Singkat')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi Lengkap')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->image()
                            ->directory('portfolio/featured')
                            ->maxSize(2048),

                        Forms\Components\FileUpload::make('gallery')
                            ->label('Galeri')
                            ->image()
                            ->multiple()
                            ->directory('portfolio/gallery')
                            ->maxSize(2048),

                        Forms\Components\TextInput::make('video_url')
                            ->label('Video URL')
                            ->url(),

                        Forms\Components\TextInput::make('live_url')
                            ->label('Live URL')
                            ->url(),

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

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')->label('Meta Title')->maxLength(60),
                        Forms\Components\Textarea::make('meta_description')->label('Meta Description')->rows(2)->maxLength(160),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->square()
                    ->size(50),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('client_name')
                    ->label('Klien')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('service_type')
                    ->label('Layanan')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('project_year')
                    ->label('Tahun')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->badge()
                    ->color('info')
                    ->sortable(),

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
            'index' => Pages\ListPortfolios::route('/'),
            'create' => Pages\CreatePortfolio::route('/create'),
            'edit' => Pages\EditPortfolio::route('/{record}/edit'),
        ];
    }
}