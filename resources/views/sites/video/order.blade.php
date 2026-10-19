@extends('layouts.app')

@section('title', 'Pesan Jasa Videografi')

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
<section class="vdo-body">
    <div class="vdo-container">

        {{-- Alert Error --}}
        @if(session('error'))
            <div class="vdo-alert vdo-alert-error">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('services.video.order.store') }}" method="POST" id="orderForm" target="_blank">
            @csrf

            <div class="vdo-layout">
                
                {{-- KOLOM KIRI: FORM --}}
                <div class="vdo-main">

                    {{-- 1. Pilih Paket --}}
                    <div class="vdo-card">
                        <h2 class="vdo-card-title">
                            <span class="vdo-step">1</span> Pilih Paket Video
                        </h2>
                        
                        <div class="vdo-form-group">
                            <label class="vdo-label">Paket Videografi</label>
                            <select name="video_package_id" id="packageSelect" class="vdo-select">
                                <option value="">-- Custom / Belum Tahu --</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" 
                                            data-price="{{ $package->price }}"
                                            {{ ($selectedPackage && $selectedPackage->id == $package->id) ? 'selected' : '' }}>
                                        {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="vdo-helper">Pilih paket yang sesuai, atau biarkan kosong jika ingin konsultasi terlebih dahulu.</p>
                        </div>
                    </div>

                    {{-- 2. Data Diri --}}
                    <div class="vdo-card">
                        <h2 class="vdo-card-title">
                            <span class="vdo-step">2</span> Informasi Kontak
                        </h2>

                        <div class="vdo-grid-2">
                            <div class="vdo-form-group">
                                <label class="vdo-label">Nama Lengkap <span class="vdo-required">*</span></label>
                                <input type="text" name="customer_name" class="vdo-input" value="{{ old('customer_name') }}" required>
                            </div>
                            <div class="vdo-form-group">
                                <label class="vdo-label">No. WhatsApp <span class="vdo-required">*</span></label>
                                <input type="text" name="customer_phone" class="vdo-input" value="{{ old('customer_phone') }}" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>

                        <div class="vdo-form-group">
                            <label class="vdo-label">Email <span class="vdo-required">*</span></label>
                            <input type="email" name="customer_email" class="vdo-input" value="{{ old('customer_email') }}" required>
                        </div>
                    </div>

                    {{-- 3. Detail Proyek --}}
                    <div class="vdo-card">
                        <h2 class="vdo-card-title">
                            <span class="vdo-step">3</span> Detail Proyek Video
                        </h2>

                        <div class="vdo-form-group">
                            <label class="vdo-label">Judul Proyek <span class="vdo-required">*</span></label>
                            <input type="text" name="project_title" class="vdo-input" value="{{ old('project_title') }}" placeholder="Misal: Video Company Profile PT Maju Jaya" required>
                        </div>

                        <div class="vdo-grid-2">
                            <div class="vdo-form-group">
                                <label class="vdo-label">Tipe Video</label>
                                <input type="text" name="video_type" class="vdo-input" value="{{ old('video_type') }}" placeholder="Wedding, Event, Company Profile, dll">
                            </div>
                            <div class="vdo-form-group">
                                <label class="vdo-label">Lokasi Shooting</label>
                                <input type="text" name="location" class="vdo-input" value="{{ old('location') }}" placeholder="Jakarta, Bandung, dll">
                            </div>
                        </div>

                        <div class="vdo-grid-2">
                            <div class="vdo-form-group">
                                <label class="vdo-label">Tanggal Shooting</label>
                                <input type="date" name="shooting_date" class="vdo-input" value="{{ old('shooting_date') }}">
                            </div>
                            <div class="vdo-form-group">
                                <label class="vdo-label">Waktu Shooting</label>
                                <input type="time" name="shooting_time" class="vdo-input" value="{{ old('shooting_time') }}">
                            </div>
                        </div>

                        <div class="vdo-form-group">
                            <label class="vdo-label">Konsep / Ide Cerita</label>
                            <textarea name="concept" class="vdo-textarea" rows="3" placeholder="Jelaskan secara singkat konsep video yang Anda inginkan...">{{ old('concept') }}</textarea>
                        </div>

                        <div class="vdo-form-group">
                            <label class="vdo-label">Referensi Video (Opsional)</label>
                            <textarea name="reference" class="vdo-textarea" rows="2" placeholder="Link video (YouTube/Instagram) sebagai acuan...">{{ old('reference') }}</textarea>
                        </div>

                        <div class="vdo-form-group">
                            <label class="vdo-label">Catatan Tambahan</label>
                            <textarea name="notes" class="vdo-textarea" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN: RINGKASAN --}}
                <aside class="vdo-sidebar">
                    <div class="vdo-summary">
                        <h2 class="vdo-summary-title">Ringkasan Pesanan</h2>
                        
                        <div class="vdo-summary-item">
                            <span>Paket Dipilih</span>
                            <strong id="summaryPackageName">Custom / Konsultasi</strong>
                        </div>
                        
                        <hr class="vdo-divider">
                        
                        <div class="vdo-summary-total">
                            <span>Estimasi Biaya</span>
                            <div class="vdo-total-price" id="summaryTotal">Rp 0</div>
                        </div>

                        <button type="submit" class="vdo-btn-submit" id="btnSubmit">
                            <span class="btn-text">Kirim & Lanjut ke WhatsApp</span>
                            <span class="btn-loading" style="display: none;">
                                <svg class="spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;display:inline-block;vertical-align:middle;margin-right:5px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Memproses...
                            </span>
                        </button>
                        <p class="vdo-terms">
                            * Dengan menekan tombol ini, Anda akan diarahkan ke WhatsApp untuk konfirmasi & pembayaran DP.
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
/* CSS HALAMAN FORM ORDER VIDEOGRAFI */
/* ============================================================ */

