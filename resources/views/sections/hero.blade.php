@php
    // ═══ PERBAIKAN PATH GAMBAR ═══
    $rawImage = $content['background_image'] ?? $content['hero_image'] ?? null;
    $backgroundImage = null;
    
    if ($rawImage) {
        if (Str::startsWith($rawImage, 'http')) {
            $backgroundImage = $rawImage;
        } else {
            // Membersihkan string dari awalan 'public/' atau 'storage/' jika ada
            $cleanPath = str_replace(['public/', 'storage/'], '', $rawImage);
            $backgroundImage = asset('storage/' . $cleanPath);
        }
    }

    // Variabel lainnya
    $title = $content['title'] ?? '';
    $titleHighlight = $content['title_highlight'] ?? '';
    $subtitle = $content['subtitle'] ?? '';
    $description = $content['description'] ?? '';
    $ctaText = $content['cta_text'] ?? null;
    $ctaUrl = $content['cta_url'] ?? '/kontak';
    $secondaryText = $content['secondary_text'] ?? null;
    $secondaryUrl = $content['secondary_url'] ?? null;
    $eyebrow = $content['eyebrow'] ?? null;
    $stats = $content['stats'] ?? [];
    $overlayOpacity = (int) ($content['overlay_opacity'] ?? 60);
    $textAlign = $content['text_align'] ?? 'center';
    $height = $content['height'] ?? 'large';
    $showWave = $content['show_wave'] ?? true;
    $waveColor = $content['wave_color'] ?? '#FFFFFF';

    $heightClass = match($height) {
        'small' => 'min-h-[500px] lg:min-h-[600px]',
        'medium' => 'min-h-[600px] lg:min-h-[700px]',
        'large' => 'min-h-[700px] lg:min-h-[850px]',
        'full' => 'min-h-screen',
        default => 'min-h-[700px] lg:min-h-[850px]',
    };

    $alignClass = match($textAlign) {
        'left' => 'text-left items-start',
        'right' => 'text-right items-end',
        'center' => 'text-center items-center',
        default => 'text-center items-center',
    };
@endphp

<section class="relative overflow-hidden {{ $heightClass }} flex items-center">
    
    {{-- ═══ BACKGROUND IMAGE ═══ --}}
    @if($backgroundImage)
        <div class="absolute inset-0 z-0">
            <img src="{{ $backgroundImage }}" alt="{{ $title }}" class="w-full h-full object-cover">
        </div>
    @else
        <div class="absolute inset-0 z-0" style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));"></div>
    @endif

    {{-- ═══ OVERLAYS, PATTERNS, & SHAPES ═══ --}}
    <div class="absolute inset-0 z-10 opacity-[0.07] pointer-events-none">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="hero-dots-{{ $section['id'] ?? uniqid() }}" width="30" height="30" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hero-dots-{{ $section['id'] ?? uniqid() }})"/>
        </svg>
    </div>
    <div class="absolute top-0 left-0 right-0 z-10 pointer-events-none" style="height: 220px; background: linear-gradient(180deg, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.25) 50%, transparent 100%);"></div>
    <div class="absolute inset-0 z-10" style="background: linear-gradient(180deg, rgba(0,0,0,{{ $overlayOpacity / 100 }}) 0%, rgba(0,0,0,{{ ($overlayOpacity + 15) / 100 }}) 100%);"></div>
    <div class="absolute bottom-0 left-0 right-0 z-10 pointer-events-none" style="height: 220px; background: linear-gradient(0deg, rgba(0,0,0,0.3) 0%, transparent 100%);"></div>

    {{-- ═══ CONTENT ═══ --}}
    <div class="relative z-20 w-full max-w-7xl mx-auto px-4 lg:px-8 pt-32 lg:pt-40 pb-24 lg:pb-32">
        <div class="flex flex-col {{ $alignClass }} {{ $textAlign === 'center' ? 'mx-auto max-w-4xl' : 'max-w-3xl' }}">
            @if($eyebrow)
                <div class="inline-flex items-center gap-3 mb-6 text-xs font-semibold uppercase tracking-widest text-white/90 animate-fade-in-up">
                    <span class="w-8 h-px bg-white/60"></span>
                    <span>{{ $eyebrow }}</span>
                    <span class="w-8 h-px bg-white/60"></span>
                </div>
            @endif

            @if($title)
                <h1 class="text-4xl lg:text-6xl xl:text-7xl font-bold mb-6 leading-[1.1] tracking-tight text-white text-balance animate-fade-in-up" style="animation-delay: 0.1s; text-shadow: 0 4px 30px rgba(0,0,0,0.3);">
                    {{ $title }}
                    @if($titleHighlight)
                        <span class="block mt-2" style="background: linear-gradient(135deg, var(--theme-accent), #FFFFFF); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; filter: drop-shadow(0 4px 30px rgba(0,0,0,0.3));">
                            {{ $titleHighlight }}
                        </span>
                    @endif
                </h1>
            @endif

            @if($subtitle)
                <p class="text-xl lg:text-2xl mb-4 text-white/95 leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s; text-shadow: 0 2px 15px rgba(0,0,0,0.3);">
                    {!! $subtitle !!}
                </p>
            @endif

            @if($description)
                <p class="text-base lg:text-lg mb-8 text-white/85 leading-relaxed max-w-2xl animate-fade-in-up" style="animation-delay: 0.3s; text-shadow: 0 1px 10px rgba(0,0,0,0.2);">
                    {{ $description }}
                </p>
            @endif

            @if($ctaText || $secondaryText)
                <div class="flex flex-wrap gap-4 mb-10 animate-fade-in-up {{ $textAlign === 'center' ? 'justify-center' : '' }}" style="animation-delay: 0.4s;">
                    @if($ctaText)
                        <a href="{{ $ctaUrl }}" class="group inline-flex items-center justify-center gap-2 px-8 py-4 font-semibold rounded-full transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 text-white" style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary)); box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                            {{ $ctaText }}
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @endif
                    @if($secondaryText)
                        <a href="{{ $secondaryUrl ?? '/portfolio' }}" class="group inline-flex items-center justify-center gap-2 px-8 py-4 font-semibold rounded-full border-2 border-white/80 text-white backdrop-blur-sm transition-all duration-300 hover:bg-white hover:text-gray-900 hover:-translate-y-1" style="background-color: rgba(255,255,255,0.1);">
                            {{ $secondaryText }}
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endif
                </div>
            @endif

            @if(!empty($stats))
                <div class="flex flex-wrap gap-8 lg:gap-12 pt-8 border-t border-white/20 animate-fade-in-up {{ $textAlign === 'center' ? 'justify-center' : '' }}" style="animation-delay: 0.5s;">
                    @foreach($stats as $stat)
                        <div class="{{ $textAlign === 'center' ? 'text-center' : '' }}">
                            <div class="text-3xl lg:text-4xl font-bold text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">{{ $stat['value'] ?? '0' }}</div>
                            <div class="text-sm mt-1 text-white/80" style="text-shadow: 0 1px 6px rgba(0,0,0,0.2);">{{ $stat['label'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if($showWave)
        <div class="absolute bottom-0 left-0 right-0 z-20 pointer-events-none">
            <svg class="w-full h-16 lg:h-24" viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path fill="{{ $waveColor }}" fill-opacity="1" d="M0,64L48,58.7C96,53,192,43,288,48C384,53,480,75,576,80C672,85,768,75,864,64C960,53,1056,43,1152,48C1248,53,1344,75,1392,85.3L1440,96L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"></path>
            </svg>
        </div>
    @endif
</section>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
</style>