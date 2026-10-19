@extends('layouts.app')

@section('title', $package->name . ' | KREA Video')

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
        
        {{-- Breadcrumb & Judul --}}
        <div style="margin-bottom: 2rem;">
            <div class="vdp-breadcrumb">
                <a href="/">Beranda</a>
                <span>/</span>
                <a href="{{ route('services.video.package') }}">Paket Video</a>
                <span>/</span>
                <span class="vdp-breadcrumb-active">{{ $package->name }}</span>
            </div>
            <h1 class="vdp-title" style="font-size: 2.5rem; margin-bottom: 0.5rem;">{{ $package->name }}</h1>
            <p class="vdp-desc" style="margin: 0;">{{ $package->description }}</p>
        </div>

        <div class="vdp-detail-layout">
            
            {{-- KIRI: Informasi Detail --}}
            <div class="vdp-detail-main">
                
                {{-- Fitur --}}
                <div class="vdp-card" style="margin-bottom: 2rem;">
                    <h2 class="vdp-card-title" style="margin-bottom: 1.5rem; font-size: 1.25rem;">Fasilitas & Fitur</h2>
                    <ul class="vdp-features">
                        @if(is_array($package->features))
                            @foreach($package->features as $feature)
                                <li style="font-size: 1rem; margin-bottom: 1rem;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                
                {{-- Spesifikasi Teknis --}}
                <div class="vdp-card">
                    <h2 class="vdp-card-title" style="margin-bottom: 1rem; font-size: 1.25rem;">Spesifikasi Produksi</h2>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem; color: #cbd5e1;">
                        <tr style="border-bottom: 1px solid #334155;">
                            <td style="padding: 1rem 0; font-weight: 600;">Durasi Video</td>
                            <td style="padding: 1rem 0; text-align: right;">{{ $package->duration ?? '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #334155;">
                            <td style="padding: 1rem 0; font-weight: 600;">Jumlah Kru</td>
                            <td style="padding: 1rem 0; text-align: right;">{{ $package->crew_count ?? '-' }} Orang</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #334155;">
                            <td style="padding: 1rem 0; font-weight: 600;">Format Output</td>
                            <td style="padding: 1rem 0; text-align: right;">{{ $package->output_format ?? '-' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #334155;">
                            <td style="padding: 1rem 0; font-weight: 600;">Batas Revisi</td>
                            <td style="padding: 1rem 0; text-align: right;">{{ $package->revision_count ?? '-' }} Kali</td>
                        </tr>
                        <tr>
                            <td style="padding: 1rem 0; font-weight: 600;">Waktu Pengerjaan</td>
                            <td style="padding: 1rem 0; text-align: right;">{{ $package->delivery_days ?? '-' }} Hari Kerja</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- KANAN: Sidebar Pembayaran --}}
            <aside class="vdp-detail-sidebar">
                <div class="vdp-card" style="position: sticky; top: 2rem;">
                    <div class="vdp-card-price" style="font-size: 2.5rem; margin-bottom: 0.5rem;">
                        Rp {{ number_format($package->price, 0, ',', '.') }}
                    </div>
                    @if($package->compare_price)
                        <div class="vdp-strike" style="font-size: 1.25rem; margin-bottom: 1.5rem;">Rp {{ number_format($package->compare_price, 0, ',', '.') }}</div>
                    @endif
                    
                    <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 2rem; line-height: 1.6;">
                        Harga di atas belum termasuk PPN. Konsultasikan kebutuhan Anda terlebih dahulu sebelum memesan.
                    </p>

                    <a href="{{ route('services.video.order', ['package' => $package->slug]) ?? '#' }}" class="vdp-btn-order" style="width: 100%; margin-bottom: 1rem; padding: 1rem; font-size: 1rem;">
                        Pesan Paket Ini
                    </a>
                    
                    <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '' }}?text=Halo, saya ingin bertanya tentang paket {{ $package->name }}" target="_blank" class="vdp-btn-detail" style="width: 100%; padding: 1rem; font-size: 1rem;">
                        Tanya via WhatsApp
                    </a>
                </div>
            </aside>

        </div>

        {{-- Paket Terkait --}}
        @if($related->count() > 0)
            <div style="margin-top: 5rem;">
                <h2 class="vdp-card-title" style="margin-bottom: 2rem;">Paket Lainnya</h2>
                <div class="vdp-grid">
                    @foreach($related as $rel)
                        <div class="vdp-card" style="padding: 1.5rem;">
                            <div class="vdp-card-header" style="margin-bottom: 0; border-bottom: none; padding-bottom: 0;">
                                <h3 class="vdp-card-title" style="font-size: 1.25rem;">{{ $rel->name }}</h3>
                                <div class="vdp-card-price" style="font-size: 1.25rem; margin-top: 0.5rem;">Rp {{ number_format($rel->price, 0, ',', '.') }}</div>
                            </div>
                            <div class="vdp-card-footer">
                                <a href="{{ route('services.video.package.detail', $rel->slug) }}" class="vdp-btn-detail" style="padding: 0.75rem; font-size: 0.9rem;">
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
/* CSS HALAMAN DETAIL PAKET VIDEOGRAFI */
/* ============================================================ */

.vdp-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
.vdp-body { padding: 4rem 0 6rem; background-color: #0f172a; color: #ffffff; }

.vdp-breadcrumb { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #94a3b8; margin-bottom: 1.5rem; }
.vdp-breadcrumb a { color: inherit; text-decoration: none; transition: color 0.2s; }
.vdp-breadcrumb a:hover { color: #ffffff; }
.vdp-breadcrumb-active { color: #ffffff; font-weight: 600; }

.vdp-title { font-weight: 800; color: #ffffff; line-height: 1.2; }
.vdp-desc { font-size: 1.1rem; color: #94a3b8; max-width: 800px; }

/* Layout Grid */
.vdp-detail-layout { display: grid; grid-template-columns: 1fr; gap: 2rem; margin-top: 3rem; }
@media (min-width: 992px) { .vdp-detail-layout { grid-template-columns: 1fr 360px; gap: 3rem; } }

/* Card & Elements */
.vdp-card { background: #1e293b; border-radius: 1.5rem; padding: 2.5rem; border: 1px solid #334155; }
.vdp-card-title { font-weight: 800; color: #ffffff; }
.vdp-card-price { font-weight: 800; color: var(--theme-primary, #3B82F6); line-height: 1.2; }
.vdp-strike { color: #64748b; text-decoration: line-through; font-weight: 500; }

.vdp-features { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem; }
.vdp-features li { display: flex; align-items: flex-start; gap: 0.75rem; color: #cbd5e1; }
.vdp-features svg { color: #10b981; flex-shrink: 0; }

.vdp-btn-detail { display: block; text-align: center; border-radius: 8px; background: #334155; color: #ffffff; font-weight: 700; text-decoration: none; transition: all 0.2s; }
.vdp-btn-detail:hover { background: #475569; }
.vdp-btn-order { display: block; text-align: center; border-radius: 8px; background: var(--theme-primary, #3B82F6); color: #ffffff; font-weight: 700; text-decoration: none; transition: all 0.2s; border: 1px solid var(--theme-primary, #3B82F6); }
.vdp-btn-order:hover { background: #2563eb; border-color: #2563eb; }

.vdp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem; }
.vdp-card-footer { display: flex; gap: 1rem; margin-top: 1.5rem; }
</style>
@endpush