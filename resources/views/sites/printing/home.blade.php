@extends('layouts.app')

@section('title', 'Custom Printing Berkualitas')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@include('sections.hero', ['content' => $hero['content'] ?? []])

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- SERVICES SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(!empty($services) && isset($services['content']))
    @include('sections.services', [
        'content' => $services['content'],
        'section' => $services,
    ])
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- FEATURED PRODUCTS SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(isset($featuredProducts) && $featuredProducts->count() > 0)
<section class="prt-section bg-gray-50">
    <div class="prt-container">
        <div class="prt-section-header prt-text-center">
            <h2 class="prt-section-title">Produk Unggulan</h2>
            <p class="prt-section-desc">Kualitas cetak terbaik untuk kebutuhan bisnis dan personal Anda.</p>
        </div>

        <div class="prt-grid">
            @foreach($featuredProducts as $product)
                <div class="prt-card">
                    <div class="prt-card-img-wrap">
                        @if($product->featured_image)
                            <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="prt-card-img" loading="lazy">
                        @else
                            <div class="prt-card-placeholder">
                                <span>{{ strtoupper(substr($product->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        
                        @if($product->base_price)
                            <div class="prt-badge">
                                Rp {{ number_format($product->base_price, 0, ',', '.') }}
                            </div>
                        @endif
                    </div>
                    <div class="prt-card-content">
                        <h3 class="prt-card-title">{{ $product->name }}</h3>
                        <p class="prt-card-desc">{{ Str::limit($product->description, 80) }}</p>
                        
                        <a href="{{ route('services.printing.product.detail', $product->slug) ?? '#' }}" class="prt-card-link">
                            Lihat Detail
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="prt-text-center" style="margin-top: 3rem;">
            <a href="{{ route('services.printing.product') }}" class="prt-btn-outline">Lihat Semua Produk</a>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- PORTFOLIO SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(isset($portfolios) && $portfolios->count() > 0)
<section class="prt-section bg-white">
    <div class="prt-container">
        <div class="prt-section-header prt-text-center">
            <h2 class="prt-section-title">Hasil Karya Kami</h2>
            <p class="prt-section-desc">Beberapa proyek cetak yang telah kami selesaikan.</p>
        </div>

        <div class="prt-grid">
            @foreach($portfolios as $portfolio)
                <div class="prt-card">
                    <div class="prt-card-img-wrap" style="aspect-ratio: 1/1;">
                        @if($portfolio->featured_image)
                            <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="prt-card-img" loading="lazy">
                        @else
                            <div class="prt-card-placeholder">
                                <span>{{ strtoupper(substr($portfolio->title, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="prt-card-content">
                        <h3 class="prt-card-title">{{ $portfolio->title }}</h3>
                        <p class="prt-card-desc">{{ $portfolio->client_name }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- TESTIMONIALS SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(isset($testimonials) && $testimonials->count() > 0)
<section class="prt-section bg-gray-50">
    <div class="prt-container">
        <div class="prt-section-header prt-text-center">
            <h2 class="prt-section-title">Apa Kata Klien Kami?</h2>
            <p class="prt-section-desc">Kepuasan pelanggan adalah prioritas utama kami.</p>
        </div>

        <div class="prt-grid-3">
            @foreach($testimonials as $testi)
                <div class="prt-testi-card">
                    <div class="prt-testi-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="{{ $i <= $testi->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="prt-testi-text">"{{ $testi->content }}"</p>
                    <div class="prt-testi-author">
                        <div class="prt-testi-avatar">{{ substr($testi->name, 0, 1) }}</div>
                        <div>
                            <div class="prt-testi-name">{{ $testi->name }}</div>
                            <div class="prt-testi-role">{{ $testi->position ?? 'Pelanggan' }}</div>
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
<section class="prt-section bg-white">
    <div class="prt-container">
        <div class="prt-section-header prt-text-center">
            <h2 class="prt-section-title">Pertanyaan Umum (FAQ)</h2>
            <p class="prt-section-desc">Hal-hal yang sering ditanyakan seputar proses custom printing.</p>
        </div>

        <div class="prt-faq-list" x-data="{ openItem: null }">
            @foreach($faqs as $faq)
                <div class="prt-faq-item">
                    <button class="prt-faq-btn" @click="openItem = openItem === {{ $loop->index }} ? null : {{ $loop->index }}">
                        <span>{{ $faq->question }}</span>
                        <svg class="prt-faq-icon" :class="openItem === {{ $loop->index }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="prt-faq-answer" x-show="openItem === {{ $loop->index }}" x-collapse>
                        <p>{!! nl2br(e($faq->answer)) !!}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- CTA SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(!empty($ctaSection) && isset($ctaSection['content']))
    @include('sections.cta', [
        'content' => $ctaSection['content'],
        'section' => $ctaSection,
    ])
@else
    {{-- Fallback CTA --}}
    <section class="prt-cta-section">
        <div class="prt-container prt-text-center">
            <h2 class="prt-cta-title">Siap Mencetak Ide Anda?</h2>
            <p class="prt-cta-desc">Konsultasikan kebutuhan custom printing Anda sekarang dan dapatkan penawaran terbaik.</p>
            <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '' }}" target="_blank" class="prt-btn-primary" style="background: #ffffff; color: var(--theme-primary, #3B82F6);">
                Hubungi via WhatsApp
            </a>
        </div>
    </section>
@endif

@endsection

@push('styles')
<style>
/* ============================================================ */
/* CUSTOM CSS HALAMAN PRINTING HOME */
/* ============================================================ */

.prt-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; position: relative; }
.prt-section { padding: 5rem 0; }
.bg-white { background-color: #ffffff; }
.bg-gray-50 { background-color: #f8fafc; }
.prt-text-center { text-align: center; }
.text-yellow-400 { color: #facc15; }
.text-gray-300 { color: #d1d5db; }

/* Section Header */
.prt-section-header { margin-bottom: 3rem; }
.prt-section-title { font-size: 2.25rem; font-weight: 800; color: #0f172a; margin-bottom: 0.75rem; letter-spacing: -0.02em; }
.prt-section-desc { font-size: 1rem; color: #64748b; max-width: 600px; margin: 0 auto; }

/* Grid */
.prt-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; }
.prt-grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }

/* Cards */
.prt-card { background: #ffffff; border-radius: 1rem; overflow: hidden; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.3s; display: flex; flex-direction: column; }
.prt-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }

.prt-card-img-wrap { position: relative; aspect-ratio: 4/3; overflow: hidden; background: #f8fafc; }
.prt-card-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s; }
.prt-card:hover .prt-card-img { transform: scale(1.05); }
.prt-card-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--theme-primary, #3B82F6), var(--theme-secondary, #8B5CF6)); }
.prt-card-placeholder span { font-size: 4rem; font-weight: 700; color: #ffffff; opacity: 0.3; }

.prt-badge { position: absolute; top: 1rem; right: 1rem; background: rgba(255,255,255,0.9); backdrop-filter: blur(4px); padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 700; color: #0f172a; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }

.prt-card-content { padding: 1.5rem; display: flex; flex-direction: column; flex: 1; }
.prt-card-title { font-size: 1.25rem; font-weight: 700; color: #0f172a; line-height: 1.35; margin-bottom: 0.5rem; transition: color 0.3s; }
.prt-card:hover .prt-card-title { color: var(--theme-primary, #3B82F6); }
.prt-card-desc { font-size: 0.9rem; color: #64748b; margin-bottom: 1.5rem; flex: 1; line-height: 1.5; }

.prt-card-link { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; color: var(--theme-primary, #3B82F6); text-decoration: none; }
.prt-card-link svg { width: 16px; height: 16px; transition: transform 0.3s; }
.prt-card:hover .prt-card-link svg { transform: translateX(4px); }

/* Button Outline */
.prt-btn-outline { display: inline-flex; align-items: center; justify-content: center; padding: 0.8rem 2rem; border-radius: 50px; background: transparent; color: #0f172a; border: 2px solid #e2e8f0; font-size: 0.95rem; font-weight: 700; text-decoration: none; transition: all 0.2s; }
.prt-btn-outline:hover { background: #f1f5f9; border-color: #cbd5e1; }

/* Testimonials */
.prt-testi-card { background: #ffffff; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; }
.prt-testi-stars { display: flex; gap: 0.25rem; margin-bottom: 1rem; }
.prt-testi-stars svg { width: 18px; height: 18px; }
.prt-testi-text { font-size: 0.95rem; color: #475569; line-height: 1.7; margin-bottom: 1.5rem; font-style: italic; }
.prt-testi-author { display: flex; align-items: center; gap: 1rem; }
.prt-testi-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--theme-primary, #3B82F6); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; }
.prt-testi-name { font-size: 0.9rem; font-weight: 700; color: #0f172a; }
.prt-testi-role { font-size: 0.75rem; color: #94a3b8; }

/* FAQ */
.prt-faq-list { max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 1rem; }
.prt-faq-item { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
.prt-faq-btn { width: 100%; padding: 1.25rem; background: transparent; border: none; display: flex; justify-content: space-between; align-items: center; font-size: 1rem; font-weight: 700; color: #0f172a; cursor: pointer; text-align: left; }
.prt-faq-icon { width: 20px; height: 20px; color: #64748b; transition: transform 0.3s; }
.prt-faq-icon.rotate-180 { transform: rotate(180deg); }
.prt-faq-answer { padding: 0 1.25rem 1.25rem 1.25rem; color: #475569; font-size: 0.95rem; line-height: 1.7; }

/* CTA */
.prt-cta-section { padding: 6rem 0; background: linear-gradient(135deg, var(--theme-primary, #3B82F6), var(--theme-secondary, #8B5CF6)); position: relative; overflow: hidden; }
.prt-cta-title { font-size: 2.5rem; font-weight: 800; color: #ffffff; margin-bottom: 1rem; }
.prt-cta-desc { font-size: 1.125rem; color: rgba(255,255,255,0.9); margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto; }
.prt-btn-primary { display: inline-flex; align-items: center; justify-content: center; padding: 1rem 2rem; border-radius: 50px; font-size: 1rem; font-weight: 700; text-decoration: none; transition: all 0.3s; }
.prt-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }

@media (max-width: 640px) {
    .prt-section { padding: 3rem 0; }
    .prt-cta-title { font-size: 1.75rem; }
}
</style>
@endpush