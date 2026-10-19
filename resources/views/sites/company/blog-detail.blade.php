@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)

@section('content')

@php
    // Resolve URLs
    $blogUrl = route('company.blog');
    $contactUrl = \Illuminate\Support\Facades\Route::has('company.contact') ? route('company.contact') : url('/contact');
    
    // WhatsApp setting
    $waRaw = \App\Models\Setting::where('key', 'contact_whatsapp')->value('value');
    $wa = preg_replace('/[^0-9]/', '', $waRaw ?? '6281234567890');

    // Image
    $heroImage = $post->featured_image ?? $post->thumbnail;
    $hasHero = $heroImage && file_exists(storage_path('app/public/' . $heroImage));
    $heroUrl = $hasHero ? asset('storage/' . $heroImage) : null;
@endphp

{{-- ============================================================ --}}
{{-- HERO SECTION (Foto di-blur + Gradasi Gelap) --}}
{{-- ============================================================ --}}
<section class="post-hero">
    
    {{-- Background: Foto Asli yang di-Blur --}}
    @if($hasHero)
        <div class="post-hero-bg" style="background-image: url('{{ $heroUrl }}');"></div>
    @else
        <div class="post-hero-bg" style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));"></div>
    @endif

    {{-- Overlay Gradasi Gelap ke Bawah --}}
    <div class="post-hero-overlay"></div>

    <div class="post-container post-text-left">
        {{-- Breadcrumb --}}
        <nav class="post-breadcrumb">
            <a href="/">Beranda</a>
            <span class="post-sep">/</span>
            <a href="{{ $blogUrl }}">Blog</a>
            <span class="post-sep">/</span>
            <span class="post-current">{{ $post->title }}</span>
        </nav>

        {{-- Badge Kategori --}}
        @if($post->category)
            <div class="post-badge-wrap">
                <span class="post-badge">
                    {{ $post->category->name }}
                </span>
            </div>
        @endif

        {{-- Judul --}}
        <h1 class="post-title">
            {{ $post->title }}
        </h1>

        {{-- Meta Info (Glassmorphism) --}}
        <div class="post-meta-glass">
            {{-- Author --}}
            <div class="post-author">
                <div class="post-avatar">
                    <span>{{ substr($post->author->name ?? 'A', 0, 1) }}</span>
                </div>
                <div class="post-author-info">
                    <p class="post-author-name">{{ $post->author->name ?? 'Admin' }}</p>
                    <p class="post-author-role">Penulis</p>
                </div>
            </div>

            @if($post->published_at)
                <span class="post-divider"></span>
                <div class="post-meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ $post->published_at->format('d F Y') }}</span>
                </div>
            @endif

            <span class="post-divider"></span>
            <div class="post-meta-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>{{ number_format($post->view_count ?? 0) }} views</span>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{-- GAMBAR UTAMA (Mengambang / Overlap di Tengah) --}}
{{-- ============================================================ --}}
@if($hasHero)
    <section class="post-image-section">
        <div class="post-container">
            <div class="post-image-frame">
                <img src="{{ $heroUrl }}" alt="{{ $post->title }}">
            </div>
        </div>
    </section>
@else
    <div class="post-image-section-fallback"></div>
@endif

{{-- ============================================================ --}}
{{-- KONTEN ARTIKEL (Putih Bersih) --}}
{{-- ============================================================ --}}
<article class="post-body">
    <div class="post-container">
        {{-- Kotak Konten Utama --}}
        <div class="post-content-box">

            {{-- Excerpt --}}
            @if($post->excerpt)
                <div class="post-excerpt-wrap">
                    <p class="post-excerpt">
                        {{ $post->excerpt }}
                    </p>
                </div>
            @endif
            
            {{-- Isi Konten --}}
            <div class="article-content">
                {!! $post->content !!}
            </div>

            {{-- Tags --}}
            @if(isset($post->tags) && $post->tags->count() > 0)
                <div class="post-section-divider">
                    <p class="post-label">Tags</p>
                    <div class="post-tags">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('company.blog', ['tag' => $tag->slug]) }}" class="post-tag">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Share --}}
            <div class="post-section-divider">
                <p class="post-label">Bagikan Artikel</p>
                <div class="post-share-btns">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="post-btn-glass">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Facebook
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="post-btn-glass">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                </div>
            </div>
            
            {{-- Kembali --}}
            <div class="post-section-divider post-text-center">
                <a href="{{ $blogUrl }}" class="post-btn-glass-dark">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Blog
                </a>
            </div>

        </div>
    </div>
