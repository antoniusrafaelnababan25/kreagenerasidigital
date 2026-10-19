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
    <section class="blog-hero">
        <div class="blog-hero-grid"></div>
        <div class="blog-hero-glow blog-hero-glow-1"></div>
        <div class="blog-hero-glow blog-hero-glow-2"></div>

        <div class="blog-container blog-z-10">
            <nav class="blog-breadcrumb">
                <a href="/">Beranda</a>
                <span>/</span>
                <span class="blog-breadcrumb-active">Blog</span>
            </nav>
            <h1 class="blog-hero-title">Blog & Artikel</h1>
            <p class="blog-hero-desc">
                Tips, berita, dan insight terbaru dari KREA Generasi Digital.
            </p>
        </div>
    </section>
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- SHOWCASE — Artikel Featured --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($featured->count() > 0)
    @php $mainFeatured = $featured->first(); @endphp

    <section class="blog-section-featured">
        <div class="blog-container-wide">
            
            <div class="blog-featured-box">
                <div class="blog-featured-grid">

                    {{-- KIRI: Gambar --}}
                    <div class="blog-featured-img-col">
                        <div class="blog-featured-img-wrap">
                            @if($mainFeatured->featured_image)
                                <img src="{{ Storage::url($mainFeatured->featured_image) }}"
                                     alt="{{ $mainFeatured->title }}"
                                     class="blog-featured-img">
                            @else
                                <div class="blog-featured-img-fallback" style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));">
                                    <span>{{ strtoupper(substr($mainFeatured->title, 0, 1)) }}</span>
                                </div>
                            @endif

                            <div class="blog-featured-badge">
                                ⭐ Artikel Pilihan
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: Konten --}}
                    <div class="blog-featured-content-col">
                        {{-- Meta --}}
                        <div class="blog-meta">
                            @if($mainFeatured->published_at)
                                <span>{{ $mainFeatured->published_at->format('d F Y') }}</span>
                            @endif
                            @if($mainFeatured->category)
                                <span>·</span>
                                <span class="blog-meta-category">{{ $mainFeatured->category->name }}</span>
                            @endif
                        </div>

                        {{-- Judul --}}
                        <h2 class="blog-featured-title">
                            {{ $mainFeatured->title }}
                        </h2>

                        {{-- Excerpt --}}
                        @if($mainFeatured->excerpt)
                            <p class="blog-featured-desc">
                                {{ Str::limit($mainFeatured->excerpt, 200) }}
                            </p>
                        @endif

                        {{-- Button --}}
                        <a href="{{ route('company.blog.detail', $mainFeatured->slug) }}" class="blog-btn-dark">
                            Baca Artikel
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- BERITA LAINNYA --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<section class="blog-section-grid">
    <div class="blog-container-wide">

        {{-- Header --}}
        <div class="blog-grid-header">
            <div>
                <h2 class="blog-grid-title">Artikel Terbaru</h2>
                <div class="blog-grid-line"></div>
            </div>
            <p class="blog-grid-subtitle">
                Jelajahi kumpulan wawasan dan tips bermanfaat untuk mengembangkan bisnis digital Anda.
            </p>
        </div>

        @if($posts->count() > 0)

            {{-- Grid Kartu --}}
            <div class="blog-grid">
                @foreach($posts as $post)
                    <a href="{{ route('company.blog.detail', $post->slug) }}" class="blog-card">
                        
                        {{-- Image --}}
                        <div class="blog-card-img-wrap">
                            @if($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="blog-card-img">
                            @else
                                <div class="blog-card-img-fallback" style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));">
                                    <span>{{ strtoupper(substr($post->title, 0, 1)) }}</span>
                                </div>
                            @endif
                            
                            {{-- Kategori Overlay --}}
                            @if($post->category)
                                <div class="blog-card-category">
                                    {{ $post->category->name }}
                                </div>
                            @endif
                        </div>

                        {{-- Konten --}}
                        <div class="blog-card-content">
                            {{-- Meta --}}
                            <div class="blog-card-meta">
                                @if($post->published_at)
                                    <span>{{ $post->published_at->format('d M Y') }}</span>
                                @endif
                            </div>

                            {{-- Judul --}}
                            <h3 class="blog-card-title">
                                {{ $post->title }}
                            </h3>

                            {{-- Excerpt --}}
                            @if($post->excerpt)
                                <p class="blog-card-desc">
                                    {{ Str::limit($post->excerpt, 110) }}
                                </p>
                            @endif

                            {{-- Read More --}}
                            <div class="blog-card-link">
                                Baca Artikel
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="blog-pagination">
                {{ $posts->withQueryString()->links() }}
            </div>

        @else
            <div class="blog-empty">
                <p>Belum ada artikel yang dipublikasikan.</p>
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
@endif

@endsection

@push('styles')
<style>
/* ============================================================ */
/* CUSTOM CSS HALAMAN BLOG INDEX */
/* ============================================================ */

/* --- Container & Utility --- */
.blog-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
    text-align: center;
}
.blog-container-wide {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
}
.blog-z-10 { z-index: 10; }

/* --- HERO SECTION --- */
.blog-hero {
    position: relative;
    padding: 8rem 0 6rem;
    background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));
    overflow: hidden;
    color: #ffffff;
}
.blog-hero-grid {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255,255,255,0.15) 1px, transparent 1px);
    background-size: 20px 20px;
    pointer-events: none;
}
.blog-hero-glow {
    position: absolute;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    filter: blur(80px);
    pointer-events: none;
}
.blog-hero-glow-1 { top: -10%; left: -10%; }
.blog-hero-glow-2 { bottom: -10%; right: -10%; }

