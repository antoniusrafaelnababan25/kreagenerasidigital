<?php

namespace App\Http\Controllers\Printing;

use App\Http\Controllers\SiteController;
use App\Models\Category;
use App\Models\PrintProduct;
use Illuminate\Http\Request;

class ProductController extends SiteController
{
    public function index(Request $request)
    {
        $query = PrintProduct::where('site_id', $this->site->id)
                             ->where('is_active', true);

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by price
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('material', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->input('sort', 'order');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('base_price', 'desc');
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

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('site_id', $this->site->id)
                              ->where('is_active', true)
                              ->orderBy('order')
                              ->get();

        $types = PrintProduct::where('site_id', $this->site->id)
                             ->where('is_active', true)
                             ->distinct()
                             ->pluck('type')
                             ->filter();

        // ✅ Ambil Hero dari database (sesuaikan dengan halaman Produk Printing)
        $hero = $this->getSection('produk', 'hero');

        return view('sites.printing.product', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Produk Custom Printing | KREA',
                'metaDescription' => 'Jelajahi produk custom printing kami: kaos, hoodie, totebag, mug, dan lainnya.',
            ]),
            compact('products', 'categories', 'types', 'sort', 'hero')
        ));
    }
}