</article>

{{-- ============================================================ --}}
{{-- ARTIKEL TERKAIT (Putih Bersih) --}}
{{-- ============================================================ --}}
@if(isset($related) && $related->count() > 0)
<section class="post-related-section">
    <div class="post-container-wide">
        <div class="post-related-head">
            <div>
                <h2 class="post-related-title">Artikel Terkait</h2>
                <div class="post-related-line"></div>
            </div>
            <p class="post-related-desc">Jelajahi wawasan lain yang mungkin relevan dengan topik ini.</p>
        </div>
        
        <div class="post-related-grid">
            @foreach($related as $rel)
                @php
                    $relImage = $rel->featured_image ?? $rel->thumbnail;
                    $hasRelImage = $relImage && file_exists(storage_path('app/public/' . $relImage));
                @endphp
                <a href="{{ route('company.blog.detail', $rel->slug) }}" class="post-related-card">
                    <div class="post-related-img-wrap">
                        @if($hasRelImage)
                            <img src="{{ asset('storage/' . $relImage) }}" alt="{{ $rel->title }}">
                        @else
                            <div class="post-related-fallback">
                                <span>{{ strtoupper(substr($rel->title, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="post-related-content">
                        <div class="post-related-date">{{ $rel->published_at?->format('d M Y') }}</div>
                        <h3 class="post-related-card-title">{{ $rel->title }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================================ --}}
{{-- CTA SECTION (Putih Bersih) --}}
{{-- ============================================================ --}}
<section class="post-cta-section">
    <div class="post-container">
        <h2 class="post-cta-title">Butuh Bantuan?</h2>
        <p class="post-cta-desc">Tim kami siap membantu Anda dengan pertanyaan seputar layanan kami.</p>

        <div class="post-cta-btns">
            <a href="{{ $contactUrl }}" class="post-btn-solid-dark">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Hubungi Kami
            </a>

            @if($wa)
                <a href="https://wa.me/{{ $wa }}?text=Halo%2C+saya+mau+tanya" target="_blank" class="post-btn-outline-dark">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Chat Sales
                </a>
            @endif
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* ============================================================ */
/* HALAMAN BLOG DETAIL (CUSTOM CSS) */
/* ============================================================ */

/* --- Reset & Container Dasar --- */
.post-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
    z-index: 10;
}
.post-container-wide {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
    z-index: 10;
}
.post-text-left { text-align: left; }
.post-text-center { text-align: center; }

/* --- HERO SECTION --- */
.post-hero {
    position: relative;
    padding: 10rem 0 8rem;
    background-color: #0a0a0a; 
    overflow: hidden;
}

/* Foto Background yang di-Blur */
.post-hero-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    /* Efek Blur & Skala agar tidak ada garis tepi putih */
    filter: blur(15px) brightness(0.6);
    transform: scale(1.1); 
    z-index: 1;
}

/* Overlay Gradasi Gelap */
.post-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.7) 60%, #0a0a0a 100%);
    z-index: 2;
}

