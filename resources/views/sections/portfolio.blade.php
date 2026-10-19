@php
    $title = $content['title'] ?? 'Portfolio Terbaru';
    $subtitle = $content['subtitle'] ?? 'Karya-karya terbaik kami';
    $limit = $content['limit'] ?? 6;
    $showButton = $content['show_button'] ?? true;
    $buttonText = $content['button_text'] ?? 'Lihat Semua Portfolio';
    $buttonUrl = $content['button_url'] ?? '/portfolio';
    $autoplay = $content['autoplay'] ?? true;
    $autoplayDelay = $content['autoplay_delay'] ?? 5000;
    $backgroundType = $content['background_type'] ?? 'light';
    $isDark = $backgroundType === 'dark';
@endphp

@if(isset($portfolios) && $portfolios->count() > 0)
<section class="py-20 lg:py-28 relative overflow-hidden {{ $isDark ? 'bg-gray-900' : 'bg-gray-50' }}">

    {{-- Decorative Blur --}}
    @if($isDark)
        <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full blur-3xl opacity-20 pointer-events-none"
             style="background: var(--theme-primary);"></div>
    @endif

    <div class="relative max-w-7xl mx-auto px-4 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-16">
            @if($title)
                <h2 class="text-3xl lg:text-4xl font-bold mb-4 {{ $isDark ? 'text-white' : '' }}"
                    style="{{ !$isDark ? 'color: var(--theme-primary);' : '' }}">
                    {{ $title }}
                </h2>
            @endif
            @if($subtitle)
                <p class="text-lg max-w-2xl mx-auto {{ $isDark ? 'text-white/70' : 'opacity-70' }}">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        {{-- Swiper Portfolio --}}
        <div class="swiper-portfolio relative">
            <div class="swiper !pb-20">
                <div class="swiper-wrapper">

                    @foreach($portfolios->take($limit) as $portfolio)
                        <div class="swiper-slide !h-auto">
                            <a href="/portfolio/{{ $portfolio->slug }}"
                               class="group block bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 no-underline">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-0">

                                    {{-- Kiri: Konten --}}
                                    <div class="p-8 lg:p-12 flex flex-col justify-between order-2 md:order-1">

                                        {{-- Top: Badge + Tanggal --}}
                                        <div class="flex items-center justify-between mb-6">
                                            @if($portfolio->service_type)
                                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider text-white"
                                                      style="background-color: var(--theme-primary);">
                                                    {{ ucfirst($portfolio->service_type) }}
                                                </span>
                                            @endif

                                            @if($portfolio->created_at)
                                                <span class="text-xs lg:text-sm font-semibold" style="color: #DC2626;">
                                                    {{ $portfolio->created_at->format('d F Y') }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Judul --}}
                                        <div class="mb-6">
                                            <h3 class="text-xl lg:text-2xl font-bold leading-tight text-gray-900 mb-4 line-clamp-3">
                                                {{ $portfolio->title }}
                                            </h3>

                                            {{-- Client --}}
                                            @if($portfolio->client_name)
                                                <p class="text-sm text-gray-500 mb-3">
                                                    <span class="font-semibold">Klien:</span> {{ $portfolio->client_name }}
                                                </p>
                                            @endif

                                            {{-- Deskripsi --}}
                                            @if($portfolio->short_description)
                                                <p class="text-sm lg:text-base text-gray-600 leading-relaxed line-clamp-3">
                                                    {{ Str::limit($portfolio->short_description, 180) }}
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Bottom: CTA --}}
                                        <div class="mt-auto">
                                            <span class="inline-flex items-center gap-2 px-6 py-3 rounded-full border-2 font-semibold text-sm transition-all duration-300 group-hover:gap-4"
                                                  style="border-color: var(--theme-primary); color: var(--theme-primary);">
                                                Baca Selengkapnya
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Kanan: Gambar --}}
                                    <div class="relative order-1 md:order-2 aspect-video md:aspect-auto md:min-h-[400px] overflow-hidden bg-gray-200">
                                        @if($portfolio->featured_image)
                                            <img src="{{ Storage::url($portfolio->featured_image) }}"
                                                 alt="{{ $portfolio->title }}"
                                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center"
                                                 style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));">
                                                <span class="text-white text-6xl lg:text-8xl font-bold opacity-30">
                                                    {{ strtoupper(substr($portfolio->title, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif

                                        {{-- Overlay Gradient --}}
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none"></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>

                {{-- Pagination --}}
                <div class="swiper-pagination !-bottom-2"></div>
            </div>

            {{-- Navigation Buttons --}}
            <button type="button"
                    class="swiper-portfolio-prev absolute -left-4 lg:-left-6 top-1/2 -translate-y-1/2 z-10 w-12 h-12 lg:w-14 lg:h-14 rounded-full bg-white shadow-xl flex items-center justify-center hover:scale-110 transition-all duration-300 border border-gray-100">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button type="button"
                    class="swiper-portfolio-next absolute -right-4 lg:-right-6 top-1/2 -translate-y-1/2 z-10 w-12 h-12 lg:w-14 lg:h-14 rounded-full bg-white shadow-xl flex items-center justify-center hover:scale-110 transition-all duration-300 border border-gray-100">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        {{-- Lihat Semua Button --}}
        @if($showButton)
            <div class="text-center mt-16">
                <a href="{{ $buttonUrl }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full font-semibold text-white transition-all duration-300 hover:shadow-2xl hover:-translate-y-1"
                   style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));">
                    {{ $buttonText }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</section>

{{-- Custom Styles --}}
<style>
    .swiper-portfolio .swiper-pagination-bullet {
        width: 40px;
        height: 3px;
        border-radius: 0;
        background-color: #D1D5DB;
        opacity: 1;
        transition: all 0.3s;
    }

    .swiper-portfolio .swiper-pagination-bullet-active {
        background: linear-gradient(90deg, var(--theme-primary), var(--theme-secondary));
        height: 3px;
    }

    .swiper {
        cursor: grab;
    }

    .swiper:active {
        cursor: grabbing;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endif