.blog-breadcrumb {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(255,255,255,0.7);
    margin-bottom: 1.5rem;
}
.blog-breadcrumb a { color: inherit; text-decoration: none; transition: color 0.2s; }
.blog-breadcrumb a:hover { color: #ffffff; }
.blog-breadcrumb-active { color: #ffffff; }

.blog-hero-title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 1.5rem;
    letter-spacing: -0.02em;
}
.blog-hero-desc {
    font-size: 1.125rem;
    color: rgba(255,255,255,0.9);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
    font-weight: 300;
}

/* --- FEATURED SECTION --- */
.blog-section-featured {
    padding: 4rem 0 6rem;
    background-color: #ffffff;
}
.blog-featured-box {
    background: #ffffff;
    border-radius: 2rem;
    padding: 2rem;
    border: 1px solid #f1f5f9;
    box-shadow: 0 8px 30px rgba(0,0,0,0.04);
    transition: box-shadow 0.4s ease;
}
.blog-featured-box:hover {
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
}
.blog-featured-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    align-items: center;
}
@media (min-width: 1024px) {
    .blog-featured-grid { grid-template-columns: 5fr 7fr; gap: 3rem; }
}

/* Gambar Featured */
.blog-featured-img-wrap {
    position: relative;
    aspect-ratio: 4/3;
    border-radius: 1.25rem;
    overflow: hidden;
    background-color: #f1f5f9;
}
.blog-featured-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s ease;
}
.blog-featured-box:hover .blog-featured-img {
    transform: scale(1.05);
}
.blog-featured-img-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.blog-featured-img-fallback span {
    font-size: 8rem;
    font-weight: 700;
    color: #ffffff;
    opacity: 0.2;
}
.blog-featured-badge {
    position: absolute;
    top: 1.25rem;
    left: 1.25rem;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(4px);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #0f172a;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Konten Featured */
.blog-featured-content-col {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.blog-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 1rem;
}
.blog-meta-category {
    background: #f1f5f9;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    color: #334155;
}
.blog-featured-title {
    font-size: clamp(1.75rem, 3vw, 2.5rem);
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
    margin-bottom: 1.25rem;
    letter-spacing: -0.02em;
    transition: color 0.3s;
}
.blog-featured-box:hover .blog-featured-title {
    color: var(--theme-primary);
}
.blog-featured-desc {
    font-size: 1rem;
    color: #64748b;
    line-height: 1.7;
    margin-bottom: 2rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.blog-btn-dark {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: #0f172a;
    color: #ffffff;
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
}
.blog-btn-dark svg { width: 16px; height: 16px; transition: transform 0.3s; }
.blog-btn-dark:hover {
    background: #000000;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}
.blog-btn-dark:hover svg { transform: translateX(4px); }

/* --- GRID SECTION --- */
.blog-section-grid {
    padding: 5rem 0;
    background-color: #fafafa;
    border-top: 1px solid #f1f5f9;
}
.blog-grid-header {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 3rem;
}
@media (min-width: 768px) {
    .blog-grid-header { flex-direction: row; align-items: flex-end; justify-content: space-between; }
}
.blog-grid-title {
    font-size: 2rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.75rem;
    letter-spacing: -0.02em;
}
.blog-grid-line {
    width: 60px;
    height: 4px;
    background: #0f172a;
    border-radius: 4px;
}
.blog-grid-subtitle {
    font-size: 0.9rem;
    color: #64748b;
    max-width: 400px;
    line-height: 1.6;
    margin: 0;
}

/* Grid Kartu */
.blog-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}
@media (min-width: 768px) { .blog-grid { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 1024px) { .blog-grid { grid-template-columns: repeat(3, 1fr); } }

.blog-card {
    display: flex;
    flex-direction: column;
    background: #ffffff;
    border-radius: 1.25rem;
    overflow: hidden;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    text-decoration: none;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.blog-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
}

.blog-card-img-wrap {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
    background: #f1f5f9;
}
.blog-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s ease;
}
.blog-card:hover .blog-card-img { transform: scale(1.05); }
.blog-card-img-fallback {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
}
.blog-card-img-fallback span { font-size: 4rem; font-weight: 700; color: #fff; opacity: 0.3; }

.blog-card-category {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(4px);
    padding: 0.4rem 0.75rem;
    border-radius: 6px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #0f172a;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.blog-card-content {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.blog-card-meta {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #94a3b8;
    margin-bottom: 0.75rem;
}
.blog-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.35;
    margin-bottom: 0.75rem;
    transition: color 0.3s;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.blog-card:hover .blog-card-title { color: var(--theme-primary); }
.blog-card-desc {
    font-size: 0.9rem;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.blog-card-link {
    margin-top: auto;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--theme-primary);
}
.blog-card-link svg { width: 16px; height: 16px; transition: transform 0.3s; }
.blog-card:hover .blog-card-link svg { transform: translateX(4px); }

.blog-pagination {
    margin-top: 4rem;
    display: flex;
    justify-content: center;
}
.blog-empty {
    text-align: center;
    padding: 4rem 0;
    color: #64748b;
}
</style>
@endpush