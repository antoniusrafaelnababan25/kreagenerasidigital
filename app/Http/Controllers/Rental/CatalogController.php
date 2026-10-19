<?php

namespace App\Http\Controllers\Rental;

use App\Http\Controllers\SiteController;
use App\Models\Category;
use App\Models\RentalItem;
use Illuminate\Http\Request;

class CatalogController extends SiteController
{
    public function index(Request $request)
    {
        $query = RentalItem::where('site_id', $this->site->id)
                           ->where('is_active', true);

        if ($request->filled('category')) {
            $categorySlug = str_replace('-', ' ', $request->category);
            $query->where(function ($q) use ($categorySlug) {
                $q->where('name', 'LIKE', "%{$categorySlug}%")
                  ->orWhere('description', 'LIKE', "%{$categorySlug}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'default');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price_per_day', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_per_day', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('name', 'asc');
        }

        $items = $query->paginate(12)->withQueryString();

        $categories = Category::where('site_id', $this->site->id)
                              ->where('is_active', true)
                              ->orderBy('order')
                              ->get();

        // ⬇️⬇️⬇️ TAMBAH 1 BARIS INI ⬇️⬇️⬇️
        $hero = $this->getSection('katalog', 'hero');

        return view('sites.rental.catalog', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Katalog Alat Camping | KREA Rental',
                'metaDescription' => 'Jelajahi katalog alat camping kami. Tenda, sleeping bag, carrier, dan lainnya.',
            ]),
            compact('items', 'categories', 'sort', 'hero')   // ⬅️ TAMBAH 'hero'
        ));
    }
}