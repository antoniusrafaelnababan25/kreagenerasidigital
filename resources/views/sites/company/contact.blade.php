@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if(isset($sections['hero']))
    @include('sections.hero', [
        'content' => $sections['hero']['content'] ?? [],
        'section' => $sections['hero'],
    ])
@else
    {{-- Fallback Hero Default --}}
    <section class="ct-hero">
        <div class="ct-hero-bg"></div>
        <div class="ct-hero-glow ct-hero-glow-1"></div>
        <div class="ct-hero-glow ct-hero-glow-2"></div>

        <div class="ct-container ct-text-center ct-z-10">
            <nav class="ct-breadcrumb">
                <a href="/">Beranda</a>
                <span>/</span>
                <span class="ct-active">Kontak</span>
            </nav>
            <h1 class="ct-hero-title">
                Mari <span class="ct-gradient-text">Berkolaborasi</span>
            </h1>
            <p class="ct-hero-desc">
                Punya pertanyaan, ide, atau ingin memulai proyek? Tim kami siap mendengarkan dan membantu Anda.
            </p>
        </div>
    </section>
@endif

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- MAIN CONTENT (Form & Info) --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<section class="ct-body">
    <div class="ct-container-wide ct-z-20">
        
        <div class="ct-grid-layout">

            {{-- ═══ KIRI: Info Kontak ═══ --}}
            <div class="ct-info-col">
                
                {{-- Card: Alamat --}}
                <div class="ct-info-card">
                    <div class="ct-icon-box ct-icon-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="ct-info-title">Kantor Kami</h3>
                        <p class="ct-info-desc">
                            {{ $siteSettings['address'] ?? 'Jl. Contoh Alamat No. 123, Kota Anda' }}
                        </p>
                    </div>
                </div>

                {{-- Card: Telepon / WhatsApp --}}
                <div class="ct-info-card">
                    <div class="ct-icon-box ct-icon-green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <h3 class="ct-info-title">Telepon / WhatsApp</h3>
                        <p class="ct-info-desc mb-2">Senin - Sabtu (08.00 - 17.00 WIB)</p>
                        <a href="https://wa.me/{{ $siteSettings['whatsapp_number'] ?? '' }}" target="_blank" class="ct-info-link ct-text-green">
                            {{ $siteSettings['phone_number'] ?? '+62 812 3456 7890' }}
                        </a>
                    </div>
                </div>

                {{-- Card: Email --}}
                <div class="ct-info-card">
                    <div class="ct-icon-box ct-icon-blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="ct-info-title">Email</h3>
                        <p class="ct-info-desc mb-2">Kirimkan pertanyaan Anda kapan saja.</p>
                        <a href="mailto:{{ $siteSettings['email'] ?? 'hello@example.com' }}" class="ct-info-link ct-text-blue">
                            {{ $siteSettings['email'] ?? 'hello@example.com' }}
                        </a>
                    </div>
                </div>

            </div>

            {{-- ═══ KANAN: Form Kontak ═══ --}}
            <div class="ct-form-col">
                <div class="ct-form-header">
                    <h2 class="ct-form-title">Kirim Pesan</h2>
                    <p class="ct-form-desc">Isi formulir di bawah ini dan tim kami akan segera menghubungi Anda.</p>
                </div>

                {{-- Alert Success --}}
                @if(session('success'))
                    <div class="ct-alert-success">
                        <svg class="ct-alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="ct-alert-text">{{ session('success') }}</p>
                    </div>
                @endif

                <form action="{{ route('company.contact.store') }}" method="POST" class="ct-form">
                    @csrf
                    
                    <div class="ct-form-row">
                        {{-- Nama --}}
                        <div class="ct-form-group">
                            <label for="name" class="ct-label">Nama Lengkap <span class="ct-required">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="ct-input @error('name') is-invalid @enderror" placeholder="Masukkan nama Anda">
                            @error('name')
                                <p class="ct-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="ct-form-group">
                            <label for="email" class="ct-label">Alamat Email <span class="ct-required">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   class="ct-input @error('email') is-invalid @enderror" placeholder="nama@email.com">
                            @error('email')
                                <p class="ct-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="ct-form-row">
                        {{-- Telepon --}}
                        <div class="ct-form-group">
                            <label for="phone" class="ct-label">Nomor WhatsApp <span class="ct-required">*</span></label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                   class="ct-input @error('phone') is-invalid @enderror" placeholder="0812xxxxxx">
                            @error('phone')
                                <p class="ct-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Perusahaan --}}
                        <div class="ct-form-group">
                            <label for="company" class="ct-label">Nama Perusahaan <span class="ct-optional">(Opsional)</span></label>
                            <input type="text" name="company" id="company" value="{{ old('company') }}"
                                   class="ct-input" placeholder="PT Maju Jaya">
                        </div>
                    </div>

                    {{-- Layanan --}}
                    <div class="ct-form-group">
                        <label for="service_type" class="ct-label">Layanan yang Diminati <span class="ct-optional">(Opsional)</span></label>
                        <select name="service_type" id="service_type" class="ct-input ct-select">
                            <option value="">-- Pilih Layanan --</option>
                            <option value="website" {{ old('service_type') == 'website' ? 'selected' : '' }}>Pembuatan Website</option>
                            <option value="rental" {{ old('service_type') == 'rental' ? 'selected' : '' }}>Rental Komputer / Laptop</option>
                            <option value="video" {{ old('service_type') == 'video' ? 'selected' : '' }}>Produksi Video</option>
                            <option value="printing" {{ old('service_type') == 'printing' ? 'selected' : '' }}>Percetakan / Printing</option>
                            <option value="lainnya" {{ old('service_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    {{-- Pesan --}}
                    <div class="ct-form-group">
                        <label for="message" class="ct-label">Pesan Anda <span class="ct-required">*</span></label>
                        <textarea name="message" id="message" rows="4" required
                                  class="ct-input ct-textarea @error('message') is-invalid @enderror" 
                                  placeholder="Ceritakan sedikit tentang proyek atau kebutuhan Anda...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="ct-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="ct-form-submit">
                        <button type="submit" class="ct-btn-submit">
                            Kirim Pesan Sekarang
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* ============================================================ */
/* CUSTOM CSS HALAMAN KONTAK */
/* ============================================================ */

/* --- Container & Layout Dasar --- */
.ct-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
}
.ct-container-wide {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
}
.ct-z-10 { z-index: 10; }
.ct-z-20 { z-index: 20; }
.ct-text-center { text-align: center; }

/* --- HERO SECTION --- */
.ct-hero {
    position: relative;
    padding: 10rem 0 8rem;
    background-color: #0f172a; /* Gelap elegan */
    overflow: hidden;
}
.ct-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
}
.ct-hero-glow {
    position: absolute;
    width: 600px;
    height: 600px;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.4;
    pointer-events: none;
}
.ct-hero-glow-1 {
    top: -10%; right: -5%;
    background-color: rgba(99, 102, 241, 0.4); /* Indigo */
}
.ct-hero-glow-2 {
    bottom: -10%; left: -5%;
    background-color: rgba(168, 85, 247, 0.3); /* Purple */
}

.ct-breadcrumb {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: rgba(255,255,255,0.6);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 1.5rem;
}
.ct-breadcrumb a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}
.ct-breadcrumb a:hover { color: #ffffff; }
.ct-breadcrumb .ct-active { color: #ffffff; }

.ct-hero-title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 800;
    color: #ffffff;
    line-height: 1.1;
    letter-spacing: -0.02em;
    margin-bottom: 1.5rem;
}
.ct-gradient-text {
    background: linear-gradient(to right, #818cf8, #c084fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.ct-hero-desc {
    font-size: 1.125rem;
    color: #cbd5e1;
    max-width: 650px;
    margin: 0 auto;
    line-height: 1.6;
}

/* --- BODY SECTION (Form & Info) --- */
.ct-body {
    background-color: #f8fafc;
    padding-bottom: 6rem;
}

.ct-grid-layout {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    margin-top: -5rem; /* Efek menimpa Hero */
}
@media (min-width: 1024px) {
    .ct-grid-layout {
        grid-template-columns: 4fr 7fr;
        gap: 3rem;
    }
}

/* --- INFO CONTACT CARDS (KIRI) --- */
.ct-info-col {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}
.ct-info-card {
    background: #ffffff;
    padding: 1.5rem;
    border-radius: 20px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 20px -5px rgba(0,0,0,0.03);
    display: flex;
    gap: 1.25rem;
    align-items: flex-start;
    transition: all 0.3s ease;
}
.ct-info-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px -10px rgba(0,0,0,0.08);
}

.ct-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.ct-icon-box svg { width: 24px; height: 24px; }
.ct-icon-primary {
    background-color: color-mix(in srgb, var(--theme-primary) 10%, transparent);
    color: var(--theme-primary);
}
.ct-icon-green { background-color: #ecfdf5; color: #10b981; }
.ct-icon-blue { background-color: #eff6ff; color: #3b82f6; }

.ct-info-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 0.25rem 0;
}
.ct-info-desc {
    font-size: 0.9rem;
    color: #64748b;
    line-height: 1.5;
    margin: 0;
}
.ct-info-link {
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
}
.ct-text-green { color: #10b981; }
.ct-text-green:hover { color: #059669; }
.ct-text-blue { color: #3b82f6; }
.ct-text-blue:hover { color: #2563eb; }
.mb-2 { margin-bottom: 0.5rem; }

/* --- FORM CONTACT (KANAN) --- */
.ct-form-col {
    background: #ffffff;
    padding: 2.5rem;
    border-radius: 24px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.04);
    border: 1px solid #f1f5f9;
}
@media (max-width: 768px) {
    .ct-form-col { padding: 1.5rem; }
}

.ct-form-header { margin-bottom: 2rem; }
.ct-form-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 0.5rem 0;
}
.ct-form-desc {
    font-size: 0.9rem;
    color: #64748b;
    margin: 0;
}

/* Alert Success */
.ct-alert-success {
    display: flex;
    gap: 0.75rem;
    padding: 1rem;
    background-color: #ecfdf5;
    border: 1px solid #a7f3d0;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    align-items: flex-start;
}
.ct-alert-icon { width: 20px; height: 20px; color: #059669; flex-shrink: 0; margin-top: 2px; }
.ct-alert-text { font-size: 0.9rem; font-weight: 500; color: #065f46; margin: 0; }

/* Form Elements */
.ct-form { display: flex; flex-direction: column; gap: 1.25rem; }
.ct-form-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
}
@media (min-width: 768px) {
    .ct-form-row { grid-template-columns: 1fr 1fr; }
}

.ct-form-group { display: flex; flex-direction: column; }
.ct-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
}
.ct-required { color: #ef4444; }
.ct-optional { font-weight: 400; color: #94a3b8; }

.ct-input {
    width: 100%;
    padding: 0.875rem 1rem;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    color: #0f172a;
    outline: none;
    transition: all 0.2s ease;
}
.ct-input::placeholder { color: #94a3b8; }
.ct-input:focus {
    background-color: #ffffff;
    border-color: var(--theme-primary);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--theme-primary) 15%, transparent);
}
.ct-input.is-invalid {
    border-color: #ef4444;
    background-color: #fef2f2;
}
.ct-error {
    font-size: 0.75rem;
    color: #ef4444;
    margin-top: 0.375rem;
    margin-bottom: 0;
}

.ct-textarea { resize: vertical; min-height: 120px; }
.ct-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1rem;
    padding-right: 2.5rem;
}

/* Button Submit */
.ct-form-submit { padding-top: 1rem; }
.ct-btn-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 1rem;
    border-radius: 12px;
    border: none;
    font-size: 1rem;
    font-weight: 700;
    color: #ffffff;
    background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px color-mix(in srgb, var(--theme-primary) 30%, transparent);
}
.ct-btn-submit svg { width: 18px; height: 18px; }
.ct-btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px color-mix(in srgb, var(--theme-primary) 40%, transparent);
    filter: brightness(1.05);
}
</style>
@endpush