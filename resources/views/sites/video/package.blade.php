@extends('layouts.app')

@section('title', 'Paket Videografi')

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
        
        <div class="vdp-header vdp-text-center">
            <h1 class="vdp-title">Paket Videografi</h1>
            <p class="vdp-desc">Pilih paket yang paling sesuai dengan kebutuhan visual Anda.</p>
        </div>

        {{-- Filter Kategori --}}
        @if($categories->count() > 0)
            <div class="vdp-filter-wrap">
                <a href="{{ route('services.video.package') }}" 
                   class="vdp-filter-btn {{ !request('category') ? 'vdp-active' : '' }}">
                    Semua Paket
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('services.video.package', ['category' => $cat]) }}" 
                       class="vdp-filter-btn {{ request('category') === $cat ? 'vdp-active' : '' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Grid Paket --}}
        @if($packages->count() > 0)
            <div class="vdp-grid">
                @foreach($packages as $package)
                    <div class="vdp-card {{ $package->is_featured ? 'vdp-featured' : '' }}">
                        @if($package->is_featured)
                            <div class="vdp-badge">Paling Populer</div>
                        @endif

                        <div class="vdp-card-header">
                            <h3 class="vdp-card-title">{{ $package->name }}</h3>
                            <div class="vdp-card-price">
                                Rp {{ number_format($package->price, 0, ',', '.') }}
                                @if($package->compare_price)
                                    <span class="vdp-strike">Rp {{ number_format($package->compare_price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <p class="vdp-card-desc">{{ $package->description }}</p>
                        </div>

                        <ul class="vdp-features">
                            @if(is_array($package->features))
                                @foreach($package->features as $feature)
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
                        </ul>

                        <div class="vdp-card-footer">
                            <a href="{{ route('services.video.package.detail', $package->slug) }}" class="vdp-btn-detail">
                                Lihat Detail
                            </a>
                            <a href="{{ route('services.video.order', ['package' => $package->slug]) }}" class="vdp-btn-order">
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="vdp-empty">
                <p>Belum ada paket videografi yang tersedia saat ini.</p>
            </div>
        @endif

    </div>
</section>

@endsection

@push('styles')
<style>
/* ============================================================ */
/* CSS HALAMAN DAFTAR PAKET VIDEOGRAFI */
/* ============================================================ */

.vdp-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
.vdp-body { padding: 4rem 0 6rem; background-color: #0f172a; color: #ffffff; }
.vdp-text-center { text-align: center; }

.vdp-header { margin-bottom: 3rem; }
.vdp-title { font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; }
.vdp-desc { font-size: 1.125rem; color: #94a3b8; max-width: 600px; margin: 0 auto; }

/* Filters */
.vdp-filter-wrap { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 3rem; justify-content: center; }
.vdp-filter-btn { padding: 0.6rem 1.5rem; border-radius: 50px; background: #1e293b; border: 1px solid #334155; color: #94a3b8; font-size: 0.9rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
.vdp-filter-btn:hover { background: #334155; color: #ffffff; }
.vdp-filter-btn.vdp-active { background: var(--theme-primary, #3B82F6); border-color: var(--theme-primary, #3B82F6); color: #ffffff; }

/* Grid & Cards */
.vdp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem; }
.vdp-card { background: #1e293b; border-radius: 1.5rem; padding: 2.5rem; border: 1px solid #334155; position: relative; display: flex; flex-direction: column; transition: all 0.3s; }
.vdp-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.4); border-color: #475569; }
.vdp-card.vdp-featured { border-color: var(--theme-primary, #3B82F6); border-width: 2px; }
.vdp-badge { position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--theme-primary, #3B82F6); color: #ffffff; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.4rem 1rem; border-radius: 50px; }

.vdp-card-header { margin-bottom: 1.5rem; border-bottom: 1px dashed #334155; padding-bottom: 1.5rem; }
.vdp-card-title { font-size: 1.5rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; }
.vdp-card-price { font-size: 2rem; font-weight: 800; color: var(--theme-primary, #3B82F6); line-height: 1.2; margin-bottom: 1rem; }
.vdp-strike { font-size: 1rem; color: #64748b; text-decoration: line-through; font-weight: 500; margin-left: 0.5rem; display: block; margin-top: 0.25rem; }
.vdp-card-desc { font-size: 0.9rem; color: #94a3b8; line-height: 1.6; }

.vdp-features { list-style: none; padding: 0; margin: 0 0 2rem 0; display: flex; flex-direction: column; gap: 0.75rem; flex: 1; }
.vdp-features li { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.95rem; color: #cbd5e1; }
.vdp-features svg { width: 18px; height: 18px; color: #10b981; flex-shrink: 0; margin-top: 2px; }

.vdp-card-footer { display: flex; gap: 1rem; margin-top: auto; }
.vdp-btn-detail { flex: 1; text-align: center; padding: 0.8rem; border-radius: 8px; background: #334155; color: #ffffff; font-weight: 700; text-decoration: none; transition: all 0.2s; font-size: 0.9rem; }
.vdp-btn-detail:hover { background: #475569; }
.vdp-btn-order { flex: 1; text-align: center; padding: 0.8rem; border-radius: 8px; background: var(--theme-primary, #3B82F6); color: #ffffff; font-weight: 700; text-decoration: none; transition: all 0.2s; font-size: 0.9rem; }
.vdp-btn-order:hover { background: #2563eb; }

.vdp-empty { text-align: center; padding: 4rem 0; color: #94a3b8; }
</style>
@endpush