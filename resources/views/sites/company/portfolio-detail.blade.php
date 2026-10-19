@extends('layouts.app')

@push('styles')
<style>
/* ============================================================
   PORTFOLIO DETAIL — Showroom Style Layout (With Lightbox)
============================================================ */

/* --- HERO SECTION --- */
.sw-hero {
    position: relative;
    min-height: 520px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 120px 1.5rem 60px;
}

/* Background Blur (Mobil blur di belakang) */
.sw-hero__bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: blur(4px) brightness(0.6);
    transform: scale(1.05);
    z-index: 1;
}
.sw-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.6) 100%);
    z-index: 2;
}

/* Navigasi Breadcrumb */
.sw-breadcrumb {
    position: absolute;
    top: 100px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    font-size: 0.8rem;
    color: rgba(255,255,255,0.8);
    width: 100%;
    max-width: 1400px;
    padding: 0 2rem;
}
.sw-breadcrumb a { color: inherit; text-decoration: none; transition: color 0.3s; }
.sw-breadcrumb a:hover { color: #fff; }
.sw-breadcrumb span { margin: 0 0.5rem; opacity: 0.5; }

/* Floating Card Utama di Hero */
.sw-hero-card {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 1000px;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

/* Gambar Utama di dalam Card */
.sw-hero-card__img-wrap {
    position: relative;
    aspect-ratio: 16 / 9;
    background: #f1f5f9;
    cursor: pointer; /* Menandakan bisa diklik */
}
.sw-hero-card__img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.3s;
}
.sw-hero-card__img-wrap:hover img {
    opacity: 0.9; /* Efek hover sedikit redup */
}

/* Badge Melayang di kiri atas gambar */
.sw-hero-card__badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.8rem;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-radius: 6px;
}

/* Info Bar di bawah gambar */
.sw-hero-card__info {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    border-top: 1px solid #e2e8f0;
    background: #fff;
}
@media (min-width: 640px) {
    .sw-hero-card__info { grid-template-columns: repeat(4, 1fr); }
}

.sw-info-item {
    padding: 1rem 1.25rem;
    border-right: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
}
.sw-info-item:nth-child(2n) { border-right: none; }
@media (min-width: 640px) {
    .sw-info-item { border-bottom: none; }
    .sw-info-item:nth-child(2n) { border-right: 1px solid #e2e8f0; }
    .sw-info-item:last-child { border-right: none; }
}

.sw-info-item__label {
    display: block;
    font-size: 0.65rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}
.sw-info-item__value {
    display: block;
    font-size: 0.95rem;
    font-weight: 700;
    color: #0f172a;
}

/* --- MAIN CONTENT (Bawah) --- */
.sw-body {
    background: #fff;
    padding: 4rem 2rem;
}

.sw-container {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr;
    gap: 3rem;
}
@media (min-width: 992px) {
    .sw-container {
        grid-template-columns: 1fr 360px;
        gap: 4rem;
    }
}

/* Kolom Kiri */
.sw-main-content {
    min-width: 0;
}
.sw-main-content__eyebrow {
    font-size: 0.75rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.5rem;
}
.sw-main-content__title {
    font-size: clamp(2rem, 4vw, 2.75rem);
    font-weight: 800;
    color: #0f172a;
    line-height: 1.15;
    margin-bottom: 1.5rem;
}

/* Prose (Deskripsi) */
.sw-prose {
    font-size: 1.05rem;
    line-height: 1.7;
    color: #475569;
    margin-bottom: 2.5rem;
}
.sw-prose :where(p) { margin-bottom: 1rem; }
.sw-prose :where(a) { color: var(--theme-primary); text-decoration: underline; }

/* Kotak Highlight (Live Link) */
.sw-highlight-box {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border-radius: 12px;
    padding: 1.5rem 2rem;
    margin-bottom: 2.5rem;
    color: #fff;
}
.sw-highlight-box__label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #94a3b8;
    margin-bottom: 0.5rem;
}
.sw-highlight-box__value {
    font-size: 2rem;
    font-weight: 800;
    color: #fff;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Galeri Grid */
.sw-gallery {
    margin-top: 3rem;
}
.sw-gallery__title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e2e8f0;
}
.sw-gallery__grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}
@media (min-width: 640px) {
    .sw-gallery__grid { grid-template-columns: repeat(3, 1fr); }
}
.sw-gallery__item {
    aspect-ratio: 4 / 3;
    border-radius: 8px;
    overflow: hidden;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    cursor: pointer; /* Menandakan bisa diklik */
}
.sw-gallery__item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s;
}
.sw-gallery__item:hover img {
    transform: scale(1.05);
}

