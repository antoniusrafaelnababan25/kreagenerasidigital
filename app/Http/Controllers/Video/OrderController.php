<?php

namespace App\Http\Controllers\Video;

use App\Http\Controllers\SiteController;
use App\Models\VideoOrder;
use App\Models\VideoPackage;
use Illuminate\Http\Request;

class OrderController extends SiteController
{
    public function create(Request $request)
    {
        $packages = VideoPackage::where('site_id', $this->site->id)
                                ->where('is_active', true)
                                ->orderBy('order')
                                ->get();

        $selectedPackage = null;
        if ($request->filled('package')) {
            $selectedPackage = VideoPackage::where('site_id', $this->site->id)
                                           ->where('slug', $request->package)
                                           ->first();
        }

        // Ambil Hero
        $hero = $this->getSection('order', 'hero');

        return view('sites.video.order', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Pesan Jasa Videografi | KREA',
                'metaDescription' => 'Isi form pemesanan jasa videografi. Tim kami akan menghubungi Anda.',
            ]),
            compact('packages', 'selectedPackage', 'hero')
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'video_package_id' => 'nullable|exists:video_packages,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'project_title' => 'required|string|max:255',
            'video_type' => 'nullable|string|max:255',
            'concept' => 'nullable|string',
            'reference' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'shooting_date' => 'nullable|date',
            'shooting_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $package = null;
        if (! empty($validated['video_package_id'])) {
            $package = VideoPackage::find($validated['video_package_id']);
        }

        $validated['order_number'] = VideoOrder::generateOrderNumber();
        $validated['site_id'] = $this->site->id;
        $validated['subtotal'] = $package?->price ?? 0;
        $validated['total'] = $package?->price ?? 0;
        $validated['status'] = 'pending';
        $validated['payment_status'] = 'unpaid';

        $order = VideoOrder::create($validated);

        // ─── PERSIAPAN WHATSAPP INVOICE ───
        $adminPhone = $this->settings->get('whatsapp_number', '6281234567890'); 
        $adminPhone = preg_replace('/[^0-9]/', '', $adminPhone);
        if (str_starts_with($adminPhone, '0')) $adminPhone = '62' . substr($adminPhone, 1);

        $packageName = $package ? $package->name : 'Custom Request';
        $shootingDate = $validated['shooting_date'] ? \Carbon\Carbon::parse($validated['shooting_date'])->translatedFormat('l, d F Y') : 'Belum ditentukan';

        $waMessage = "Halo Admin *KREA Video*,\n";
        $waMessage .= "Saya baru saja melakukan pemesanan jasa videografi. Berikut detailnya:\n\n";
        
        $waMessage .= "📄 *A. INFORMASI PEMESAN*\n";
        $waMessage .= "No. Order: *{$order->order_number}*\n";
        $waMessage .= "Nama: {$validated['customer_name']}\n";
        $waMessage .= "No. HP: {$validated['customer_phone']}\n";
        $waMessage .= "Email: {$validated['customer_email']}\n\n";
        
        $waMessage .= "🎬 *B. DETAIL PROYEK*\n";
        $waMessage .= "Judul Proyek: {$validated['project_title']}\n";
        $waMessage .= "Paket: *{$packageName}*\n";
        if($validated['video_type']) $waMessage .= "Tipe Video: {$validated['video_type']}\n";
        if($validated['location']) $waMessage .= "Lokasi: {$validated['location']}\n";
        $waMessage .= "Jadwal Shooting: {$shootingDate} " . ($validated['shooting_time'] ? "({$validated['shooting_time']})" : "") . "\n\n";

        if($validated['concept'] || $validated['reference']) {
            $waMessage .= "📝 *C. KONSEP & REFERENSI*\n";
            if($validated['concept']) $waMessage .= "Konsep: {$validated['concept']}\n";
            if($validated['reference']) $waMessage .= "Referensi: {$validated['reference']}\n\n";
        }

        $waMessage .= "💰 *D. ESTIMASI BIAYA*\n";
        $waMessage .= "Total Tagihan: *Rp " . number_format($order->total, 0, ',', '.') . "*\n\n";
        
        $waMessage .= "⚠️ *E. SYARAT & KETENTUAN*\n";
        $waMessage .= "1. Pembayaran DP sebesar 50% wajib dilakukan untuk mengunci jadwal shooting.\n";
        $waMessage .= "2. Jadwal shooting akan dikonfirmasi ulang oleh tim kami.\n";
        $waMessage .= "3. Perubahan jadwal (reschedule) maksimal H-3 sebelum shooting.\n";
        $waMessage .= "4. Sisa pelunasan dibayarkan setelah video selesai diedit dan disetujui.\n\n";
        
        $waMessage .= "Silakan balas pesan ini dengan mengirimkan *Bukti Transfer DP* Anda agar pesanan segera diproses.\n";
        $waMessage .= "Terima kasih! 🙏🎥";

        $waUrl = "https://wa.me/{$adminPhone}?text=" . urlencode($waMessage);

        return redirect()->away($waUrl);
    }
}