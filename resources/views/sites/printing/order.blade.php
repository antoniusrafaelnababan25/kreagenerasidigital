@extends('layouts.app')

@section('title', 'Pesan Custom Printing')

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
<section class="prt-body">
    <div class="prt-container">

        {{-- Notifikasi --}}
        @if(session('error'))
            <div class="prt-alert prt-alert-error">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="prt-alert prt-alert-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('services.printing.order.store') }}" method="POST" enctype="multipart/form-data" id="orderForm">
            @csrf

            <div class="prt-layout">
                
                {{-- KOLOM KIRI: FORM --}}
                <div class="prt-main">

                    {{-- 1. Pilih Produk --}}
                    <div class="prt-card">
                        <h2 class="prt-card-title">
                            <span class="prt-step">1</span> Pilih Produk
                        </h2>
                        
                        <div class="prt-form-group">
                            <label class="prt-label">Produk Printing <span class="prt-required">*</span></label>
                            <select name="print_product_id" id="productSelect" class="prt-select" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-price="{{ $product->base_price }}"
                                            data-min="{{ $product->min_order_qty }}"
                                            data-days="{{ $product->production_days }}"
                                            {{ ($selectedProduct && $selectedProduct->id == $product->id) ? 'selected' : '' }}>
                                        {{ $product->name }} (Rp {{ number_format($product->base_price, 0, ',', '.') }}/pcs)
                                    </option>
                                @endforeach
                            </select>
                            <p class="prt-helper" id="productMinOrder"></p>
                        </div>

                        <div class="prt-form-group">
                            <label class="prt-label">Jumlah (Qty) <span class="prt-required">*</span></label>
                            <input type="number" name="quantity" id="quantityInput" class="prt-input" value="1" min="1" required>
                            <p class="prt-helper" id="minOrderWarning" style="color: #ef4444; display: none;">Jumlah belum memenuhi minimal order.</p>
                        </div>
                    </div>

                    {{-- 2. Spesifikasi Custom --}}
                    <div class="prt-card">
                        <h2 class="prt-card-title">
                            <span class="prt-step">2</span> Spesifikasi (Opsional)
                        </h2>

                        <div class="prt-grid-2">
                            <div class="prt-form-group">
                                <label class="prt-label">Ukuran (Size)</label>
                                <input type="text" name="size" class="prt-input" placeholder="Contoh: L, XL, A3, 20x30cm">
                            </div>
                            <div class="prt-form-group">
                                <label class="prt-label">Warna</label>
                                <input type="text" name="color" class="prt-input" placeholder="Contoh: Hitam, Putih, Full Color">
                            </div>
                        </div>

                        <div class="prt-grid-2">
                            <div class="prt-form-group">
                                <label class="prt-label">Bahan (Material)</label>
                                <input type="text" name="material" class="prt-input" placeholder="Contoh: Cotton Combed 30s">
                            </div>
                            <div class="prt-form-group">
                                <label class="prt-label">Teknik Cetak</label>
                                <input type="text" name="print_technique" class="prt-input" placeholder="Contoh: DTF, Sablon Plastisol">
                            </div>
                        </div>
                    </div>

                    {{-- 3. Upload Desain --}}
                    <div class="prt-card">
                        <h2 class="prt-card-title">
                            <span class="prt-step">3</span> File Desain & Catatan
                        </h2>

                        <div class="prt-form-group">
                            <label class="prt-label">Upload File Desain</label>
                            <input type="file" name="design_file" class="prt-file-input" accept=".jpg,.jpeg,.png,.pdf,.ai,.psd">
                            <p class="prt-helper">Format: JPG, PNG, PDF, AI, PSD. Maksimal 10MB.</p>
                        </div>

                        <div class="prt-form-group">
                            <label class="prt-label">Catatan Desain</label>
                            <textarea name="design_notes" class="prt-textarea" rows="2" placeholder="Contoh: Tolong posisi logo di bagian dada kiri..."></textarea>
                        </div>
                    </div>

                    {{-- 4. Data Diri & Pengiriman --}}
                    <div class="prt-card">
                        <h2 class="prt-card-title">
                            <span class="prt-step">4</span> Data Pemesan
                        </h2>

                        <div class="prt-grid-2">
                            <div class="prt-form-group">
                                <label class="prt-label">Nama Lengkap <span class="prt-required">*</span></label>
                                <input type="text" name="customer_name" class="prt-input" value="{{ old('customer_name') }}" required>
                            </div>
                            <div class="prt-form-group">
                                <label class="prt-label">No. WhatsApp <span class="prt-required">*</span></label>
                                <input type="text" name="customer_phone" class="prt-input" value="{{ old('customer_phone') }}" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>

                        <div class="prt-form-group">
                            <label class="prt-label">Email</label>
                            <input type="email" name="customer_email" class="prt-input" value="{{ old('customer_email') }}">
                        </div>

                        <div class="prt-form-group">
                            <label class="prt-label">Metode Pengambilan <span class="prt-required">*</span></label>
                            <select name="shipping_method" id="shippingMethod" class="prt-select" required>
                                <option value="pickup">Ambil Sendiri di Toko</option>
                                <option value="delivery">Diantar ke Lokasi (Delivery)</option>
                            </select>
                        </div>

                        <div class="prt-form-group" id="addressGroup" style="display: none;">
                            <label class="prt-label">Alamat Pengantaran <span class="prt-required">*</span></label>
                            <textarea name="customer_address" class="prt-textarea" rows="2"></textarea>
                        </div>

                        <div class="prt-form-group">
                            <label class="prt-label">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" class="prt-textarea" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN: RINGKASAN --}}
                <aside class="prt-sidebar">
                    <div class="prt-summary">
                        <h2 class="prt-summary-title">Ringkasan Pesanan</h2>
                        
                        <div class="prt-summary-item">
                            <span>Produk</span>
                            <strong id="summaryProductName">-</strong>
                        </div>
                        <div class="prt-summary-item">
                            <span>Jumlah</span>
                            <strong id="summaryQty">0 pcs</strong>
                        </div>
                        <div class="prt-summary-item">
                            <span>Harga Satuan</span>
                            <strong id="summaryUnitPrice">Rp 0</strong>
                        </div>
                        
                        <hr class="prt-divider">
                        
                        <div class="prt-summary-total">
                            <span>Total Biaya</span>
                            <div class="prt-total-price" id="summaryTotal">Rp 0</div>
                        </div>

                        <button type="submit" class="prt-btn-submit" id="btnSubmit" disabled>
                            Kirim Pesanan
                        </button>
                        <p class="prt-terms">
                            * Dengan menekan tombol ini, Anda menyetujui syarat & ketentuan yang berlaku.
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
/* CSS HALAMAN FORM ORDER PRINTING */
/* ============================================================ */

