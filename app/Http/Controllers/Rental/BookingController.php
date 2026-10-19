<?php

namespace App\Http\Controllers\Rental;

use App\Http\Controllers\SiteController;
use App\Models\RentalBooking;
use App\Models\RentalBookingItem;
use App\Models\RentalItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends SiteController
{
    /**
     * Menampilkan halaman form booking.
     */
    public function create(Request $request)
    {
        // Ambil semua item rental yang aktif dan tersedia
        $items = RentalItem::where('site_id', $this->site->id)
                           ->where('is_active', true)
                           ->where('status', 'available')
                           ->orderBy('name')
                           ->get();

        // Jika ada parameter ?item=slug di URL, set sebagai item yang terpilih otomatis
        $selectedItem = null;
        if ($request->filled('item')) {
            $selectedItem = RentalItem::where('site_id', $this->site->id)
                                      ->where('slug', $request->item)
                                      ->first();
        }

        // Ambil hero section untuk halaman booking (jika ada di database)
        $hero = $this->getSection('booking', 'hero');

        return view('sites.rental.booking', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Booking Alat Camping | KREA Rental',
                'metaDescription' => 'Booking alat camping online. Cek ketersediaan, hitung harga, dan pesan sekarang.',
            ]),
            compact('items', 'selectedItem', 'hero')
        ));
    }

    /**
     * Cek ketersediaan barang via AJAX dan kalkulasi harga.
     */
    public function checkAvailability(Request $request)
    {
        try {
            // Validasi input dari AJAX (mendukung multiple items)
            $validated = $request->validate([
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|exists:rental_items,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            // Hitung total hari sewa
            $days = Carbon::parse($validated['start_date'])
                           ->diffInDays(Carbon::parse($validated['end_date'])) + 1;

            $subtotal = 0;
            $totalDeposit = 0;
            $allAvailable = true;

            foreach ($validated['items'] as $itemData) {
                $item = RentalItem::findOrFail($itemData['id']);
                
                // Cek ketersediaan barang pada tanggal yang dipilih
                $available = $item->isAvailableForPeriod(
                    $validated['start_date'],
                    $validated['end_date'],
                    $itemData['quantity']
                );

                if (!$available) {
                    $allAvailable = false;
                }

                // Hitung subtotal dan deposit
                $subtotal += $item->calculatePrice($itemData['quantity'], $days);
                $totalDeposit += $item->deposit * $itemData['quantity'];
            }

            return response()->json([
                'available' => $allAvailable,
                'days' => $days,
                'subtotal' => $subtotal,
                'deposit' => $totalDeposit,
                'total' => $subtotal + $totalDeposit,
            ]);

        } catch (ValidationException $e) {
            // Tangkap error validasi (misal tanggal kosong)
            return response()->json([
                'message' => 'Data tidak lengkap.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Tangkap error server (misal relasi / kode error)
            return response()->json([
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simpan data booking ke database & redirect ke WhatsApp.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'customer_id_number' => 'nullable|string|max:50',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pickup_method' => 'required|in:self_pickup,delivery',
            'pickup_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:rental_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);
            $totalDays = $startDate->diffInDays($endDate) + 1;

            $subtotal = 0;
            $deposit = 0;
            $invoiceDetails = ""; 

            // Hitung total & susun teks rincian barang untuk WhatsApp
            foreach ($validated['items'] as $index => $itemData) {
                $item = RentalItem::findOrFail($itemData['id']);
                $itemSubtotal = $item->calculatePrice($itemData['quantity'], $totalDays);
                
                $subtotal += $itemSubtotal;
                $deposit += $item->deposit * $itemData['quantity'];

                $invoiceDetails .= "  " . ($index + 1) . ". {$item->name}\n";
                $invoiceDetails .= "      {$itemData['quantity']} unit x {$totalDays} hari = Rp " . number_format($itemSubtotal, 0, ',', '.') . "\n";
            }

            $totalTagihan = $subtotal + $deposit;
            $nominalDp = $totalTagihan * 0.5; // DP 50%
            $sisaPelunasan = $totalTagihan - $nominalDp;

            // Simpan ke tabel rental_bookings
            $booking = RentalBooking::create([
                'booking_number' => RentalBooking::generateBookingNumber(),
                'site_id' => $this->site->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'] ?? null,
                'customer_id_number' => $validated['customer_id_number'] ?? null,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'total_days' => $totalDays,
                'pickup_method' => $validated['pickup_method'],
                'pickup_address' => $validated['pickup_address'] ?? null,
                'subtotal' => $subtotal,
                'deposit' => $deposit,
                'total' => $totalTagihan,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Simpan ke tabel rental_booking_items
            foreach ($validated['items'] as $itemData) {
                $item = RentalItem::findOrFail($itemData['id']);
                RentalBookingItem::create([
                    'rental_booking_id' => $booking->id,
                    'rental_item_id' => $item->id,
                    'quantity' => $itemData['quantity'],
                    'price_per_day' => $item->price_per_day,
                    'days' => $totalDays,
                    'subtotal' => $item->calculatePrice($itemData['quantity'], $totalDays),
                ]);
            }

            DB::commit();

            // ═══════════════════════════════════════════════════════════
            // PERSIAPAN WHATSAPP INVOICE (PESAN PANJANG & REGULASI)
            // ═══════════════════════════════════════════════════════════
            
            // Ambil nomor WA Admin dari settings site
            $adminPhone = $this->settings->get('whatsapp_number', '6281234567890'); 
            $adminPhone = preg_replace('/[^0-9]/', '', $adminPhone);
            if (str_starts_with($adminPhone, '0')) {
                $adminPhone = '62' . substr($adminPhone, 1);
            }

            // Format Tanggal Indonesia
            $startFormatted = $startDate->translatedFormat('l, d F Y');
            $endFormatted = $endDate->translatedFormat('l, d F Y');
            $pickupMethodText = $validated['pickup_method'] === 'self_pickup' ? 'Ambil Sendiri di Toko' : 'Diantar ke Lokasi (Delivery)';

            // Susun Pesan WhatsApp
            $waMessage = "Halo Admin *KREA Generasi Digital*,\n";
            $waMessage .= "Saya baru saja melakukan pemesanan sewa alat camping melalui website. Berikut adalah detail pesanan saya:\n\n";
            
            $waMessage .= "📄 *A. INFORMASI BOOKING*\n";
            $waMessage .= "───────────────────────\n";
            $waMessage .= "▪️ *No. Booking* : {$booking->booking_number}\n";
            $waMessage .= "▪️ *Nama Penyewa* : {$booking->customer_name}\n";
            $waMessage .= "▪️ *No. WhatsApp* : {$booking->customer_phone}\n";
            if($booking->customer_email) {
                $waMessage .= "▪️ *Email* : {$booking->customer_email}\n";
            }
            $waMessage .= "▪️ *Metode Ambil* : {$pickupMethodText}\n\n";
            
            $waMessage .= "🗓️ *B. JADWAL SEWA*\n";
            $waMessage .= "───────────────────────\n";
            $waMessage .= "▪️ *Mulai* : {$startFormatted}\n";
            $waMessage .= "▪️ *Selesai* : {$endFormatted}\n";
            $waMessage .= "▪️ *Durasi* : {$totalDays} Hari\n\n";
            
            $waMessage .= "🛒 *C. RINCIAN BARANG*\n";
            $waMessage .= "───────────────────────\n";
            $waMessage .= $invoiceDetails . "\n";
            
            $waMessage .= "💰 *D. RINGKASAN BIAYA*\n";
            $waMessage .= "───────────────────────\n";
            $waMessage .= "Subtotal Sewa : Rp " . number_format($subtotal, 0, ',', '.') . "\n";
            $waMessage .= "Deposit Jaminan : Rp " . number_format($deposit, 0, ',', '.') . "\n";
            $waMessage .= "----------------------------------\n";
            $waMessage .= "*TOTAL TAGIHAN* : *Rp " . number_format($totalTagihan, 0, ',', '.') . "*\n\n";
            
            $waMessage .= "💳 *E. INSTRUKSI PEMBAYARAN DP*\n";
            $waMessage .= "───────────────────────\n";
            $waMessage .= "Untuk mengunci jadwal sewa, mohon lakukan pembayaran *DP sebesar 50%*:\n";
            $waMessage .= "👉 *Nominal DP* : *Rp " . number_format($nominalDp, 0, ',', '.') . "*\n";
            $waMessage .= "👉 *Sisa Pelunasan* : Rp " . number_format($sisaPelunasan, 0, ',', '.') . " (dibayar saat ambil barang)\n\n";
            
            $waMessage .= "Silakan transfer ke rekening resmi kami:\n";
            $waMessage .= "🏦 *Bank BCA*\n";
            $waMessage .= "💳 *No. Rekening* : 1234567890\n";
            $waMessage .= "👤 *Atas Nama* : PT KREA Generasi Digital\n\n";
            
            $waMessage .= "⚠️ *F. SYARAT & KETENTUAN (WAJIB DIBACA)*\n";
            $waMessage .= "───────────────────────\n";
            $waMessage .= "1. *Batas Waktu DP*: Booking akan dibatalkan otomatis oleh sistem jika DP belum dibayarkan dalam 1x24 jam.\n";
            $waMessage .= "2. *Pelunasan*: Sisa pembayaran wajib dilunasi saat pengambilan barang atau sebelum barang diantar.\n";
            $waMessage .= "3. *Jaminan (Deposit)*: Uang jaminan akan dikembalikan 100% setelah barang dikembalikan dalam kondisi lengkap dan tidak ada kerusakan.\n";
            $waMessage .= "4. *Keterlambatan*: Pengembalian melebihi batas waktu akan dikenakan denda sebesar 20% dari harga sewa per hari keterlambatan.\n";
            $waMessage .= "5. *Kerusakan/Hilang*: Segala bentuk kerusakan atau kehilangan barang menjadi tanggung jawab penyewa dan akan dipotong dari deposit.\n";
            $waMessage .= "6. *Identitas*: Penyewa wajib menyerahkan fotokopi KTP sebagai syarat administrasi saat pengambilan barang.\n\n";
            
            $waMessage .= "Terakhir, silakan balas pesan ini dengan melampirkan *Bukti Transfer DP* Anda agar pesanan segera kami konfirmasi dan proses.\n\n";
            
            $waMessage .= "Terima kasih telah mempercayakan petualangan Anda kepada *KREA Generasi Digital*. 🙏⛺";

            $waUrl = "https://wa.me/{$adminPhone}?text=" . urlencode($waMessage);

            // Redirect pengguna ke WhatsApp
            return redirect()->away($waUrl);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan booking: ' . $e->getMessage());
        }
    }
}