/* Breadcrumb */
.post-breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 1.5rem;
}
.post-breadcrumb a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}
.post-breadcrumb a:hover { color: #fff; }
.post-sep { opacity: 0.4; }
.post-current {
    color: #fff;
    font-weight: 500;
    max-width: 300px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Badge Kategori */
.post-badge-wrap { margin-bottom: 1.5rem; }
.post-badge {
    display: inline-flex;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    background-color: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

/* Judul */
.post-title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 900;
    color: #ffffff;
    line-height: 1.15;
    letter-spacing: -0.02em;
    margin: 0 0 2rem 0;
    text-shadow: 0 4px 12px rgba(0,0,0,0.5);
}

/* Glassmorphism Meta Info */
.post-meta-glass {
    display: inline-flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 1rem 1.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 16px;
    background-color: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
}
.post-author { display: flex; align-items: center; gap: 0.75rem; }
.post-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem; font-weight: 700; color: #fff;
}
.post-author-info { line-height: 1.2; text-align: left; }
.post-author-name { margin: 0; font-size: 0.85rem; font-weight: 600; color: #fff; }
.post-author-role { margin: 0; font-size: 0.65rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.05em; }
.post-divider { width: 1px; height: 24px; background-color: rgba(255,255,255,0.2); }
.post-meta-item {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.85rem; color: rgba(255,255,255,0.8);
}
.post-meta-item svg { width: 16px; height: 16px; opacity: 0.6; }

/* --- OVERLAP IMAGE SECTION (Batas Transisi) --- */
.post-image-section {
    position: relative;
    z-index: 20;
    margin-top: -7rem;
    padding-bottom: 3rem;
    background-color: #ffffff; /* Latar putih dimulai dari sini */
}
.post-image-section-fallback {
    padding-bottom: 3rem;
    background-color: #ffffff;
}
.post-image-frame {
    border-radius: 16px;
    overflow: hidden;
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    padding: 0.5rem;
}
.post-image-frame img {
    width: 100%;
    height: auto;
    max-height: 600px;
    object-fit: contain;
    border-radius: 12px;
    display: block;
}

/* --- KONTEN ARTIKEL (Putih Bersih) --- */
.post-body {
    background-color: #ffffff;
    padding-bottom: 5rem;
}
.post-content-box {
    background-color: #ffffff;
    border-radius: 24px;
    padding: 1rem 0;
}

/* Excerpt */
.post-excerpt-wrap {
    margin-bottom: 2.5rem;
    padding-bottom: 2.5rem;
    border-bottom: 1px solid #e5e7eb;
}
.post-excerpt {
    font-size: 1.15rem;
    line-height: 1.8;
    color: #374151;
    font-style: italic;
    text-align: justify;
    border-left: 4px solid #111827;
    padding-left: 1.5rem;
    margin: 0;
}

/* Isi Artikel (Typography) */
.article-content { font-size: 1.05rem; line-height: 1.85; color: #374151; word-wrap: break-word; }
.article-content p { margin-bottom: 1.5rem; text-align: justify; text-justify: inter-word; hyphens: auto; }
.article-content h2, .article-content h3, .article-content h4 {
    font-weight: 800; color: #111827; margin-top: 2.5rem; margin-bottom: 1rem; letter-spacing: -0.02em; text-align: left; line-height: 1.3;
}
.article-content h2 { font-size: 1.5rem; }
.article-content h3 { font-size: 1.25rem; }
.article-content strong, .article-content b { font-weight: 700; color: #111827; }
.article-content a { color: #2563eb; text-decoration: underline; font-weight: 500; }
.article-content a:hover { color: #1d4ed8; }
.article-content ul, .article-content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
.article-content ul { list-style-type: disc; }
.article-content ol { list-style-type: decimal; }
.article-content li { margin-bottom: 0.5rem; text-align: justify; }
.article-content img { border-radius: 1rem; box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.15); margin: 2rem 0; width: 100%; height: auto; display: block; }
.article-content blockquote { border-left: 4px solid #111827; padding-left: 1.5rem; font-style: italic; color: #4b5563; margin: 1.5rem 0; text-align: justify; }
.article-content table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; }
.article-content th, .article-content td { border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; }
.article-content th { background-color: #f9fafb; font-weight: 700; color: #111827; }

/* Section Divider */
.post-section-divider {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
}
.post-label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    color: #6b7280;
    margin-bottom: 1rem;
}

/* Tags */
.post-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.post-tag {
    display: inline-flex;
    padding: 0.4rem 0.8rem;
    border-radius: 50px;
    background-color: #f3f4f6;
    border: 1px solid #e5e7eb;
    color: #374151;
    font-size: 0.75rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}
.post-tag:hover { background-color: #111827; color: #ffffff; border-color: #111827; }

/* Share Buttons (Gaya Kapsul Hitam Putih) */
.post-share-btns { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.post-btn-glass {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.25rem;
    border-radius: 50px;
    background-color: #ffffff;
    border: 2px solid #111827;
    color: #111827;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s;
}
.post-btn-glass svg { width: 16px; height: 16px; }
.post-btn-glass:hover { background-color: #111827; color: #ffffff; }

.post-btn-glass-dark {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.5rem;
    border-radius: 50px;
    background-color: #ffffff;
    border: 2px solid #111827;
    color: #111827;
    font-size: 0.9rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s;
}
.post-btn-glass-dark svg { width: 16px; height: 16px; }
.post-btn-glass-dark:hover { background-color: #111827; color: #ffffff; }

/* --- ARTIKEL TERKAIT --- */
.post-related-section {
    background-color: #f9fafb;
    padding: 4rem 0;
    border-top: 1px solid #e5e7eb;
}
.post-related-head {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2.5rem;
}
@media (min-width: 768px) {
    .post-related-head { flex-direction: row; align-items: flex-end; justify-content: space-between; }
}
.post-related-title { font-size: 2rem; font-weight: 900; color: #111827; margin: 0; }
.post-related-line { width: 64px; height: 4px; background-color: #111827; border-radius: 4px; margin-top: 1rem; }
.post-related-desc { font-size: 0.9rem; color: #6b7280; max-width: 400px; margin: 0; }
.post-related-grid { display: grid; grid-template-columns: 1fr; gap: 2rem; }
@media (min-width: 768px) { .post-related-grid { grid-template-columns: repeat(3, 1fr); } }

.post-related-card {
    display: block;
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}
.post-related-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}
.post-related-img-wrap { aspect-ratio: 4/3; overflow: hidden; background-color: #f3f4f6; position: relative; }
.post-related-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: all 0.7s; }
.post-related-card:hover .post-related-img-wrap img { transform: scale(1.05); }
.post-related-fallback {
    width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));
}
.post-related-fallback span { font-size: 4rem; font-weight: 700; color: #fff; opacity: 0.3; }
.post-related-content { padding: 1.5rem; }
.post-related-date { font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
.post-related-card-title { font-size: 1.1rem; font-weight: 700; color: #111827; line-height: 1.4; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

/* --- CTA SECTION --- */
.post-cta-section {
    background-color: #ffffff;
    padding: 5rem 0;
    border-top: 1px solid #e5e7eb;
    text-align: center;
}
.post-cta-title { font-size: 2.25rem; font-weight: 700; color: #111827; margin-bottom: 1rem; }
.post-cta-desc { font-size: 1rem; color: #6b7280; max-width: 500px; margin: 0 auto 2.5rem auto; line-height: 1.6; }
.post-cta-btns { display: flex; flex-direction: column; gap: 1rem; justify-content: center; }
@media (min-width: 640px) { .post-cta-btns { flex-direction: row; } }

.post-btn-solid-dark {
    display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
    padding: 0.9rem 2rem; border-radius: 50px;
    background-color: #ffffff; color: #111827;
    border: 2px solid #111827;
    font-size: 0.9rem; font-weight: 700; text-decoration: none;
    transition: all 0.2s;
}
.post-btn-solid-dark:hover { background-color: #111827; color: #ffffff; }

.post-btn-outline-dark {
    display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
    padding: 0.9rem 2rem; border-radius: 50px;
    background-color: #111827; color: #ffffff;
    border: 2px solid #111827;
    font-size: 0.9rem; font-weight: 700; text-decoration: none;
    transition: all 0.2s;
}
.post-btn-outline-dark:hover { background-color: #374151; border-color: #374151; }

/* --- Responsive Mobile --- */
@media (max-width: 768px) {
    .post-hero { padding: 8rem 0 6rem; }
    .post-meta-glass { flex-direction: column; align-items: flex-start; border-radius: 12px; }
    .post-divider { display: none; }
    .post-image-section { margin-top: -4rem; }
    .post-content-box { padding: 1.5rem; }
    .post-related-head { align-items: flex-start; }
    .post-cta-title { font-size: 1.75rem; }
}
</style>
@endpush