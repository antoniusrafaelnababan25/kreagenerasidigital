@php
    $title = $content['title'] ?? 'Siap Memulai Proyek Anda?';
    $description = $content['description'] ?? '';
    $ctaText = $content['cta_text'] ?? 'Hubungi Kami';
    $ctaUrl = $content['cta_url'] ?? '/kontak';
    $secondaryText = $content['secondary_text'] ?? null;
    $secondaryUrl = $content['secondary_url'] ?? null;
    $waveTop = $content['wave_top'] ?? true;
    $waveBottom = $content['wave_bottom'] ?? true;
@endphp

@if($title)
<section class="relative py-20 lg:py-24 mt-20 mb-20"
         style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));">

    {{-- ═══ WAVE ATAS ═══ --}}
    @if($waveTop)
        <div class="absolute top-0 left-0 right-0 -translate-y-full pointer-events-none">
            <svg class="w-full h-12 lg:h-20" viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path fill="none"
                      stroke="url(#waveGradientTop)"
                      stroke-width="2"
                      d="M0,60 Q360,20 720,60 T1440,60"
                      vector-effect="non-scaling-stroke"/>
                <defs>
                    <linearGradient id="waveGradientTop" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color: var(--theme-primary); stop-opacity: 1"/>
                        <stop offset="100%" style="stop-color: var(--theme-secondary); stop-opacity: 1"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
    @endif

    {{-- ═══ DECORATIVE CIRCLES ═══ --}}
    <div class="absolute top-0 -left-32 w-64 h-64 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 -right-32 w-64 h-64 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>

    {{-- ═══ CONTENT ═══ --}}
    <div class="relative max-w-3xl mx-auto px-4 lg:px-8 text-center text-white">

        <h2 class="text-3xl lg:text-5xl font-bold mb-4 text-balance">
            {{ $title }}
        </h2>

        @if($description)
            <p class="text-lg mb-8 opacity-90 leading-relaxed">
                {{ $description }}
            </p>
        @endif

        <div class="flex flex-wrap justify-center gap-4">
            @if($ctaText)
                <a href="{{ $ctaUrl }}"
                   class="inline-flex items-center justify-center px-8 py-4 bg-white font-semibold rounded-full hover:shadow-2xl transition-all duration-300 hover:-translate-y-1"
                   style="color: var(--theme-primary);">
                    {{ $ctaText }}
                </a>
            @endif

            @if($secondaryText)
                <a href="{{ $secondaryUrl ?? '/portfolio' }}"
                   class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white font-semibold rounded-full hover:bg-white hover:text-gray-900 transition-all duration-300 hover:-translate-y-1">
                    {{ $secondaryText }}
                </a>
            @endif
        </div>
    </div>

    {{-- ═══ WAVE BAWAH ═══ --}}
    @if($waveBottom)
        <div class="absolute bottom-0 left-0 right-0 translate-y-full pointer-events-none">
            <svg class="w-full h-12 lg:h-20" viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path fill="none"
                      stroke="url(#waveGradientBottom)"
                      stroke-width="2"
                      d="M0,60 Q360,100 720,60 T1440,60"
                      vector-effect="non-scaling-stroke"/>
                <defs>
                    <linearGradient id="waveGradientBottom" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color: var(--theme-primary); stop-opacity: 1"/>
                        <stop offset="100%" style="stop-color: var(--theme-secondary); stop-opacity: 1"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
    @endif
</section>
@endif