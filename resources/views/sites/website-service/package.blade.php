@extends('layouts.app')

@section('title', 'Paket & Harga Jasa Website')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION (Sama seperti Home) --}}
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
<section class="wsp-body">
    <div class="wsp-container">
        
        {{-- Judul Section --}}
        <div class="wsp-text-center" style="margin-bottom: 3rem;">
            <h1 class="wsp-title" style="color: #0f172a; font-size: 2.25rem; font-weight: 800;">Paket Pembuatan Website</h1>
            <p class="wsp-desc" style="color: #64748b;">Pilih paket yang paling sesuai dengan kebutuhan bisnis Anda.</p>
        </div>

        {{-- Filter Kategori --}}
        @if($categories->count() > 0)
            <div class="wsp-filter-wrap">
                <a href="{{ route('services.website.package') }}" 
                   class="wsp-filter-btn {{ !request('category') ? 'wsp-active' : '' }}">
                    Semua Paket
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('services.website.package', ['category' => $cat]) }}" 
                       class="wsp-filter-btn {{ request('category') === $cat ? 'wsp-active' : '' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Grid Paket --}}
        @if($packages->count() > 0)
            <div class="wsp-grid">
                @foreach($packages as $package)
                    <div class="wsp-card {{ $package->is_featured ? 'wsp-featured' : '' }}">
                        
                        @if($package->is_featured)
                            <div class="wsp-badge">Paling Populer</div>
                        @endif

                        <div class="wsp-card-header">
                            <h3 class="wsp-card-title">{{ $package->name }}</h3>
                            <div class="wsp-card-price">
                                Rp {{ number_format($package->price, 0, ',', '.') }}
                                @if($package->compare_price)
                                    <span class="wsp-strike">Rp {{ number_format($package->compare_price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <p class="wsp-card-desc">{{ $package->description }}</p>
                        </div>

                        <ul class="wsp-features">
                            @if(is_array($package->features) || is_object($package->features))
                                @foreach($package->features as $feature)
                                    <li>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            @endif
                        </ul>

                        <div class="wsp-card-footer">
                            <a href="{{ route('services.website.package.detail', $package->slug) }}" class="wsp-btn-detail">
                                Lihat Detail
                            </a>
                            {{-- Tombol Order sementara diarahkan ke WhatsApp karena OrderController mungkin belum siap --}}
                            <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '' }}?text=Halo, saya ingin memesan paket {{ $package->name }}" target="_blank" class="wsp-btn-order">
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="wsp-empty">
                <p>Belum ada paket website yang tersedia saat ini.</p>
            </div>
        @endif

    </div>
</section>

@endsection

@push('styles')
<style>
/* ============================================================ */
/* CSS HALAMAN DAFTAR PAKET WEBSITE */
/* ============================================================ */

.wsp-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
.wsp-text-center { text-align: center; }

/* --- MAIN BODY --- */
.wsp-body { padding: 4rem 0 6rem; background-color: #f8fafc; }

/* --- FILTERS --- */
.wsp-filter-wrap { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 3rem; justify-content: center; }
.wsp-filter-btn { padding: 0.6rem 1.5rem; border-radius: 50px; background: #ffffff; border: 1px solid #e2e8f0; color: #64748b; font-size: 0.9rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
.wsp-filter-btn:hover { background: #f1f5f9; color: #0f172a; }
.wsp-filter-btn.wsp-active { background: var(--theme-primary, #3B82F6); border-color: var(--theme-primary, #3B82F6); color: #ffffff; }

/* --- GRID & CARDS --- */
.wsp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem; }
.wsp-card { background: #ffffff; border-radius: 1.5rem; padding: 2.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.03); position: relative; display: flex; flex-direction: column; transition: all 0.3s; }
.wsp-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
.wsp-card.wsp-featured { border-color: var(--theme-primary, #3B82F6); border-width: 2px; }
.wsp-badge { position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--theme-primary, #3B82F6); color: #ffffff; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.4rem 1rem; border-radius: 50px; }

.wsp-card-header { margin-bottom: 1.5rem; border-bottom: 1px dashed #e2e8f0; padding-bottom: 1.5rem; }
.wsp-card-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; }
.wsp-card-price { font-size: 2rem; font-weight: 800; color: var(--theme-primary, #3B82F6); line-height: 1.2; margin-bottom: 1rem; }
.wsp-strike { font-size: 1rem; color: #94a3b8; text-decoration: line-through; font-weight: 500; margin-left: 0.5rem; display: block; margin-top: 0.25rem; }
.wsp-card-desc { font-size: 0.9rem; color: #64748b; line-height: 1.6; }

.wsp-features { list-style: none; padding: 0; margin: 0 0 2rem 0; display: flex; flex-direction: column; gap: 0.75rem; flex: 1; }
.wsp-features li { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.95rem; color: #334155; }
.wsp-features svg { width: 18px; height: 18px; color: #10b981; flex-shrink: 0; margin-top: 2px; }

.wsp-card-footer { display: flex; gap: 1rem; margin-top: auto; }
.wsp-btn-detail { flex: 1; text-align: center; padding: 0.8rem; border-radius: 8px; background: #f1f5f9; color: #0f172a; font-weight: 700; text-decoration: none; border: 1px solid #e2e8f0; transition: all 0.2s; font-size: 0.9rem; }
.wsp-btn-detail:hover { background: #e2e8f0; }
.wsp-btn-order { flex: 1; text-align: center; padding: 0.8rem; border-radius: 8px; background: var(--theme-primary, #3B82F6); color: #ffffff; font-weight: 700; text-decoration: none; border: 1px solid var(--theme-primary, #3B82F6); transition: all 0.2s; font-size: 0.9rem; }
.wsp-btn-order:hover { background: #1e293b; border-color: #1e293b; }

.wsp-empty { text-align: center; padding: 4rem 0; color: #64748b; }
</style>
@endpush