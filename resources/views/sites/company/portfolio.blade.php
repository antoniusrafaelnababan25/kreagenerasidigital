@extends('layouts.app')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(isset($sections['hero']))
    @include('sections.hero', [
        'content' => $sections['hero']['content'] ?? [],
        'section' => $sections['hero'],
    ])
@else
    {{-- Fallback Hero Default --}}
    <section class="pf-hero">
        <div class="pf-hero-glow pf-hero-glow-1"></div>
        <div class="pf-hero-glow pf-hero-glow-2"></div>
        <div class="pf-container pf-z-10 pf-text-center">
            <nav class="pf-breadcrumb">
                <a href="/">Beranda</a>
                <span>/</span>
                <span class="pf-breadcrumb-active">Portfolio</span>
            </nav>
            <h1 class="pf-hero-title">Portfolio Kami</h1>
            <p class="pf-hero-desc">
                Karya-karya terbaik KREA Generasi Digital untuk klien-klien kami.
            </p>
        </div>
    </section>
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- SHOWCASE (SWIPER CAROUSEL) --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($portfolios->count() > 0)
    @php
        $featuredPortfolios = $portfolios->take(3); 
    @endphp

    {{-- Panggil CSS Swiper via CDN & Custom Style --}}
    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        /* Custom Style untuk Swiper Portfolio */
        .pf-swiper-section { padding: 4rem 0 2rem; background: #ffffff; position: relative; z-index: 20; }
        
        .swiper-pagination { position: relative !important; margin-top: 1.5rem; }
        .swiper-pagination-bullet { background: #cbd5e1; opacity: 1; width: 8px; height: 8px; transition: all 0.3s; }
        .swiper-pagination-bullet-active { background: var(--theme-primary); width: 24px; border-radius: 4px; }
        
        /* Tombol Navigasi Melingkar */
        .swiper-button-next, .swiper-button-prev { 
            width: 48px; height: 48px; 
            background: #ffffff; 
            border-radius: 50%; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.08); 
            color: #0f172a; 
            border: 1px solid #f1f5f9; 
            transition: all 0.3s;
        }
        .swiper-button-next:after, .swiper-button-prev:after { font-size: 18px; font-weight: 900; }
        .swiper-button-next:hover, .swiper-button-prev:hover { background: #f8fafc; transform: scale(1.05); }
        .swiper-button-prev { left: -24px; }
        .swiper-button-next { right: -24px; }
        
        @media (max-width: 1024px) {
            .swiper-button-prev, .swiper-button-next { display: none !important; }
        }

        /* Desain Kartu di dalam Swiper */
        .pf-slide-card {
            background-color: #f8fafc;
            border-radius: 2rem;
            padding: 2rem 2rem 0 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
            position: relative;
            min-height: 500px;
            border: 1px solid #f1f5f9;
        }
        @media(min-width: 1024px) { .pf-slide-card { padding: 3rem 3rem 0 3rem; } }

        .pf-slide-img-wrap { position: relative; width: 100%; display: flex; justify-content: center; margin-bottom: 1.5rem; }
        .pf-slide-img { width: 100%; max-width: 500px; object-fit: contain; z-index: 10; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.15)); transition: transform 0.7s; }
        .pf-slide-card:hover .pf-slide-img { transform: scale(1.05); }
        
        .pf-slide-content { z-index: 10; display: flex; flex-direction: column; align-items: center; max-width: 600px; margin: 0 auto; }
        .pf-slide-eyebrow { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: #94a3b8; margin-bottom: 0.75rem; }
        .pf-slide-title { font-size: 1.75rem; font-weight: 800; color: #0f172a; line-height: 1.2; margin-bottom: 1rem; }
        .pf-slide-desc { font-size: 0.9rem; color: #64748b; margin-bottom: 1.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        
        .pf-slide-year-box { margin-bottom: 1.5rem; }
        .pf-slide-year-label { font-size: 11px; font-weight: 600; color: #94a3b8; margin-bottom: 0.25rem; }
        .pf-slide-year-val { font-size: 2.5rem; font-weight: 900; color: var(--theme-primary); line-height: 1; }
        
        .pf-slide-actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 0.75rem; }
        .pf-btn-outline {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.875rem 1.5rem; border-radius: 50px;
            font-size: 0.9rem; font-weight: 700; text-decoration: none;
            color: #1e293b; background: #ffffff; border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }
        .pf-btn-outline:hover { background: #f8fafc; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .pf-btn-outline svg { width: 16px; height: 16px; }
    </style>
    @endpush

    <section class="pf-swiper-section">
        <div class="pf-container-wide pf-max-w-5xl">
            
            <div class="swiper featuredSwiper">
                <div class="swiper-wrapper">
                    @foreach($featuredPortfolios as $item)
                    <div class="swiper-slide h-auto">
                        
                        {{-- KOTAK UTAMA CAROUSEL --}}
                        <div class="pf-slide-card">
                            
                            {{-- Gambar --}}
                            <div class="pf-slide-img-wrap">
                                @if($item->featured_image)
                                    <img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->title }}" class="pf-slide-img">
                                @else
                                    <div class="pf-slide-img-fallback">
                                        <span>{{ strtoupper(substr($item->title, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Teks & CTA --}}
                            <div class="pf-slide-content">
                                <span class="pf-slide-eyebrow">{{ ucfirst($item->service_type ?? 'PROJECT') }}</span>
                                <h2 class="pf-slide-title">{{ $item->title }}</h2>
                                
                                @if($item->short_description)
                                    <p class="pf-slide-desc">{{ Str::limit($item->short_description, 120) }}</p>
                                @endif

                                @if($item->project_year)
                                    <div class="pf-slide-year-box">
                                        <div class="pf-slide-year-label">Tahun Rilis</div>
                                        <div class="pf-slide-year-val">{{ $item->project_year }}</div>
                                    </div>
                                @endif

                                <div class="pf-slide-actions">
                                    <a href="{{ route('company.portfolio.detail', $item->slug) }}" class="pf-btn-outline">
                                        Lihat Detail
                                    </a>
                                    <a href="https://wa.me/6281234567890" target="_blank" class="pf-btn-outline">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        Chat Sales
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="swiper-pagination"></div>
            </div>

            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </section>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper(".featuredSwiper", {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                grabCursor: true,
                autoplay: { delay: 6000, disableOnInteraction: false },
                pagination: { el: ".swiper-pagination", clickable: true },
                navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            });
        });
    </script>
    @endpush
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- FILTER KATEGORI (PILLS) --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($services->count() > 0)
<section class="pf-filter-section">
    <div class="pf-container-wide pf-flex-center">
        
        <div class="pf-filter-capsule">
            {{-- Tombol Semua --}}
            <a href="{{ route('company.portfolio') }}" class="pf-filter-btn {{ !request('service') ? 'is-active' : '' }}">
                Semua
            </a>

            {{-- Tombol Per Kategori --}}
            @foreach($services as $service)
                <a href="{{ route('company.portfolio', ['service' => $service]) }}" class="pf-filter-btn {{ request('service') === $service ? 'is-active' : '' }}">
                    {{ ucfirst($service) }}
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- PORTFOLIO GRID --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<section class="pf-grid-section">
    <div class="pf-container-wide">

        @if($portfolios->count() > 0)

            {{-- Header Grid --}}
            <div class="pf-grid-header">
                <div>
                    <h2 class="pf-grid-title">
                        @if(request('service'))
                            Portfolio {{ ucfirst(request('service')) }}
                        @else
                            Semua Portfolio
                        @endif
                    </h2>
                    <p class="pf-grid-count">
                        Menampilkan <strong>{{ $portfolios->count() }}</strong> dari <strong>{{ $portfolios->total() }}</strong> proyek
                    </p>
                </div>

                @if(request('service'))
                    <a href="{{ route('company.portfolio') }}" class="pf-btn-reset">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reset Filter
                    </a>
                @endif
            </div>

            {{-- Grid Cards --}}
            <div class="pf-grid">
                @foreach($portfolios as $portfolio)
                    <a href="{{ route('company.portfolio.detail', $portfolio->slug) }}" class="pf-card">

                        {{-- Image --}}
                        <div class="pf-card-img-wrap">
                            @if($portfolio->featured_image)
                                <img src="{{ Storage::url($portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="pf-card-img">
                            @else
                                <div class="pf-card-img-fallback">
                                    <span>{{ strtoupper(substr($portfolio->title, 0, 1)) }}</span>
                                </div>
                            @endif

                            {{-- Badges --}}
                            <div class="pf-card-badges">
                                @if($portfolio->service_type)
                                    <span class="pf-badge-dark">{{ ucfirst($portfolio->service_type) }}</span>
                                @endif
                                @if($portfolio->project_year)
                                    <span class="pf-badge-light">{{ $portfolio->project_year }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="pf-card-content">
                            <h3 class="pf-card-title">{{ $portfolio->title }}</h3>
                            
                            @if($portfolio->client_name)
                                <div class="pf-card-client">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ $portfolio->client_name }}
                                </div>
                            @endif

                            <div class="pf-card-footer">
                                <span class="pf-card-link-text">Lihat Detail</span>
                                <svg class="pf-card-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pf-pagination">
                {{ $portfolios->withQueryString()->links() }}
            </div>

        @else
            {{-- Empty State --}}
            <div class="pf-empty">
                <div class="pf-empty-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3>Belum Ada Portfolio</h3>
                <p>Portfolio akan segera hadir.</p>
            </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- CTA SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(isset($sections['cta']))
    @include('sections.cta', [
        'content' => $sections['cta']['content'] ?? [],
        'section' => $sections['cta'],
    ])
@elseif($portfolios->count() > 0)
    <section class="pf-cta-section">
        <div class="pf-container pf-z-10 pf-text-center">
            <h2 class="pf-cta-title">Punya Proyek Menarik?</h2>
            <p class="pf-cta-desc">
                Konsultasikan kebutuhan digital Anda dengan tim kami. Gratis!
            </p>
            <a href="/kontak" class="pf-btn-cta">
                Konsultasi Gratis
            </a>
        </div>
    </section>
@endif

@endsection

@push('styles')
<style>
/* ============================================================ */
/* CUSTOM CSS HALAMAN PORTFOLIO INDEX */
/* ============================================================ */

/* --- Container & Utility --- */
.pf-container { max-width: 900px; margin: 0 auto; padding: 0 1.5rem; position: relative; }
.pf-container-wide { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; position: relative; }
.pf-max-w-5xl { max-width: 1024px; }
.pf-z-10 { z-index: 10; }
.pf-text-center { text-align: center; }
.pf-flex-center { display: flex; justify-content: center; }

/* --- HERO SECTION --- */
.pf-hero {
    position: relative;
    padding: 8rem 0 6rem;
    background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));
    overflow: hidden;
    color: #ffffff;
}
.pf-hero-glow {
    position: absolute;
    width: 400px; height: 400px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    filter: blur(80px);
    pointer-events: none;
}
.pf-hero-glow-1 { top: -10%; left: -10%; }
.pf-hero-glow-2 { bottom: -10%; right: -10%; }

