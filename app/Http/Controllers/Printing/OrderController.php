<?php

namespace App\Http\Controllers\Printing;

use App\Http\Controllers\SiteController;
use App\Models\PrintOrder;
use App\Models\PrintOrderFile;
use App\Models\PrintProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends SiteController
{
    public function create(Request $request)
    {
        $products = PrintProduct::where('site_id', $this->site->id)
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->get();

        $selectedProduct = null;
        if ($request->filled('product')) {
            $selectedProduct = PrintProduct::where('site_id', $this->site->id)
                                           ->where('slug', $request->product)
                                           ->first();
        }

        // Ambil Hero dari halaman 'order'
        $hero = $this->getSection('order', 'hero');

        return view('sites.printing.order', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Pesan Custom Printing | KREA',
                'metaDescription' => 'Upload desain Anda dan pesan custom printing sekarang.',
            ]),
            compact('products', 'selectedProduct', 'hero')
        ));
    }

    public function calculatePrice(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:print_products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = PrintProduct::findOrFail($validated['product_id']);
        $subtotal = $product->calculatePrice($validated['quantity']);

        return response()->json([
            'unit_price' => $product->base_price,
            'quantity' => $validated['quantity'],
            'subtotal' => $subtotal,
            'min_order' => $product->min_order_qty,
            'meets_min' => $product->meetsMinOrder($validated['quantity']),
            'production_days' => $product->production_days,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'print_product_id' => 'required|exists:print_products,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'material' => 'nullable|string|max:100',
            'print_technique' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:1',
            'shipping_method' => 'required|in:pickup,delivery',
            'notes' => 'nullable|string',
            'design_notes' => 'nullable|string',
            'design_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,psd|max:10240',
        ]);

        $product = PrintProduct::findOrFail($validated['print_product_id']);

        if (! $product->meetsMinOrder($validated['quantity'])) {
            return back()
                ->withInput()
                ->with('error', 'Minimal order untuk produk ini adalah ' . $product->min_order_qty . ' pcs.');
        }

        $subtotal = $product->calculatePrice($validated['quantity']);

        DB::beginTransaction();

        try {
            $order = PrintOrder::create([
                'order_number' => PrintOrder::generateOrderNumber(),
                'site_id' => $this->site->id,
                'print_product_id' => $product->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'] ?? null,
                'size' => $validated['size'] ?? null,
                'color' => $validated['color'] ?? null,
                'material' => $validated['material'] ?? null,
                'print_technique' => $validated['print_technique'] ?? null,
                'quantity' => $validated['quantity'],
                'unit_price' => $product->base_price,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'shipping_method' => $validated['shipping_method'],
                'notes' => $validated['notes'] ?? null,
                'design_notes' => $validated['design_notes'] ?? null,
            ]);

            // Upload file desain
            if ($request->hasFile('design_file')) {
                $file = $request->file('design_file');
                $path = $file->store('print-orders/' . $order->id, 'public');

                PrintOrderFile::create([
                    'print_order_id' => $order->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => 'design',
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'version' => 1,
                ]);
            }

            DB::commit();

            // (Opsional) Redirect ke WhatsApp
            // $adminPhone = preg_replace('/[^0-9]/', '', $this->settings->get('whatsapp_number', '6281234567890'));
            // $waMessage = "Halo Admin, saya baru saja memesan custom printing.\nNo. Order: *{$order->order_number}*\nProduk: {$product->name}\nJumlah: {$order->quantity} pcs\n\nMohon info selanjutnya. Terima kasih.";
            // return redirect()->away("https://wa.me/{$adminPhone}?text=" . urlencode($waMessage));

            return redirect()
                ->route('services.printing.order')
                ->with('success', 'Pesanan berhasil! Nomor order: ' . $order->order_number . '. Tim kami akan menghubungi Anda untuk konfirmasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}