/* --- SIDEBAR (Kanan) --- */
.sw-sidebar {
    position: relative;
}
@media (min-width: 992px) {
    .sw-sidebar {
        position: sticky;
        top: 100px;
    }
}

/* Kartu KTA (Kontak & Aksi) */
.sw-action-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.sw-action-card__title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.5rem;
}
.sw-action-card__desc {
    font-size: 0.85rem;
    color: #64748b;
    margin-bottom: 1.25rem;
    line-height: 1.5;
}

/* Tombol di Sidebar */
.sw-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 0.8rem 1rem;
    font-size: 0.9rem;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s;
    margin-bottom: 0.75rem;
    border: none;
    cursor: pointer;
}
.sw-btn svg { width: 18px; height: 18px; margin-right: 0.5rem; }

.sw-btn--dark {
    background: #0f172a;
    color: #fff;
}
.sw-btn--dark:hover {
    background: #1e293b;
}

.sw-btn--outline {
    background: #fff;
    color: #0f172a;
    border: 1px solid #cbd5e1;
}
.sw-btn--outline:hover {
    border-color: #0f172a;
}

/* Info List (di Sidebar) */
.sw-info-list {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}
.sw-info-list__title {
    padding: 1rem 1.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
}
.sw-info-list__row {
    display: flex;
    justify-content: space-between;
    padding: 0.875rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    font-size: 0.85rem;
}
.sw-info-list__row:last-child { border-bottom: none; }
.sw-info-list__label { color: #64748b; }
.sw-info-list__value { font-weight: 600; color: #0f172a; text-align: right; }

/* --- RELATED PORTFOLIO (Full Width) --- */
.sw-related-section {
    padding: 4rem 2rem; 
    background: #f8fafc; 
    border-top: 1px solid #e2e8f0;
}
.sw-related-container {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
}
.sw-related-title {
    font-size: 1.75rem; 
    font-weight: 800; 
    color: #0f172a; 
    margin-bottom: 2rem;
}
.sw-related-grid {
    display: grid; 
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); 
    gap: 1.5rem;
}
.sw-related-card {
    background: #fff; 
    border-radius: 12px; 
    overflow: hidden; 
    border: 1px solid #e2e8f0; 
    transition: transform 0.3s, box-shadow 0.3s;
}
.sw-related-card:hover {
    transform: translateY(-4px); 
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

/* ============================================================
   LIGHTBOX MODAL (Untuk Pop-up Gambar)
============================================================ */
.sw-lightbox {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(15, 23, 42, 0.9); /* Warna gelap transparan */
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

/* State ketika Lightbox Aktif */
.sw-lightbox.is-active {
    opacity: 1;
    visibility: visible;
}

/* Gambar di dalam Lightbox */
.sw-lightbox__img {
    max-width: 90vw;
    max-height: 90vh;
    border-radius: 8px;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
    transform: scale(0.95);
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    object-fit: contain;
}

.sw-lightbox.is-active .sw-lightbox__img {
    transform: scale(1);
}

/* Tombol Close (X) */
.sw-lightbox__close {
    position: absolute;
    top: 2rem;
    right: 2rem;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    transition: all 0.2s;
}

.sw-lightbox__close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg);
}

.sw-lightbox__close svg {
    width: 24px;
    height: 24px;
}
</style>
@endpush

@section('content')

{{-- ============ HERO SECTION ============ --}}
<section class="sw-hero">
    {{-- Background Blur --}}
    @if($portfolio->featured_image)
        <img src="{{ Storage::url($portfolio->featured_image) }}" class="sw-hero__bg" alt="">
        <div class="sw-hero__overlay"></div>
    @else
        <div class="sw-hero__bg" style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));"></div>
        <div class="sw-hero__overlay"></div>
    @endif

    {{-- Breadcrumb (Di atas card) --}}
    <nav class="sw-breadcrumb">
        <a href="/">Beranda</a>
        <span>/</span>
        <a href="{{ route('company.portfolio') }}">Portfolio</a>
        <span>/</span>
        <span class="text-white">{{ $portfolio->title }}</span>
    </nav>

    {{-- Floating Card Utama --}}
    <div class="sw-hero-card">
        
        {{-- Gambar Utama (Tajam) - BISA DIKLIK --}}
        <div class="sw-hero-card__img-wrap" onclick="openLightbox('{{ $portfolio->featured_image ? Storage::url($portfolio->featured_image) : '' }}')">
            @if($portfolio->featured_image)
                <img src="{{ Storage::url($portfolio->featured_image) }}" alt="{{ $portfolio->title }}">
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f1f5f9;color:#94a3b8;">
                    No Image
                </div>
            @endif
            
            {{-- Badge Service Type --}}
            @if($portfolio->service_type)
                <div class="sw-hero-card__badge">
                    <span style="width:6px;height:6px;background:#fff;border-radius:50%;"></span>
                    {{ ucfirst($portfolio->service_type) }}
                </div>
            @endif
        </div>

        {{-- Info Bar --}}
        <div class="sw-hero-card__info">
            <div class="sw-info-item">
                <span class="sw-info-item__label">Kategori</span>
                <span class="sw-info-item__value">{{ ucfirst($portfolio->service_type ?? '-') }}</span>
            </div>
            <div class="sw-info-item">
                <span class="sw-info-item__label">Klien</span>
                <span class="sw-info-item__value">{{ $portfolio->client_name ?? 'Internal / Personal' }}</span>
            </div>
            <div class="sw-info-item">
                <span class="sw-info-item__label">Tahun Proyek</span>
                <span class="sw-info-item__value">{{ $portfolio->project_year ?? '-' }}</span>
            </div>
            <div class="sw-info-item">
                <span class="sw-info-item__label">Status</span>
                <span class="sw-info-item__value">Selesai</span>
            </div>
        </div>
    </div>
