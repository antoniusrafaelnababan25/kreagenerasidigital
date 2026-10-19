<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Halaman';

    protected static ?string $modelLabel = 'Halaman';

    protected static ?string $pluralModelLabel = 'Halaman';

    protected static ?string $navigationGroup = '🏢 Multi-Site';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Halaman')
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
                            ->unique(ignoreRecord: true)
                            ->helperText('URL friendly, contoh: home, tentang, kontak'),

                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(500),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sections_count')
                    ->label('Sections')
                    ->counts('sections')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status Published'),
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
            ->defaultSort('id', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}