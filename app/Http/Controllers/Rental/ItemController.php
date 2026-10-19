<?php

namespace App\Http\Controllers\Rental;

use App\Http\Controllers\SiteController;
use App\Models\RentalItem;

class ItemController extends SiteController
{
    public function show(string $slug)
    {
        $item = RentalItem::where('site_id', $this->site->id)
                          ->where('slug', $slug)
                          ->where('is_active', true)
                          ->firstOrFail();

        // Alat terkait (kategori sama)
        $related = RentalItem::where('site_id', $this->site->id)
                             ->where('is_active', true)
                             ->where('id', '!=', $item->id)
                             ->where('category_id', $item->category_id)
                             ->limit(4)
                             ->get();

        // Cek ketersediaan stok
        $isAvailable = $item->isAvailable();

        // Schema markup untuk Product
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $item->name,
            'description' => $item->description,
            'brand' => [
                '@type' => 'Brand',
                'name' => $item->brand ?? 'KREA',
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $item->price_per_day,
                'priceCurrency' => 'IDR',
                'availability' => $isAvailable
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
            ],
        ];

        return view('sites.rental.item', array_merge(
            $this->seoMeta([
                'metaTitle' => $item->name . ' | Rental KREA',
                'metaDescription' => $item->description,
                'schema' => $schema,
            ]),
            compact('item', 'related', 'isAvailable', 'schema')
        ));
    }
}