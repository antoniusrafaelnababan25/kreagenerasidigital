<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Artikel Blog';

    protected static ?string $modelLabel = 'Artikel';

    protected static ?string $pluralModelLabel = 'Artikel Blog';

    protected static ?string $navigationGroup = '📝 Konten';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konten Utama')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('blog_category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('author_id')
                            ->label('Penulis')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->id()),

                        Forms\Components\Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('content')
                            ->label('Konten')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->image()
                            ->directory('blog/featured')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'scheduled' => 'Scheduled',
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Tanggal Publish'),

                        Forms\Components\TextInput::make('reading_time_minutes')
                            ->label('Waktu Baca (menit)')
                            ->numeric(),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured'),

                        Forms\Components\Toggle::make('allow_comments')
                            ->label('Izinkan Komentar')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(2)
                            ->maxLength(160),

                        Forms\Components\TextInput::make('focus_keyword')
                            ->label('Focus Keyword')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('canonical_url')
                            ->label('Canonical URL')
                            ->url(),

                        Forms\Components\FileUpload::make('og_image')
                            ->label('OG Image')
                            ->image()
                            ->directory('blog/og'),

                        Forms\Components\Toggle::make('is_indexable')
                            ->label('Indexable')
                            ->default(true),
                    ])
                    ->columns(2)
                    ->collapsed(),

                Forms\Components\Section::make('Tags')
                    ->schema([
                        Forms\Components\Select::make('tags')
                            ->label('Tag')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ]),
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
                    ->limit(50),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Penulis')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'published' => 'success',
                        'draft' => 'gray',
                        'scheduled' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publish')
                    ->dateTime('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'scheduled' => 'Scheduled',
                    ]),

                Tables\Filters\SelectFilter::make('blog_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}