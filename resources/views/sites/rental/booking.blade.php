@extends('layouts.app')

@section('title', 'Booking Alat Camping')

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
<section class="bk-body">
    <div class="bk-container">

        {{-- Notifikasi Error --}}
        @if(session('error'))
            <div class="bk-alert bk-alert-error">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Notifikasi Sukses (jika ada) --}}
        @if(session('success'))
            <div class="bk-alert bk-alert-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('services.rental.booking.store') }}" method="POST" id="bookingForm" target="_blank">
            @csrf

            <div class="bk-layout">
                
                {{-- KOLOM KIRI: FORM --}}
                <div class="bk-main">

                    {{-- 1. Pilih Alat Camping (Multi-Item) --}}
                    <div class="bk-card">
                        <div class="bk-card-header">
                            <h2 class="bk-card-title">
                                <span class="bk-step">1</span> Pilih Alat Camping
                            </h2>
                            <button type="button" id="btnAddItem" class="bk-btn-add">
                                + Tambah Barang
                            </button>
                        </div>
                        
                        <div id="itemsContainer">
                            {{-- Item Pertama (Default) --}}
                            <div class="bk-item-row" data-index="0">
                                <div class="bk-form-group">
                                    <label class="bk-label">Barang <span class="bk-required">*</span></label>
                                    <select name="items[0][id]" class="bk-select item-select" required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" 
                                                    data-price="{{ $item->price_per_day }}" 
                                                    data-deposit="{{ $item->deposit }}"
                                                    data-stock="{{ $item->stock_available }}"
                                                    {{ ($selectedItem && $selectedItem->id == $item->id) ? 'selected' : '' }}>
                                                {{ $item->name }} (Rp {{ number_format($item->price_per_day, 0, ',', '.') }}/hari)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="bk-form-group bk-qty-group">
                                    <label class="bk-label">Jumlah <span class="bk-required">*</span></label>
                                    <input type="number" name="items[0][quantity]" class="bk-input item-quantity" value="1" min="1" required>
                                </div>
                                <button type="button" class="bk-btn-remove" style="display:none;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Jadwal Sewa --}}
                    <div class="bk-card">
                        <h2 class="bk-card-title">
                            <span class="bk-step">2</span> Jadwal Sewa
                        </h2>
                        
                        <div class="bk-grid-2">
                            <div class="bk-form-group">
                                <label class="bk-label">Tanggal Mulai <span class="bk-required">*</span></label>
                                <input type="date" name="start_date" id="startDate" class="bk-input" required min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="bk-form-group">
                                <label class="bk-label">Tanggal Selesai <span class="bk-required">*</span></label>
                                <input type="date" name="end_date" id="endDate" class="bk-input" required min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div id="availabilityStatus" class="bk-status" style="display: none;"></div>
                    </div>

                    {{-- 3. Data Diri --}}
                    <div class="bk-card">
                        <h2 class="bk-card-title">
                            <span class="bk-step">3</span> Data Penyewa
                        </h2>

                        <div class="bk-grid-2">
                            <div class="bk-form-group">
                                <label class="bk-label">Nama Lengkap <span class="bk-required">*</span></label>
                                <input type="text" name="customer_name" class="bk-input" value="{{ old('customer_name') }}" required>
                            </div>
                            <div class="bk-form-group">
                                <label class="bk-label">No. WhatsApp <span class="bk-required">*</span></label>
                                <input type="text" name="customer_phone" class="bk-input" value="{{ old('customer_phone') }}" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>

                        <div class="bk-form-group">
                            <label class="bk-label">Email</label>
                            <input type="email" name="customer_email" class="bk-input" value="{{ old('customer_email') }}">
                        </div>

                        <div class="bk-form-group">
                            <label class="bk-label">No. KTP / Identitas</label>
                            <input type="text" name="customer_id_number" class="bk-input" value="{{ old('customer_id_number') }}">
                        </div>

                        <div class="bk-form-group">
                            <label class="bk-label">Alamat Lengkap</label>
                            <textarea name="customer_address" class="bk-textarea" rows="2">{{ old('customer_address') }}</textarea>
                        </div>
                    </div>

                    {{-- 4. Metode Pengambilan --}}
                    <div class="bk-card">
                        <h2 class="bk-card-title">
                            <span class="bk-step">4</span> Metode Pengambilan
                        </h2>
                        
                        <div class="bk-form-group">
                            <label class="bk-label">Pilih Metode <span class="bk-required">*</span></label>
                            <select name="pickup_method" id="pickupMethod" class="bk-select" required>
                                <option value="self_pickup">Ambil Sendiri di Toko</option>
                                <option value="delivery">Diantar ke Lokasi (Delivery)</option>
                            </select>
                        </div>

                        <div class="bk-form-group" id="pickupAddressGroup" style="display: none;">
                            <label class="bk-label">Alamat Pengantaran <span class="bk-required">*</span></label>
                            <textarea name="pickup_address" class="bk-textarea" rows="2"></textarea>
                        </div>

                        <div class="bk-form-group">
                            <label class="bk-label">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" class="bk-textarea" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                </div>

                {{-- KOLOM KANAN: RINGKASAN --}}
                <aside class="bk-sidebar">
                    <div class="bk-summary">
                        <h2 class="bk-summary-title">Ringkasan Booking</h2>
                        
                        <div class="bk-summary-item">
                            <span>Durasi Sewa</span>
                            <strong id="summaryDays">0 Hari</strong>
                        </div>
                        <div class="bk-summary-item">
                            <span>Total Harga Sewa</span>
                            <strong id="summarySubtotal">Rp 0</strong>
                        </div>
                        <div class="bk-summary-item">
                            <span>Total Deposit</span>
                            <strong id="summaryDeposit">Rp 0</strong>
                        </div>
                        
                        <hr class="bk-divider">
                        
                        <div class="bk-summary-total">
                            <span>Total Tagihan</span>
                            <div class="bk-total-price" id="summaryTotal">Rp 0</div>
                        </div>

                        <button type="submit" class="bk-btn-submit" id="btnSubmit" disabled>
                            <span class="btn-text">Kirim & Lanjut ke WhatsApp</span>
                            <span class="btn-loading" style="display: none;">
                                <svg class="spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;display:inline-block;vertical-align:middle;margin-right:5px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Memproses...
                            </span>
                        </button>
                        <p class="bk-terms">
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
/* ═══════════════════════════════════════════════════════════ */
/* CSS BOOKING RENTAL */
/* ═══════════════════════════════════════════════════════════ */

