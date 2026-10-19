@extends('layouts.app')

@section('title', $product->name . ' | KREA Printing')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION (Langsung memanggil data Product) --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<section class="prd-hero">
    {{-- Background Blur --}}
    <div class="prd-hero-bg">
        @if($product->featured_image)
            <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="prd-hero-img">
        @endif
        <div class="prd-hero-overlay"></div>
    </div>

    <div class="prd-container" style="position: relative; z-index: 10;">
        
        {{-- Breadcrumb --}}
        <nav class="prd-hero-breadcrumb">
            <a href="/">Beranda</a>
            <span>/</span>
            <a href="{{ route('services.printing.home') }}">Printing</a>
            <span>/</span>
            <a href="{{ route('services.printing.product') }}">Produk</a>
            <span>/</span>
            <span class="prd-active">{{ $product->name }}</span>
        </nav>

        {{-- Teks Hero --}}
        <div class="prd-hero-content">
            <span class="prd-hero-tag">{{ ucfirst($product->type ?? 'Custom Printing') }}</span>
            <h1 class="prd-hero-title">{{ $product->name }}</h1>
            <div class="prd-hero-price">
                Rp {{ number_format($product->base_price, 0, ',', '.') }} <span>/ pcs</span>
            </div>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- MAIN CONTENT (DETAIL PRODUK) --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<section class="prd-detail-body">
    <div class="prd-container">
        
        <div class="prd-detail-layout">
            
            {{-- KIRI: Gambar Produk --}}
            <div class="prd-detail-img-col">
                <div class="prd-main-img-wrap">
                    @if($product->featured_image)
                        <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="prd-main-img" id="mainProductImage">
                    @else
                        <div class="prd-img-placeholder">
                            <span>{{ strtoupper(substr($product->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- KANAN: Info Produk & Form --}}
            <div class="prd-detail-info-col">
                
                <div class="prd-info-header">
                    <h2 class="prd-sub-title" style="font-size: 1.25rem; margin-bottom: 1rem;">Tentang Produk Ini</h2>
                    <div class="prd-info-desc" style="margin-bottom: 2rem;">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                {{-- Spesifikasi Singkat --}}
                <div class="prd-specs">
                    @if($product->material)
                        <div class="prd-spec-item">
                            <span class="prd-spec-label">Bahan</span>
                            <span class="prd-spec-value">{{ $product->material }}</span>
                        </div>
                    @endif
                    @if($product->print_technique)
                        <div class="prd-spec-item">
                            <span class="prd-spec-label">Teknik Cetak</span>
                            <span class="prd-spec-value">{{ $product->print_technique }}</span>
                        </div>
                    @endif
                    @if($product->min_order_qty)
                        <div class="prd-spec-item">
                            <span class="prd-spec-label">Min. Order</span>
                            <span class="prd-spec-value">{{ number_format($product->min_order_qty, 0, ',', '.') }} pcs</span>
                        </div>
                    @endif
                    @if($product->production_days)
                        <div class="prd-spec-item">
                            <span class="prd-spec-label">Waktu Produksi</span>
                            <span class="prd-spec-value">{{ $product->production_days }} Hari Kerja</span>
                        </div>
                    @endif
                </div>

                {{-- Tombol Aksi --}}
                <div class="prd-actions">
                    <a href="{{ route('services.printing.order', ['product' => $product->slug]) ?? '#' }}" class="prd-btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Pesan Sekarang
                    </a>
                    
                    <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '' }}?text=Halo, saya ingin bertanya tentang produk {{ $product->name }}" target="_blank" class="prd-btn-outline">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Tanya via WhatsApp
                    </a>
                </div>

                <div class="prd-share">
                    <span>Bagikan:</span>
                    <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . url()->current()) }}" target="_blank" class="prd-share-link">WhatsApp</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="prd-share-link">Facebook</a>
                </div>

            </div>
        </div>

        {{-- PRODUK TERKAIT --}}
        @if($related->count() > 0)
            <div class="prd-related-section">
                <h2 class="prd-related-title">Produk Terkait</h2>
                
                <div class="prd-grid">
                    @foreach($related as $rel)
                        <div class="prd-card">
                            <div class="prd-card-img-wrap">
                                @if($rel->featured_image)
                                    <img src="{{ asset('storage/' . $rel->featured_image) }}" alt="{{ $rel->name }}" class="prd-card-img" loading="lazy">
                                @else
                                    <div class="prd-card-placeholder">
                                        <span>{{ strtoupper(substr($rel->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div class="prd-badge">Rp {{ number_format($rel->base_price, 0, ',', '.') }}</div>
                            </div>
                            <div class="prd-card-content">
                                <h3 class="prd-card-title">{{ $rel->name }}</h3>
                                <a href="{{ route('services.printing.product.detail', $rel->slug) }}" class="prd-btn-detail">
                                    Lihat Detail
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
/* CSS DETAIL PRODUK PRINTING (WITH CUSTOM HERO) */
/* ============================================================ */

.prd-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }

/* --- HERO SECTION BARU --- */
.prd-hero { position: relative; padding: 8rem 0 6rem; background-color: #0f172a; color: #ffffff; overflow: hidden; }
.prd-hero-bg { position: absolute; inset: 0; z-index: 1; }
.prd-hero-img { width: 100%; height: 100%; object-fit: cover; }
.prd-hero-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(15,23,42,0.6) 0%, rgba(15,23,42,0.95) 100%); backdrop-filter: blur(4px); }

.prd-hero-breadcrumb { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: rgba(255,255,255,0.7); margin-bottom: 2rem; position: relative; z-index: 10; }
.prd-hero-breadcrumb a { color: inherit; text-decoration: none; transition: color 0.2s; }
.prd-hero-breadcrumb a:hover { color: #ffffff; }
.prd-hero-breadcrumb span { opacity: 0.5; }
.prd-hero-breadcrumb .prd-active { color: #ffffff; font-weight: 600; opacity: 1; }

.prd-hero-content { position: relative; z-index: 10; max-width: 800px; }
.prd-hero-tag { display: inline-block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; background: var(--theme-primary, #3B82F6); color: #ffffff; padding: 0.4rem 0.8rem; border-radius: 50px; margin-bottom: 1rem; }
.prd-hero-title { font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem; letter-spacing: -0.02em; }
.prd-hero-price { font-size: 2rem; font-weight: 700; color: #ffffff; }
.prd-hero-price span { font-size: 1rem; font-weight: 500; color: rgba(255,255,255,0.7); }

/* --- MAIN DETAIL BODY --- */
.prd-detail-body { padding: 4rem 0 6rem; background-color: #ffffff; }
.prd-detail-layout { display: grid; grid-template-columns: 1fr; gap: 3rem; }
@media (min-width: 992px) { .prd-detail-layout { grid-template-columns: 1fr 450px; gap: 4rem; align-items: start; } }

/* Gambar Kiri */
.prd-detail-img-col { position: sticky; top: 100px; }
.prd-main-img-wrap { background: #f8fafc; border-radius: 1.5rem; overflow: hidden; border: 1px solid #f1f5f9; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
.prd-main-img { width: 100%; height: 100%; object-fit: cover; }
.prd-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--theme-primary, #3B82F6), var(--theme-secondary, #8B5CF6)); }
.prd-img-placeholder span { font-size: 8rem; font-weight: 700; color: #ffffff; opacity: 0.3; }

/* Info Kanan */
.prd-detail-info-col { display: flex; flex-direction: column; }
.prd-info-desc { color: #475569; line-height: 1.7; font-size: 1rem; }

/* Spesifikasi */
.prd-specs { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2.5rem; background: #f8fafc; padding: 1.5rem; border-radius: 1rem; border: 1px solid #e2e8f0; }
.prd-spec-item { display: flex; flex-direction: column; gap: 0.25rem; }
.prd-spec-label { font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
.prd-spec-value { font-size: 0.95rem; font-weight: 700; color: #0f172a; }

/* Tombol Aksi */
.prd-actions { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem; }
.prd-btn-primary { display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; padding: 1rem; border-radius: 12px; background: var(--theme-primary, #3B82F6); color: #ffffff; font-size: 1rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
.prd-btn-primary:hover { background: #1e293b; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
.prd-btn-primary svg { width: 20px; height: 20px; }

.prd-btn-outline { display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; padding: 1rem; border-radius: 12px; background: transparent; color: #0f172a; font-size: 1rem; font-weight: 700; text-decoration: none; border: 2px solid #e2e8f0; cursor: pointer; transition: all 0.2s; }
.prd-btn-outline:hover { border-color: #cbd5e1; background: #f8fafc; }
.prd-btn-outline svg { width: 20px; height: 20px; color: #25D366; }

/* Share */
.prd-share { display: flex; align-items: center; gap: 1rem; font-size: 0.85rem; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 1.5rem; }
.prd-share-link { color: #0f172a; font-weight: 600; text-decoration: none; transition: color 0.2s; }
.prd-share-link:hover { color: var(--theme-primary, #3B82F6); }

/* Produk Terkait */
.prd-related-section { margin-top: 5rem; padding-top: 3rem; border-top: 1px solid #e2e8f0; }
.prd-related-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 2rem; }

.prd-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem; }
.prd-card { background: #ffffff; border-radius: 1rem; overflow: hidden; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.3s; display: flex; flex-direction: column; }
.prd-card:hover { transform: translateY(-4px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }

.prd-card-img-wrap { position: relative; aspect-ratio: 4/3; overflow: hidden; background: #f8fafc; }
.prd-card-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
.prd-card:hover .prd-card-img { transform: scale(1.05); }
.prd-card-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f1f5f9; }
.prd-card-placeholder span { font-size: 3rem; font-weight: 700; color: #cbd5e1; }
.prd-badge { position: absolute; top: 0.75rem; right: 0.75rem; background: rgba(255,255,255,0.9); backdrop-filter: blur(4px); padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; color: #0f172a; }

.prd-card-content { padding: 1.25rem; display: flex; flex-direction: column; flex: 1; }
.prd-card-title { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem; line-height: 1.3; }
.prd-btn-detail { display: block; width: 100%; text-align: center; padding: 0.6rem; border-radius: 8px; background: #f8fafc; color: #0f172a; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: 1px solid #e2e8f0; transition: all 0.2s; margin-top: auto; }
.prd-btn-detail:hover { background: var(--theme-primary, #3B82F6); color: #ffffff; border-color: var(--theme-primary, #3B82F6); }
</style>
@endpush