</section>

{{-- ============ BODY SECTION (2 Kolom) ============ --}}
<section class="sw-body">
    <div class="sw-container">
        
        {{-- Kolom Kiri (Konten Utama) --}}
        <div class="sw-main-content">
            <div class="sw-main-content__eyebrow">{{ ucfirst($portfolio->service_type ?? 'PROJECT') }}</div>
            <h1 class="sw-main-content__title">{{ $portfolio->title }}</h1>

            {{-- Deskripsi --}}
            <div class="sw-prose">
                @if($portfolio->description)
                    {!! $portfolio->description !!}
                @else
                    <p>{{ $portfolio->short_description }}</p>
                @endif
            </div>

            {{-- Highlight Box --}}
            @if($portfolio->live_url)
            <div class="sw-highlight-box">
                <div class="sw-highlight-box__label">Proyek Telah Rilis</div>
                <div class="sw-highlight-box__value">
                    <span>Lihat Hasilnya</span>
                    <a href="{{ $portfolio->live_url }}" target="_blank" rel="noopener" class="sw-btn sw-btn--outline" style="width:auto; margin:0; padding: 0.5rem 1rem;">
                        Kunjungi Website
                        <svg style="margin-left: 0.5rem; margin-right: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>
            @endif

            {{-- Galeri Foto - BISA DIKLIK --}}
            @if($portfolio->gallery && count($portfolio->gallery) > 0)
                <div class="sw-gallery">
                    <h3 class="sw-gallery__title">Galeri Proyek</h3>
                    <div class="sw-gallery__grid">
                        @foreach($portfolio->gallery as $image)
                            <div class="sw-gallery__item" onclick="openLightbox('{{ Storage::url($image) }}')">
                                <img src="{{ Storage::url($image) }}" alt="Galeri" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Kolom Kanan (Sidebar) --}}
        <aside class="sw-sidebar">
            
            {{-- Card Aksi --}}
            <div class="sw-action-card">
                <h3 class="sw-action-card__title">Tertarik Proyek Serupa?</h3>
                <p class="sw-action-card__desc">Hubungi tim kami untuk konsultasi gratis mengenai kebutuhan digital Anda.</p>
                
                <a href="https://wa.me/6281234567890" target="_blank" class="sw-btn sw-btn--dark">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Hubungi via WhatsApp
                </a>
                
                @if($portfolio->video_url)
                <a href="{{ $portfolio->video_url }}" target="_blank" class="sw-btn sw-btn--outline">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    Lihat Video Proyek
                </a>
                @endif
            </div>

            {{-- Card Info --}}
            <div class="sw-info-list">
                <div class="sw-info-list__title">Informasi Proyek</div>
                <div class="sw-info-list__row">
                    <span class="sw-info-list__label">Tipe Layanan</span>
                    <span class="sw-info-list__value">{{ ucfirst($portfolio->service_type ?? '-') }}</span>
                </div>
                <div class="sw-info-list__row">
                    <span class="sw-info-list__label">Nama Klien</span>
                    <span class="sw-info-list__value">{{ $portfolio->client_name ?? '-' }}</span>
                </div>
                <div class="sw-info-list__row">
                    <span class="sw-info-list__label">Tahun Rilis</span>
                    <span class="sw-info-list__value">{{ $portfolio->project_year ?? '-' }}</span>
                </div>
                <div class="sw-info-list__row">
                    <span class="sw-info-list__label">Status</span>
                    <span class="sw-info-list__value" style="color: #10b981;">Selesai</span>
                </div>
            </div>

            {{-- Tombol Kembali --}}
            <div style="margin-top: 1.5rem;">
                <a href="{{ route('company.portfolio') }}" class="sw-btn sw-btn--outline" style="border:none; padding-left:0; justify-content:flex-start; color:#64748b;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Portfolio
                </a>
            </div>

        </aside>

    </div>