.bk-body { padding: 4rem 0 6rem; background-color: #f8fafc; }
.bk-container { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }

.bk-alert { display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; font-weight: 500; font-size: 0.95rem; }
.bk-alert svg { width: 24px; height: 24px; flex-shrink: 0; }
.bk-alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.bk-alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

.bk-layout { display: grid; grid-template-columns: 1fr; gap: 2rem; }
@media (min-width: 992px) { .bk-layout { grid-template-columns: 1fr 340px; align-items: start; gap: 3rem; } }

.bk-card { background: #ffffff; border-radius: 1rem; padding: 2rem; margin-bottom: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
.bk-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.bk-card-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.75rem; margin: 0; }
.bk-step { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: var(--theme-primary, #3B82F6); color: #fff; font-size: 0.9rem; font-weight: 700; }

.bk-item-row { display: flex; gap: 1rem; align-items: flex-start; padding-bottom: 1rem; margin-bottom: 1rem; border-bottom: 1px dashed #e2e8f0; }
.bk-item-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
.bk-item-row .bk-form-group { margin-bottom: 0; flex: 1; }
.bk-item-row .bk-qty-group { flex: 0 0 100px; }
.bk-btn-add { background: #f1f5f9; color: #0f172a; border: 1px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: 0.2s; }
.bk-btn-add:hover { background: #e2e8f0; }
.bk-btn-remove { display: flex; align-items: center; justify-content: center; width: 42px; height: 42px; background: #fef2f2; color: #ef4444; border: none; border-radius: 8px; cursor: pointer; transition: 0.2s; margin-top: 28px; flex-shrink: 0; }
.bk-btn-remove:hover { background: #fee2e2; }
.bk-btn-remove svg { width: 18px; height: 18px; }

.bk-form-group { margin-bottom: 1.25rem; }
.bk-grid-2 { display: grid; grid-template-columns: 1fr; gap: 1rem; }
@media (min-width: 640px) { .bk-grid-2 { grid-template-columns: 1fr 1fr; } }

.bk-label { display: block; font-size: 0.9rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; }
.bk-required { color: #ef4444; }
.bk-input, .bk-select, .bk-textarea { width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; background: #ffffff; transition: all 0.2s; outline: none; }
.bk-input:focus, .bk-select:focus, .bk-textarea:focus { border-color: var(--theme-primary, #3B82F6); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
.bk-textarea { resize: vertical; }

.bk-status { padding: 1rem; border-radius: 8px; font-size: 0.9rem; font-weight: 600; margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem; }
.bk-status.loading { background: #f8fafc; color: #64748b; }
.bk-status.available { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.bk-status.unavailable { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

.bk-summary { background: #ffffff; border-radius: 1rem; padding: 2rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02); position: sticky; top: 2rem; }
.bk-summary-title { font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #f1f5f9; }
.bk-summary-item { display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.95rem; color: #475569; }
.bk-summary-item strong { color: #0f172a; font-weight: 700; text-align: right; }
.bk-divider { border: none; border-top: 1px dashed #e2e8f0; margin: 1.5rem 0; }
.bk-summary-total { display: flex; flex-direction: column; gap: 0.25rem; margin-bottom: 1.5rem; }
.bk-summary-total span { font-size: 0.9rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.bk-total-price { font-size: 1.75rem; font-weight: 800; color: var(--theme-primary, #3B82F6); line-height: 1.2; }

.bk-btn-submit { display: flex; align-items: center; justify-content: center; width: 100%; padding: 1rem; border-radius: 50px; background: #0f172a; color: #ffffff; font-size: 1rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.3s; text-align: center; }
.bk-btn-submit:hover:not(:disabled) { background: #1e293b; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
.bk-btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
.bk-terms { font-size: 0.75rem; color: #94a3b8; text-align: center; margin-top: 1rem; line-height: 1.5; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemsContainer = document.getElementById('itemsContainer');
    const btnAddItem = document.getElementById('btnAddItem');
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    const pickupMethod = document.getElementById('pickupMethod');
    const pickupAddressGroup = document.getElementById('pickupAddressGroup');
    const btnSubmit = document.getElementById('btnSubmit');
    const availabilityStatus = document.getElementById('availabilityStatus');
    const bookingForm = document.getElementById('bookingForm');

    const summaryDays = document.getElementById('summaryDays');
    const summarySubtotal = document.getElementById('summarySubtotal');
    const summaryDeposit = document.getElementById('summaryDeposit');
    const summaryTotal = document.getElementById('summaryTotal');

    let itemIndex = 0;

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    // ─── TAMBAH BARANG BARU ───
    btnAddItem.addEventListener('click', function() {
        itemIndex++;
        const newRow = document.createElement('div');
        newRow.className = 'bk-item-row';
        newRow.dataset.index = itemIndex;
        
        const firstSelect = document.querySelector('.item-select');
        let optionsHtml = '<option value="">-- Pilih Barang --</option>';
        Array.from(firstSelect.options).forEach(opt => {
            if (opt.value !== '') {
                optionsHtml += `<option value="${opt.value}" data-price="${opt.dataset.price}" data-deposit="${opt.dataset.deposit}" data-stock="${opt.dataset.stock}">${opt.text}</option>`;
            }
        });

        newRow.innerHTML = `
            <div class="bk-form-group">
                <label class="bk-label">Barang <span class="bk-required">*</span></label>
                <select name="items[${itemIndex}][id]" class="bk-select item-select" required>
                    ${optionsHtml}
                </select>
            </div>
            <div class="bk-form-group bk-qty-group">
                <label class="bk-label">Jumlah <span class="bk-required">*</span></label>
                <input type="number" name="items[${itemIndex}][quantity]" class="bk-input item-quantity" value="1" min="1" required>
            </div>
            <button type="button" class="bk-btn-remove">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        `;

        itemsContainer.appendChild(newRow);
        attachRowEvents(newRow);
        updateRemoveButtons();
    });

    function attachRowEvents(row) {
        row.querySelector('.item-select').addEventListener('change', checkAvailability);
        row.querySelector('.item-quantity').addEventListener('input', checkAvailability);
        
        const btnRemove = row.querySelector('.bk-btn-remove');
        if (btnRemove) {
            btnRemove.addEventListener('click', function() {
                row.remove();
                updateRemoveButtons();
                checkAvailability();
            });
        }
    }

    function updateRemoveButtons() {
        const rows = itemsContainer.querySelectorAll('.bk-item-row');
        rows.forEach((row) => {
            const btn = row.querySelector('.bk-btn-remove');
            if (btn) {
                btn.style.display = rows.length > 1 ? 'flex' : 'none';
            }
        });
    }

    // ─── CEK KETERSEDIAAN & HITUNG HARGA ───
    function checkAvailability() {
        const start = startDate.value;
        const end = endDate.value;
        
        const itemsData = [];
        let hasEmptyItem = false;
        
        const rows = itemsContainer.querySelectorAll('.bk-item-row');
        rows.forEach(row => {
            const select = row.querySelector('.item-select');
            const qty = parseInt(row.querySelector('.item-quantity').value) || 0;
            
            if (!select.value) hasEmptyItem = true;
            if (select.value) {
                itemsData.push({
                    id: select.value,
                    quantity: qty
                });
            }
        });

        availabilityStatus.style.display = 'none';
        btnSubmit.disabled = true;

        if (hasEmptyItem || itemsData.length === 0 || !start || !end) {
            resetSummary();
            return;
        }

        // Tampilkan loading
        availabilityStatus.style.display = 'flex';
        availabilityStatus.className = 'bk-status loading';
        availabilityStatus.innerHTML = '<svg class="spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Mengecek ketersediaan...';

        fetch('{{ route("services.rental.check-availability") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                start_date: start,
                end_date: end,
                items: itemsData
            })
        })
        .then(async response => {
            const data = await response.json();
            
            // Jika server membalas dengan error (422, 500, dll)
            if (!response.ok) {
                let errorMsg = data.message || 'Terjadi kesalahan pada server.';
                if (data.errors) {
                    errorMsg = Object.values(data.errors)[0][0]; // Ambil pesan error pertama
                }
                throw new Error(errorMsg);
            }
            return data;
        })
        .then(data => {
            if (data.available) {
                availabilityStatus.className = 'bk-status available';
                availabilityStatus.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Semua barang tersedia untuk tanggal yang dipilih.';
                btnSubmit.disabled = false;
            } else {
                availabilityStatus.className = 'bk-status unavailable';
                availabilityStatus.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Maaf, stok salah satu barang tidak mencukupi.';
                btnSubmit.disabled = true;
            }

            summaryDays.textContent = data.days + ' Hari';
            summarySubtotal.textContent = formatRupiah(data.subtotal);
            summaryDeposit.textContent = formatRupiah(data.deposit);
            summaryTotal.textContent = formatRupiah(data.total);
        })
        .catch(error => {
            console.error('Error:', error);
            availabilityStatus.className = 'bk-status unavailable';
            // Tampilkan pesan error asli dari sistem (misal: tanggal kosong)
            availabilityStatus.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> ' + error.message;
        });
    }

    function resetSummary() {
        summaryDays.textContent = '0 Hari';
        summarySubtotal.textContent = 'Rp 0';
        summaryDeposit.textContent = 'Rp 0';
        summaryTotal.textContent = 'Rp 0';
    }

    // ─── EVENT LISTENERS ───
    startDate.addEventListener('change', function() {
        if (this.value) {
            endDate.min = this.value;
            if (endDate.value && endDate.value < this.value) endDate.value = this.value;
        }
        checkAvailability();
    });
    
    endDate.addEventListener('change', checkAvailability);

    pickupMethod.addEventListener('change', function() {
        if (this.value === 'delivery') {
            pickupAddressGroup.style.display = 'block';
            pickupAddressGroup.querySelector('textarea').required = true;
        } else {
            pickupAddressGroup.style.display = 'none';
            pickupAddressGroup.querySelector('textarea').required = false;
        }
    });

    // ─── TOMBOL SUBMIT KE WHATSAPP (LOADING STATE) ───
    bookingForm.addEventListener('submit', function() {
        const btnText = btnSubmit.querySelector('.btn-text');
        const btnLoading = btnSubmit.querySelector('.btn-loading');
        
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline-flex';
        btnSubmit.style.opacity = '0.8';
        btnSubmit.disabled = true; // Mencegah double click
    });

    // Init baris pertama
    attachRowEvents(itemsContainer.querySelector('.bk-item-row'));
    updateRemoveButtons();
    
    // Jika ada item terpilih dari URL
    if (document.querySelector('.item-select').value) {
        checkAvailability();
    }
});
</script>
<style>
@keyframes spin { 100% { transform: rotate(360deg); } }
.spin { animation: spin 1s linear infinite; }
</style>
@endpush