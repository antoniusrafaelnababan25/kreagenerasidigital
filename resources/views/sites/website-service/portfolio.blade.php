@extends('layouts.app')

@section('title', 'Portfolio Website')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@php
    $heroContent = [];
    if (!empty($hero) && isset($hero['content'])) {
        $heroContent = $hero['content'];
    }
@endphp
@include('sections.hero', ['content' => $heroContent])

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- MAIN CONTENT --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<section class="wspf-body">
    <div class="wspf-container">
        
        {{-- Judul & Filter --}}
        <div class="wspf-header">
            <div>
                <h1 class="wspf-title">Portfolio Kami</h1>
                <p class="wspf-desc">Beberapa proyek website yang telah kami kerjakan.</p>
            </div>
            
            {{-- Filter Tahun --}}
            @if($years->count() > 0)
                <div class="wspf-filter-wrap">
                    <a href="{{ route('services.website.portfolio') }}" 
                       class="wspf-filter-btn {{ !request('year') ? 'wspf-active' : '' }}">
                        Semua
                    </a>
                    @foreach($years as $year)
                        <a href="{{ route('services.website.portfolio', ['year' => $year]) }}" 
                           class="wspf-filter-btn {{ request('year') == $year ? 'wspf-active' : '' }}">
                            {{ $year }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Grid Portfolio --}}
        @if($portfolios->count() > 0)
            <div class="wspf-grid">
                @foreach($portfolios as $portfolio)
                    <div class="wspf-card">
                        <div class="wspf-card-img-wrap">
                            @if($portfolio->featured_image)
                                <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="wspf-card-img" loading="lazy">
                            @else
                                <div class="wspf-card-placeholder" style="background: linear-gradient(135deg, var(--theme-primary, #3B82F6), var(--theme-secondary, #8B5CF6));">
                                    <span>{{ substr($portfolio->title, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="wspf-badge">{{ $portfolio->project_year ?? 'Project' }}</div>
                        </div>
                        <div class="wspf-card-content">
                            <h3 class="wspf-card-title">{{ $portfolio->title }}</h3>
                            <p class="wspf-card-client">{{ $portfolio->client_name }}</p>
                            <p class="wspf-card-desc">{{ Str::limit($portfolio->short_description, 80) }}</p>
                            <a href="{{ route('services.website.portfolio.detail', $portfolio->slug) }}" class="wspf-card-link">
                                Lihat Detail
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($portfolios->hasPages())
                <div class="wspf-pagination">
                    {{ $portfolios->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="wspf-empty">
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
/* CSS HALAMAN DAFTAR PORTFOLIO WEBSITE */
/* ============================================================ */

.wspf-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
.wspf-body { padding: 4rem 0 6rem; background-color: #f8fafc; }

/* Header & Filter */
.wspf-header { display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 3rem; }
@media (min-width: 768px) {
    .wspf-header { flex-direction: row; align-items: flex-end; justify-content: space-between; }
}
.wspf-title { font-size: 2.25rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; letter-spacing: -0.02em; }
.wspf-desc { font-size: 1rem; color: #64748b; max-width: 500px; margin: 0; }

.wspf-filter-wrap { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.wspf-filter-btn { padding: 0.6rem 1.25rem; border-radius: 50px; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
.wspf-filter-btn:hover { background: #f1f5f9; color: #0f172a; }
.wspf-filter-btn.wspf-active { background: var(--theme-primary, #3B82F6); border-color: var(--theme-primary, #3B82F6); color: #ffffff; }

/* Grid & Cards */
.wspf-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem; }
.wspf-card { background: #ffffff; border-radius: 1rem; overflow: hidden; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.3s; display: flex; flex-direction: column; }
.wspf-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }

.wspf-card-img-wrap { position: relative; aspect-ratio: 4/3; overflow: hidden; background: #f8fafc; }
.wspf-card-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
.wspf-card:hover .wspf-card-img { transform: scale(1.05); }
.wspf-card-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.wspf-card-placeholder span { font-size: 4rem; font-weight: 700; color: #ffffff; opacity: 0.3; }

.wspf-badge { position: absolute; top: 1rem; right: 1rem; background: rgba(255,255,255,0.9); backdrop-filter: blur(4px); padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.7rem; font-weight: 800; color: #0f172a; }

.wspf-card-content { padding: 1.5rem; display: flex; flex-direction: column; flex: 1; }
.wspf-card-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem; line-height: 1.3; }
.wspf-card-client { font-size: 0.85rem; color: var(--theme-primary, #3B82F6); font-weight: 600; margin-bottom: 0.75rem; }
.wspf-card-desc { font-size: 0.9rem; color: #64748b; line-height: 1.6; margin-bottom: 1.5rem; flex: 1; }

.wspf-card-link { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; color: #0f172a; text-decoration: none; margin-top: auto; }
.wspf-card-link svg { width: 16px; height: 16px; transition: transform 0.3s; }
.wspf-card:hover .wspf-card-link svg { transform: translateX(4px); color: var(--theme-primary, #3B82F6); }
.wspf-card:hover .wspf-card-link { color: var(--theme-primary, #3B82F6); }

/* Pagination & Empty */
.wspf-pagination { margin-top: 3rem; display: flex; justify-content: center; }
.wspf-empty { text-align: center; padding: 5rem 0; }
.wspf-empty svg { width: 64px; height: 64px; color: #cbd5e1; margin: 0 auto 1.5rem auto; }
.wspf-empty h3 { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; }
.wspf-empty p { color: #64748b; font-size: 1rem; }
</style>
@endpush