</section>

{{-- ============ RELATED PORTFOLIO (Full Width) ============ --}}
@if($related->count() > 0)
    <section class="sw-related-section">
        <div class="sw-related-container">
            <h2 class="sw-related-title">Portfolio Terkait</h2>

            <div class="sw-related-grid">
                @foreach($related as $item)
                    <a href="{{ route('company.portfolio.detail', $item->slug) }}" style="text-decoration: none; display: block;">
                        <div class="sw-related-card">
                            <div style="aspect-ratio: 16/9; background: #f1f5f9;">
                                @if($item->featured_image)
                                    <img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </div>
                            <div style="padding: 1.25rem;">
                                <span style="font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">{{ $item->service_type ?? 'PROJECT' }}</span>
                                <h3 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.4;">{{ $item->title }}</h3>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ============ LIGHTBOX MODAL HTML ============ --}}
<div id="swLightbox" class="sw-lightbox" onclick="closeLightbox(event)">
    {{-- Tombol Close --}}
    <button class="sw-lightbox__close" onclick="closeLightbox(event)">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    
    {{-- Gambar Full Size --}}
    <img id="swLightboxImage" class="sw-lightbox__img" src="" alt="Fullscreen Image">
</div>

{{-- ============ JAVASCRIPT UNTUK LIGHTBOX ============ --}}
<script>
    // Fungsi Membuka Lightbox
    function openLightbox(imageUrl) {
        if (!imageUrl || imageUrl === '') return; // Abaikan jika tidak ada gambar

        const lightbox = document.getElementById('swLightbox');
        const image = document.getElementById('swLightboxImage');
        
        image.src = imageUrl;
        lightbox.classList.add('is-active');
        
        // Kunci scroll body agar tidak bisa digulir saat pop-up muncul
        document.body.style.overflow = 'hidden';
    }

    // Fungsi Menutup Lightbox
    function closeLightbox(event) {
        // Pastikan hanya menutup jika yang diklik adalah area luar gambar atau tombol close
        if (event.target.id === 'swLightbox' || event.target.closest('.sw-lightbox__close')) {
            const lightbox = document.getElementById('swLightbox');
            lightbox.classList.remove('is-active');
            
            // Kembalikan scroll body
            document.body.style.overflow = '';
            
            // Hapus src gambar setelah animasi selesai (opsional, untuk performa)
            setTimeout(() => {
                document.getElementById('swLightboxImage').src = '';
            }, 300);
        }
    }

    // Menutup Lightbox dengan tombol ESC pada keyboard
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const lightbox = document.getElementById('swLightbox');
            if (lightbox.classList.contains('is-active')) {
                lightbox.classList.remove('is-active');
                document.body.style.overflow = '';
                setTimeout(() => {
                    document.getElementById('swLightboxImage').src = '';
                }, 300);
            }
        }
    });
</script>

@endsection