.vdo-body { padding: 4rem 0 6rem; background-color: #0f172a; color: #ffffff; }
.vdo-container { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }

.vdo-alert { display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; font-weight: 500; font-size: 0.95rem; }
.vdo-alert svg { width: 24px; height: 24px; flex-shrink: 0; }
.vdo-alert-error { background: #450a0a; color: #fca5a5; border: 1px solid #7f1d1d; }

.vdo-layout { display: grid; grid-template-columns: 1fr; gap: 2rem; }
@media (min-width: 992px) { .vdo-layout { grid-template-columns: 1fr 340px; align-items: start; gap: 3rem; } }

.vdo-card { background: #1e293b; border-radius: 1rem; padding: 2rem; margin-bottom: 1.5rem; border: 1px solid #334155; }
.vdo-card-title { font-size: 1.25rem; font-weight: 800; color: #ffffff; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
.vdo-step { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: var(--theme-primary, #3B82F6); color: #fff; font-size: 0.9rem; font-weight: 700; }

.vdo-form-group { margin-bottom: 1.25rem; }
.vdo-grid-2 { display: grid; grid-template-columns: 1fr; gap: 1rem; }
@media (min-width: 640px) { .vdo-grid-2 { grid-template-columns: 1fr 1fr; } }

.vdo-label { display: block; font-size: 0.9rem; font-weight: 600; color: #cbd5e1; margin-bottom: 0.5rem; }
.vdo-required { color: #ef4444; }
.vdo-helper { font-size: 0.8rem; color: #94a3b8; margin-top: 0.35rem; }
.vdo-input, .vdo-select, .vdo-textarea { width: 100%; padding: 0.75rem 1rem; border: 1px solid #334155; border-radius: 8px; font-size: 0.95rem; color: #ffffff; background: #0f172a; transition: all 0.2s; outline: none; }
.vdo-input:focus, .vdo-select:focus, .vdo-textarea:focus { border-color: var(--theme-primary, #3B82F6); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25); }
.vdo-textarea { resize: vertical; }

.vdo-summary { background: #1e293b; border-radius: 1rem; padding: 2rem; border: 1px solid #334155; position: sticky; top: 2rem; }
.vdo-summary-title { font-size: 1.15rem; font-weight: 800; color: #ffffff; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #334155; }
.vdo-summary-item { display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.95rem; color: #94a3b8; }
.vdo-summary-item strong { color: #ffffff; font-weight: 700; text-align: right; }
.vdo-divider { border: none; border-top: 1px dashed #334155; margin: 1.5rem 0; }
.vdo-summary-total { display: flex; flex-direction: column; gap: 0.25rem; margin-bottom: 1.5rem; }
.vdo-summary-total span { font-size: 0.9rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.vdo-total-price { font-size: 1.75rem; font-weight: 800; color: var(--theme-primary, #3B82F6); line-height: 1.2; }

.vdo-btn-submit { display: flex; align-items: center; justify-content: center; width: 100%; padding: 1rem; border-radius: 50px; background: var(--theme-primary, #3B82F6); color: #ffffff; font-size: 1rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.3s; text-align: center; }
.vdo-btn-submit:hover:not(:disabled) { background: #2563eb; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(37,99,235,0.3); }
.vdo-btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
.vdo-terms { font-size: 0.75rem; color: #64748b; text-align: center; margin-top: 1rem; line-height: 1.5; }
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
        const btnText = btnSubmit.querySelector('.btn-text');
        const btnLoading = btnSubmit.querySelector('.btn-loading');
        
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline-flex';
        btnSubmit.style.opacity = '0.8';
        btnSubmit.disabled = true;
    });

    // Init on load
    updateSummary();
});
</script>
<style>
@keyframes spin { 100% { transform: rotate(360deg); } }
.spin { animation: spin 1s linear infinite; }
</style>
@endpush