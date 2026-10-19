@extends('layouts.app')

@section('title', $package->name . ' | KREA')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION --}}
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
        
        {{-- Breadcrumb & Judul --}}
        <div style="margin-bottom: 2rem;">
            <div class="wsp-breadcrumb" style="color: #64748b; margin-bottom: 1rem;">
                <a href="/" style="color: inherit; text-decoration: none;">Beranda</a>
                <span>/</span>
                <a href="{{ route('services.website.package') }}" style="color: inherit; text-decoration: none;">Paket Website</a>
                <span>/</span>
                <span style="color: #0f172a; font-weight: 600;">{{ $package->name }}</span>
            </div>
            <h1 class="wsp-card-title" style="font-size: 2.5rem; margin-bottom: 0.5rem;">{{ $package->name }}</h1>
            <p style="color: #64748b; font-size: 1.1rem; max-width: 800px; line-height: 1.6;">{{ $package->description }}</p>
        </div>

        <div class="wsp-detail-layout">
            
            {{-- KIRI: Informasi Detail --}}
            <div class="wsp-detail-main">
                {{-- Fitur --}}
                <div class="wsp-card" style="margin-bottom: 2rem;">
                    <h2 class="wsp-card-title" style="margin-bottom: 1.5rem; font-size: 1.25rem;">Fitur yang Didapatkan</h2>
                    
                    <ul class="wsp-features">
                        @if(is_array($package->features) || is_object($package->features))
                            @foreach($package->features as $feature)
                                <li style="font-size: 1rem; margin-bottom: 1rem;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        @else
                            <li>Fitur belum diatur.</li>
                        @endif
                    </ul>
                </div>
                
                {{-- Spesifikasi --}}
                <div class="wsp-card">
                    <h2 class="wsp-card-title" style="margin-bottom: 1rem; font-size: 1.25rem;">Spesifikasi Teknis</h2>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem; color: #334155;">
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 1rem 0; font-weight: 600;">Jumlah Halaman</td>
                            <td style="padding: 1rem 0; text-align: right;">{{ $package->pages_count ?? '-' }} Halaman</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 1rem 0; font-weight: 600;">Batas Revisi</td>
                            <td style="padding: 1rem 0; text-align: right;">{{ $package->revision_count ?? '-' }} Kali</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 1rem 0; font-weight: 600;">Lama Pengerjaan</td>
                            <td style="padding: 1rem 0; text-align: right;">{{ $package->delivery_days ?? '-' }} Hari Kerja</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 1rem 0; font-weight: 600;">Domain</td>
                            <td style="padding: 1rem 0; text-align: right;">{{ $package->include_domain ? 'Termasuk' : 'Tidak Termasuk' }}</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 1rem 0; font-weight: 600;">Hosting</td>
                            <td style="padding: 1rem 0; text-align: right;">{{ $package->include_hosting ? 'Termasuk' : 'Tidak Termasuk' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 1rem 0; font-weight: 600;">Maintenance</td>
                            <td style="padding: 1rem 0; text-align: right;">
                                {{ $package->include_maintenance ? 'Termasuk (' . $package->maintenance_months . ' Bulan)' : 'Tidak Termasuk' }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- KANAN: Sidebar Pembayaran --}}
            <aside class="wsp-detail-sidebar">
                <div class="wsp-card" style="position: sticky; top: 2rem;">
                    <div style="font-size: 0.9rem; color: #64748b; margin-bottom: 0.5rem;">Harga Paket:</div>
                    <div class="wsp-card-price" style="font-size: 2.5rem; margin-bottom: 0.5rem;">
                        Rp {{ number_format($package->price, 0, ',', '.') }}
                    </div>
                    @if($package->compare_price)
                        <div class="wsp-strike" style="font-size: 1.25rem; margin-bottom: 1.5rem;">Rp {{ number_format($package->compare_price, 0, ',', '.') }}</div>
                    @endif
                    
                    <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 2rem; line-height: 1.6;">
                        Harga di atas belum termasuk PPN. Anda dapat mendiskusikan kebutuhan khusus dengan tim kami setelah mengisi form pemesanan.
                    </p>

                    {{-- ✅ PERUBAHAN ALUR BISNIS: Tombol mengarah ke Form Order, bukan langsung ke WA --}}
                    <a href="{{ route('services.website.order', ['package' => $package->slug]) }}" class="wsp-btn-order" style="width: 100%; margin-bottom: 1rem; padding: 1rem; font-size: 1rem;">
                        Pesan Paket Ini
                    </a>
                    
                    <div style="text-align: center; margin: 1rem 0; font-size: 0.85rem; color: #94a3b8;">atau</div>

                    {{-- Tombol WA tetap ada, tapi untuk sekedar tanya-tanya (bukan order) --}}
                    <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '' }}?text=Halo, saya ingin bertanya tentang paket {{ $package->name }}" target="_blank" class="wsp-btn-detail" style="width: 100%; padding: 1rem; font-size: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <svg fill="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px; color: #25D366;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Tanya via WhatsApp
                    </a>
                </div>
            </aside>

        </div>

        {{-- Paket Terkait --}}
        @if($related->count() > 0)
            <div style="margin-top: 5rem;">
                <h2 class="wsp-card-title" style="margin-bottom: 2rem; font-size: 1.5rem;">Paket Lainnya</h2>
                <div class="wsp-grid">
                    @foreach($related as $rel)
                        <div class="wsp-card" style="padding: 1.5rem;">
                            <div class="wsp-card-header" style="margin-bottom: 0; border-bottom: none; padding-bottom: 0;">
                                <h3 class="wsp-card-title" style="font-size: 1.25rem;">{{ $rel->name }}</h3>
                                <div class="wsp-card-price" style="font-size: 1.25rem; margin-top: 0.5rem;">Rp {{ number_format($rel->price, 0, ',', '.') }}</div>
                            </div>
                            <div class="wsp-card-footer">
                                <a href="{{ route('services.website.package.detail', $rel->slug) }}" class="wsp-btn-detail" style="padding: 0.75rem; font-size: 0.9rem;">
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
/* CSS HALAMAN DETAIL PAKET WEBSITE */
/* ============================================================ */

