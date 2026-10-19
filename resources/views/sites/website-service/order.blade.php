@extends('layouts.app')

@section('title', 'Pesan Jasa Website')

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
<section class="wso-body">
    <div class="wso-container">

        {{-- Notifikasi Error --}}
        @if(session('error'))
            <div class="wso-alert wso-alert-error">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="wso-alert wso-alert-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('services.website.order.store') }}" method="POST" id="orderForm">
            @csrf

            <div class="wso-layout">
                
                {{-- KOLOM KIRI: FORM --}}
                <div class="wso-main">

                    {{-- 1. Pilih Paket --}}
                    <div class="wso-card">
                        <h2 class="wso-card-title">
                            <span class="wso-step">1</span> Pilih Paket Layanan
                        </h2>
                        
                        <div class="wso-form-group">
                            <label class="wso-label">Paket Website</label>
                            <select name="web_package_id" id="packageSelect" class="wso-select">
                                <option value="">-- Custom / Belum Tahu --</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" 
                                            data-price="{{ $package->price }}"
                                            {{ ($selectedPackage && $selectedPackage->id == $package->id) ? 'selected' : '' }}>
                                        {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="wso-helper">Pilih paket yang sesuai, atau biarkan kosong jika ingin konsultasi terlebih dahulu.</p>
                        </div>
                    </div>

                    {{-- 2. Data Diri --}}
                    <div class="wso-card">
                        <h2 class="wso-card-title">
                            <span class="wso-step">2</span> Informasi Kontak
                        </h2>

                        <div class="wso-grid-2">
                            <div class="wso-form-group">
                                <label class="wso-label">Nama Lengkap <span class="wso-required">*</span></label>
                                <input type="text" name="customer_name" class="wso-input" value="{{ old('customer_name') }}" required>
                            </div>
                            <div class="wso-form-group">
                                <label class="wso-label">No. WhatsApp <span class="wso-required">*</span></label>
                                <input type="text" name="customer_phone" class="wso-input" value="{{ old('customer_phone') }}" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>

                        <div class="wso-grid-2">
                            <div class="wso-form-group">
                                <label class="wso-label">Email <span class="wso-required">*</span></label>
                                <input type="email" name="customer_email" class="wso-input" value="{{ old('customer_email') }}" required>
                            </div>
                            <div class="wso-form-group">
                                <label class="wso-label">Nama Perusahaan / Usaha</label>
                                <input type="text" name="company_name" class="wso-input" value="{{ old('company_name') }}">
                            </div>
                        </div>
                    </div>

                    {{-- 3. Detail Proyek --}}
                    <div class="wso-card">
                        <h2 class="wso-card-title">
                            <span class="wso-step">3</span> Detail Proyek
                        </h2>

                        <div class="wso-form-group">
                            <label class="wso-label">Judul Proyek / Website <span class="wso-required">*</span></label>
                            <input type="text" name="project_title" class="wso-input" value="{{ old('project_title') }}" placeholder="Misal: Website Toko Online Kopi Kenangan" required>
                        </div>

                        <div class="wso-form-group">
                            <label class="wso-label">Jenis Bisnis</label>
                            <input type="text" name="business_type" class="wso-input" value="{{ old('business_type') }}" placeholder="Misal: F&B, Retail, Jasa, dll">
                        </div>

                        <div class="wso-form-group">
                            <label class="wso-label">Deskripsi Proyek</label>
                            <textarea name="description" class="wso-textarea" rows="3" placeholder="Jelaskan secara singkat tentang bisnis Anda dan tujuan website ini...">{{ old('description') }}</textarea>
                        </div>

                        <div class="wso-form-group">
                            <label class="wso-label">Referensi Website (Opsional)</label>
                            <textarea name="reference_websites" class="wso-textarea" rows="2" placeholder="Link website yang Anda sukai sebagai acuan desain...">{{ old('reference_websites') }}</textarea>
                        </div>

                        <div class="wso-form-group">
                            <label class="wso-label">Preferensi Domain</label>
                            <input type="text" name="domain_preference" class="wso-input" value="{{ old('domain_preference') }}" placeholder="Misal: www.namabisnis.com">
                        </div>

                        <div class="wso-checkbox-group">
                            <label class="wso-checkbox-label">
                                <input type="checkbox" name="needs_hosting" value="1" {{ old('needs_hosting') ? 'checked' : '' }}>
                                Saya butuh bantuan untuk Hosting & Domain
                            </label>
                            <label class="wso-checkbox-label">
                                <input type="checkbox" name="needs_content" value="1" {{ old('needs_content') ? 'checked' : '' }}>
                                Saya butuh jasa penulisan konten / foto produk
                            </label>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN: RINGKASAN --}}
                <aside class="wso-sidebar">
                    <div class="wso-summary">
                        <h2 class="wso-summary-title">Ringkasan Pesanan</h2>
                        
                        <div class="wso-summary-item">
                            <span>Paket Dipilih</span>
                            <strong id="summaryPackageName">Custom / Konsultasi</strong>
                        </div>
                        
                        <hr class="wso-divider">
                        
                        <div class="wso-summary-total">
                            <span>Estimasi Biaya</span>
                            <div class="wso-total-price" id="summaryTotal">Rp 0</div>
                        </div>

                        <button type="submit" class="wso-btn-submit" id="btnSubmit">
                            Kirim Pesanan
                        </button>
                        <p class="wso-terms">
                            * Tim kami akan menghubungi Anda via WhatsApp untuk konfirmasi dan penawaran final.
                        </p>
                    </div>
                </aside>

            </div>
        </form>

    </div>
