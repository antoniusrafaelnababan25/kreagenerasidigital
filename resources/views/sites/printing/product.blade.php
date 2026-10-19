@extends('layouts.app')

@section('title', 'Katalog Produk Printing')

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
<section class="prd-body">
    <div class="prd-container">

        <div class="prd-layout">
            
            {{-- SIDEBAR FILTER --}}
            <aside class="prd-sidebar">
                
                {{-- Pencarian --}}
                <div class="prd-filter-card">
                    <h3 class="prd-filter-title">Pencarian</h3>
                    <form action="{{ route('services.printing.product') }}" method="GET" class="prd-search-form">
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request('type')) <input type="hidden" name="type" value="{{ request('type') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <div class="prd-search-wrap">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="prd-search-input">
                            <button type="submit" class="prd-search-btn" aria-label="Cari">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Kategori --}}
                <div class="prd-filter-card">
                    <h3 class="prd-filter-title">Kategori</h3>
                    <ul class="prd-filter-list">
                        <li>
                            <a href="{{ route('services.printing.product', request()->except('category', 'page')) }}"
                               class="prd-filter-link {{ !request('category') ? 'prd-active' : '' }}">
                                Semua Kategori
                            </a>
                        </li>
                        @if(!empty($categories))
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('services.printing.product', array_merge(request()->except('page'), ['category' => $cat->slug])) }}"
                                       class="prd-filter-link {{ request('category') === $cat->slug ? 'prd-active' : '' }}">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                {{-- Tipe Produk --}}
                @if($types->count() > 0)
                <div class="prd-filter-card">
                    <h3 class="prd-filter-title">Tipe Produk</h3>
                    <ul class="prd-filter-list">
                        <li>
                            <a href="{{ route('services.printing.product', request()->except('type', 'page')) }}"
                               class="prd-filter-link {{ !request('type') ? 'prd-active' : '' }}">
                                Semua Tipe
                            </a>
                        </li>
                        @foreach($types as $type)
                            <li>
                                <a href="{{ route('services.printing.product', array_merge(request()->except('page'), ['type' => $type])) }}"
                                   class="prd-filter-link {{ request('type') === $type ? 'prd-active' : '' }}">
                                    {{ ucfirst($type) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Urutkan --}}
                <div class="prd-filter-card">
                    <h3 class="prd-filter-title">Urutkan</h3>
                    <ul class="prd-filter-list">
                        @php
                            $sortOptions = [
                                'order'      => 'Rekomendasi',
                                'price_asc'  => 'Harga Terendah',
                                'price_desc' => 'Harga Tertinggi',
                                'newest'     => 'Terbaru',
                            ];
                            $currentSort = request('sort', 'order');
                        @endphp
                        @foreach($sortOptions as $value => $label)
                            <li>
                                <a href="{{ route('services.printing.product', array_merge(request()->except('sort', 'page'), ['sort' => $value])) }}"
                                   class="prd-filter-link {{ $currentSort === $value ? 'prd-active' : '' }}">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Reset --}}
                @if(request()->hasAny(['q', 'category', 'type', 'sort']))
                    <a href="{{ route('services.printing.product') }}" class="prd-btn-reset" style="width:100%; text-align:center;">
                        Reset Semua Filter
                    </a>
                @endif

            </aside>

            {{-- GRID PRODUK --}}
            <div class="prd-content">
                
                <div class="prd-content-header">
                    <p class="prd-result-count">
                        Menampilkan <strong>{{ $products->count() }}</strong> dari <strong>{{ $products->total() }}</strong> produk
                    </p>
                </div>

                @if($products->count() > 0)
                    <div class="prd-grid">
                        @foreach($products as $product)
                            <div class="prd-card">
                                <div class="prd-card-img-wrap">
                                    @if($product->featured_image)
                                        <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="prd-card-img" loading="lazy">
                                    @else
                                        <div class="prd-card-placeholder">
                                            <span>{{ strtoupper(substr($product->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    
                                    <div class="prd-badge">
                                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                    </div>
                                </div>
                                
                                <div class="prd-card-content">
                                    <h3 class="prd-card-title">{{ $product->name }}</h3>
                                    <p class="prd-card-type">{{ ucfirst($product->type ?? 'Printing') }}</p>
                                    
                                    <a href="{{ route('services.printing.product.detail', $product->slug) ?? '#' }}" class="prd-btn-detail">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($products->hasPages())
                        <div class="prd-pagination">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="prd-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3>Produk Tidak Ditemukan</h3>
                        <p>Coba ubah filter atau kata kunci pencarian Anda.</p>
                        <a href="{{ route('services.printing.product') }}" class="prd-btn-reset">Reset Filter</a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* ============================================================ */
/* CUSTOM CSS HALAMAN KATALOG PRINTING */
/* ============================================================ */

.prd-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
.prd-body { padding: 4rem 0 6rem; background-color: #f8fafc; }
.prd-layout { display: grid; grid-template-columns: 1fr; gap: 2rem; }
@media (min-width: 992px) { .prd-layout { grid-template-columns: 280px 1fr; gap: 3rem; } }

/* SIDEBAR */
.prd-sidebar { display: flex; flex-direction: column; gap: 1.5rem; }
.prd-filter-card { background: #ffffff; border-radius: 1rem; padding: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
.prd-filter-title { font-size: 1rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 2px solid #f1f5f9; }

.prd-search-wrap { position: relative; display: flex; }
.prd-search-input { width: 100%; padding: 0.75rem 2.5rem 0.75rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; outline: none; transition: all 0.2s; }
.prd-search-input:focus { border-color: var(--theme-primary, #3B82F6); background: #ffffff; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
.prd-search-btn { position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #64748b; padding: 0.25rem; display: flex; align-items: center; justify-content: center; }
.prd-search-btn:hover { color: var(--theme-primary, #3B82F6); }
.prd-search-btn svg { width: 18px; height: 18px; }

.prd-filter-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem; }
.prd-filter-link { display: block; padding: 0.5rem 0.75rem; border-radius: 6px; font-size: 0.9rem; font-weight: 500; color: #475569; text-decoration: none; transition: all 0.2s; }
.prd-filter-link:hover { background: #f8fafc; color: #0f172a; }
.prd-filter-link.prd-active { background: var(--theme-primary, #3B82F6); color: #ffffff; font-weight: 700; }

.prd-btn-reset { display: inline-block; padding: 0.6rem 1.5rem; border-radius: 50px; background: #0f172a; color: #ffffff; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
.prd-btn-reset:hover { background: #1e293b; }

/* CONTENT & GRID */
.prd-content-header { margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; }
.prd-result-count { font-size: 0.9rem; color: #64748b; margin: 0; }
.prd-result-count strong { color: #0f172a; }

.prd-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem; }
.prd-card { background: #ffffff; border-radius: 1rem; overflow: hidden; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.3s; display: flex; flex-direction: column; }
.prd-card:hover { transform: translateY(-4px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }

.prd-card-img-wrap { position: relative; aspect-ratio: 4/3; overflow: hidden; background: #f8fafc; }
.prd-card-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
.prd-card:hover .prd-card-img { transform: scale(1.05); }
.prd-card-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f1f5f9; }
.prd-card-placeholder span { font-size: 3rem; font-weight: 700; color: #cbd5e1; }

.prd-badge { position: absolute; top: 0.75rem; right: 0.75rem; background: rgba(255,255,255,0.9); backdrop-filter: blur(4px); padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; color: #0f172a; }

.prd-card-content { padding: 1.25rem; display: flex; flex-direction: column; flex: 1; }
.prd-card-title { font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.25rem; line-height: 1.3; }
.prd-card-type { font-size: 0.8rem; color: var(--theme-primary, #3B82F6); font-weight: 600; margin-bottom: 1rem; }

.prd-btn-detail { display: block; width: 100%; text-align: center; padding: 0.6rem; border-radius: 8px; background: #f8fafc; color: #0f172a; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: 1px solid #e2e8f0; transition: all 0.2s; margin-top: auto; }
.prd-btn-detail:hover { background: var(--theme-primary, #3B82F6); color: #ffffff; border-color: var(--theme-primary, #3B82F6); }

/* PAGINATION */
.prd-pagination { margin-top: 3rem; display: flex; justify-content: center; }
.prd-pagination nav { display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: center; }
.prd-pagination a, .prd-pagination span { display: inline-block; padding: 0.5rem 0.85rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-decoration: none; border: 1px solid #e2e8f0; background: #ffffff; color: #0f172a; transition: all 0.2s; }
.prd-pagination a:hover { background: var(--theme-primary, #3B82F6); color: #ffffff; border-color: var(--theme-primary, #3B82F6); }
.prd-pagination .active span, .prd-pagination span[aria-current="page"] { background: var(--theme-primary, #3B82F6); color: #ffffff; border-color: var(--theme-primary, #3B82F6); }

/* EMPTY STATE */
.prd-empty { text-align: center; padding: 4rem 0; }
.prd-empty svg { width: 48px; height: 48px; color: #cbd5e1; margin: 0 auto 1rem auto; }
.prd-empty h3 { font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; }
.prd-empty p { color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem; }

@media (max-width: 640px) {
    .prd-body { padding: 2.5rem 0 4rem; }
    .prd-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
    .prd-card-content { padding: 1rem; }
    .prd-card-title { font-size: 0.95rem; }
    .prd-btn-detail { font-size: 0.8rem; padding: 0.5rem; }
}
</style>
@endpush