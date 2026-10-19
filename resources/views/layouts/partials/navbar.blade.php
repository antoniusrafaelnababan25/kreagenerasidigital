@php
    // ═══ Ambil menu dari DATABASE ═══
    $rawMenuItems = \App\Models\Menu::where('site_id', $currentSite->id)
        ->where('is_active', true)
        ->whereNull('parent_id')
        ->orderBy('order')
        ->get();

    // Deteksi apakah halaman ini termasuk salah satu menu yang ada
    // (Jika iya, kita asumsikan halaman ini punya Hero Image, jadi Navbar harus Transparan)
    $isTransparent = false;
    foreach ($rawMenuItems as $menu) {
        if ($menu->isActive()) {
            $isTransparent = true;
            break;
        }
    }

    // Format data menu untuk View (Tampilan)
    $menuItems = $rawMenuItems->map(function ($menu) {
        return [
            'label' => $menu->label,
            'url' => $menu->url,
            'target_blank' => $menu->target_blank,
            'active' => $menu->isActive(),
        ];
    })->toArray();

    // ═══ WhatsApp ═══
    $waNumber = $siteSettings['whatsapp_number'] ?? null;
    $waText = urlencode('Halo ' . ($siteSettings['company_name'] ?? 'KREA') . ', saya ingin bertanya');
@endphp

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- NAVBAR --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<nav id="mainNavbar"
     data-transparent="{{ $isTransparent ? 'true' : 'false' }}"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 ease-in-out {{ $isTransparent ? 'navbar-transparent' : 'navbar-solid' }}">

    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20 relative">

            {{-- ═══ Logo ═══ --}}
            <a href="/" class="flex items-center gap-3 group flex-shrink-0 z-10">
                @if($theme?->logo_path)
                    <img src="{{ Storage::url($theme->logo_path) }}"
                         alt="{{ $currentSite->name }}"
                         class="h-10 w-auto transition-transform duration-300 group-hover:scale-105 navbar-logo-img">
                @else
                    <span class="navbar-logo-text text-2xl font-bold tracking-tight transition-colors duration-300">
                        {{ $siteSettings['company_name'] ?? 'KREA' }}
                    </span>
                @endif
            </a>

            {{-- ═══ Desktop Menu (Tengah) ═══ --}}
            <div class="hidden lg:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 items-center gap-6 xl:gap-8 z-10">
                @forelse($menuItems as $item)
                    <a href="{{ $item['url'] }}"
                       @if($item['target_blank']) target="_blank" rel="noopener" @endif
                       class="navbar-link relative text-sm font-medium transition-colors duration-300 group whitespace-nowrap
                              {{ $item['active'] ? 'navbar-link-active font-bold' : '' }}">

                        {{ $item['label'] }}

                        {{-- Underline Indicator --}}
                        <span class="absolute -bottom-1 left-0 h-0.5 rounded-full transition-all duration-300
                                     {{ $item['active'] ? 'w-full navbar-underline' : 'w-0 group-hover:w-full navbar-underline' }}"></span>
                    </a>
                @empty
                    <span class="text-sm text-red-400 font-medium">Menu belum diatur</span>
                @endforelse
            </div>

            {{-- ═══ CTA Button (WhatsApp) ═══ --}}
            <div class="hidden lg:block flex-shrink-0 z-10">
                @if($waNumber)
                    <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}"
                       target="_blank"
                       rel="noopener"
                       class="navbar-cta inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Konsultasi Gratis
                    </a>
                @endif
            </div>

            {{-- ═══ Mobile Toggle ═══ --}}
            <button type="button"
                    id="mobileMenuToggle"
                    class="navbar-mobile-toggle lg:hidden p-2 rounded-lg transition-colors duration-300 z-10"
                    aria-label="Toggle Menu">
                {{-- Hamburger --}}
                <svg id="hamburgerIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                {{-- Close --}}
                <svg id="closeIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- ═══ Mobile Menu ═══ --}}
    <div id="mobileMenu"
         class="hidden lg:hidden bg-white border-t border-gray-100 shadow-xl absolute w-full left-0">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
            @forelse($menuItems as $item)
                <a href="{{ $item['url'] }}"
                   @if($item['target_blank']) target="_blank" rel="noopener" @endif
                   class="block py-3 px-4 rounded-lg text-base font-medium transition-all duration-200
                          {{ $item['active'] ? 'font-bold' : 'text-gray-700 hover:bg-gray-50' }}"
                   @if($item['active']) style="color: var(--theme-primary); background-color: rgba(0,0,0,0.02);" @endif>
                    {{ $item['label'] }}
                </a>
            @empty
                <div class="py-3 px-4 text-sm text-red-500">Menu belum diatur</div>
            @endforelse

            @if($waNumber)
                <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}"
                   target="_blank"
                   rel="noopener"
                   class="block text-center py-3 mt-2 rounded-full text-base font-semibold text-white transition-all hover:shadow-lg"
                   style="background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));">
                    Konsultasi Gratis
                </a>
            @endif
        </div>
    </div>