.wsp-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
.wsp-body { padding: 4rem 0 6rem; background-color: #f8fafc; }

.wsp-breadcrumb { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; }

/* Layout Grid */
.wsp-detail-layout { display: grid; grid-template-columns: 1fr; gap: 2rem; }
@media (min-width: 992px) { .wsp-detail-layout { grid-template-columns: 1fr 360px; gap: 3rem; } }

/* Card & Elements */
.wsp-card { background: #ffffff; border-radius: 1.5rem; padding: 2.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
.wsp-card-title { font-weight: 800; color: #0f172a; }
.wsp-card-price { font-weight: 800; color: var(--theme-primary, #3B82F6); line-height: 1.2; }
.wsp-strike { color: #94a3b8; text-decoration: line-through; font-weight: 500; }

.wsp-features { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem; }
.wsp-features li { display: flex; align-items: flex-start; gap: 0.75rem; color: #334155; }
.wsp-features svg { color: #10b981; flex-shrink: 0; }

.wsp-btn-detail { display: block; text-align: center; border-radius: 8px; background: #f1f5f9; color: #0f172a; font-weight: 700; text-decoration: none; border: 1px solid #e2e8f0; transition: all 0.2s; }
.wsp-btn-detail:hover { background: #e2e8f0; }
.wsp-btn-order { display: block; text-align: center; border-radius: 8px; background: var(--theme-primary, #3B82F6); color: #ffffff; font-weight: 700; text-decoration: none; border: 1px solid var(--theme-primary, #3B82F6); transition: all 0.2s; }
.wsp-btn-order:hover { background: #1e293b; border-color: #1e293b; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2); }

.wsp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem; }
.wsp-card-footer { display: flex; gap: 1rem; margin-top: 1.5rem; }
</style>
@endpush