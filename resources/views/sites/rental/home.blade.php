@extends('layouts.app')

@section('title', 'Rental Alat Camping')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(isset($hero) && $hero)
    @include('sections.hero', [
        'content' => $hero['content'] ?? [],
        'section' => $hero,
    ])
@else
    {{-- Fallback Hero Default --}}
    <section class="rt-hero">
        <div class="rt-hero-bg" style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));"></div>
        <div class="rt-hero-glow rt-hero-glow-1"></div>
        <div class="rt-hero-glow rt-hero-glow-2"></div>

        <div class="rt-container rt-z-10 rt-text-center">
            <nav class="rt-breadcrumb">
                <a href="/">Beranda</a>
                <span>/</span>
                <span class="rt-active">Rental</span>
            </nav>
            <h1 class="rt-hero-title">Sewa Alat Camping Terlengkap</h1>
            <p class="rt-hero-desc">Sewa alat camping berkualitas: tenda, sleeping bag, carrier, dan lainnya.</p>
        </div>
    </section>
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- KATALOG BARANG --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<section id="katalog" class="rt-section bg-white">
    <div class="rt-container-wide">
        
        {{-- Header Katalog --}}
        <div class="rt-katalog-header">
            <div class="rt-katalog-text">
                <h2 class="rt-section-title">Katalog Barang</h2>
                <div class="rt-section-line"></div>
            </div>
            <p class="rt-katalog-desc">
                Pilih alat camping yang Anda butuhkan di bawah ini.
            </p>
        </div>

        {{-- Filter Kategori --}}
        @if(isset($categories) && $categories->count() > 0)
            <div class="rt-filter-wrap">
                <a href="{{ route('services.rental.home') }}" class="rt-filter-btn {{ !request('category') ? 'rt-active' : '' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('services.rental.home', ['category' => $cat->slug]) }}" 
                       class="rt-filter-btn {{ request('category') === $cat->slug ? 'rt-active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Grid Barang --}}
        @if(isset($featuredItems) && $featuredItems->count() > 0)
            <div class="rt-grid">
                @foreach($featuredItems as $item)
                    <div class="rt-card">
                        {{-- Image Wrapper --}}
                        <div class="rt-card-img-wrap">
                            @if($item->featured_image)
                                <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->name }}" class="rt-card-img">
                            @else
                                <div class="rt-card-img-placeholder" style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));">
                                    <span>{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            
                            {{-- Badge Stok --}}
                            <div class="rt-card-category">
                                Stok: {{ $item->stock_available }}
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="rt-card-content">
                            <h3 class="rt-card-title">{{ $item->name }}</h3>
                            <div class="rt-card-price">
                                Rp {{ number_format($item->price_per_day, 0, ',', '.') }} <span>/ hari</span>
                            </div>
                            
                            {{-- Footer --}}
                            <div class="rt-card-footer">
                                <a href="{{ route('services.rental.booking', ['item' => $item->slug]) ?? '#' }}" class="rt-card-link">
                                    Booking Sekarang
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rt-empty">
                <p>Belum ada barang yang tersedia saat ini.</p>
            </div>
        @endif

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- TESTIMONIALS SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(isset($testimonials) && $testimonials->count() > 0)
<section class="rt-section bg-gray-50">
    <div class="rt-container-wide">
        <div class="rt-section-header rt-text-center">
            <h2 class="rt-section-title">Apa Kata Mereka?</h2>
            <p class="rt-section-desc">Pengalaman pelanggan yang telah menyewa alat camping di tempat kami.</p>
        </div>

        <div class="rt-grid-3">
            @foreach($testimonials as $testi)
                <div class="rt-testi-card">
                    <div class="rt-testi-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="{{ $i <= $testi->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="rt-testi-text">"{{ $testi->content }}"</p>
                    <div class="rt-testi-author">
                        <div class="rt-testi-avatar">{{ substr($testi->name, 0, 1) }}</div>
                        <div>
                            <div class="rt-testi-name">{{ $testi->name }}</div>
                            <div class="rt-testi-role">{{ $testi->position ?? 'Pelanggan' }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- FAQ SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(isset($faqs) && $faqs->count() > 0)
<section class="rt-section bg-white">
    <div class="rt-container">
        <div class="rt-section-header rt-text-center">
            <h2 class="rt-section-title">Pertanyaan Umum (FAQ)</h2>
            <p class="rt-section-desc">Hal-hal yang sering ditanyakan seputar proses sewa menyewa.</p>
        </div>

        <div class="rt-faq-list" x-data="{ openItem: null }">
            @foreach($faqs as $faq)
                <div class="rt-faq-item">
                    <button class="rt-faq-btn" @click="openItem = openItem === {{ $loop->index }} ? null : {{ $loop->index }}">
                        <span>{{ $faq->question }}</span>
                        <svg class="rt-faq-icon" :class="openItem === {{ $loop->index }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="rt-faq-answer" x-show="openItem === {{ $loop->index }}" x-collapse>
                        <p>{{ $faq->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- CTA SECTION (Dinamis dari Database) --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(isset($ctaSection) && $ctaSection)
    @include('sections.cta', [
        'content' => $ctaSection['content'] ?? [],
        'section' => $ctaSection,
    ])
@else
    {{-- Fallback CTA Default (Jika admin belum mengatur CTA) --}}
    <section class="rt-cta-section">
        <div class="rt-container rt-text-center rt-z-10">
            <h2 class="rt-cta-title">Siap Berpetualang?</h2>
            <p class="rt-cta-desc">Booking alat camping Anda sekarang dan nikmati kemudahan prosesnya.</p>
            <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '' }}" target="_blank" class="rt-btn-primary" style="background: #ffffff; color: var(--theme-primary);">
                Hubungi Kami via WhatsApp
            </a>
        </div>
    </section>
@endif

@endsection

@push('styles')
<style>
/* ============================================================ */
/* CUSTOM CSS HALAMAN RENTAL HOME */
/* ============================================================ */

.rt-container { max-width: 900px; margin: 0 auto; padding: 0 1.5rem; position: relative; }
.rt-container-wide { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; position: relative; }
.rt-z-10 { z-index: 10; }
.rt-text-center { text-align: center; }
.bg-white { background-color: #ffffff; }
.bg-gray-50 { background-color: #f8fafc; }
.text-yellow-400 { color: #facc15; }
.text-gray-300 { color: #d1d5db; }

/* --- HERO SECTION --- */
.rt-hero { position: relative; padding: 10rem 0 8rem; background-color: #0f172a; overflow: hidden; color: #ffffff; }
.rt-hero-bg { position: absolute; inset: 0; background-size: cover; background-position: center; z-index: 1; }
.rt-hero-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(15,23,42,0.6) 0%, rgba(15,23,42,0.9) 100%); z-index: 2; }
.rt-hero-glow { position: absolute; width: 400px; height: 400px; border-radius: 50%; background: rgba(255,255,255,0.1); filter: blur(80px); pointer-events: none; z-index: 2; }
.rt-hero-glow-1 { top: -10%; left: -10%; }
.rt-hero-glow-2 { bottom: -10%; right: -10%; }

.rt-breadcrumb { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.7); margin-bottom: 1.5rem; position: relative; z-index: 10; }
.rt-breadcrumb a { color: inherit; text-decoration: none; transition: color 0.2s; }
.rt-breadcrumb a:hover { color: #ffffff; }
.rt-active { color: #ffffff; }

.rt-hero-title { font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem; letter-spacing: -0.02em; position: relative; z-index: 10; }
.rt-hero-desc { font-size: 1.125rem; color: rgba(255,255,255,0.9); max-width: 650px; margin: 0 auto 2.5rem auto; line-height: 1.6; position: relative; z-index: 10; }

.rt-hero-actions { display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; position: relative; z-index: 10; }
.rt-btn-primary { display: inline-flex; align-items: center; justify-content: center; padding: 1rem 2rem; border-radius: 50px; background: var(--theme-primary); color: #ffffff; font-size: 1rem; font-weight: 700; text-decoration: none; transition: all 0.3s; }
.rt-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
.rt-btn-outline-white { display: inline-flex; align-items: center; justify-content: center; padding: 1rem 2rem; border-radius: 50px; background: transparent; color: #ffffff; border: 2px solid rgba(255,255,255,0.5); font-size: 1rem; font-weight: 700; text-decoration: none; transition: all 0.3s; }
.rt-btn-outline-white:hover { background: rgba(255,255,255,0.1); border-color: #ffffff; }

/* --- SECTION GENERAL --- */
.rt-section { padding: 5rem 0; }
.rt-section-header { margin-bottom: 3rem; }
.rt-section-title { font-size: 2.25rem; font-weight: 800; color: #0f172a; margin-bottom: 0.75rem; letter-spacing: -0.02em; }
.rt-section-desc { font-size: 1rem; color: #64748b; max-width: 600px; margin: 0 auto; }

/* Header Katalog & Filter */
.rt-katalog-header { display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 3rem; }
@media (min-width: 768px) {
    .rt-katalog-header { flex-direction: row; align-items: flex-end; justify-content: space-between; }
}
.rt-section-line { width: 60px; height: 4px; background: #0f172a; border-radius: 4px; margin-top: 1rem; }
.rt-katalog-desc { font-size: 0.9rem; color: #64748b; max-width: 400px; line-height: 1.6; margin: 0; }

.rt-filter-wrap { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 3rem; }
.rt-filter-btn { padding: 0.6rem 1.25rem; border-radius: 50px; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
.rt-filter-btn:hover { background: #f8fafc; color: #0f172a; }
.rt-filter-btn.rt-active { background: var(--theme-primary); border-color: var(--theme-primary); color: #ffffff; }

/* --- GRID KATALOG --- */
.rt-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; }
.rt-card { background: #ffffff; border-radius: 1rem; overflow: hidden; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.3s; display: flex; flex-direction: column; }
.rt-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }

.rt-card-img-wrap { position: relative; aspect-ratio: 4/3; overflow: hidden; background: #f8fafc; }
.rt-card-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s; }
.rt-card:hover .rt-card-img { transform: scale(1.05); }
.rt-card-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.rt-card-img-placeholder span { font-size: 4rem; font-weight: 700; color: #ffffff; opacity: 0.3; }

.rt-card-category { position: absolute; top: 1rem; left: 1rem; background: rgba(255,255,255,0.9); backdrop-filter: blur(4px); padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #0f172a; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }

.rt-card-content { padding: 1.5rem; display: flex; flex-direction: column; flex: 1; }
.rt-card-title { font-size: 1.25rem; font-weight: 700; color: #0f172a; line-height: 1.35; margin-bottom: 0.5rem; transition: color 0.3s; }
.rt-card:hover .rt-card-title { color: var(--theme-primary); }
.rt-card-price { font-size: 1rem; font-weight: 800; color: var(--theme-primary); margin-bottom: 1.5rem; }
.rt-card-price span { font-size: 0.8rem; font-weight: 500; color: #64748b; }

.rt-card-footer { margin-top: auto; }
.rt-card-link { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; color: var(--theme-primary); text-decoration: none; }
.rt-card-link svg { width: 16px; height: 16px; transition: transform 0.3s; }
.rt-card:hover .rt-card-link svg { transform: translateX(4px); }

.rt-empty { text-align: center; padding: 4rem 0; color: #64748b; }

/* --- TESTIMONIALS --- */
.rt-section-header { margin-bottom: 3rem; }
.rt-grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
.rt-testi-card { background: #ffffff; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; }
.rt-testi-stars { display: flex; gap: 0.25rem; margin-bottom: 1rem; }
.rt-testi-stars svg { width: 18px; height: 18px; }
.rt-testi-text { font-size: 0.95rem; color: #475569; line-height: 1.7; margin-bottom: 1.5rem; font-style: italic; }
.rt-testi-author { display: flex; align-items: center; gap: 1rem; }
.rt-testi-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--theme-primary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; }
.rt-testi-name { font-size: 0.9rem; font-weight: 700; color: #0f172a; }
.rt-testi-role { font-size: 0.75rem; color: #94a3b8; }

/* --- FAQ SECTION --- */
.rt-faq-list { max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 1rem; }
.rt-faq-item { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
.rt-faq-btn { width: 100%; padding: 1.25rem; background: transparent; border: none; display: flex; justify-content: space-between; align-items: center; font-size: 1rem; font-weight: 700; color: #0f172a; cursor: pointer; text-align: left; }
.rt-faq-icon { width: 20px; height: 20px; color: #64748b; transition: transform 0.3s; }
.rt-faq-icon.rotate-180 { transform: rotate(180deg); }
.rt-faq-answer { padding: 0 1.25rem 1.25rem 1.25rem; color: #475569; font-size: 0.95rem; line-height: 1.7; }

/* --- CTA SECTION --- */
.rt-cta-section { padding: 6rem 0; background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary)); position: relative; overflow: hidden; }
.rt-cta-title { font-size: 2.5rem; font-weight: 800; color: #ffffff; margin-bottom: 1rem; }
.rt-cta-desc { font-size: 1.125rem; color: rgba(255,255,255,0.9); margin-bottom: 2rem; }
</style>
@endpush