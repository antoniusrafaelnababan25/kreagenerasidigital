<?php

namespace App\Http\Controllers\WebsiteService;

use App\Http\Controllers\SiteController;
use App\Models\Portfolio;
use App\Models\Testimonial;
use App\Models\WebPackage;

class HomeController extends SiteController
{
    public function index()
    {
        $hero = $this->getSection('home', 'hero');
        $services = $this->getSection('home', 'services');
        $ctaSection = $this->getSection('home', 'cta');

        $packages = WebPackage::where('site_id', $this->site->id)
                              ->where('is_active', true)
                              ->orderBy('order')
                              ->limit(4)
                              ->get();

        $portfolios = Portfolio::where('site_id', $this->site->id)
                               ->where('is_published', true)
                               ->whereIn('service_type', ['website', 'multi'])
                               ->orderBy('order')
                               ->limit(6)
                               ->get();

        $testimonials = Testimonial::where('site_id', $this->site->id)
                                   ->where('is_published', true)
                                   ->whereIn('service_type', ['website', 'multi'])
                                   ->limit(3)
                                   ->get();

        return view('sites.website-service.home', array_merge(
            $this->seoMeta([
                'metaTitle' => 'Jasa Pembuatan Website Profesional | KREA',
                'metaDescription' => 'Jasa pembuatan website profesional, cepat, modern, dan SEO-friendly. Mulai dari landing page hingga e-commerce.',
            ]),
            compact('hero', 'services', 'ctaSection', 'packages', 'portfolios', 'testimonials')
        ));
    }
}