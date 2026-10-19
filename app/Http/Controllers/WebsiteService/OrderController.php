<?php

namespace App\Http\Controllers\WebsiteService;

use App\Http\Controllers\SiteController;
use App\Models\WebOrder;
use App\Models\WebPackage;
use Illuminate\Http\Request;

class OrderController extends SiteController
{
    public function create(Request $request)
    {
        $packages = WebPackage::where('site_id', $this->site->id)
                              ->where('is_active', true)
                              ->orderBy('order')
                              ->get();

        $selectedPackage = null;
        if ($request->filled('package')) {
            $selectedPackage = WebPackage::where('site_id', $this->site->id)
                                         ->where('slug', $request->package)
                                         ->first();
        }

        // Ambil hero dari halaman order atau home
        $hero = $this->getSection('order', 'hero') ?? $this->getSection('home', 'hero');

        return view('sites.website-service.order', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Pesan Jasa Website | KREA',
                'metaDescription' => 'Isi form pemesanan jasa website. Tim kami akan menghubungi Anda.',
            ]),
            compact('packages', 'selectedPackage', 'hero')
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'web_package_id' => 'nullable|exists:web_packages,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:255',
            'project_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reference_websites' => 'nullable|string',
            'domain_preference' => 'nullable|string|max:255',
            'needs_hosting' => 'boolean',
            'needs_content' => 'boolean',
            'requested_features' => 'nullable|array',
        ]);

        $package = null;
        if (! empty($validated['web_package_id'])) {
            $package = WebPackage::find($validated['web_package_id']);
        }

        $validated['order_number'] = WebOrder::generateOrderNumber();
        $validated['site_id'] = $this->site->id;
        $validated['subtotal'] = $package?->price ?? 0;
        $validated['total'] = $package?->price ?? 0;
        $validated['status'] = 'pending';
        $validated['payment_status'] = 'unpaid';
        $validated['requested_features'] = $request->input('requested_features', []);

        // Pastikan nilai boolean tersimpan dengan benar (jika checkbox tidak dicentang)
        $validated['needs_hosting'] = $request->boolean('needs_hosting');
        $validated['needs_content'] = $request->boolean('needs_content');

        $order = WebOrder::create($validated);

        // (Opsional) Redirect ke WhatsApp
        // $adminPhone = preg_replace('/[^0-9]/', '', $this->settings->get('whatsapp_number', '6281234567890'));
        // $waMessage = "Halo Admin, saya baru saja memesan jasa website.\nNo. Order: *{$order->order_number}*\nNama: {$order->customer_name}\nPaket: " . ($package?->name ?? 'Custom') . "\n\nMohon info selanjutnya. Terima kasih.";
        // return redirect()->away("https://wa.me/{$adminPhone}?text=" . urlencode($waMessage));

        return redirect()
            ->route('services.website.order')
            ->with('success', 'Pesanan Anda berhasil dikirim! Nomor order: ' . $order->order_number . '. Tim kami akan menghubungi Anda segera.');
    }
}