</section>

@endsection

@push('styles')
<style>
/* ============================================================ */
/* CSS HALAMAN FORM ORDER WEBSITE */
/* ============================================================ */

.wso-body { padding: 4rem 0 6rem; background-color: #f8fafc; }
.wso-container { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }

.wso-alert { display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; font-weight: 500; font-size: 0.95rem; }
.wso-alert svg { width: 24px; height: 24px; flex-shrink: 0; }
.wso-alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.wso-alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

.wso-layout { display: grid; grid-template-columns: 1fr; gap: 2rem; }
@media (min-width: 992px) { .wso-layout { grid-template-columns: 1fr 340px; align-items: start; gap: 3rem; } }

.wso-card { background: #ffffff; border-radius: 1rem; padding: 2rem; margin-bottom: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
.wso-card-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
.wso-step { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: var(--theme-primary, #3B82F6); color: #fff; font-size: 0.9rem; font-weight: 700; }

.wso-form-group { margin-bottom: 1.25rem; }
.wso-grid-2 { display: grid; grid-template-columns: 1fr; gap: 1rem; }
@media (min-width: 640px) { .wso-grid-2 { grid-template-columns: 1fr 1fr; } }

.wso-label { display: block; font-size: 0.9rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
.wso-required { color: #ef4444; }
.wso-helper { font-size: 0.8rem; color: #94a3b8; margin-top: 0.35rem; }
.wso-input, .wso-select, .wso-textarea { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; background: #ffffff; transition: all 0.2s; outline: none; }
.wso-input:focus, .wso-select:focus, .wso-textarea:focus { border-color: var(--theme-primary, #3B82F6); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
.wso-textarea { resize: vertical; }

.wso-checkbox-group { display: flex; flex-direction: column; gap: 0.75rem; margin-top: 1rem; }
.wso-checkbox-label { display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: #334155; cursor: pointer; }
.wso-checkbox-label input { width: 18px; height: 18px; accent-color: var(--theme-primary, #3B82F6); }

.wso-summary { background: #ffffff; border-radius: 1rem; padding: 2rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); position: sticky; top: 2rem; }
.wso-summary-title { font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #f1f5f9; }
.wso-summary-item { display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.95rem; color: #475569; }
.wso-summary-item strong { color: #0f172a; font-weight: 700; text-align: right; }
.wso-divider { border: none; border-top: 1px dashed #e2e8f0; margin: 1.5rem 0; }
.wso-summary-total { display: flex; flex-direction: column; gap: 0.25rem; margin-bottom: 1.5rem; }
.wso-summary-total span { font-size: 0.9rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.wso-total-price { font-size: 1.75rem; font-weight: 800; color: var(--theme-primary, #3B82F6); line-height: 1.2; }

.wso-btn-submit { width: 100%; padding: 1rem; border-radius: 50px; background: #0f172a; color: #ffffff; font-size: 1rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.3s; text-align: center; }
.wso-btn-submit:hover { background: #1e293b; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
.wso-terms { font-size: 0.75rem; color: #94a3b8; text-align: center; margin-top: 1rem; line-height: 1.5; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const packageSelect = document.getElementById('packageSelect');
    const summaryPackageName = document.getElementById('summaryPackageName');
    const summaryTotal = document.getElementById('summaryTotal');
    const orderForm = document.getElementById('orderForm');
    const btnSubmit = document.getElementById('btnSubmit');

    function formatRupiah(number) {
        if (number === 0) return 'Konsultasi (Rp 0)';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    function updateSummary() {
        const selectedOption = packageSelect.options[packageSelect.selectedIndex];
        const price = parseInt(selectedOption.getAttribute('data-price')) || 0;
        const name = selectedOption.text.split(' - ')[0];

        if (price > 0) {
            summaryPackageName.textContent = name;
            summaryTotal.textContent = formatRupiah(price);
        } else {
            summaryPackageName.textContent = 'Custom / Konsultasi';
            summaryTotal.textContent = 'Rp 0';
        }
    }

    packageSelect.addEventListener('change', updateSummary);

    // Submit state loading
    orderForm.addEventListener('submit', function() {
        btnSubmit.textContent = 'Memproses...';
        btnSubmit.style.opacity = '0.7';
        btnSubmit.disabled = true;
    });

    // Init on load
    updateSummary();
});
</script>
@endpush