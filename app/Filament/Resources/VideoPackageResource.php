<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoPackageResource\Pages;
use App\Models\VideoPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VideoPackageResource extends Resource
{
    protected static ?string $model = VideoPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationLabel = 'Paket Video';

    protected static ?string $modelLabel = 'Paket Video';

    protected static ?string $pluralModelLabel = 'Paket Video';

    protected static ?string $navigationGroup = '🎨 Paket Layanan';

    protected static ?int $navigationSort = 2;

    // ─── Filter: cuma muncul di tenant video ───
    public static function shouldRegisterNavigation(): bool
    {
        return filament()->getTenant()?->key === 'video';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Paket')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Paket')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'wedding' => 'Wedding',
                                'corporate' => 'Corporate',
                                'event' => 'Event',
                                'content' => 'Content Creator',
                            ]),

                        Forms\Components\Textarea::make('description')->label('Deskripsi')->rows(3)->columnSpanFull(),

                        Forms\Components\Textarea::make('features')->label('Fitur (enter per baris)')->rows(5)->columnSpanFull(),

                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Gambar')
                            ->image()
                            ->directory('video-packages')
                            ->maxSize(2048),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Harga & Detail')
                    ->schema([
                        Forms\Components\TextInput::make('price')->label('Harga')->numeric()->prefix('Rp')->required(),
                        Forms\Components\TextInput::make('compare_price')->label('Harga Sebelum Diskon')->numeric()->prefix('Rp'),
                        Forms\Components\TextInput::make('duration')->label('Durasi')->maxLength(255),
                        Forms\Components\TextInput::make('crew_count')->label('Jumlah Crew')->numeric()->default(1),
                        Forms\Components\TextInput::make('output_format')->label('Format Output')->maxLength(255),
                        Forms\Components\TextInput::make('revision_count')->label('Jumlah Revisi')->numeric()->default(1),
                        Forms\Components\TextInput::make('delivery_days')->label('Hari Pengerjaan')->numeric()->default(7),
                        Forms\Components\TextInput::make('order')->label('Urutan')->numeric()->default(0),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\Toggle::make('is_featured')->label('Featured'),
                        Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Paket')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->label('Kategori')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('price')->label('Harga')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('crew_count')->label('Crew')->badge()->color('info'),
                Tables\Columns\TextColumn::make('delivery_days')->label('Pengerjaan')->suffix(' hari'),
                Tables\Columns\IconColumn::make('is_featured')->label('Featured')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('order')->label('Urutan')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'wedding' => 'Wedding',
                        'corporate' => 'Corporate',
                        'event' => 'Event',
                        'content' => 'Content Creator',
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
                Tables\Filters\TernaryFilter::make('is_active')->label('Aktif'),
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
            'index' => Pages\ListVideoPackages::route('/'),
            'create' => Pages\CreateVideoPackage::route('/create'),
            'edit' => Pages\EditVideoPackage::route('/{record}/edit'),
        ];
    }
}