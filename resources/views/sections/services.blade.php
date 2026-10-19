@php
    $title = $content['title'] ?? 'Layanan Kami';
    $subtitle = $content['subtitle'] ?? '';
    $items = $content['items'] ?? [];
    $autoplay = $content['autoplay'] ?? true;
    $autoplayDelay = $content['autoplay_delay'] ?? 5000;
    $backgroundType = $content['background_type'] ?? 'light';
    $isDark = $backgroundType === 'dark';
@endphp

@if(!empty($items))
<section class="py-16 lg:py-24 relative overflow-hidden {{ $isDark ? 'bg-gray-900' : '' }}"
         style="{{ !$isDark ? 'background-color: var(--theme-background);' : '' }}">

    {{-- Decorative Blur --}}
    @if($isDark)
        <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full blur-3xl opacity-20 pointer-events-none"
             style="background: var(--theme-primary);"></div>
    @endif

    <div class="relative max-w-7xl mx-auto px-4 lg:px-8">

        {{-- Header --}}
        @if($title)
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold mb-4 {{ $isDark ? 'text-white' : '' }}"
                    style="{{ !$isDark ? 'color: var(--theme-primary);' : '' }}">
                    {{ $title }}
                </h2>
                @if($subtitle)
                    <p class="text-lg max-w-2xl mx-auto {{ $isDark ? 'text-white/70' : 'opacity-70' }}">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        @endif

        {{-- Swiper Showcase --}}
        <div class="swiper-showcase relative">
            <div class="swiper !pb-20">
                <div class="swiper-wrapper">

                    @foreach($items as $item)
                        @php
                            $itemTitle = $item['title'] ?? '';
                            $itemDesc = $item['desc'] ?? $item['description'] ?? '';
                            $itemUrl = $item['url'] ?? '#';
                            $itemImage = $item['image'] ?? $item['image_url'] ?? null;
                            $itemLabel = $item['label'] ?? 'Layanan Unggulan';
                            $itemPrice = $item['price'] ?? null;
                            $itemCtaText = $item['cta_text'] ?? 'Selengkapnya';
                        @endphp

                        <div class="swiper-slide !h-auto">
                            <div class="flex flex-col items-center text-center px-4">

                                {{-- Gambar Utama --}}
                                <div class="relative w-full max-w-3xl mb-10 flex items-center justify-center min-h-[300px] lg:min-h-[420px]">
                                    @if($itemImage)
                                        <img src="{{ Str::startsWith($itemImage, 'http') ? $itemImage : Storage::url($itemImage) }}"
                                             alt="{{ $itemTitle }}"
                                             class="max-h-[300px] lg:max-h-[420px] w-auto object-contain drop-shadow-2xl transition-transform duration-700 hover:scale-105">
                                    @else
                                        {{-- Fallback Icon --}}
                                        <div class="w-48 h-48 lg:w-64 lg:h-64 rounded-3xl flex items-center justify-center shadow-2xl"
                                             style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));">
                                            <svg class="w-24 h-24 lg:w-32 lg:h-32 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="max-w-2xl">
                                    {{-- Label --}}
                                    <div class="text-xs lg:text-sm font-medium uppercase tracking-widest mb-3 {{ $isDark ? 'text-white/60' : 'text-gray-500' }}">
                                        {{ $itemLabel }}
                                    </div>

                                    {{-- Title / Price --}}
                                    @if($itemPrice)
                                        <div class="text-3xl lg:text-5xl font-bold mb-4 {{ $isDark ? 'text-white' : 'text-gray-900' }}">
                                            {{ $itemPrice }}
                                        </div>
                                    @else
                                        <h3 class="text-3xl lg:text-5xl font-bold mb-4 {{ $isDark ? 'text-white' : 'text-gray-900' }}">
                                            {{ $itemTitle }}
                                        </h3>
                                    @endif

                                    {{-- Description --}}
                                    @if($itemDesc)
                                        <p class="text-base lg:text-lg mb-8 leading-relaxed {{ $isDark ? 'text-white/70' : 'text-gray-600' }}">
                                            {{ $itemDesc }}
                                        </p>
                                    @endif

                                    {{-- CTA Button --}}
                                    <a href="{{ $itemUrl }}"
                                       class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full font-semibold text-white transition-all duration-300 hover:shadow-2xl hover:-translate-y-1"
                                       style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));">
                                        {{ $itemCtaText }}
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="swiper-pagination !-bottom-2"></div>
            </div>

            {{-- Navigation Buttons --}}
            <button type="button"
                    class="swiper-showcase-prev absolute left-0 lg:left-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 lg:w-14 lg:h-14 rounded-full bg-white shadow-xl flex items-center justify-center hover:scale-110 transition-all duration-300 border border-gray-100">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button type="button"
                    class="swiper-showcase-next absolute right-0 lg:right-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 lg:w-14 lg:h-14 rounded-full bg-white shadow-xl flex items-center justify-center hover:scale-110 transition-all duration-300 border border-gray-100">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>
</section>

{{-- Custom Styles --}}
<style>
    .swiper-showcase .swiper-pagination-bullet {
        width: 40px;
        height: 3px;
        border-radius: 0;
        background-color: #D1D5DB;
        opacity: 1;
        transition: all 0.3s;
    }

    .swiper-showcase .swiper-pagination-bullet-active {
        background-color: #111827;
        height: 3px;
    }

    .swiper {
        cursor: grab;
    }

    .swiper:active {
        cursor: grabbing;
    }
</style>

{{-- Init Script --}}

@endif