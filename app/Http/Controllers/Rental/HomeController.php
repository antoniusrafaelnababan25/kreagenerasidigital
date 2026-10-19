<?php

namespace App\Http\Controllers\Rental;

use App\Http\Controllers\SiteController;
use App\Models\Category;
use App\Models\Faq;
use App\Models\RentalItem;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends SiteController
{
    public function index(Request $request)
    {
        // ═══ AMBIL SECTIONS ═══
        $hero = $this->getSection('home', 'hero');
        $ctaSection = $this->getSection('home', 'cta');

        // ═══ QUERY KATEGORI ═══
        $categories = Category::where('site_id', $this->site->id)
                              ->where('is_active', true)
                              ->orderBy('order')
                              ->get();

        // ═══ QUERY BARANG RENTAL ═══
        $query = RentalItem::where('site_id', $this->site->id)
                           ->where('is_active', true)
                           ->where('status', 'available');

        // Filter berdasarkan Kategori
        if ($request->filled('category')) {
            $categorySlug = str_replace('-', ' ', $request->category); // Ubah 'sleeping-bag' jadi 'sleeping bag'
            
            // Cari berdasarkan nama barang ATAU deskripsi (karena tidak ada kolom type/category_id)
            $query->where(function($q) use ($categorySlug) {
                $q->where('name', 'LIKE', "%{$categorySlug}%")
                  ->orWhere('description', 'LIKE', "%{$categorySlug}%");
            });
        } else {
            // Jika tidak ada filter, tampilkan yang featured
            $query->where('is_featured', true);
        }

        $featuredItems = $query->limit(6)->get();

        // ═══ QUERY TESTIMONIALS & FAQ ═══
        $testimonials = Testimonial::where('site_id', $this->site->id)
                                   ->where('is_published', true)
                                   ->where('service_type', 'rental')
                                   ->limit(3)
                                   ->get();

        $faqs = Faq::where('site_id', $this->site->id)
                   ->where('is_published', true)
                   ->where('service_type', 'rental')
                   ->orderBy('order')
                   ->limit(5)
                   ->get();

        return view('sites.rental.home', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Rental Alat Camping Terlengkap | KREA',
                'metaDescription' => 'Sewa alat camping berkualitas: tenda, sleeping bag, carrier, dan lainnya.',
            ]),
            compact('hero', 'ctaSection', 'categories', 'featuredItems', 'testimonials', 'faqs')
        ));
    }
}