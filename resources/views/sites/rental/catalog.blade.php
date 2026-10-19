@extends('layouts.app')

@section('title', 'Katalog Rental')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@php
    // Prioritas: $hero (dari controller) → kosong (partial pakai default)
    // Pastikan di CatalogController Anda mengirim: 
    // $hero = $this->getSection('katalog', 'hero');
    $heroContent = [];
    if (!empty($hero) && isset($hero['content'])) {
        $heroContent = $hero['content'];
    }
@endphp

@include('sections.hero', ['content' => $heroContent])

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- MAIN CONTENT --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<section class="cat-body" id="katalog">
    <div class="cat-container-wide">

        <div class="cat-layout">

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- SIDEBAR FILTER --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <aside class="cat-sidebar">

                {{-- Pencarian --}}
                <div class="cat-filter-card">
                    <h3 class="cat-filter-title">Pencarian</h3>
                    <form action="{{ route('services.rental.catalog') }}" method="GET" class="cat-search-form">
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <div class="cat-search-wrap">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari barang..." class="cat-search-input">
                            <button type="submit" class="cat-search-btn" aria-label="Cari">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Kategori --}}
                <div class="cat-filter-card">
                    <h3 class="cat-filter-title">Kategori</h3>
                    <ul class="cat-filter-list">
                        <li>
                            <a href="{{ route('services.rental.catalog', request()->except('category', 'page')) }}"
                               class="cat-filter-link {{ !request('category') ? 'cat-active' : '' }}">
                                Semua Kategori
                            </a>
                        </li>
                        @if(!empty($categories))
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('services.rental.catalog', array_merge(request()->except('page'), ['category' => $cat->slug])) }}"
                                       class="cat-filter-link {{ request('category') === $cat->slug ? 'cat-active' : '' }}">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                {{-- Urutkan --}}
                <div class="cat-filter-card">
                    <h3 class="cat-filter-title">Urutkan</h3>
                    <ul class="cat-filter-list">
                        @php
                            $sortOptions = [
                                'default'    => 'Rekomendasi',
                                'price_asc'  => 'Harga Terendah',
                                'price_desc' => 'Harga Tertinggi',
                                'newest'     => 'Terbaru',
                            ];
                            $currentSort = request('sort', 'default');
                        @endphp
                        @foreach($sortOptions as $value => $label)
                            <li>
                                <a href="{{ route('services.rental.catalog', array_merge(request()->except('sort', 'page'), ['sort' => $value])) }}"
                                   class="cat-filter-link {{ $currentSort === $value ? 'cat-active' : '' }}">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Reset --}}
                @if(request()->hasAny(['q', 'category', 'sort']))
                    <a href="{{ route('services.rental.catalog') }}" class="cat-btn-reset" style="width:100%; text-align:center;">
                        Reset Semua Filter
                    </a>
                @endif

            </aside>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- GRID BARANG --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <div class="cat-content">

                <div class="cat-content-header">
                    <p class="cat-result-count">
                        Menampilkan <strong>{{ $items->count() }}</strong> dari <strong>{{ $items->total() }}</strong> barang
                    </p>
                </div>

                @if($items->count() > 0)

                    <div class="cat-grid">
                        @foreach($items as $item)
                            <div class="cat-card">

                                <div class="cat-card-img-wrap">
                                    @if($item->featured_image)
                                        <img src="{{ asset('storage/' . $item->featured_image) }}"
                                             alt="{{ $item->name }}"
                                             class="cat-card-img"
                                             loading="lazy">
                                    @else
                                        <div class="cat-card-placeholder">
                                            <span>{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                                        </div>
                                    @endif

                                    <div class="cat-badge">
                                        Stok: {{ $item->stock_available }}
                                    </div>
                                </div>

                                <div class="cat-card-content">
                                    <h3 class="cat-card-title">{{ $item->name }}</h3>

                                    @if($item->brand)
                                        <p class="cat-card-brand">{{ $item->brand }}</p>
                                    @endif

                                    <div class="cat-card-price">
                                        Rp {{ number_format($item->price_per_day, 0, ',', '.') }} <span>/ hari</span>
                                    </div>

                                    @if(\Illuminate\Support\Facades\Route::has('services.rental.booking'))
                                        <a href="{{ route('services.rental.booking', ['item' => $item->slug]) }}" class="cat-btn-book">
                                            Booking Sekarang
                                        </a>
                                    @else
                                        <a href="#" class="cat-btn-book" onclick="return false;" aria-disabled="true">
                                            Booking Segera
                                        </a>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($items->hasPages())
                        <div class="cat-pagination">
                            {{ $items->appends(request()->query())->links() }}
                        </div>
                    @endif

                @else

                    <div class="cat-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3>Barang Tidak Ditemukan</h3>
                        <p>Coba ubah filter atau kata kunci pencarian Anda.</p>
                        <a href="{{ route('services.rental.catalog') }}" class="cat-btn-reset">Reset Filter</a>
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
/* KATALOG RENTAL — CSS KONTEN SAJA (hero dihandle partial)     */
/* ============================================================ */

.cat-container-wide { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; position: relative; }

.cat-body { padding: 4rem 0 6rem; background-color: #f8fafc; }
.cat-layout { display: grid; grid-template-columns: 1fr; gap: 2rem; }
@media (min-width: 992px) {
    .cat-layout { grid-template-columns: 280px 1fr; gap: 3rem; }
}

.cat-sidebar { display: flex; flex-direction: column; gap: 1.5rem; }
.cat-filter-card {
    background: #ffffff; border-radius: 1rem; padding: 1.5rem;
    border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);
}
.cat-filter-title {
    font-size: 1rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;
    padding-bottom: 0.75rem; border-bottom: 2px solid #f1f5f9;
}

.cat-search-wrap { position: relative; display: flex; }
.cat-search-input {
    width: 100%; padding: 0.75rem 2.5rem 0.75rem 1rem;
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
    font-size: 0.9rem; outline: none; transition: all 0.2s;
}
.cat-search-input:focus {
    border-color: var(--theme-primary, #3B82F6);
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--theme-primary, #3B82F6) 15%, transparent);
}
.cat-search-btn {
    position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: #64748b;
    padding: 0.25rem; display: flex; align-items: center; justify-content: center;
}
.cat-search-btn:hover { color: var(--theme-primary, #3B82F6); }
.cat-search-btn svg { width: 18px; height: 18px; }

.cat-filter-list {
    list-style: none; padding: 0; margin: 0;
    display: flex; flex-direction: column; gap: 0.5rem;
}
.cat-filter-link {
    display: block; padding: 0.5rem 0.75rem; border-radius: 6px;
    font-size: 0.9rem; font-weight: 500; color: #475569;
    text-decoration: none; transition: all 0.2s;
}
.cat-filter-link:hover { background: #f8fafc; color: #0f172a; }
.cat-filter-link.cat-active {
    background: var(--theme-primary, #3B82F6); color: #ffffff; font-weight: 700;
}

.cat-btn-reset {
    display: inline-block; padding: 0.6rem 1.5rem; border-radius: 50px;
    background: #0f172a; color: #ffffff; font-size: 0.85rem;
    font-weight: 600; text-decoration: none; transition: all 0.2s;
}
.cat-btn-reset:hover { background: #1e293b; }

.cat-content-header {
    margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;
}
.cat-result-count { font-size: 0.9rem; color: #64748b; margin: 0; }
.cat-result-count strong { color: #0f172a; }

.cat-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem;
}

.cat-card {
    background: #ffffff; border-radius: 1rem; overflow: hidden;
    border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    transition: all 0.3s; display: flex; flex-direction: column;
}
.cat-card:hover { transform: translateY(-4px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }

.cat-card-img-wrap {
    position: relative; aspect-ratio: 4/3; overflow: hidden; background: #f8fafc;
}
.cat-card-img {
    width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;
}
.cat-card:hover .cat-card-img { transform: scale(1.05); }
.cat-card-placeholder {
    width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
    background: #f1f5f9;
}
.cat-card-placeholder span { font-size: 3rem; font-weight: 700; color: #cbd5e1; }

.cat-badge {
    position: absolute; top: 0.75rem; right: 0.75rem;
    background: rgba(255,255,255,0.9); backdrop-filter: blur(4px);
    padding: 0.25rem 0.6rem; border-radius: 4px;
    font-size: 0.65rem; font-weight: 700; color: #0f172a;
}

.cat-card-content {
    padding: 1.25rem; display: flex; flex-direction: column; flex: 1;
}
.cat-card-title {
    font-size: 1.1rem; font-weight: 700; color: #0f172a;
    margin-bottom: 0.25rem; line-height: 1.3;
}
.cat-card-brand { font-size: 0.8rem; color: #64748b; margin-bottom: 0.75rem; }
.cat-card-price {
    font-size: 1rem; font-weight: 800; color: var(--theme-primary, #3B82F6);
    margin-bottom: 1rem;
}
.cat-card-price span { font-size: 0.75rem; font-weight: 500; color: #64748b; }

.cat-btn-book {
    display: block; width: 100%; text-align: center; padding: 0.6rem;
    border-radius: 8px; background: #f8fafc; color: #0f172a;
    font-size: 0.85rem; font-weight: 700; text-decoration: none;
    border: 1px solid #e2e8f0; transition: all 0.2s; margin-top: auto;
}
.cat-btn-book:hover {
    background: var(--theme-primary, #3B82F6); color: #ffffff;
    border-color: var(--theme-primary, #3B82F6);
}
.cat-btn-book[aria-disabled="true"] { opacity: 0.5; cursor: not-allowed; }

.cat-pagination {
    margin-top: 3rem; display: flex; justify-content: center;
}
.cat-pagination nav { display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: center; }
.cat-pagination a,
.cat-pagination span {
    display: inline-block; padding: 0.5rem 0.85rem; border-radius: 8px;
    font-size: 0.85rem; font-weight: 600; text-decoration: none;
    border: 1px solid #e2e8f0; background: #ffffff; color: #0f172a;
    transition: all 0.2s;
}
.cat-pagination a:hover {
    background: var(--theme-primary, #3B82F6); color: #ffffff;
    border-color: var(--theme-primary, #3B82F6);
}
.cat-pagination .active span,
.cat-pagination span[aria-current="page"] {
    background: var(--theme-primary, #3B82F6); color: #ffffff;
    border-color: var(--theme-primary, #3B82F6);
}
.cat-pagination .disabled span { opacity: 0.4; cursor: not-allowed; }

.cat-empty { text-align: center; padding: 4rem 0; }
.cat-empty svg { width: 48px; height: 48px; color: #cbd5e1; margin: 0 auto 1rem auto; }
.cat-empty h3 { font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem; }
.cat-empty p { color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem; }

@media (max-width: 640px) {
    .cat-body { padding: 2.5rem 0 4rem; }
    .cat-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
    .cat-card-content { padding: 1rem; }
    .cat-card-title { font-size: 0.95rem; }
    .cat-card-price { font-size: 0.9rem; }
}
</style>
@endpush