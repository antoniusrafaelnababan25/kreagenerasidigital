<?php

namespace App\Http\Controllers\Printing;

use App\Http\Controllers\SiteController;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Portfolio;
use App\Models\PrintProduct;
use App\Models\Testimonial;

class HomeController extends SiteController
{
    public function index()
    {
        // Mengambil section dinamis dari database (Filament)
        $hero = $this->getSection('home', 'hero');
        $services = $this->getSection('home', 'services');
        $ctaSection = $this->getSection('home', 'cta');

        // Kategori untuk filter
        $categories = Category::where('site_id', $this->site->id)
                              ->where('is_active', true)
                              ->orderBy('order')
                              ->get();

        // Produk Unggulan (Featured)
        $featuredProducts = PrintProduct::where('site_id', $this->site->id)
                                        ->where('is_active', true)
                                        ->where('is_featured', true)
                                        ->limit(6)
                                        ->get();

        // Portfolio khusus Printing
        $portfolios = Portfolio::where('site_id', $this->site->id)
                               ->where('is_published', true)
                               ->whereIn('service_type', ['printing', 'multi'])
                               ->orderBy('order')
                               ->limit(6)
                               ->get();

        // Testimoni
        $testimonials = Testimonial::where('site_id', $this->site->id)
                                   ->where('is_published', true)
                                   ->whereIn('service_type', ['printing', 'multi'])
                                   ->limit(3)
                                   ->get();

        // FAQ
        $faqs = Faq::where('site_id', $this->site->id)
                   ->where('is_published', true)
                   ->where('service_type', 'printing')
                   ->orderBy('order')
                   ->limit(5)
                   ->get();

        return view('sites.printing.home', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Custom Printing Berkualitas | KREA',
                'metaDescription' => 'Jasa custom printing untuk kaos, hoodie, totebag, mug, dan merchandise lainnya. Bahan premium, hasil tajam.',
            ]),
            compact('hero', 'services', 'ctaSection', 'categories', 'featuredProducts', 'portfolios', 'testimonials', 'faqs')
        ));
    }
}