</nav>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- SPACER (Hanya muncul jika halaman TIDAK transparan) --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(!$isTransparent)
    <div class="h-16 lg:h-20"></div>
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- NAVBAR STYLES --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<style>
    /* ─── Transisi Smooth ─── */
    #mainNavbar {
        transition: background-color 0.4s ease,
                    backdrop-filter 0.4s ease,
                    border-color 0.4s ease,
                    box-shadow 0.4s ease;
    }

    /* ─── Navbar Transparan ─── */
    .navbar-transparent {
        background-color: transparent;
        backdrop-filter: none;
        border-bottom: 1px solid transparent;
        box-shadow: none;
    }

    /* ─── Navbar Solid ─── */
    .navbar-solid {
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    /* ─── Logo ─── */
    .navbar-transparent .navbar-logo-text {
        color: white;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .navbar-solid .navbar-logo-text {
        color: var(--theme-primary);
    }

    /* Jika logo menggunakan gambar, filter invert (putih) saat transparan */
    .navbar-transparent .navbar-logo-img {
        filter: brightness(0) invert(1);
    }
    
    .navbar-solid .navbar-logo-img {
        filter: none;
    }

    /* ─── Menu Link ─── */
    .navbar-transparent .navbar-link {
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 0 1px 8px rgba(0, 0, 0, 0.3);
    }

    .navbar-transparent .navbar-link:hover {
        color: white;
    }

    .navbar-transparent .navbar-link-active {
        color: white;
    }

    .navbar-solid .navbar-link {
        color: #374151;
    }

    .navbar-solid .navbar-link:hover {
        color: #111827;
    }

    .navbar-solid .navbar-link-active {
        color: var(--theme-primary);
    }

    /* ─── Underline ─── */
    .navbar-transparent .navbar-underline {
        background-color: white;
    }

    .navbar-solid .navbar-underline {
        background-color: var(--theme-primary);
    }

    /* ─── CTA Button ─── */
    .navbar-transparent .navbar-cta {
        background-color: white;
        color: #111827;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .navbar-transparent .navbar-cta:hover {
        background-color: rgba(255, 255, 255, 0.9);
    }

    .navbar-solid .navbar-cta {
        background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .navbar-solid .navbar-cta:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* ─── Mobile Toggle ─── */
    .navbar-transparent .navbar-mobile-toggle {
        color: white;
    }

    .navbar-transparent .navbar-mobile-toggle:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .navbar-solid .navbar-mobile-toggle {
        color: #374151;
    }

    .navbar-solid .navbar-mobile-toggle:hover {
        background-color: #F3F4F6;
    }
</style>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- NAVBAR SCRIPT (Vanilla JS) --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<script>
    (function() {
        'use strict';

        const navbar = document.getElementById('mainNavbar');
        if (!navbar) return;

        const isTransparentPage = navbar.dataset.transparent === 'true';
        const mobileToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const hamburgerIcon = document.getElementById('hamburgerIcon');
        const closeIcon = document.getElementById('closeIcon');

        // ─── Scroll Handler ───
        if (isTransparentPage) {
            let ticking = false;

            function updateNavbar() {
                if (window.scrollY > 20) {
                    navbar.classList.remove('navbar-transparent');
                    navbar.classList.add('navbar-solid');
                } else {
                    navbar.classList.remove('navbar-solid');
                    navbar.classList.add('navbar-transparent');
                }
                ticking = false;
            }

            function onScroll() {
                if (!ticking) {
                    window.requestAnimationFrame(updateNavbar);
                    ticking = true;
                }
            }

            // Set initial state
            updateNavbar();

            // Listen scroll
            window.addEventListener('scroll', onScroll, { passive: true });
        }

        // ─── Mobile Menu Toggle ───
        if (mobileToggle && mobileMenu) {
            mobileToggle.addEventListener('click', function() {
                const isHidden = mobileMenu.classList.contains('hidden');

                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    hamburgerIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                } else {
                    mobileMenu.classList.add('hidden');
                    hamburgerIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            });

            // Close menu saat klik link
            mobileMenu.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                    hamburgerIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                });
            });
        }
    })();
</script>