.prt-body { padding: 4rem 0 6rem; background-color: #f8fafc; }
.prt-container { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }

.prt-alert { display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; font-weight: 500; font-size: 0.95rem; }
.prt-alert svg { width: 24px; height: 24px; flex-shrink: 0; }
.prt-alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.prt-alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

.prt-layout { display: grid; grid-template-columns: 1fr; gap: 2rem; }
@media (min-width: 992px) { .prt-layout { grid-template-columns: 1fr 340px; align-items: start; gap: 3rem; } }

.prt-card { background: #ffffff; border-radius: 1rem; padding: 2rem; margin-bottom: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
.prt-card-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
.prt-step { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: var(--theme-primary, #3B82F6); color: #fff; font-size: 0.9rem; font-weight: 700; }

.prt-form-group { margin-bottom: 1.25rem; }
.prt-grid-2 { display: grid; grid-template-columns: 1fr; gap: 1rem; }
@media (min-width: 640px) { .prt-grid-2 { grid-template-columns: 1fr 1fr; } }

.prt-label { display: block; font-size: 0.9rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
.prt-required { color: #ef4444; }
.prt-helper { font-size: 0.8rem; color: #94a3b8; margin-top: 0.35rem; }
.prt-input, .prt-select, .prt-textarea, .prt-file-input { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; background: #ffffff; transition: all 0.2s; outline: none; }
.prt-input:focus, .prt-select:focus, .prt-textarea:focus { border-color: var(--theme-primary, #3B82F6); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
.prt-textarea { resize: vertical; }
.prt-file-input { padding: 0.5rem; }

.prt-summary { background: #ffffff; border-radius: 1rem; padding: 2rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); position: sticky; top: 2rem; }
.prt-summary-title { font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #f1f5f9; }
.prt-summary-item { display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.95rem; color: #475569; }
.prt-summary-item strong { color: #0f172a; font-weight: 700; text-align: right; }
.prt-divider { border: none; border-top: 1px dashed #e2e8f0; margin: 1.5rem 0; }
.prt-summary-total { display: flex; flex-direction: column; gap: 0.25rem; margin-bottom: 1.5rem; }
.prt-summary-total span { font-size: 0.9rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.prt-total-price { font-size: 1.75rem; font-weight: 800; color: var(--theme-primary, #3B82F6); line-height: 1.2; }

.prt-btn-submit { width: 100%; padding: 1rem; border-radius: 50px; background: #0f172a; color: #ffffff; font-size: 1rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.3s; text-align: center; }
.prt-btn-submit:hover:not(:disabled) { background: #1e293b; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
.prt-btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
.prt-terms { font-size: 0.75rem; color: #94a3b8; text-align: center; margin-top: 1rem; line-height: 1.5; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('productSelect');
    const quantityInput = document.getElementById('quantityInput');
    const shippingMethod = document.getElementById('shippingMethod');
    const addressGroup = document.getElementById('addressGroup');
    const btnSubmit = document.getElementById('btnSubmit');
    const orderForm = document.getElementById('orderForm');

    const minOrderWarning = document.getElementById('minOrderWarning');
    const productMinOrder = document.getElementById('productMinOrder');

    const summaryProductName = document.getElementById('summaryProductName');
    const summaryQty = document.getElementById('summaryQty');
    const summaryUnitPrice = document.getElementById('summaryUnitPrice');
    const summaryTotal = document.getElementById('summaryTotal');

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    function calculatePrice() {
        const productId = productSelect.value;
        const qty = parseInt(quantityInput.value) || 0;

        btnSubmit.disabled = true;
        minOrderWarning.style.display = 'none';

        if (!productId || qty < 1) {
            resetSummary();
            return;
        }

        fetch('{{ route("services.printing.calculate-price") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: qty
            })
        })
        .then(response => response.json())
        .then(data => {
            const option = productSelect.options[productSelect.selectedIndex];
            const productName = option.text.split(' (')[0];

            productMinOrder.textContent = `Minimal order: ${data.min_order} pcs. Waktu produksi: ${data.production_days} hari.`;
            
            if (data.meets_min) {
                btnSubmit.disabled = false;
                minOrderWarning.style.display = 'none';
            } else {
                btnSubmit.disabled = true;
                minOrderWarning.style.display = 'block';
            }

            summaryProductName.textContent = productName;
            summaryQty.textContent = qty + ' pcs';
            summaryUnitPrice.textContent = formatRupiah(data.unit_price);
            summaryTotal.textContent = formatRupiah(data.subtotal);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function resetSummary() {
        productMinOrder.textContent = '';
        summaryProductName.textContent = '-';
        summaryQty.textContent = '0 pcs';
        summaryUnitPrice.textContent = 'Rp 0';
        summaryTotal.textContent = 'Rp 0';
    }

    productSelect.addEventListener('change', calculatePrice);
    quantityInput.addEventListener('input', calculatePrice);

    shippingMethod.addEventListener('change', function() {
        if (this.value === 'delivery') {
            addressGroup.style.display = 'block';
            addressGroup.querySelector('textarea').required = true;
        } else {
            addressGroup.style.display = 'none';
            addressGroup.querySelector('textarea').required = false;
        }
    });

    orderForm.addEventListener('submit', function() {
        btnSubmit.textContent = 'Memproses...';
        btnSubmit.style.opacity = '0.7';
        btnSubmit.disabled = true;
    });

    if (productSelect.value) {
        calculatePrice();
    }
});
</script>
@endpush