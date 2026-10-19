@extends('layouts.app')

@section('title', 'Portfolio Video')

@section('content')

{{-- HERO SECTION --}}
@php
    $heroContent = [];
    if (!empty($hero) && isset($hero['content'])) {
        $heroContent = $hero['content'];
    }
@endphp
@include('sections.hero', ['content' => $heroContent])

{{-- MAIN CONTENT --}}
<section class="vdp-body">
    <div class="vdp-container">
        
        {{-- Judul & Filter --}}
        <div class="vdp-header-row">
            <div>
                <h1 class="vdp-title">Portfolio Video</h1>
                <p class="vdp-desc" style="margin: 0;">Kumpulan karya visual yang telah kami produksi.</p>
            </div>
            
            {{-- Filter Tahun --}}
            @if($years->count() > 0)
                <div class="vdp-filter-wrap">
                    <a href="{{ route('services.video.portfolio') }}" 
                       class="vdp-filter-btn {{ !request('year') ? 'vdp-active' : '' }}">
                        Semua Tahun
                    </a>
                    @foreach($years as $year)
                        <a href="{{ route('services.video.portfolio', ['year' => $year]) }}" 
                           class="vdp-filter-btn {{ request('year') == $year ? 'vdp-active' : '' }}">
                            {{ $year }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Grid Portfolio --}}
        @if($portfolios->count() > 0)
            <div class="vdp-grid">
                @foreach($portfolios as $portfolio)
                    <div class="vdp-card" style="padding: 0; overflow: hidden;">
                        
                        <div class="vdp-portfolio-img-wrap">
                            @if($portfolio->featured_image)
                                <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="vdp-portfolio-img" loading="lazy">
                            @else
                                <div class="vdp-portfolio-placeholder">
                                    <span>{{ substr($portfolio->title, 0, 1) }}</span>
                                </div>
                            @endif
                            
                            {{-- Play Icon --}}
                            <div class="vdp-play-icon">
                                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>

                        <div class="vdp-portfolio-content">
                            <span class="vdp-portfolio-year">{{ $portfolio->project_year }}</span>
                            <h3 class="vdp-portfolio-title">{{ $portfolio->title }}</h3>
                            <p class="vdp-portfolio-client">{{ $portfolio->client_name }}</p>
                            
                            <a href="{{ route('services.video.portfolio.detail', $portfolio->slug) }}" class="vdp-portfolio-link">
                                Lihat Karya
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($portfolios->hasPages())
                <div class="vdp-pagination">
                    {{ $portfolios->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="vdp-empty">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3>Belum Ada Portfolio</h3>
                <p>Portfolio untuk kategori ini belum tersedia saat ini.</p>
            </div>
        @endif

    </div>
</section>

@endsection

@push('styles')
<style>
/* ============================================================ */
/* CSS HALAMAN DAFTAR PORTFOLIO VIDEO */
/* ============================================================ */

.vdp-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
.vdp-body { padding: 4rem 0 6rem; background-color: #0f172a; color: #ffffff; }

/* Header & Filter */
.vdp-header-row { display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 3rem; }
@media (min-width: 768px) { .vdp-header-row { flex-direction: row; align-items: flex-end; justify-content: space-between; } }
.vdp-title { font-size: 2.25rem; font-weight: 800; margin-bottom: 0.5rem; letter-spacing: -0.02em; }
.vdp-desc { font-size: 1rem; color: #94a3b8; max-width: 500px; }

.vdp-filter-wrap { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.vdp-filter-btn { padding: 0.6rem 1.25rem; border-radius: 50px; border: 1px solid #334155; background: #1e293b; color: #94a3b8; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
.vdp-filter-btn:hover { background: #334155; color: #ffffff; }
.vdp-filter-btn.vdp-active { background: var(--theme-primary, #3B82F6); border-color: var(--theme-primary, #3B82F6); color: #ffffff; }

/* Grid & Cards */
.vdp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem; }
.vdp-card { background: #1e293b; border-radius: 1.5rem; border: 1px solid #334155; transition: all 0.3s; display: flex; flex-direction: column; }
.vdp-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.4); border-color: #475569; }

.vdp-portfolio-img-wrap { position: relative; aspect-ratio: 16/9; overflow: hidden; background: #0f172a; border-top-left-radius: 1.5rem; border-top-right-radius: 1.5rem; }
.vdp-portfolio-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
.vdp-card:hover .vdp-portfolio-img { transform: scale(1.05); }
.vdp-portfolio-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1e293b, #0f172a); }
.vdp-portfolio-placeholder span { font-size: 4rem; font-weight: 700; color: #ffffff; opacity: 0.1; }

.vdp-play-icon { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.3); transition: background 0.3s; }
.vdp-card:hover .vdp-play-icon { background: rgba(0,0,0,0.5); }
.vdp-play-icon svg { width: 48px; height: 48px; color: #ffffff; opacity: 0.8; transition: transform 0.3s; }
.vdp-card:hover .vdp-play-icon svg { transform: scale(1.1); opacity: 1; }

.vdp-portfolio-content { padding: 1.5rem; display: flex; flex-direction: column; flex: 1; }
.vdp-portfolio-year { display: inline-block; font-size: 0.75rem; font-weight: 700; color: var(--theme-primary, #3B82F6); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; }
.vdp-portfolio-title { font-size: 1.25rem; font-weight: 800; color: #ffffff; margin-bottom: 0.25rem; line-height: 1.3; }
.vdp-portfolio-client { font-size: 0.85rem; color: #94a3b8; margin-bottom: 1.5rem; flex: 1; }
.vdp-portfolio-link { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; color: var(--theme-primary, #3B82F6); text-decoration: none; }
.vdp-portfolio-link svg { width: 16px; height: 16px; transition: transform 0.3s; }
.vdp-card:hover .vdp-portfolio-link svg { transform: translateX(4px); }

/* Pagination & Empty */
.vdp-pagination { margin-top: 3rem; display: flex; justify-content: center; }
.vdp-pagination .pagination { display: flex; gap: 0.5rem; }
.vdp-pagination a, .vdp-pagination span { display: inline-block; padding: 0.5rem 0.85rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-decoration: none; border: 1px solid #334155; background: #1e293b; color: #94a3b8; transition: all 0.2s; }
.vdp-pagination a:hover { background: var(--theme-primary, #3B82F6); color: #ffffff; border-color: var(--theme-primary, #3B82F6); }
.vdp-pagination span[aria-current="page"] { background: var(--theme-primary, #3B82F6); color: #ffffff; border-color: var(--theme-primary, #3B82F6); }

.vdp-empty { text-align: center; padding: 5rem 0; }
.vdp-empty svg { width: 64px; height: 64px; color: #475569; margin: 0 auto 1.5rem auto; }
.vdp-empty h3 { font-size: 1.5rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; }
.vdp-empty p { color: #94a3b8; font-size: 1rem; }
</style>
@endpush