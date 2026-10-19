@extends('layouts.app')

@section('title', 'Jasa Pembuatan Website Profesional')

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
{{-- PAKET LAYANAN WEBSITE --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(isset($packages) && $packages->count() > 0)
<section class="ws-section bg-gray-50">
    <div class="ws-container">
        <div class="ws-section-header ws-text-center">
            <h2 class="ws-section-title">Pilih Paket Website Anda</h2>
            <p class="ws-section-desc">Solusi website yang disesuaikan dengan kebutuhan bisnis Anda.</p>
        </div>

        <div class="ws-grid-3">
            @foreach($packages as $package)
                <div class="ws-package-card {{ $package->is_featured ? 'ws-featured' : '' }}">
                    @if($package->is_featured)
                        <div class="ws-badge-popular">Paling Populer</div>
                    @endif
                    
                    <h3 class="ws-package-name">{{ $package->name }}</h3>
                    <div class="ws-package-price">
                        Rp {{ number_format($package->price, 0, ',', '.') }}
                        @if($package->compare_price)
                            <span class="ws-price-strike">Rp {{ number_format($package->compare_price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <p class="ws-package-desc">{{ $package->description }}</p>
                    
                    <ul class="ws-feature-list">
                        @if($package->features_array)
                            @foreach($package->features_array as $feature)
                                <li>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        @endif
                    </ul>

                    <a href="{{ url('/layanan/website/order?package=' . $package->slug) }}" class="ws-btn-order">
                        Pilih Paket Ini
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- PORTFOLIO SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(isset($portfolios) && $portfolios->count() > 0)
<section class="ws-section bg-white">
    <div class="ws-container">
        <div class="ws-section-header ws-text-center">
            <h2 class="ws-section-title">Portfolio Website Kami</h2>
            <p class="ws-section-desc">Beberapa proyek website yang telah kami kerjakan.</p>
        </div>

        <div class="ws-grid-3">
            @foreach($portfolios as $portfolio)
                <div class="ws-portfolio-card">
                    <div class="ws-portfolio-img-wrap">
                        @if($portfolio->featured_image)
                            <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="ws-portfolio-img" loading="lazy">
                        @else
                            <div class="ws-portfolio-placeholder" style="background: linear-gradient(135deg, var(--theme-primary, #3B82F6), var(--theme-secondary, #8B5CF6));">
                                <span>{{ substr($portfolio->title, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="ws-portfolio-content">
                        <h3 class="ws-portfolio-title">{{ $portfolio->title }}</h3>
                        <p class="ws-portfolio-client">{{ $portfolio->client_name }}</p>
                        {{-- Sesuaikan route detail portfolio jika ada --}}
                        <a href="{{ url('/portfolio/' . $portfolio->slug) }}" class="ws-portfolio-link">
                            Lihat Detail
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
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
<section class="ws-section bg-gray-50">
    <div class="ws-container">
        <div class="ws-section-header ws-text-center">
            <h2 class="ws-section-title">Apa Kata Klien Kami?</h2>
            <p class="ws-section-desc">Pengalaman mereka yang telah menggunakan jasa pembuatan website kami.</p>
        </div>

        <div class="ws-grid-3">
            @foreach($testimonials as $testi)
                <div class="ws-testi-card">
                    <div class="ws-testi-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="{{ $i <= $testi->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="ws-testi-text">"{{ $testi->content }}"</p>
                    <div class="ws-testi-author">
                        <div class="ws-testi-avatar">{{ substr($testi->name, 0, 1) }}</div>
                        <div>
                            <div class="ws-testi-name">{{ $testi->name }}</div>
                            <div class="ws-testi-role">{{ $testi->position ?? 'Klien' }}</div>
                        </div>
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
@if(!empty($ctaSection) && isset($ctaSection['content']))
    @include('sections.cta', [
        'content' => $ctaSection['content'],
        'section' => $ctaSection,
    ])
@else
    {{-- Fallback CTA Jika Admin Belum Mengatur --}}
    <section class="ws-cta-section">
        <div class="ws-container ws-text-center ws-z-10" style="position: relative; z-index: 10;">
            <h2 class="ws-cta-title">Siap Membangun Website Impian Anda?</h2>
            <p class="ws-cta-desc">Konsultasikan kebutuhan website bisnis Anda dengan tim kami secara gratis.</p>
            <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '' }}" target="_blank" class="ws-btn-cta" style="background: #ffffff; color: var(--theme-primary);">
                Konsultasi Gratis Sekarang
            </a>
        </div>
    </section>
@endif

@endsection

@push('styles')
<style>
/* ============================================================ */
/* CUSTOM CSS HALAMAN WEBSITE SERVICE HOME */
/* ============================================================ */

.ws-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; position: relative; }
.ws-section { padding: 5rem 0; }
.bg-white { background-color: #ffffff; }
.bg-gray-50 { background-color: #f8fafc; }
.ws-text-center { text-align: center; }
.text-yellow-400 { color: #facc15; }
.text-gray-300 { color: #d1d5db; }

/* Section Header */
.ws-section-header { margin-bottom: 3rem; }
.ws-section-title { font-size: 2.25rem; font-weight: 800; color: #0f172a; margin-bottom: 0.75rem; letter-spacing: -0.02em; }
.ws-section-desc { font-size: 1rem; color: #64748b; max-width: 600px; margin: 0 auto; }

/* Grid Utilities */
.ws-grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }

/* --- PACKAGES --- */
.ws-package-card { background: #ffffff; border-radius: 1.5rem; padding: 2.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.03); position: relative; display: flex; flex-direction: column; transition: all 0.3s; }
.ws-package-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
.ws-package-card.ws-featured { border-color: var(--theme-primary, #3B82F6); border-width: 2px; }
.ws-badge-popular { position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--theme-primary, #3B82F6); color: #ffffff; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.4rem 1rem; border-radius: 50px; }

.ws-package-name { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; }
.ws-package-price { font-size: 2rem; font-weight: 800; color: var(--theme-primary, #3B82F6); margin-bottom: 1rem; line-height: 1.2; }
.ws-price-strike { font-size: 1rem; color: #94a3b8; text-decoration: line-through; font-weight: 500; margin-left: 0.5rem; display: block; margin-top: 0.25rem; }
.ws-package-desc { font-size: 0.9rem; color: #64748b; margin-bottom: 1.5rem; line-height: 1.6; border-bottom: 1px dashed #e2e8f0; padding-bottom: 1.5rem; }

.ws-feature-list { list-style: none; padding: 0; margin: 0 0 2rem 0; display: flex; flex-direction: column; gap: 0.75rem; flex: 1; }
.ws-feature-list li { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.95rem; color: #334155; }
.ws-feature-list svg { width: 18px; height: 18px; color: #10b981; flex-shrink: 0; margin-top: 2px; }

.ws-btn-order { display: block; width: 100%; text-align: center; padding: 1rem; border-radius: 50px; background: #f1f5f9; color: #0f172a; font-weight: 700; text-decoration: none; border: 1px solid #e2e8f0; transition: all 0.2s; margin-top: auto; }
.ws-btn-order:hover { background: var(--theme-primary, #3B82F6); color: #ffffff; border-color: var(--theme-primary, #3B82F6); }
.ws-featured .ws-btn-order { background: var(--theme-primary, #3B82F6); color: #ffffff; border-color: var(--theme-primary, #3B82F6); }
.ws-featured .ws-btn-order:hover { background: #1e293b; border-color: #1e293b; }

/* --- PORTFOLIO --- */
.ws-portfolio-card { background: #ffffff; border-radius: 1rem; overflow: hidden; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.3s; }
.ws-portfolio-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }
.ws-portfolio-img-wrap { position: relative; aspect-ratio: 4/3; overflow: hidden; }
.ws-portfolio-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
.ws-portfolio-card:hover .ws-portfolio-img { transform: scale(1.05); }
.ws-portfolio-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.ws-portfolio-placeholder span { font-size: 4rem; font-weight: 700; color: #ffffff; opacity: 0.3; }

.ws-portfolio-content { padding: 1.5rem; }
.ws-portfolio-title { font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem; }
.ws-portfolio-client { font-size: 0.85rem; color: #64748b; margin-bottom: 1rem; }
.ws-portfolio-link { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; color: var(--theme-primary, #3B82F6); text-decoration: none; }
.ws-portfolio-link svg { width: 16px; height: 16px; transition: transform 0.3s; }
.ws-portfolio-card:hover .ws-portfolio-link svg { transform: translateX(4px); }

/* --- TESTIMONIALS --- */
.ws-testi-card { background: #ffffff; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; }
.ws-testi-stars { display: flex; gap: 0.25rem; margin-bottom: 1rem; }
.ws-testi-stars svg { width: 18px; height: 18px; }
.ws-testi-text { font-size: 0.95rem; color: #475569; line-height: 1.7; margin-bottom: 1.5rem; font-style: italic; }
.ws-testi-author { display: flex; align-items: center; gap: 1rem; }
.ws-testi-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--theme-primary, #3B82F6); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; }
.ws-testi-name { font-size: 0.9rem; font-weight: 700; color: #0f172a; }
.ws-testi-role { font-size: 0.75rem; color: #94a3b8; }

/* --- CTA SECTION --- */
.ws-cta-section { padding: 6rem 0; background: linear-gradient(135deg, var(--theme-primary, #3B82F6), var(--theme-secondary, #8B5CF6)); position: relative; overflow: hidden; text-align: center; }
.ws-cta-title { font-size: 2.5rem; font-weight: 800; color: #ffffff; margin-bottom: 1rem; }
.ws-cta-desc { font-size: 1.125rem; color: rgba(255,255,255,0.9); margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto; }
.ws-btn-cta { display: inline-flex; align-items: center; justify-content: center; padding: 1rem 2.5rem; border-radius: 50px; font-size: 1rem; font-weight: 700; text-decoration: none; transition: all 0.3s; }
.ws-btn-cta:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }

@media (max-width: 640px) {
    .ws-section { padding: 3rem 0; }
    .ws-cta-title { font-size: 1.75rem; }
}
</style>
@endpush