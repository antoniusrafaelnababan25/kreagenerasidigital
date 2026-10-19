@extends('layouts.app')

@section('title', $portfolio->title . ' | KREA Video')

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
        
        {{-- Breadcrumb --}}
        <div class="vdp-breadcrumb">
            <a href="/">Beranda</a>
            <span>/</span>
            <a href="{{ route('services.video.portfolio') }}">Portfolio</a>
            <span>/</span>
            <span class="vdp-breadcrumb-active">{{ $portfolio->title }}</span>
        </div>

        <div class="vdp-detail-layout">
            
            {{-- KIRI: Video & Deskripsi --}}
            <div class="vdp-detail-main">
                
                {{-- Video Player (Jika ada video_url) --}}
                @if($portfolio->video_url)
                    <div class="vdp-video-wrapper">
                        @if(str_contains($portfolio->video_url, 'youtube.com') || str_contains($portfolio->video_url, 'youtu.be'))
                            @php
                                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $portfolio->video_url, $matches);
                                $youtubeId = $matches[1] ?? '';
                            @endphp
                            <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" title="{{ $portfolio->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                            <div class="vdp-video-placeholder">
                                <a href="{{ $portfolio->video_url }}" target="_blank" class="vdp-btn-primary" style="padding: 1rem 2rem;">
                                    Tonton Video Eksternal
                                </a>
                            </div>
                        @endif
                    </div>
                @elseif($portfolio->featured_image)
                    <div class="vdp-video-wrapper">
                        <img src="{{ asset('storage/' . $portfolio->featured_image) }}" alt="{{ $portfolio->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                @endif

                <div class="vdp-content-box">
                    <h1 class="vdp-content-title">{{ $portfolio->title }}</h1>
                    <p class="vdp-content-client">Klien: <strong>{{ $portfolio->client_name ?? '-' }}</strong></p>
                    
                    <div class="vdp-prose">
                        {!! nl2br(e($portfolio->description ?? $portfolio->short_description)) !!}
                    </div>
                </div>
            </div>

            {{-- KANAN: Sidebar Info --}}
            <aside class="vdp-detail-sidebar">
                <div class="vdp-card" style="position: sticky; top: 2rem;">
                    <h3 class="vdp-sidebar-title">Informasi Proyek</h3>
                    
                    <ul class="vdp-info-list">
                        <li>
                            <span>Tahun Rilis</span>
                            <strong>{{ $portfolio->project_year ?? '-' }}</strong>
                        </li>
                        <li>
                            <span>Kategori</span>
                            <strong>{{ ucfirst($portfolio->service_type ?? 'Video') }}</strong>
                        </li>
                        <li>
                            <span>Klien</span>
                            <strong>{{ $portfolio->client_name ?? '-' }}</strong>
                        </li>
                    </ul>

                    <hr class="vdp-divider" style="border-top: 1px dashed #334155; margin: 1.5rem 0;">

                    <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '' }}?text=Halo, saya ingin memesan video seperti portfolio {{ $portfolio->title }}" target="_blank" class="vdp-btn-order" style="width: 100%; margin-bottom: 1rem; padding: 1rem; font-size: 1rem;">
                        Pesan Video Serupa
                    </a>

                    <a href="{{ route('services.video.portfolio') }}" class="vdp-btn-detail" style="width: 100%; padding: 1rem; font-size: 1rem;">
                        Kembali ke Portfolio
                    </a>
                </div>
            </aside>

        </div>

        {{-- Portfolio Terkait --}}
        @if($related->count() > 0)
            <div style="margin-top: 5rem;">
                <h2 class="vdp-sidebar-title" style="margin-bottom: 2rem; font-size: 1.5rem;">Karya Lainnya</h2>
                <div class="vdp-grid">
                    @foreach($related as $rel)
                        <div class="vdp-card" style="padding: 0; overflow: hidden;">
                            <div class="vdp-portfolio-img-wrap">
                                @if($rel->featured_image)
                                    <img src="{{ asset('storage/' . $rel->featured_image) }}" alt="{{ $rel->title }}" class="vdp-portfolio-img" loading="lazy">
                                @else
                                    <div class="vdp-portfolio-placeholder">
                                        <span>{{ substr($rel->title, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="vdp-portfolio-content" style="padding: 1.25rem;">
                                <h3 class="vdp-portfolio-title" style="font-size: 1.1rem;">{{ $rel->title }}</h3>
                                <a href="{{ route('services.video.portfolio.detail', $rel->slug) }}" class="vdp-portfolio-link" style="margin-top: 1rem;">
                                    Lihat Karya
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>

@endsection

@push('styles')
<style>
/* ============================================================ */
/* CSS HALAMAN DETAIL PORTFOLIO VIDEO */
/* ============================================================ */

.vdp-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
.vdp-body { padding: 4rem 0 6rem; background-color: #0f172a; color: #ffffff; }

.vdp-breadcrumb { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #94a3b8; margin-bottom: 1.5rem; }
.vdp-breadcrumb a { color: inherit; text-decoration: none; transition: color 0.2s; }
.vdp-breadcrumb a:hover { color: #ffffff; }
.vdp-breadcrumb-active { color: #ffffff; font-weight: 600; }

.vdp-detail-layout { display: grid; grid-template-columns: 1fr; gap: 3rem; }
@media (min-width: 992px) { .vdp-detail-layout { grid-template-columns: 1fr 360px; align-items: start; } }

/* Video Wrapper */
.vdp-video-wrapper { position: relative; aspect-ratio: 16/9; background: #000; border-radius: 1.5rem; overflow: hidden; border: 1px solid #334155; margin-bottom: 2rem; }
.vdp-video-wrapper iframe { position: absolute; inset: 0; width: 100%; height: 100%; }
.vdp-video-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #1e293b; }

/* Content Box */
.vdp-content-box { background: #1e293b; border-radius: 1.5rem; padding: 2.5rem; border: 1px solid #334155; }
.vdp-content-title { font-size: 2rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; line-height: 1.2; }
.vdp-content-client { font-size: 1rem; color: #94a3b8; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #334155; }
.vdp-content-client strong { color: #ffffff; }
.vdp-prose { font-size: 1.05rem; line-height: 1.8; color: #cbd5e1; }

/* Sidebar */
.vdp-card { background: #1e293b; border-radius: 1.5rem; padding: 2rem; border: 1px solid #334155; }
.vdp-sidebar-title { font-size: 1.15rem; font-weight: 800; color: #ffffff; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #334155; }

.vdp-info-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem; }
.vdp-info-list li { display: flex; justify-content: space-between; font-size: 0.9rem; color: #94a3b8; }
.vdp-info-list li strong { color: #ffffff; font-weight: 600; text-align: right; }

.vdp-btn-detail { display: block; text-align: center; border-radius: 8px; background: #334155; color: #ffffff; font-weight: 700; text-decoration: none; transition: all 0.2s; }
.vdp-btn-detail:hover { background: #475569; }
.vdp-btn-order { display: block; text-align: center; border-radius: 8px; background: var(--theme-primary, #3B82F6); color: #ffffff; font-weight: 700; text-decoration: none; transition: all 0.2s; border: 1px solid var(--theme-primary, #3B82F6); }
.vdp-btn-order:hover { background: #2563eb; border-color: #2563eb; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(37,99,235,0.3); }

/* Related */
.vdp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem; }
.vdp-card-related { background: #1e293b; border-radius: 1.5rem; border: 1px solid #334155; transition: all 0.3s; }
.vdp-card-related:hover { transform: translateY(-5px); border-color: #475569; }
</style>
@endpush