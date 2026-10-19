<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageSectionResource\Pages;
use App\Models\Page;
use App\Models\PageSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageSectionResource extends Resource
{
    protected static ?string $model = PageSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationLabel = 'Page Section';
    protected static ?string $modelLabel = 'Page Section';
    protected static ?string $pluralModelLabel = 'Page Sections';
    protected static ?string $navigationGroup = '🏢 Multi-Site';
    protected static ?int $navigationSort = 4;

    protected static ?string $tenantRelationshipName = 'pageSections';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Section')
                    ->schema([
                        Forms\Components\Select::make('page_id')
                            ->label('Halaman')
                            // ✅ Mengambil data halaman tanpa terkena filter tenant
                            ->options(function () {
                                $tenantId = filament()->getTenant()?->id;
                                return Page::withoutGlobalScopes()
                                    ->where('site_id', $tenantId)
                                    ->pluck('title', 'id');
                            })
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('type')
                            ->label('Tipe Section')
                            ->options([
                                'hero' => 'Hero',
                                'services' => 'Services',
                                'portfolio' => 'Portfolio',
                                'testimonials' => 'Testimonials',
                                'cta' => 'Call to Action',
                                'faq' => 'FAQ',
                                'stats' => 'Stats',
                                'gallery' => 'Gallery',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (callable $set) {
                                $set('content', []);
                            })
                            ->helperText('Pilih tipe section, form konten akan menyesuaikan otomatis'),

                        Forms\Components\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->helperText('Semakin kecil, semakin atas tampilnya'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),

                // ═══════════════════════════════════════════════════
                // KONTEN DINAMIS PER TIPE SECTION
                // ═══════════════════════════════════════════════════

                Forms\Components\Section::make('Konten')
                    ->schema([

                        // ─── HERO ───
                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('content.eyebrow')
                                ->label('Eyebrow (label kecil di atas judul)')
                                ->maxLength(255)
                                ->placeholder('Selamat Datang di KREA')
                                ->default('Selamat Datang di KREA'),

                            Forms\Components\TextInput::make('content.title')
                                ->label('Judul Utama')
                                ->maxLength(255)
                                ->placeholder('KREA')
                                ->default('KREA'),

                            Forms\Components\TextInput::make('content.title_highlight')
                                ->label('Judul Highlight (dengan gradien)')
                                ->maxLength(255)
                                ->placeholder('Generasi Digital')
                                ->default('Generasi Digital'),

                            Forms\Components\TextInput::make('content.subtitle')
                                ->label('Subjudul')
                                ->maxLength(255)
                                ->placeholder('Solusi Digital Terpadu untuk Bisnis Anda')
                                ->default('Solusi Digital Terpadu untuk Bisnis Anda'),

                            Forms\Components\Textarea::make('content.description')
                                ->label('Deskripsi')
                                ->rows(3)
                                ->placeholder('Kami menyediakan layanan website, videografi, rental alat camping, dan custom printing.')
                                ->default('Kami menyediakan layanan website, videografi, rental alat camping, dan custom printing dengan kualitas terbaik.')
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('content.cta_text')
                                ->label('Teks Tombol Utama')
                                ->maxLength(255)
                                ->placeholder('Konsultasi Gratis')
                                ->default('Konsultasi Gratis'),

                            Forms\Components\TextInput::make('content.cta_url')
                                ->label('URL Tombol Utama')
                                ->maxLength(255)
                                ->placeholder('/kontak')
                                ->default('/kontak'),

                            Forms\Components\TextInput::make('content.secondary_text')
                                ->label('Teks Tombol Sekunder')
                                ->maxLength(255)
                                ->placeholder('Lihat Portfolio')
                                ->default('Lihat Portfolio'),

                            Forms\Components\TextInput::make('content.secondary_url')
                                ->label('URL Tombol Sekunder')
                                ->maxLength(255)
                                ->placeholder('/portfolio')
                                ->default('/portfolio'),

                            Forms\Components\FileUpload::make('content.hero_image')
                                ->label('Gambar Background Hero')
                                ->image()
                                ->directory('hero')
                                ->disk('public')
                                ->visibility('public')
                                ->maxSize(3072)
                                ->columnSpanFull()
                                ->helperText('Upload gambar background. Rekomendasi: 1920x1080px, JPG/WebP, max 500KB. Kosongkan untuk pakai gradient warna theme.'),

                            Forms\Components\Select::make('content.height')
                                ->label('Tinggi Hero')
                                ->options([
                                    'small' => 'Kecil (400-500px)',
                                    'medium' => 'Sedang (500-600px)',
                                    'large' => 'Besar (600-800px)',
                                    'full' => 'Fullscreen (100vh)',
                                ])
                                ->default('large'),

                            Forms\Components\Select::make('content.text_align')
                                ->label('Perataan Text')
                                ->options([
                                    'left' => 'Kiri',
                                    'center' => 'Tengah',
                                    'right' => 'Kanan',
                                ])
                                ->default('center'),

                            Forms\Components\TextInput::make('content.overlay_opacity')
                                ->label('Overlay Gelap (0-100)')
                                ->numeric()
                                ->default(60)
                                ->minValue(0)
                                ->maxValue(100)
                                ->helperText('Semakin tinggi, semakin gelap overlay di atas gambar.'),

                            Forms\Components\Toggle::make('content.show_wave')
                                ->label('Tampilkan Wave di Bawah')
                                ->default(true)
                                ->helperText('Curve putih di bawah hero untuk transisi ke section berikutnya'),

                            Forms\Components\ColorPicker::make('content.wave_color')
                                ->label('Warna Wave')
                                ->default('#FFFFFF')
                                ->helperText('Samakan dengan background section berikutnya'),

                            Forms\Components\Repeater::make('content.stats')
                                ->label('Statistik (opsional)')
                                ->schema([
                                    Forms\Components\TextInput::make('value')
                                        ->label('Nilai')
                                        ->placeholder('100+')
                                        ->required(),
                                    Forms\Components\TextInput::make('label')
                                        ->label('Label')
                                        ->placeholder('Klien Puas')
                                        ->required(),
                                ])
                                ->columns(2)
                                ->defaultItems(3)
                                ->default([
                                    ['value' => '100+', 'label' => 'Klien Puas'],
                                    ['value' => '500+', 'label' => 'Proyek Selesai'],
                                    ['value' => '5+', 'label' => 'Tahun Pengalaman'],
                                ])
                                ->reorderable()
                                ->collapsible()
                                ->columnSpanFull()
                                ->helperText('Kosongkan kalau tidak mau tampil statistik'),
                        ])
                        ->columns(2)
                        ->visible(fn (callable $get) => $get('type') === 'hero'),

                        // ─── SERVICES ───
                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('content.title')
                                ->label('Judul Section')
                                ->maxLength(255)
                                ->placeholder('Layanan Kami')
                                ->default('Layanan Kami'),

                            Forms\Components\TextInput::make('content.subtitle')
                                ->label('Subjudul Section')
                                ->maxLength(255)
                                ->placeholder('Empat layanan utama untuk kebutuhan digital Anda')
                                ->default('Empat layanan utama untuk kebutuhan digital Anda'),

                            Forms\Components\Select::make('content.background_type')
                                ->label('Tipe Background')
                                ->options([
                                    'light' => 'Terang (Light)',
                                    'dark' => 'Gelap (Dark)',
                                ])
                                ->default('light'),

                            Forms\Components\Select::make('content.columns')
                                ->label('Jumlah Kolom (Desktop)')
                                ->options([2 => '2 Kolom', 3 => '3 Kolom', 4 => '4 Kolom'])
                                ->default(4),

                            Forms\Components\Repeater::make('content.items')
                                ->label('Item Layanan')
                                ->schema([
                                    Forms\Components\FileUpload::make('image')
                                        ->label('Upload Gambar / Icon')
                                        ->image()
                                        ->directory('services')
                                        ->disk('public')
                                        ->visibility('public')
                                        ->maxSize(2048)
                                        ->imagePreviewHeight('120')
                                        ->helperText('Upload icon/gambar. Rekomendasi: PNG transparan 400x400px, max 2MB.')
                                        ->columnSpanFull(),

                                    Forms\Components\TextInput::make('title')
                                        ->label('Judul')
                                        ->required()
                                        ->placeholder('Web Development'),

                                    Forms\Components\Textarea::make('desc')
                                        ->label('Deskripsi')
                                        ->required()
                                        ->rows(2)
                                        ->placeholder('Jasa pembuatan website profesional'),

                                    Forms\Components\TextInput::make('url')
                                        ->label('URL')
                                        ->placeholder('/layanan/website'),
                                ])
                                ->columns(1)
                                ->defaultItems(4)
                                ->default([
                                    ['title' => 'Web Development', 'desc' => 'Jasa pembuatan website profesional', 'url' => '/layanan/website'],
                                    ['title' => 'Rental Camping', 'desc' => 'Sewa alat camping lengkap', 'url' => '/layanan/rental'],
                                    ['title' => 'Video Production', 'desc' => 'Produksi video profesional', 'url' => '/layanan/video'],
                                    ['title' => 'Custom Printing', 'desc' => 'Cetak custom kaos & merchandise', 'url' => '/layanan/printing'],
                                ])
                                ->reorderable()
                                ->collapsible()
                                ->columnSpanFull()
                                ->helperText('Klik "Add item" untuk tambah layanan. Drag handle untuk atur urutan.'),
                        ])
                        ->columns(2)
                        ->visible(fn (callable $get) => $get('type') === 'services'),

                        // ─── PORTFOLIO ───
                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('content.title')
                                ->label('Judul Section')
                                ->maxLength(255)
                                ->placeholder('Portfolio Terbaru')
                                ->default('Portfolio Terbaru'),

                            Forms\Components\TextInput::make('content.subtitle')
                                ->label('Subjudul')
                                ->maxLength(255)
                                ->placeholder('Karya-karya terbaik kami')
                                ->default('Karya-karya terbaik kami'),

                            Forms\Components\TextInput::make('content.limit')
                                ->label('Jumlah Item')
                                ->numeric()
                                ->default(6),

                            Forms\Components\Select::make('content.background_type')
                                ->label('Tipe Background')
                                ->options([
                                    'light' => 'Terang (Light)',
                                    'dark' => 'Gelap (Dark)',
                                ])
                                ->default('light'),

                            Forms\Components\Toggle::make('content.autoplay')
                                ->label('Auto-Play Carousel')
                                ->default(true),

                            Forms\Components\TextInput::make('content.autoplay_delay')
                                ->label('Delay Auto-Play (ms)')
                                ->numeric()
                                ->default(5000),

                            Forms\Components\Toggle::make('content.show_button')
                                ->label('Tampilkan Tombol "Lihat Semua"')
                                ->default(true),

                            Forms\Components\TextInput::make('content.button_text')
                                ->label('Teks Tombol')
                                ->maxLength(255)
                                ->placeholder('Lihat Semua Portfolio')
                                ->default('Lihat Semua Portfolio'),

                            Forms\Components\TextInput::make('content.button_url')
                                ->label('URL Tombol')
                                ->maxLength(255)
                                ->placeholder('/portfolio')
                                ->default('/portfolio'),
                        ])
                        ->columns(2)
                        ->visible(fn (callable $get) => $get('type') === 'portfolio'),

                        // ─── TESTIMONIALS ───
                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('content.title')
                                ->label('Judul Section')
                                ->maxLength(255)
                                ->placeholder('Apa Kata Klien Kami')
                                ->default('Apa Kata Klien Kami'),

                            Forms\Components\TextInput::make('content.subtitle')
                                ->label('Subjudul')
                                ->maxLength(255)
                                ->placeholder('Testimoni dari klien yang puas'),

                            Forms\Components\TextInput::make('content.limit')
                                ->label('Jumlah Testimoni')
                                ->numeric()
                                ->default(3),
                        ])
                        ->columns(2)
                        ->visible(fn (callable $get) => $get('type') === 'testimonials'),

                        // ─── CTA ───
                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('content.title')
                                ->label('Judul CTA')
                                ->maxLength(255)
                                ->placeholder('Siap Memulai Proyek Anda?')
                                ->default('Siap Memulai Proyek Anda?'),

                            Forms\Components\Textarea::make('content.description')
                                ->label('Deskripsi')
                                ->rows(2)
                                ->placeholder('Hubungi kami sekarang untuk konsultasi gratis.')
                                ->default('Hubungi kami sekarang untuk konsultasi gratis.')
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('content.cta_text')
                                ->label('Teks Tombol Utama')
                                ->maxLength(255)
                                ->placeholder('Hubungi Kami')
                                ->default('Hubungi Kami'),

                            Forms\Components\TextInput::make('content.cta_url')
                                ->label('URL Tombol Utama')
                                ->maxLength(255)
                                ->placeholder('/kontak')
                                ->default('/kontak'),

                            Forms\Components\TextInput::make('content.secondary_text')
                                ->label('Teks Tombol Sekunder')
                                ->maxLength(255)
                                ->placeholder('Lihat Portfolio'),

                            Forms\Components\TextInput::make('content.secondary_url')
                                ->label('URL Tombol Sekunder')
                                ->maxLength(255)
                                ->placeholder('/portfolio'),
                        ])
                        ->columns(2)
                        ->visible(fn (callable $get) => $get('type') === 'cta'),

                        // ─── FAQ ───
                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('content.title')
                                ->label('Judul Section')
                                ->maxLength(255)
                                ->placeholder('Pertanyaan Umum')
                                ->default('Pertanyaan Umum'),

                            Forms\Components\TextInput::make('content.subtitle')
                                ->label('Subjudul')
                                ->maxLength(255)
                                ->placeholder('Pertanyaan yang sering ditanyakan'),

                            Forms\Components\TextInput::make('content.limit')
                                ->label('Jumlah FAQ')
                                ->numeric()
                                ->default(10),
                        ])
                        ->columns(2)
                        ->visible(fn (callable $get) => $get('type') === 'faq'),

                        // ─── STATS ───
                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('content.title')
                                ->label('Judul Section')
                                ->maxLength(255)
                                ->placeholder('Pencapaian Kami')
                                ->default('Pencapaian Kami'),

                            Forms\Components\TextInput::make('content.subtitle')
                                ->label('Subjudul')
                                ->maxLength(255)
                                ->placeholder('Angka yang membanggakan')
                                ->default('Angka yang membanggakan'),

                            Forms\Components\Repeater::make('content.items')
                                ->label('Item Statistik')
                                ->schema([
                                    Forms\Components\TextInput::make('value')
                                        ->label('Nilai')
                                        ->required()
                                        ->placeholder('500+'),
                                    Forms\Components\TextInput::make('label')
                                        ->label('Label')
                                        ->required()
                                        ->placeholder('Proyek Selesai'),
                                ])
                                ->columns(2)
                                ->defaultItems(3)
                                ->default([
                                    ['value' => '100+', 'label' => 'Klien Puas'],
                                    ['value' => '500+', 'label' => 'Proyek Selesai'],
                                    ['value' => '5+', 'label' => 'Tahun Pengalaman'],
                                ])
                                ->reorderable()
                                ->collapsible()
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->visible(fn (callable $get) => $get('type') === 'stats'),

                        // ─── GALLERY ───
                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('content.title')
                                ->label('Judul Section')
                                ->maxLength(255)
                                ->placeholder('Galeri Kami')
                                ->default('Galeri Kami'),

                            Forms\Components\TextInput::make('content.subtitle')
                                ->label('Subjudul')
                                ->maxLength(255)
                                ->placeholder('Dokumentasi kegiatan kami'),

                            Forms\Components\Select::make('content.columns')
                                ->label('Jumlah Kolom')
                                ->options([2 => '2 Kolom', 3 => '3 Kolom', 4 => '4 Kolom'])
                                ->default(3),

                            Forms\Components\FileUpload::make('content.images')
                                ->label('Gambar Galeri')
                                ->image()
                                ->multiple()
                                ->reorderable()
                                ->directory('gallery')
                                ->disk('public')
                                ->visibility('public')
                                ->maxSize(2048)
                                ->columnSpanFull()
                                ->helperText('Upload multiple gambar. Bisa drag & drop untuk reorder.'),
                        ])
                        ->columns(2)
                        ->visible(fn (callable $get) => $get('type') === 'gallery'),
                    ])
                    ->visible(fn (callable $get) => !empty($get('type'))),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),

                Tables\Columns\TextColumn::make('page.title')
                    ->label('Halaman')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('order')->label('Urutan')->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe Section')
                    ->options([
                        'hero' => 'Hero',
                        'services' => 'Services',
                        'portfolio' => 'Portfolio',
                        'testimonials' => 'Testimonials',
                        'cta' => 'Call to Action',
                        'faq' => 'FAQ',
                        'stats' => 'Stats',
                        'gallery' => 'Gallery',
                    ]),

                Tables\Filters\SelectFilter::make('page_id')
                    ->label('Halaman')
                    ->options(function () {
                        $tenantId = filament()->getTenant()?->id;
                        return Page::withoutGlobalScopes()
                            ->where('site_id', $tenantId)
                            ->pluck('title', 'id');
                    }),

                Tables\Filters\TernaryFilter::make('is_active')->label('Status Aktif'),
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
            ->defaultSort(fn ($query) => $query->orderBy('page_id')->orderBy('order'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPageSections::route('/'),
            'create' => Pages\CreatePageSection::route('/create'),
            'edit' => Pages\EditPageSection::route('/{record}/edit'),
        ];
    }
}