.pf-breadcrumb {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;
    color: rgba(255,255,255,0.7); margin-bottom: 1.5rem;
}
.pf-breadcrumb a { color: inherit; text-decoration: none; transition: color 0.2s; }
.pf-breadcrumb a:hover { color: #ffffff; }
.pf-breadcrumb-active { color: #ffffff; }

.pf-hero-title { font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem; letter-spacing: -0.02em; }
.pf-hero-desc { font-size: 1.125rem; color: rgba(255,255,255,0.9); max-width: 600px; margin: 0 auto; line-height: 1.6; }

/* --- FILTER SECTION --- */
.pf-filter-section { padding: 2rem 0; background: #ffffff; position: relative; z-index: 10; }
.pf-filter-capsule {
    display: inline-flex; flex-wrap: wrap; justify-content: center; gap: 0.25rem;
    padding: 0.375rem; border-radius: 50px; background: #ffffff;
    border: 1px solid #f1f5f9; box-shadow: 0 4px 20px -4px rgba(0,0,0,0.05);
}
.pf-filter-btn {
    padding: 0.6rem 1.5rem; border-radius: 50px; font-size: 0.875rem; font-weight: 700;
    color: #64748b; text-decoration: none; transition: all 0.3s;
}
.pf-filter-btn:hover { color: #0f172a; background: #f8fafc; }
.pf-filter-btn.is-active { color: #ffffff; background: #0f172a; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

/* --- GRID SECTION --- */
.pf-grid-section { padding: 5rem 0; background: #ffffff; }

.pf-grid-header {
    display: flex; flex-direction: column; gap: 1.5rem;
    margin-bottom: 2.5rem; padding-bottom: 1.5rem;
    border-bottom: 1px solid #f1f5f9;
}
@media (min-width: 768px) { .pf-grid-header { flex-direction: row; align-items: flex-end; justify-content: space-between; } }

.pf-grid-title { font-size: 2rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem 0; letter-spacing: -0.02em; }
.pf-grid-count { font-size: 0.9rem; color: #64748b; margin: 0; }
.pf-grid-count strong { color: #0f172a; font-weight: 700; }

.pf-btn-reset {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-size: 0.85rem; font-weight: 700; padding: 0.6rem 1rem;
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
    color: #475569; text-decoration: none; transition: all 0.2s;
}
.pf-btn-reset svg { width: 16px; height: 16px; }
.pf-btn-reset:hover { background: #f1f5f9; color: #0f172a; }

/* Grid Layout */
.pf-grid { display: grid; grid-template-columns: 1fr; gap: 2rem; }
@media (min-width: 768px) { .pf-grid { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 1024px) { .pf-grid { grid-template-columns: repeat(3, 1fr); } }

.pf-card {
    display: flex; flex-direction: column; background: #ffffff;
    border-radius: 1rem; overflow: hidden; border: 1px solid #f1f5f9;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02); text-decoration: none;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.pf-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }

.pf-card-img-wrap { position: relative; aspect-ratio: 4/3; overflow: hidden; background: #f8fafc; }
.pf-card-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s ease; }
.pf-card:hover .pf-card-img { transform: scale(1.05); }
.pf-card-img-fallback { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f1f5f9; }
.pf-card-img-fallback span { font-size: 4rem; font-weight: 700; color: #cbd5e1; }

.pf-card-badges {
    position: absolute; top: 1rem; left: 1rem; right: 1rem;
    display: flex; justify-content: space-between; align-items: flex-start; pointer-events: none;
}
.pf-badge-dark {
    padding: 0.25rem 0.75rem; border-radius: 6px;
    background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
    color: #ffffff; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
}
.pf-badge-light {
    padding: 0.25rem 0.75rem; border-radius: 6px;
    background: rgba(255,255,255,0.9); backdrop-filter: blur(4px);
    color: #0f172a; font-size: 0.65rem; font-weight: 700; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.pf-card-content { padding: 1.5rem; display: flex; flex-direction: column; flex: 1; }
.pf-card-title { font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0 0 0.5rem 0; line-height: 1.35; transition: color 0.3s; }
.pf-card:hover .pf-card-title { color: var(--theme-primary); }

.pf-card-client {
    font-size: 0.8rem; font-weight: 500; color: #64748b;
    display: flex; align-items: center; gap: 0.375rem; margin-bottom: 1rem;
}
.pf-card-client svg { width: 14px; height: 14px; color: #94a3b8; }

.pf-card-footer { margin-top: auto; padding-top: 1rem; display: flex; align-items: center; justify-content: space-between; }
.pf-card-link-text { font-size: 0.875rem; font-weight: 700; color: var(--theme-primary); }
.pf-card-arrow { width: 16px; height: 16px; color: var(--theme-primary); transition: transform 0.3s; }
.pf-card:hover .pf-card-arrow { transform: translateX(4px); }

.pf-pagination { margin-top: 4rem; display: flex; justify-content: center; }

/* Empty State */
.pf-empty { text-align: center; padding: 5rem 0; }
.pf-empty-icon { width: 80px; height: 80px; margin: 0 auto 1.5rem auto; border-radius: 50%; background: #f8fafc; display: flex; align-items: center; justify-content: center; }
.pf-empty-icon svg { width: 40px; height: 40px; color: #cbd5e1; }
.pf-empty h3 { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; }
.pf-empty p { color: #64748b; font-size: 0.9rem; }

/* --- CTA SECTION --- */
.pf-cta-section { padding: 5rem 0; background: #0f172a; position: relative; overflow: hidden; }
.pf-cta-title { font-size: 2.25rem; font-weight: 800; color: #ffffff; margin-bottom: 1rem; }
.pf-cta-desc { font-size: 1.125rem; color: #cbd5e1; margin-bottom: 2rem; }
.pf-btn-cta {
    display: inline-flex; align-items: center; justify-content: center;
    padding: 1rem 2rem; border-radius: 50px; font-size: 1rem; font-weight: 700;
    background: #ffffff; color: #0f172a; text-decoration: none;
    transition: all 0.3s;
}
.pf-btn-cta:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }
</style>
@endpush