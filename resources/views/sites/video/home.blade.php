@extends('layouts.app')

@section('title', 'Videografi Profesional')

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
{{-- PAKET VIDEOGRAFI SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(isset($packages) && $packages->count() > 0)
<section class="vd-section bg-dark">
    <div class="vd-container">
        <div class="vd-section-header vd-text-center">
            <h2 class="vd-section-title" style="color: #ffffff;">Paket Videografi</h2>
            <p class="vd-section-desc" style="color: #94a3b8;">Pilih paket yang sesuai dengan kebutuhan visual Anda.</p>
        </div>

        <div class="vd-grid-3">
            @foreach($packages as $package)
                <div class="vd-package-card {{ $package->is_featured ? 'vd-featured' : '' }}">
                    @if($package->is_featured)
                        <div class="vd-badge-popular">Paling Populer</div>
                    @endif
                    
                    <h3 class="vd-package-name">{{ $package->name }}</h3>
                    <div class="vd-package-price">
                        Rp {{ number_format($package->price, 0, ',', '.') }}
                        @if($package->compare_price)
                            <span class="vd-price-strike">Rp {{ number_format($package->compare_price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <p class="vd-package-desc">{{ $package->description }}</p>
                    
                    <ul class="vd-feature-list">
                        @if($package->features_array)
                            @foreach($package->features_array as $feature)
                                <li>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        @endif
                        
                        @if($package->duration)
                            <li>
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Durasi: {{ $package->duration }}
                            </li>
                        @endif
                        @if($package->crew_count)
                            <li>
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                {{ $package->crew_count }} Kru
                            </li>
                        @endif
                    </ul>

                    <a href="{{ route('services.video.order', ['package' => $package->slug]) ?? '#' }}" class="vd-btn-order">
                        Pesan Paket Ini
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
<section class="vd-section bg-white">
    <div class="vd-container">
        <div class="vd-section-header vd-text-center">
            <h2 class="vd-section-title">Karya Visual Kami</h2>
            <p class="vd-section-desc">Beberapa proyek video yang telah kami produksi.</p>
        </div>

        <div class="vd-grid-3">
            @foreach($portfolios as $portfolio)
                <div class="vd-portfolio-card">
                    <div class="vd-portfolio-img-wrap">
                        @if($portfolio->featured_image)
                            <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" class="vd-portfolio-img" loading="lazy">
                        @else
                            <div class="vd-portfolio-placeholder" style="background: linear-gradient(135deg, var(--theme-primary, #3B82F6), var(--theme-secondary, #8B5CF6));">
                                <span>{{ substr($portfolio->title, 0, 1) }}</span>
                            </div>
                        @endif
                        
                        {{-- Ikon Play jika ada video --}}
                        @if($portfolio->video_url)
                            <div class="vd-play-icon">
                                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="vd-portfolio-content">
                        <h3 class="vd-portfolio-title">{{ $portfolio->title }}</h3>
                        <p class="vd-portfolio-client">{{ $portfolio->client_name }}</p>
                        <a href="{{ route('services.video.portfolio.detail', $portfolio->slug) ?? '#' }}" class="vd-portfolio-link">
                            Lihat Karya
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
<section class="vd-section bg-gray-50">
    <div class="vd-container">
        <div class="vd-section-header vd-text-center">
            <h2 class="vd-section-title">Apa Kata Klien Kami?</h2>
            <p class="vd-section-desc">Pengalaman mereka yang telah menggunakan jasa videografi kami.</p>
        </div>

        <div class="vd-grid-3">
            @foreach($testimonials as $testi)
                <div class="vd-testi-card">
                    <div class="vd-testi-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="{{ $i <= $testi->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="vd-testi-text">"{{ $testi->content }}"</p>
                    <div class="vd-testi-author">
                        <div class="vd-testi-avatar">{{ substr($testi->name, 0, 1) }}</div>
                        <div>
                            <div class="vd-testi-name">{{ $testi->name }}</div>
                            <div class="vd-testi-role">{{ $testi->position ?? 'Klien' }}</div>
                        </div>
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
    <section class="vd-cta-section">
        <div class="vd-container vd-text-center">
            <h2 class="vd-cta-title">Siap Mewujudkan Visi Visual Anda?</h2>
            <p class="vd-cta-desc">Diskusikan ide dan konsep video Anda dengan tim kreatif kami.</p>
            <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '' }}" target="_blank" class="vd-btn-primary" style="background: #ffffff; color: #0f172a;">
                Konsultasi Gratis
            </a>
        </div>
    </section>
@endif

@endsection

@push('styles')
<style>
/* ============================================================ */
/* CUSTOM CSS HALAMAN VIDEOGRAFI HOME */
/* ============================================================ */

.vd-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; position: relative; }
.vd-section { padding: 5rem 0; }
.bg-white { background-color: #ffffff; }
.bg-gray-50 { background-color: #f8fafc; }
.bg-dark { background-color: #0f172a; }
.vd-text-center { text-align: center; }
.text-yellow-400 { color: #facc15; }
.text-gray-300 { color: #d1d5db; }

/* Section Header */
.vd-section-header { margin-bottom: 3rem; }
.vd-section-title { font-size: 2.25rem; font-weight: 800; color: #0f172a; margin-bottom: 0.75rem; letter-spacing: -0.02em; }
.vd-section-desc { font-size: 1rem; color: #64748b; max-width: 600px; margin: 0 auto; }

/* Grid */
.vd-grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }

/* --- PAKET VIDEOGRAFI (DARK THEME) --- */
.vd-package-card { background: #1e293b; border-radius: 1.5rem; padding: 2.5rem; border: 1px solid #334155; position: relative; display: flex; flex-direction: column; transition: all 0.3s; }
.vd-package-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.4); border-color: #475569; }
.vd-package-card.vd-featured { border-color: var(--theme-primary, #3B82F6); border-width: 2px; }
.vd-badge-popular { position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--theme-primary, #3B82F6); color: #ffffff; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.4rem 1rem; border-radius: 50px; }

.vd-package-name { font-size: 1.5rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; }
.vd-package-price { font-size: 2rem; font-weight: 800; color: var(--theme-primary, #3B82F6); margin-bottom: 1rem; line-height: 1.2; }
.vd-price-strike { font-size: 1rem; color: #64748b; text-decoration: line-through; font-weight: 500; margin-left: 0.5rem; display: block; margin-top: 0.25rem; }
.vd-package-desc { font-size: 0.9rem; color: #94a3b8; margin-bottom: 1.5rem; line-height: 1.6; border-bottom: 1px dashed #334155; padding-bottom: 1.5rem; }

.vd-feature-list { list-style: none; padding: 0; margin: 0 0 2rem 0; display: flex; flex-direction: column; gap: 0.75rem; flex: 1; }
.vd-feature-list li { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.95rem; color: #cbd5e1; }
.vd-feature-list svg { width: 18px; height: 18px; color: #10b981; flex-shrink: 0; margin-top: 2px; }

.vd-btn-order { display: block; width: 100%; text-align: center; padding: 1rem; border-radius: 50px; background: #334155; color: #ffffff; font-weight: 700; text-decoration: none; border: 1px solid #475569; transition: all 0.2s; margin-top: auto; }
.vd-btn-order:hover { background: var(--theme-primary, #3B82F6); color: #ffffff; border-color: var(--theme-primary, #3B82F6); }
.vd-featured .vd-btn-order { background: var(--theme-primary, #3B82F6); color: #ffffff; border-color: var(--theme-primary, #3B82F6); }
.vd-featured .vd-btn-order:hover { background: #2563eb; border-color: #2563eb; }

/* --- PORTFOLIO --- */
.vd-portfolio-card { background: #ffffff; border-radius: 1rem; overflow: hidden; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.3s; }
.vd-portfolio-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }
.vd-portfolio-img-wrap { position: relative; aspect-ratio: 16/9; overflow: hidden; background: #f1f5f9; }
.vd-portfolio-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
.vd-portfolio-card:hover .vd-portfolio-img { transform: scale(1.05); }
.vd-portfolio-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.vd-portfolio-placeholder span { font-size: 4rem; font-weight: 700; color: #ffffff; opacity: 0.3; }

.vd-play-icon { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.3); transition: background 0.3s; }
.vd-portfolio-card:hover .vd-play-icon { background: rgba(0,0,0,0.5); }
.vd-play-icon svg { width: 48px; height: 48px; color: #ffffff; opacity: 0.8; transition: transform 0.3s; }
.vd-portfolio-card:hover .vd-play-icon svg { transform: scale(1.1); opacity: 1; }

.vd-portfolio-content { padding: 1.5rem; }
.vd-portfolio-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem; }
.vd-portfolio-client { font-size: 0.85rem; color: #64748b; margin-bottom: 1rem; }
.vd-portfolio-link { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; color: var(--theme-primary, #3B82F6); text-decoration: none; }
.vd-portfolio-link svg { width: 16px; height: 16px; transition: transform 0.3s; }
.vd-portfolio-card:hover .vd-portfolio-link svg { transform: translateX(4px); }

/* --- TESTIMONIALS --- */
.vd-testi-card { background: #ffffff; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; }
.vd-testi-stars { display: flex; gap: 0.25rem; margin-bottom: 1rem; }
.vd-testi-stars svg { width: 18px; height: 18px; }
.vd-testi-text { font-size: 0.95rem; color: #475569; line-height: 1.7; margin-bottom: 1.5rem; font-style: italic; }
.vd-testi-author { display: flex; align-items: center; gap: 1rem; }
.vd-testi-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--theme-primary, #3B82F6); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; }
.vd-testi-name { font-size: 0.9rem; font-weight: 700; color: #0f172a; }
.vd-testi-role { font-size: 0.75rem; color: #94a3b8; }

/* --- CTA SECTION --- */
.vd-cta-section { padding: 6rem 0; background: linear-gradient(135deg, #0f172a, #1e293b); position: relative; overflow: hidden; }
.vd-cta-title { font-size: 2.5rem; font-weight: 800; color: #ffffff; margin-bottom: 1rem; }
.vd-cta-desc { font-size: 1.125rem; color: #94a3b8; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto; }
.vd-btn-primary { display: inline-flex; align-items: center; justify-content: center; padding: 1rem 2.5rem; border-radius: 50px; font-size: 1rem; font-weight: 700; text-decoration: none; transition: all 0.3s; }
.vd-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }

@media (max-width: 640px) {
    .vd-section { padding: 3rem 0; }
    .vd-cta-title { font-size: 1.75rem; }
}
</style>
@endpush