@php
    $companyName = $siteSettings['company_name'] ?? 'KREA Generasi Digital';
    $companyEmail = $siteSettings['company_email'] ?? null;
    $companyPhone = $siteSettings['company_phone'] ?? null;
    $companyAddress = $siteSettings['company_address'] ?? null;
    $instagram = $siteSettings['instagram_url'] ?? null;
    $facebook = $siteSettings['facebook_url'] ?? null;
    $youtube = $siteSettings['youtube_url'] ?? null;
    $tiktok = $siteSettings['tiktok_url'] ?? null;

    $hasSocial = $instagram || $facebook || $youtube || $tiktok;
@endphp

<footer class="relative mt-20">

    {{-- ═══ WAVE SVG ═══ --}}
    <div class="absolute top-0 left-0 right-0 -translate-y-full pointer-events-none">
        <svg class="w-full h-16 lg:h-24" viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path fill="#FFFFFF" fill-opacity="1"
                  d="M0,64L48,58.7C96,53,192,43,288,48C384,53,480,75,576,80C672,85,768,75,864,64C960,53,1056,43,1152,48C1248,53,1344,75,1392,85.3L1440,96L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z">
            </path>
        </svg>
    </div>

    {{-- ═══ FOOTER CONTENT ═══ --}}
    <div class="bg-white border-t-0">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 py-16">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

                {{-- Brand --}}
                <div>
                    <h3 class="text-2xl font-bold mb-3" style="color: var(--theme-primary);">
                        {{ $companyName }}
                    </h3>
                    <p class="text-xs uppercase tracking-widest mb-4 text-gray-400">
                        Generasi Digital
                    </p>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $siteSettings['meta_description_default'] ?? 'Solusi digital untuk bisnis Anda.' }}
                    </p>
                </div>

                {{-- Layanan --}}
                <div>
                    <h4 class="text-gray-900 font-semibold mb-4">Layanan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/layanan/website" class="text-gray-600 hover:text-[var(--theme-primary)] transition">Jasa Website</a></li>
                        <li><a href="/layanan/rental" class="text-gray-600 hover:text-[var(--theme-primary)] transition">Rental Alat Camping</a></li>
                        <li><a href="/layanan/video" class="text-gray-600 hover:text-[var(--theme-primary)] transition">Videografi</a></li>
                        <li><a href="/layanan/printing" class="text-gray-600 hover:text-[var(--theme-primary)] transition">Custom Printing</a></li>
                    </ul>
                </div>

                {{-- Kontak --}}
                <div>
                    <h4 class="text-gray-900 font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm">
                        @if($companyEmail)
                            <li>
                                <a href="mailto:{{ $companyEmail }}" class="text-gray-600 hover:text-[var(--theme-primary)] transition">
                                    {{ $companyEmail }}
                                </a>
                            </li>
                        @endif
                        @if($companyPhone)
                            <li>
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyPhone) }}" class="text-gray-600 hover:text-[var(--theme-primary)] transition">
                                    {{ $companyPhone }}
                                </a>
                            </li>
                        @endif
                        @if($companyAddress)
                            <li class="text-gray-600">{{ $companyAddress }}</li>
                        @endif
                    </ul>
                </div>

                {{-- Sosial Media --}}
                <div>
                    <h4 class="text-gray-900 font-semibold mb-4">Ikuti Kami</h4>
                    @if($hasSocial)
                        <ul class="space-y-2 text-sm">
                            @if($instagram)
                                <li>
                                    <a href="{{ $instagram }}" target="_blank" rel="noopener" class="text-gray-600 hover:text-[var(--theme-primary)] transition inline-flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                        Instagram
                                    </a>
                                </li>
                            @endif
                            @if($facebook)
                                <li>
                                    <a href="{{ $facebook }}" target="_blank" rel="noopener" class="text-gray-600 hover:text-[var(--theme-primary)] transition inline-flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                        Facebook
                                    </a>
                                </li>
                            @endif
                            @if($youtube)
                                <li>
                                    <a href="{{ $youtube }}" target="_blank" rel="noopener" class="text-gray-600 hover:text-[var(--theme-primary)] transition inline-flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                        YouTube
                                    </a>
                                </li>
                            @endif
                            @if($tiktok)
                                <li>
                                    <a href="{{ $tiktok }}" target="_blank" rel="noopener" class="text-gray-600 hover:text-[var(--theme-primary)] transition inline-flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                        TikTok
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @else
                        <p class="text-sm text-gray-400">Belum ada sosial media.</p>
                    @endif
                </div>
            </div>

            <div class="border-t border-gray-100 mt-12 pt-8 flex flex-col md:flex-row items-center justify-between gap-3 text-sm text-gray-500">
                <div>
                    &copy; {{ date('Y') }} {{ $companyName }}. All rights reserved.
                </div>
                <div class="text-xs">
                    Powered by <span style="color: var(--theme-primary);" class="font-semibold">KREA</span>
                </div>
            </div>
        </div>
    </div>
</footer>