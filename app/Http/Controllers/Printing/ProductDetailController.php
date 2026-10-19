<?php

namespace App\Http\Controllers\Printing;

use App\Http\Controllers\SiteController;
use App\Models\PrintProduct;

class ProductDetailController extends SiteController
{
    public function show(string $slug)
    {
        $product = PrintProduct::where('site_id', $this->site->id)
                               ->where('slug', $slug)
                               ->where('is_active', true)
                               ->firstOrFail();

        // Produk terkait
        $related = PrintProduct::where('site_id', $this->site->id)
                               ->where('is_active', true)
                               ->where('id', '!=', $product->id)
                               ->where('category_id', $product->category_id)
                               ->limit(4)
                               ->get();

        // Schema markup Product
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->description,
            'image' => $product->featured_image ? asset('storage/' . $product->featured_image) : null,
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->base_price,
                'priceCurrency' => 'IDR',
                'availability' => 'https://schema.org/InStock',
            ],
        ];

        return view('sites.printing.product-detail', array_merge(
            $this->seoMeta([
                'metaTitle' => $product->name . ' | KREA Printing',
                'metaDescription' => $product->description,
                'schema' => $schema,
            ]),
            compact('product', 'related', 'schema')
        ));
    }
}